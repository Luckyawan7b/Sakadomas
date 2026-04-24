<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;
use App\Models\transaksiModel;
use App\Models\ternakModel;
use App\Models\kandangModel;
use App\Models\kamarModel;
use App\Models\surveiModel;
use App\Models\detailTransaksiModel;
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

    // 1. TAMPILAN OPERASIONAL (Pending, Diproses, Dikirim)
    public function index()
    {
        // Hanya ambil transaksi yang sedang berjalan
        $data_transaksi = transaksiModel::with(['akun', 'jenisTernak', 'detailTransaksi.ternak.jenis_ternak'])
            ->whereIn('status', ['pending', 'diproses', 'dikirim'])
            ->orderBy('tgl_transaksi', 'desc')
            ->paginate(10);

        // Ambil data ternak yang bisa dibeli untuk form modal tambah
        $data_ternak = ternakModel::with(['jenis_ternak', 'kamar'])
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->get();

        // TAMBAHAN BARU: Ambil data kandang & kamar untuk Nested Dropdown
        $data_kandang = kandangModel::all();
        $data_kamar = kamarModel::all();

        return view('pages.transaksi', compact('data_transaksi', 'data_ternak', 'data_kandang', 'data_kamar'));
    }

    // 2. TAMBAH TRANSAKSI DARI ADMIN
    public function store(Request $request)
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

    // 3. EDIT TRANSAKSI
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'   => 'required|string',
            'kurir'    => 'nullable|string|max:50',
            'no_kurir' => 'nullable|string|max:20',
        ]);

        $transaksi = transaksiModel::with('detailTransaksi')->findOrFail($id);

        $transaksi->update([
            'status'   => $request->status,
            'kurir'    => $request->kurir ?? $transaksi->kurir,
            'no_kurir' => $request->no_kurir ?? $transaksi->no_kurir,
        ]);

        // Update status ternak berdasarkan detail_transaksi
        if ($request->status == 'selesai') {
            foreach ($transaksi->detailTransaksi as $detail) {
                ternakModel::where('id_ternak', $detail->id_ternak)->update(['status_jual' => 'terjual']);
            }
        } elseif ($request->status == 'batal') {
            foreach ($transaksi->detailTransaksi as $detail) {
                ternakModel::where('id_ternak', $detail->id_ternak)->update(['status_jual' => 'siap jual']);
            }
        }

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    // 4. HAPUS TRANSAKSI
    public function delete($id)
    {
        $transaksi = transaksiModel::findOrFail($id);
        $transaksi->delete();

        return back()->with('success', 'Data transaksi berhasil dihapus.');
    }

    // 5. REKAP TRANSAKSI (Fungsi untuk melengkapi halaman laporan sebelumnya)
    public function rekap(Request $request)
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

    public function createPesananUser()
    {
        $ternak_tersedia = \App\Models\ternakModel::with('jenis_ternak')
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->get();

        $jenis_ternak = $ternak_tersedia->map(function ($item) {
            $usia = $item->usia;
            $berat = $item->berat;
            $breed = $item->jenis_ternak->jenis_ternak;

            // 1. Tentukan Kategori Usia
            if ($usia <= 5) { $katUsia = 'Anakan/Bibit'; }
            elseif ($usia <= 11) { $katUsia = 'Doro/Muda'; }
            else { $katUsia = 'Indukan/Dewasa'; }

            // 2. Tentukan Kelas Berat (Logic sesuai value.json)
            // Contoh untuk Anakan (10-25kg), silakan sesuaikan rentang untuk kategori lain
            $kelasBerat = 'Uncategorized';
            if ($usia <= 5) {
                if ($berat >= 10 && $berat <= 14) $kelasBerat = 'Standard';
                elseif ($berat >= 15 && $berat <= 19) $kelasBerat = 'Medium';
                elseif ($berat >= 20 && $berat <= 25) $kelasBerat = 'Super';
            } else {
                // Logic rentang berat untuk Doro/Indukan bisa ditambahkan di sini
                if ($berat <= 25) $kelasBerat = 'Standard';
                elseif ($berat <= 35) $kelasBerat = 'Medium';
                else $kelasBerat = 'Super';
            }

            return [
                'id_jenis' => $item->id_jenis_ternak,
                'nama_produk' => $breed . ' - ' . $katUsia,
                'kelas_berat' => $kelasBerat,
                'jenis_kelamin' => $item->jenis_kelamin,
                'harga' => $item->harga,
            ];
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

    public function storePesananUser(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_jenis_ternak'       => 'required',
            'jenis_kelamin_pesanan' => 'required|string',
            'total_jumlah'          => 'required|integer|min:1',
            'total_harga'           => 'required|numeric|min:0',
            'metode_pembayaran'     => 'required|in:transfer,cash',
            'bukti_pembayaran'      => 'required_if:metode_pembayaran,transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Cek ketersediaan stok (preliminary check)
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

        // 3. Handle Survei (jika diminta)
        if ($request->has('is_survei') && $request->is_survei == 1) {
            $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';
            surveiModel::create([
                'tgl_survei' => $tgl_survei_gabungan,
                'status'     => 'pending',
                'ket'        => $request->ket_survei ?? 'Survei untuk transaksi terbaru.',
                'id_akun'    => Auth::id(),
            ]);
        }

        // 4. Upload Bukti Transfer ke Cloudinary (Jika Ada)
        $uploadedFileUrl = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        // 5. Buat Transaksi — Simpan kriteria pesanan, admin assign nanti
        transaksiModel::create([
            'id_akun'               => Auth::id(),
            'id_jenis_ternak'       => $request->id_jenis_ternak,
            'jenis_kelamin_pesanan' => $request->jenis_kelamin_pesanan,
            'tgl_transaksi'         => Carbon::now(),
            'total_jumlah'          => $request->total_jumlah,
            'total_harga'           => $request->total_harga,
            'metode_pembayaran'     => $request->metode_pembayaran,
            'bukti_pembayaran'      => $uploadedFileUrl,
            'kurir'                 => '-',
            'no_kurir'              => '-',
            'status'                => 'pending',
        ]);

        return redirect()->route('transaksi.riwayat')->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi dari Admin.');
    }

    // 7. RIWAYAT PESANAN USER
    public function riwayatUser(Request $request)
    {
        $query = transaksiModel::with(['jenisTernak', 'detailTransaksi.ternak.jenis_ternak'])
            ->where('id_akun', Auth::id())
            ->orderBy('tgl_transaksi', 'desc');

        // Filter Status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // Pencarian cepat (ID Transaksi)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('id_transaksi', 'like', "%{$q}%");
        }

        $data_transaksi = $query->paginate(10);

        // Hitung ringkasan statistik
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

    // 8. USER CANCEL PESANAN
    public function cancelPesananUser($id)
    {
        $transaksi = transaksiModel::with('detailTransaksi')->where('id_akun', Auth::id())->findOrFail($id);

        if (in_array($transaksi->status, ['dikirim', 'selesai', 'batal'])) {
            return back()->withErrors(['cancel' => 'Pesanan tidak bisa dibatalkan karena sudah ' . $transaksi->status . '.']);
        }

        // Kembalikan status semua ternak yang sudah di-assign
        foreach ($transaksi->detailTransaksi as $detail) {
            ternakModel::where('id_ternak', $detail->id_ternak)->update(['status_jual' => 'siap jual']);
        }

        $transaksi->update(['status' => 'batal']);

        return back()->with('success', 'Pesanan #TRX-' . $id . ' berhasil dibatalkan.');
    }

    // 9. ADMIN: ASSIGN TERNAK KE DETAIL TRANSAKSI
    public function assignTernak(Request $request, $id)
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

    // 10. ADMIN: HAPUS TERNAK DARI DETAIL TRANSAKSI
    public function removeDetailTernak($id)
    {
        $detail = detailTransaksiModel::findOrFail($id);

        // Kembalikan status ternak
        ternakModel::where('id_ternak', $detail->id_ternak)->update(['status_jual' => 'siap jual']);

        $detail->delete();

        return back()->with('success', 'Ternak berhasil dihapus dari pesanan.');
    }

}
