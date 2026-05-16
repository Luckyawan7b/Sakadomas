<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use App\Services\FcmService;
use App\Models\Transaksi;
use App\Models\Ternak;
use App\Models\Kandang;
use App\Models\Kamar;
use App\Models\Survei;
use App\Models\DetailTransaksi;
use App\Models\Keuangan;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    private function uploadKeCloudinary($file): string
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_KEY'),
                'api_secret' => env('CLOUDINARY_SECRET'),
            ],
            'url' => ['secure' => true]
        ]);

        $result = $cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            ['folder' => 'SMART-SAKA/bukti_transfer']
        );

        return $result['secure_url'];
    }

    /**
     * Helper: Selesaikan transaksi (ternak terjual, keluar kandang, catat keuangan)
     */
    private function selesaikanTransaksi(Transaksi $transaksi): void
    {
        // Update status ternak → terjual, keluarkan dari kandang
        foreach ($transaksi->detailTransaksi as $detail) {
            Ternak::where('id_ternak', $detail->id_ternak)->update([
                'status_jual' => 'terjual',
                'id_kamar'    => null,
            ]);
        }

        // Buat record keuangan
        Keuangan::create([
            'ket'             => 'Pemasukan dari transaksi #TRX-' . $transaksi->id_transaksi,
            'tanggal'         => Carbon::now()->toDateString(),
            'nominal'         => $transaksi->total_harga + $transaksi->ongkir,
            'jenis_keuangan'  => 'pemasukan',
            'id_transaksi'    => $transaksi->id_transaksi,
        ]);
    }

    /**
     * Helper: Batalkan transaksi (kembalikan ternak ke siap jual)
     */
    private function batalkanTransaksi(Transaksi $transaksi): void
    {
        // Kembalikan ternak ke siap jual
        foreach ($transaksi->detailTransaksi as $detail) {
            Ternak::where('id_ternak', $detail->id_ternak)->update([
                'status_jual' => 'siap jual',
            ]);
        }

        // Batalkan semua survei aktif yang terkait
        $transaksi->survei()
            ->whereIn('status', ['pending', 'disetujui'])
            ->update([
                'status'    => 'batal',
                'ket_admin' => 'Otomatis batal karena transaksi dibatalkan.',
            ]);
    }

    // ================================================================
    // 1. TAMPILAN OPERASIONAL (Pending, Diproses, Dikirim)
    // ================================================================
    public function indexAdmin()
    {
        $data_transaksi = Transaksi::with(['akun', 'jenisTernak', 'detailTransaksi.ternak.jenis_ternak', 'survei'])
            ->whereIn('status', ['pending', 'diproses', 'dikirim'])
            ->orderBy('tgl_transaksi', 'desc')
            ->paginate(10);

        $data_ternak = Ternak::with(['jenis_ternak', 'kamar'])
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->get();

        $data_kandang = Kandang::all();
        $data_kamar = Kamar::all();

        return view('pages.transaksi', compact('data_transaksi', 'data_ternak', 'data_kandang', 'data_kamar'));
    }

    // ================================================================
    // 2. TAMBAH TRANSAKSI DARI ADMIN
    // ================================================================
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'id_jenis_ternak'   => 'required|exists:jenis_ternak,id_jenis_ternak',
            'jenis_kelamin_pesanan' => 'required|string',
            'total_jumlah'      => 'required|integer|min:1',
            'total_harga'       => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kurir'             => 'nullable|string|max:50',
            'no_kurir'          => 'nullable|string|max:20',
            'status'            => 'required|string',
            'metode_pengiriman' => 'required|in:dikirim,ambil_sendiri',
            'ongkir'            => 'required|integer|min:0',
        ]);

        $uploadedFileUrl = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        Transaksi::create([
            'id_akun'               => Auth::id(),
            'id_jenis_ternak'       => $request->id_jenis_ternak,
            'jenis_kelamin_pesanan' => $request->jenis_kelamin_pesanan,
            'tgl_transaksi'         => Carbon::now(),
            'total_jumlah'          => $request->total_jumlah,
            'total_harga'           => $request->total_harga,
            'metode_pembayaran'     => $request->metode_pembayaran,
            'bukti_pembayaran'      => $uploadedFileUrl,
            'kurir'                 => $request->kurir ?? '-',
            'no_kurir'              => $request->no_kurir ?? '-',
            'status'                => $request->status,
            'metode_pengiriman'     => $request->metode_pengiriman,
            'ongkir'                => $request->ongkir,
        ]);

        return back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    // ================================================================
    // 3. EDIT TRANSAKSI (ADMIN)
    // ================================================================
    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'status'   => 'required|string',
            'kurir'    => 'nullable|string|max:50',
            'no_kurir' => 'nullable|string|max:20',
        ]);

        $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);
        $newStatus = $request->status;

        $updateData = [
            'status'   => $newStatus,
            'kurir'    => ($transaksi->metode_pengiriman === 'ambil_sendiri') ? null : ($request->kurir ?? $transaksi->kurir),
            'no_kurir' => ($transaksi->metode_pengiriman === 'ambil_sendiri') ? null : ($request->no_kurir ?? $transaksi->no_kurir),
        ];

        // Catat waktu pengiriman jika status berubah ke dikirim
        if ($newStatus == 'dikirim' && $transaksi->status != 'dikirim') {
            $updateData['tgl_dikirim'] = Carbon::now();
        }

        $transaksi->update($updateData);

        // Update status ternak berdasarkan status transaksi
        if ($newStatus == 'selesai') {
            $this->selesaikanTransaksi($transaksi);
        } elseif ($newStatus == 'batal') {
            $this->batalkanTransaksi($transaksi);
        }

        // Kirim push notification ke pelanggan
        try {
            $fcm = new FcmService();
            match ($newStatus) {
                'diproses'  => $fcm->sendToUser($transaksi->id_akun,
                                    '📦 Pesanan Diproses',
                                    'Pesanan #TRX-' . $transaksi->id_transaksi . ' sedang diproses oleh admin.'),
                'dikirim'   => $fcm->sendToUser($transaksi->id_akun,
                                    '🚚 Pesanan Dikirim',
                                    'Pesanan #TRX-' . $transaksi->id_transaksi . ' sedang dalam perjalanan.'),
                'selesai'   => $fcm->sendToUser($transaksi->id_akun,
                                    '✅ Pesanan Selesai',
                                    'Pesanan #TRX-' . $transaksi->id_transaksi . ' telah selesai.'),
                'batal'     => $fcm->sendToUser($transaksi->id_akun,
                                    '❌ Pesanan Dibatalkan',
                                    'Pesanan #TRX-' . $transaksi->id_transaksi . ' telah dibatalkan oleh admin.'),
                default     => null,
            };
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in updateAdmin: ' . $e->getMessage());
        }

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    // ================================================================
    // 4. HAPUS TRANSAKSI
    // ================================================================
    public function deleteAdmin($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return back()->with('success', 'Data transaksi berhasil dihapus.');
    }

    // ================================================================
    // 5. REKAP TRANSAKSI
    // ================================================================
    public function rekapAdmin(Request $request)
    {
        $query = Transaksi::with(['akun', 'jenisTernak'])->orderBy('tgl_transaksi', 'desc');

        if ($request->filled('tgl_awal')) {
            $query->whereDate('tgl_transaksi', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tgl_transaksi', '<=', $request->tgl_akhir);
        }
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }
        if ($request->filled('metode_pembayaran') && $request->metode_pembayaran !== 'semua') {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use($q){
                $w->where('id_transaksi', 'like', "%{$q}%")
                  ->orWhereHas('akun', function($q_akun) use($q){
                      $q_akun->where('nama', 'like', "%{$q}%");
                  });
            });
        }

        $data_transaksi = $query->paginate(15);
        return view('pages.rekap-transaksi', compact('data_transaksi'));
    }

    // ================================================================
    // 6. FORM BUAT PESANAN (PELANGGAN)
    // ================================================================
    public function createPesananUser()
    {
        $ternak_tersedia = Ternak::with('jenis_ternak')
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->get();

        // 1. Baca value.json agar rentang berat tidak di-hardcode
        $jsonPath = public_path('json/value.json');
        $klasifikasiData = [];
        if (\Illuminate\Support\Facades\File::exists($jsonPath)) {
            $klasifikasiData = json_decode(\Illuminate\Support\Facades\File::get($jsonPath), true)['ternak_klasifikasi'] ?? [];
        }

        $jenis_ternak = $ternak_tersedia->map(function ($item) use ($klasifikasiData) {
            $usia = $item->usia;
            $berat = $item->berat;
            $breed = $item->jenis_ternak->jenis_ternak ?? '';

            // 2. Samakan nama jenis DB dengan JSON
            $mapJenis = [
                'crosstexel' => 'Cross Texel',
                'merino' => 'Merino',
                'etawa' => 'Etawa (PE)'
            ];
            $searchJenis = $mapJenis[strtolower($breed)] ?? $breed;

            // 3. Tentukan Kategori Usia
            if ($usia <= 5) { $katUsia = 'Anakan/Bibit'; }
            elseif ($usia <= 11) { $katUsia = 'Doro/Muda'; }
            else { $katUsia = 'Indukan/Dewasa'; }

            // 4. Pencarian Kelas Berat Dinamis (Mencocokkan dengan JSON)
            $kelasBerat = 'Uncategorized';
            foreach ($klasifikasiData as $dataBreed) {
                if ($dataBreed['breed_name'] === $searchJenis) {
                    foreach ($dataBreed['age_categories'] as $ageCat) {
                        if ($ageCat['category_name'] === $katUsia) {
                            $lastClass = null;
                            foreach ($ageCat['weight_classes'] as $wClass) {
                                $lastClass = $wClass;
                                // Cek apakah berat sesuai dengan rentang min & max di JSON
                                if ($berat >= $wClass['min_weight'] && $berat <= $wClass['max_weight']) {
                                    $kelasBerat = $wClass['class_name'];
                                    break 3; // Keluar dari loop jika sudah ketemu
                                }
                            }
                            
                            // Jika berat di luar jangkauan (kurang dari min atau lebih dari max)
                            if ($kelasBerat === 'Uncategorized' && $lastClass) {
                                if ($berat > $lastClass['max_weight']) {
                                    $kelasBerat = $lastClass['class_name'];
                                } else {
                                    $kelasBerat = $ageCat['weight_classes'][0]['class_name'];
                                }
                                break 2; // Keluar dari loop breed_name
                            }
                        }
                    }
                }
            }

            return [
                'id_jenis' => $item->id_jenis_ternak,
                'nama_produk' => $breed . ' - ' . $katUsia,
                'kelas_berat' => $kelasBerat,
                'jenis_kelamin' => $item->jenis_kelamin,
                'harga' => $item->harga,
            ];
        })
        ->filter(function($item) {
            // Sembunyikan jika beratnya anomali (tidak masuk rentang JSON mana pun)
            return $item['kelas_berat'] !== 'Uncategorized';
        })
        ->groupBy(function ($item) {
            return $item['nama_produk'] . $item['kelas_berat'] . $item['jenis_kelamin'] . $item['harga'];
        })
        ->map(function ($group) {
            $first = $group->first();
            return [
                'id_jenis' => $first['id_jenis'],
                'nama_produk' => $first['nama_produk'],
                'kelas_berat' => $first['kelas_berat'],
                'jenis_kelamin' => $first['jenis_kelamin'],
                'harga' => $first['harga'],
                'stok' => $group->count()
            ];
        })
        ->values();

        // Hitung ongkir untuk user yang sedang login
        $ongkirInfo = null;
        $user = Auth::user();
        if ($user && $user->id_desa) {
            $jarakData = \App\Models\Jarak::where('id_desa', $user->id_desa)->first();
            if ($jarakData) {
                $km = $jarakData->jarak_km;
                if ($km <= 15) {
                    $ongkirNominal = 50000;
                } elseif ($km <= 30) {
                    $ongkirNominal = 100000;
                } elseif ($km <= 45) {
                    $ongkirNominal = 150000;
                } else {
                    $ongkirNominal = 200000;
                }
                $ongkirInfo = [
                    'jarak_km' => $km,
                    'ongkir' => $ongkirNominal,
                    'dalam_jangkauan' => true,
                ];
            } else {
                $ongkirInfo = [
                    'jarak_km' => null,
                    'ongkir' => 0,
                    'dalam_jangkauan' => false,
                ];
            }
        }

        return view('landing.form-pemesanan', compact('jenis_ternak', 'ongkirInfo', 'klasifikasiData'));
    }

    // ================================================================
    // 7. SIMPAN PESANAN PELANGGAN
    // ================================================================
    public function storePesananUser(Request $request)
    {
        $isSurvei = $request->has('is_survei') && $request->is_survei == 1;

        // 1. Validasi Input — metode_pembayaran & bukti tidak wajib jika survei
        $rules = [
            'id_jenis_ternak'       => 'required',
            'jenis_kelamin_pesanan' => 'required|string',
            'total_jumlah'          => 'required|integer|min:1',
            'total_harga'           => 'required|numeric|min:0',
            'metode_pengiriman'     => 'required|in:dikirim,ambil_sendiri',
        ];

        if ($isSurvei) {
            $rules['tanggal_survei'] = 'required|date|after_or_equal:today|before_or_equal:' . Carbon::now()->addDays(7)->toDateString();
            $rules['waktu_survei']   = 'required';
        } else {
            $rules['metode_pembayaran'] = 'required|in:transfer,cash';
            $rules['bukti_pembayaran']  = 'required_if:metode_pembayaran,transfer|nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules);

        // 1.5. Validasi Bentrok Jadwal Survei
        if ($isSurvei) {
            $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';
            $bentrok = \App\Models\Survei::where('tgl_survei', $tgl_survei_gabungan)
                ->where('status', '!=', 'batal')
                ->exists();
            if ($bentrok) {
                return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
            }

            // 1.6. Validasi: waktu survei tidak boleh sudah lewat jika tanggalnya hari ini
            if ($request->tanggal_survei === Carbon::today()->toDateString()) {
                $jamSurvei = Carbon::createFromFormat('H:i', $request->waktu_survei);
                if ($jamSurvei->lte(Carbon::now())) {
                    return back()->withErrors(['waktu_survei' => 'Jam survei yang dipilih sudah lewat. Silakan pilih sesi waktu berikutnya.'])->withInput();
                }
            }
        }

        // 2. Cek ketersediaan stok (dengan database lock untuk mencegah race condition)
        $harga_per_ekor = $request->total_harga / $request->total_jumlah;

        $stok_tersedia = Ternak::where('id_jenis_ternak', $request->id_jenis_ternak)
            ->where('jenis_kelamin', $request->jenis_kelamin_pesanan)
            ->where('harga', $harga_per_ekor)
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->lockForUpdate()
            ->count();

        if ($stok_tersedia < $request->total_jumlah) {
            return back()->withErrors(['stok' => 'Mohon maaf, stok domba kriteria ini tidak mencukupi (tersedia: ' . $stok_tersedia . ' ekor).'])->withInput();
        }

        // 2.5 Kalkulasi Ongkir
        $ongkir = 0;
        if ($request->metode_pengiriman === 'dikirim') {
            $user = Auth::user();
            $jarakData = \App\Models\Jarak::where('id_desa', $user->id_desa)->first();
            
            if (!$jarakData) {
                return back()->withErrors(['metode_pengiriman' => 'Maaf, alamat Anda berada di luar jangkauan pengiriman kami. Silakan pilih metode Ambil Sendiri.'])->withInput();
            }

            $km = $jarakData->jarak_km;
            if ($km <= 15) {
                $ongkir = 50000;
            } elseif ($km <= 30) {
                $ongkir = 100000;
            } elseif ($km <= 45) {
                $ongkir = 150000;
            } else {
                $ongkir = 200000;
            }
        }

        // 3. Upload Bukti Transfer (hanya jika bukan survei dan metode transfer)
        $uploadedFileUrl = null;
        if (!$isSurvei && $request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        // 4. Buat Transaksi
        $now = Carbon::now();
        $transaksi = Transaksi::create([
            'id_akun'               => Auth::id(),
            'id_jenis_ternak'       => $request->id_jenis_ternak,
            'jenis_kelamin_pesanan' => $request->jenis_kelamin_pesanan,
            'tgl_transaksi'         => $now,
            'total_jumlah'          => $request->total_jumlah,
            'total_harga'           => $request->total_harga,
            'metode_pembayaran'     => $isSurvei ? null : $request->metode_pembayaran,
            'bukti_pembayaran'      => $uploadedFileUrl,
            'metode_pengiriman'     => $request->metode_pengiriman,
            'ongkir'                => $ongkir,
            'kurir'                 => null,
            'no_kurir'              => null,
            'status'                => 'pending',
            'is_survei'             => $isSurvei,
            'batas_survei'          => $isSurvei ? $now->copy()->addDays(7)->toDateString() : null,
        ]);

        // 5. Buat Survei jika diminta
        if ($isSurvei) {
            $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';
            Survei::create([
                'tgl_survei'    => $tgl_survei_gabungan,
                'status'        => 'pending',
                'ket'           => $request->ket_survei ?? 'Survei untuk transaksi #TRX-' . $transaksi->id_transaksi,
                'id_akun'       => Auth::id(),
                'id_transaksi'  => $transaksi->id_transaksi,
            ]);
        }

        // Kirim push notification ke semua admin
        try {
            $fcm = new FcmService();
            $fcm->sendToAllAdmins(
                '🛒 Pesanan Baru!',
                Auth::user()->nama . ' membuat pesanan baru #TRX-' . $transaksi->id_transaksi . '.'
            );
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in storePesananUser: ' . $e->getMessage());
        }

        if ($isSurvei) {
            $survei = $transaksi->survei->first();
            return redirect()->route('survei.success', $survei->id_survei)->with('success', 'Pengajuan survei berhasil dikirim!');
        }

        return redirect()->route('transaksi.riwayat')->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi dari Admin.');
    }

    // ================================================================
    // 8. RIWAYAT PESANAN USER
    // ================================================================
    public function riwayatUser(Request $request)
    {
        $query = Transaksi::with(['jenisTernak', 'detailTransaksi.ternak.jenis_ternak', 'survei'])
            ->where('id_akun', Auth::id())
            ->orderBy('tgl_transaksi', 'desc');

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('id_transaksi', 'like', "%{$q}%");
        }

        $data_transaksi = $query->paginate(10);

        $allUserTrx = Transaksi::where('id_akun', Auth::id());
        $stats = [
            'total'     => (clone $allUserTrx)->count(),
            'pending'   => (clone $allUserTrx)->where('status', 'pending')->count(),
            'diproses'  => (clone $allUserTrx)->where('status', 'diproses')->count(),
            'dikirim'   => (clone $allUserTrx)->where('status', 'dikirim')->count(),
            'selesai'   => (clone $allUserTrx)->where('status', 'selesai')->count(),
            'batal'     => (clone $allUserTrx)->where('status', 'batal')->count(),
        ];

        return view('landing.riwayat-transaksi', compact('data_transaksi', 'stats'));
    }

    // ================================================================
    // 8.5 HALAMAN PEMBAYARAN (Transfer Page — Landing UI Baru)
    // ================================================================
    public function halamanPembayaran($id)
    {
        $transaksi = Transaksi::with(['jenisTernak', 'detailTransaksi.ternak.jenis_ternak', 'survei'])
            ->where('id_akun', Auth::id())
            ->findOrFail($id);

        // Hitung sisa waktu pembayaran (24 jam dari transaksi dibuat / survei selesai)
        $batasWaktu = Carbon::parse($transaksi->tgl_transaksi)->addHours(24);

        // Jika transaksi survei dan survei selesai, hitung dari waktu survei selesai
        if ($transaksi->is_survei && $transaksi->survei) {
            $surveiSelesai = $transaksi->survei->where('status', 'selesai')->first();
            if ($surveiSelesai) {
                $batasWaktu = Carbon::parse($surveiSelesai->updated_at)->addHours(24);
            }
        }

        $sisaDetik = max(0, Carbon::now()->diffInSeconds($batasWaktu, false));

        return view('landing.transfer-page', [
            'transaksi'  => $transaksi,
            'sisaDetik'  => $sisaDetik,
            'batasWaktu' => $batasWaktu,
            'waNumber'   => config('smartsaka.wa_number'),
        ]);
    }

    // ================================================================
    // 9. USER CANCEL PESANAN
    // ================================================================
    public function cancelPesananUser($id)
    {
        $transaksi = Transaksi::with('detailTransaksi')->where('id_akun', Auth::id())->findOrFail($id);

        if (in_array($transaksi->status, ['dikirim', 'selesai', 'batal'])) {
            return back()->withErrors(['cancel' => 'Pesanan tidak bisa dibatalkan karena sudah ' . $transaksi->status . '.']);
        }

        if ($transaksi->metode_pembayaran === 'transfer' && !empty($transaksi->bukti_pembayaran)) {
            return back()->withErrors(['cancel' => 'Pesanan dengan metode transfer yang sudah dikirimkan bukti pembayarannya tidak dapat dibatalkan.']);
        }

        $this->batalkanTransaksi($transaksi);
        $transaksi->update(['status' => 'batal']);

        // Kirim push notification ke admin
        try {
            $fcm = new FcmService();
            $fcm->sendToAllAdmins(
                '🚫 Pesanan Dibatalkan',
                Auth::user()->nama . ' membatalkan pesanan #TRX-' . $id . '.'
            );
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in cancelPesananUser: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesanan #TRX-' . $id . ' berhasil dibatalkan.');
    }

    // ================================================================
    // 10. USER: SELESAIKAN PESANAN (Pesanan Diterima)
    // ================================================================
    public function selesaiPesananUser($id)
    {
        $transaksi = Transaksi::with('detailTransaksi')->where('id_akun', Auth::id())->findOrFail($id);

        if ($transaksi->status !== 'dikirim') {
            return back()->withErrors(['selesai' => 'Pesanan hanya bisa diselesaikan jika berstatus dikirim.']);
        }

        $transaksi->update(['status' => 'selesai']);
        $this->selesaikanTransaksi($transaksi);

        // Kirim push notification ke admin
        try {
            $fcm = new FcmService();
            $fcm->sendToAllAdmins(
                '✅ Pesanan Diterima',
                Auth::user()->nama . ' mengkonfirmasi penerimaan pesanan #TRX-' . $id . '.'
            );
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in selesaiPesananUser: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesanan #TRX-' . $id . ' berhasil diselesaikan. Terima kasih!');
    }

    // ================================================================
    // 11. USER: UPLOAD BUKTI PEMBAYARAN (setelah survei selesai)
    // ================================================================
    public function uploadBuktiUser(Request $request, $id)
    {
        $transaksi = Transaksi::with('survei')->where('id_akun', Auth::id())->findOrFail($id);

        // Pastikan transaksi adalah survei dan ada survei yang sudah selesai
        if (!$transaksi->is_survei) {
            return back()->withErrors(['upload' => 'Transaksi ini bukan transaksi survei.']);
        }

        $surveiSelesai = $transaksi->survei->where('status', 'selesai')->count() > 0;
        if (!$surveiSelesai) {
            return back()->withErrors(['upload' => 'Survei belum selesai. Tunggu hingga survei diselesaikan.']);
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,cash',
            'bukti_pembayaran'  => 'required_if:metode_pembayaran,transfer|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pengiriman' => 'required|in:dikirim,ambil_sendiri',
        ]);

        $ongkir = 0;
        if ($request->metode_pengiriman === 'dikirim') {
            $user = Auth::user();
            $jarakData = \App\Models\Jarak::where('id_desa', $user->id_desa)->first();
            
            if (!$jarakData) {
                return back()->withErrors(['upload' => 'Maaf, alamat Anda berada di luar jangkauan pengiriman kami. Silakan pilih metode Ambil Sendiri.']);
            }

            $km = $jarakData->jarak_km;
            if ($km <= 15) {
                $ongkir = 50000;
            } elseif ($km <= 30) {
                $ongkir = 100000;
            } elseif ($km <= 45) {
                $ongkir = 150000;
            } else {
                $ongkir = 200000;
            }
        }

        $uploadedFileUrl = $transaksi->bukti_pembayaran;
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        $transaksi->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran'  => $uploadedFileUrl,
            'metode_pengiriman' => $request->metode_pengiriman,
            'ongkir'            => $ongkir,
        ]);

        // Kirim push notification ke admin
        try {
            $fcm = new FcmService();
            $fcm->sendToAllAdmins(
                '💰 Bukti Pembayaran Masuk',
                Auth::user()->nama . ' mengunggah bukti pembayaran untuk pesanan #TRX-' . $id . '.'
            );
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in uploadBuktiUser: ' . $e->getMessage());
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    // ================================================================
    // 12. ADMIN: ASSIGN TERNAK KE DETAIL TRANSAKSI
    // ================================================================
    public function assignTernakAdmin(Request $request, $id)
    {
        $request->validate([
            'id_ternak' => 'required|exists:ternak,id_ternak',
        ]);

        $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);

        // Guard: Cegah assign ke transaksi yang bukan pending/diproses
        if (!in_array($transaksi->status, ['pending', 'diproses'])) {
            return back()->withErrors(['assign' => 'Tidak bisa assign ternak ke transaksi berstatus ' . $transaksi->status . '.']);
        }

        if ($transaksi->detailTransaksi->count() >= $transaksi->total_jumlah) {
            return back()->withErrors(['assign' => 'Semua slot ternak sudah terisi.']);
        }

        if ($transaksi->detailTransaksi->where('id_ternak', $request->id_ternak)->count() > 0) {
            return back()->withErrors(['assign' => 'Ternak ini sudah di-assign ke transaksi ini.']);
        }

        $ternak = Ternak::findOrFail($request->id_ternak);

        // Guard: Cegah assign ternak yang sudah di-booking transaksi lain
        if ($ternak->status_jual !== 'siap jual') {
            return back()->withErrors(['assign' => 'Ternak #' . $ternak->id_ternak . ' tidak tersedia (status: ' . $ternak->status_jual . ').']);
        }

        DetailTransaksi::create([
            'sub_jumlah'   => 1,
            'sub_total'    => $ternak->harga,
            'id_ternak'    => $ternak->id_ternak,
            'id_transaksi' => $transaksi->id_transaksi,
        ]);

        // Booking ternak yang di-assign
        $ternak->update(['status_jual' => 'booking']);

        return back()->with('success', 'Ternak #' . $ternak->id_ternak . ' berhasil di-assign ke pesanan.');
    }

    // ================================================================
    // 13. ADMIN: HAPUS TERNAK DARI DETAIL TRANSAKSI
    // ================================================================
    public function removeDetailTernakAdmin($id)
    {
        $detail = DetailTransaksi::findOrFail($id);

        // Kembalikan status ternak
        Ternak::where('id_ternak', $detail->id_ternak)->update(['status_jual' => 'siap jual']);

        $detail->delete();

        return back()->with('success', 'Ternak berhasil dihapus dari pesanan.');
    }

}

