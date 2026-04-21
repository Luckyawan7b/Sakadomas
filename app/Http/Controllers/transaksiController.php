<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Cloudinary;
use App\Models\transaksiModel;
use App\Models\ternakModel;
use App\Models\kandangModel;
use App\Models\kamarModel;
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
        $data_transaksi = transaksiModel::with(['akun', 'ternak.jenis_ternak'])
            ->whereIn('status', ['pending', 'diproses', 'dikirim'])
            ->orderBy('tgl_transaksi', 'desc')
            ->paginate(10);

        // Ambil data ternak yang bisa dibeli untuk form modal tambah
        $data_ternak = ternakModel::with('jenis_ternak')
            ->whereIn('status_jual', ['siap jual', 'tidak dijual'])
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
            'id_ternak'         => 'required|exists:ternak,id_ternak',
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
            'id_akun'           => 1,
            'id_ternak'         => $request->id_ternak,
            'tgl_transaksi'     => Carbon::now(),
            'total_jumlah'      => $request->total_jumlah,
            'total_harga'       => $request->total_harga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran'  => $uploadedFileUrl,
            'kurir'             => $request->kurir ?? '-',
            'no_kurir'          => $request->no_kurir ?? '-',
            'status'            => $request->status,
        ]);

        if (in_array($request->status, ['diproses', 'dikirim', 'selesai'])) {
            ternakModel::where('id_ternak', $request->id_ternak)->update(['status_jual' => 'terjual']);
        } elseif ($request->status == 'pending') {
            ternakModel::where('id_ternak', $request->id_ternak)->update(['status_jual' => 'booking']);
        }

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

        $transaksi = transaksiModel::findOrFail($id);

        $transaksi->update([
            'status'   => $request->status,
            'kurir'    => $request->kurir ?? $transaksi->kurir,
            'no_kurir' => $request->no_kurir ?? $transaksi->no_kurir,
        ]);

        // Jika transaksi selesai/batal, kembalikan atau kunci status ternaknya
        if ($request->status == 'selesai') {
            ternakModel::where('id_ternak', $transaksi->id_ternak)->update(['status_jual' => 'terjual']);
        } elseif ($request->status == 'batal') {
            ternakModel::where('id_ternak', $transaksi->id_ternak)->update(['status_jual' => 'siap jual']);
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
        $query = transaksiModel::with(['akun', 'ternak.jenis_ternak'])->orderBy('tgl_transaksi', 'desc');

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
            'id_jenis_ternak'   => 'required',
            'total_jumlah'      => 'required|integer|min:1',
            'total_harga'       => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:transfer,cash',
            'bukti_pembayaran'  => 'required_if:metode_pembayaran,transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Trik Auto-Booking: Hitung harga per ekor untuk mencari ternak spesifik di kandang
        $harga_per_ekor = $request->total_harga / $request->total_jumlah;

        // Ambil ID ternak fisik yang benar-benar tersedia
        $ternak_tersedia = \App\Models\ternakModel::where('id_jenis_ternak', $request->id_jenis_ternak)
            ->where('harga', $harga_per_ekor)
            ->where('status_jual', 'siap jual')
            ->where('status_ternak', 'sehat')
            ->take($request->total_jumlah)
            ->get();

        // Keamanan Ganda: Cek apakah selama proses ngisi form, domba keburu dibeli orang lain
        if ($ternak_tersedia->count() < $request->total_jumlah) {
            return back()->withErrors(['stok' => 'Mohon maaf, stok domba kriteria ini baru saja habis/tidak mencukupi.'])->withInput();
        }

        // 3. Upload Bukti Transfer ke Cloudinary (Jika Ada)
        $uploadedFileUrl = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadedFileUrl = $this->uploadKeCloudinary($request->file('bukti_pembayaran'));
        }

        // 4. Buat Record Transaksi Utama
        \App\Models\transaksiModel::create([
            'id_akun'           => \Illuminate\Support\Facades\Auth::id(), // Deteksi User yang login
            'id_ternak'         => $ternak_tersedia->first()->id_ternak, // Gunakan 1 ID sebagai perwakilan relasi
            'tgl_transaksi'     => \Carbon\Carbon::now(),
            'total_jumlah'      => $request->total_jumlah,
            'total_harga'       => $request->total_harga,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran'  => $uploadedFileUrl,
            'kurir'             => '-',
            'no_kurir'          => '-',
            'status'            => 'pending',
        ]);

        // 5. Kunci (Booking) SEMUA domba yang dipesan agar stok di sistem otomatis berkurang!
        foreach ($ternak_tersedia as $ternak) {
            $ternak->update(['status_jual' => 'booking']);
        }

        // 6. Selesai
        return back()->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi dari Admin.');
    }

}
