<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;
use App\Models\transaksiModel;
use App\Models\ternakModel;
use App\Models\kandangModel;
use App\Models\kamarModel;
use App\Models\surveiModel;
use App\Models\detailTransaksiModel;
use App\Models\keuanganModel;
use Carbon\Carbon;

class transaksiController extends Controller
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
    private function selesaikanTransaksi(transaksiModel $transaksi): void
    {
        // Update status ternak → terjual, keluarkan dari kandang
        foreach ($transaksi->detailTransaksi as $detail) {
            ternakModel::where('id_ternak', $detail->id_ternak)->update([
                'status_jual' => 'terjual',
                'id_kamar'    => null,
            ]);
        }

        // Buat record keuangan
        keuanganModel::create([
            'ket'             => 'Pemasukan dari transaksi #TRX-' . $transaksi->id_transaksi,
            'tanggal'         => Carbon::now()->toDateString(),
            'nominal'         => $transaksi->total_harga,
            'jenis_keuangan'  => 'pemasukan',
            'id_transaksi'    => $transaksi->id_transaksi,
        ]);
    }

    /**
     * Helper: Batalkan transaksi (kembalikan ternak ke siap jual)
     */
    private function batalkanTransaksi(transaksiModel $transaksi): void
    {
        foreach ($transaksi->detailTransaksi as $detail) {
            ternakModel::where('id_ternak', $detail->id_ternak)->update([
                'status_jual' => 'siap jual',
            ]);
        }
    }

    // ================================================================
    // 1. TAMPILAN OPERASIONAL (Pending, Diproses, Dikirim)
    // ================================================================
    public function indexAdmin()
    {
        $data_transaksi = transaksiModel::with(['akun', 'jenisTernak', 'detailTransaksi.ternak.jenis_ternak', 'survei'])
            ->whereIn('status', ['pending', 'diproses', 'dikirim'])
            ->orderBy('tgl_transaksi', 'desc')
            ->paginate(10);

        $data_ternak = ternakModel::with(['jenis_ternak', 'kamar'])
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->get();

        $data_kandang = kandangModel::all();
        $data_kamar = kamarModel::all();

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
        ]);

        $uploadedFileUrl = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        transaksiModel::create([
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

        $transaksi = transaksiModel::with('detailTransaksi')->findOrFail($id);
        $newStatus = $request->status;

        $updateData = [
            'status'   => $newStatus,
            'kurir'    => $request->kurir ?? $transaksi->kurir,
            'no_kurir' => $request->no_kurir ?? $transaksi->no_kurir,
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

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    // ================================================================
    // 4. HAPUS TRANSAKSI
    // ================================================================
    public function deleteAdmin($id)
    {
        $transaksi = transaksiModel::findOrFail($id);
        $transaksi->delete();

        return back()->with('success', 'Data transaksi berhasil dihapus.');
    }

    // ================================================================
    // 5. REKAP TRANSAKSI
    // ================================================================
    public function rekapAdmin(Request $request)
    {
        $query = transaksiModel::with(['akun', 'jenisTernak'])->orderBy('tgl_transaksi', 'desc');

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
        $ternak_tersedia = ternakModel::with('jenis_ternak')
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
                            foreach ($ageCat['weight_classes'] as $wClass) {
                                // Cek apakah berat sesuai dengan rentang min & max di JSON
                                if ($berat >= $wClass['min_weight'] && $berat <= $wClass['max_weight']) {
                                    $kelasBerat = $wClass['class_name'];
                                    break 3; // Keluar dari loop jika sudah ketemu
                                }
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

        return view('pages.transaksi-user', compact('jenis_ternak'));
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
            $bentrok = \App\Models\surveiModel::where('tgl_survei', $tgl_survei_gabungan)
                ->where('status', '!=', 'batal')
                ->exists();
            if ($bentrok) {
                return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
            }
        }

        // 2. Cek ketersediaan stok
        $harga_per_ekor = $request->total_harga / $request->total_jumlah;

        $stok_tersedia = ternakModel::where('id_jenis_ternak', $request->id_jenis_ternak)
            ->where('jenis_kelamin', $request->jenis_kelamin_pesanan)
            ->where('harga', $harga_per_ekor)
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->count();

        if ($stok_tersedia < $request->total_jumlah) {
            return back()->withErrors(['stok' => 'Mohon maaf, stok domba kriteria ini tidak mencukupi (tersedia: ' . $stok_tersedia . ' ekor).'])->withInput();
        }

        // 3. Upload Bukti Transfer (hanya jika bukan survei dan metode transfer)
        $uploadedFileUrl = null;
        if (!$isSurvei && $request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        // 4. Buat Transaksi
        $now = Carbon::now();
        $transaksi = transaksiModel::create([
            'id_akun'               => Auth::id(),
            'id_jenis_ternak'       => $request->id_jenis_ternak,
            'jenis_kelamin_pesanan' => $request->jenis_kelamin_pesanan,
            'tgl_transaksi'         => $now,
            'total_jumlah'          => $request->total_jumlah,
            'total_harga'           => $request->total_harga,
            'metode_pembayaran'     => $isSurvei ? null : $request->metode_pembayaran,
            'bukti_pembayaran'      => $uploadedFileUrl,
            'kurir'                 => '-',
            'no_kurir'              => '-',
            'status'                => 'pending',
            'is_survei'             => $isSurvei,
            'batas_survei'          => $isSurvei ? $now->copy()->addDays(7)->toDateString() : null,
        ]);

        // 5. Buat Survei jika diminta
        if ($isSurvei) {
            $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';
            surveiModel::create([
                'tgl_survei'    => $tgl_survei_gabungan,
                'status'        => 'pending',
                'ket'           => $request->ket_survei ?? 'Survei untuk transaksi #TRX-' . $transaksi->id_transaksi,
                'id_akun'       => Auth::id(),
                'id_transaksi'  => $transaksi->id_transaksi,
            ]);
        }

        return redirect()->route('transaksi.riwayat')->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi dari Admin.');
    }

    // ================================================================
    // 8. RIWAYAT PESANAN USER
    // ================================================================
    public function riwayatUser(Request $request)
    {
        $query = transaksiModel::with(['jenisTernak', 'detailTransaksi.ternak.jenis_ternak', 'survei'])
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

        $allUserTrx = transaksiModel::where('id_akun', Auth::id());
        $stats = [
            'total'     => (clone $allUserTrx)->count(),
            'pending'   => (clone $allUserTrx)->where('status', 'pending')->count(),
            'diproses'  => (clone $allUserTrx)->where('status', 'diproses')->count(),
            'dikirim'   => (clone $allUserTrx)->where('status', 'dikirim')->count(),
            'selesai'   => (clone $allUserTrx)->where('status', 'selesai')->count(),
            'batal'     => (clone $allUserTrx)->where('status', 'batal')->count(),
        ];

        return view('pages.riwayat-user', compact('data_transaksi', 'stats'));
    }

    // ================================================================
    // 9. USER CANCEL PESANAN
    // ================================================================
    public function cancelPesananUser($id)
    {
        $transaksi = transaksiModel::with('detailTransaksi')->where('id_akun', Auth::id())->findOrFail($id);

        if (in_array($transaksi->status, ['dikirim', 'selesai', 'batal'])) {
            return back()->withErrors(['cancel' => 'Pesanan tidak bisa dibatalkan karena sudah ' . $transaksi->status . '.']);
        }

        if ($transaksi->metode_pembayaran === 'transfer' && !empty($transaksi->bukti_pembayaran)) {
            return back()->withErrors(['cancel' => 'Pesanan dengan metode transfer yang sudah dikirimkan bukti pembayarannya tidak dapat dibatalkan.']);
        }

        $this->batalkanTransaksi($transaksi);
        $transaksi->update(['status' => 'batal']);

        return back()->with('success', 'Pesanan #TRX-' . $id . ' berhasil dibatalkan.');
    }

    // ================================================================
    // 10. USER: SELESAIKAN PESANAN (Pesanan Diterima)
    // ================================================================
    public function selesaiPesananUser($id)
    {
        $transaksi = transaksiModel::with('detailTransaksi')->where('id_akun', Auth::id())->findOrFail($id);

        if ($transaksi->status !== 'dikirim') {
            return back()->withErrors(['selesai' => 'Pesanan hanya bisa diselesaikan jika berstatus dikirim.']);
        }

        $transaksi->update(['status' => 'selesai']);
        $this->selesaikanTransaksi($transaksi);

        return back()->with('success', 'Pesanan #TRX-' . $id . ' berhasil diselesaikan. Terima kasih!');
    }

    // ================================================================
    // 11. USER: UPLOAD BUKTI PEMBAYARAN (setelah survei selesai)
    // ================================================================
    public function uploadBuktiUser(Request $request, $id)
    {
        $transaksi = transaksiModel::with('survei')->where('id_akun', Auth::id())->findOrFail($id);

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
        ]);

        $uploadedFileUrl = $transaksi->bukti_pembayaran;
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        $transaksi->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran'  => $uploadedFileUrl,
        ]);

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

        $transaksi = transaksiModel::with('detailTransaksi')->findOrFail($id);

        if ($transaksi->detailTransaksi->count() >= $transaksi->total_jumlah) {
            return back()->withErrors(['assign' => 'Semua slot ternak sudah terisi.']);
        }

        if ($transaksi->detailTransaksi->where('id_ternak', $request->id_ternak)->count() > 0) {
            return back()->withErrors(['assign' => 'Ternak ini sudah di-assign ke transaksi ini.']);
        }

        $ternak = ternakModel::findOrFail($request->id_ternak);

        detailTransaksiModel::create([
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
        $detail = detailTransaksiModel::findOrFail($id);

        // Kembalikan status ternak
        ternakModel::where('id_ternak', $detail->id_ternak)->update(['status_jual' => 'siap jual']);

        $detail->delete();

        return back()->with('success', 'Ternak berhasil dihapus dari pesanan.');
    }

}
