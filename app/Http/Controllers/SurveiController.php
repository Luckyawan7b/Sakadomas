<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FcmService;
use App\Models\Survei;
use App\Models\Akun;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SurveiController extends Controller
{
    // ================================================================
    // HELPER: Cek Bentrokan Jadwal
    // ================================================================
    private function cekBentrokanJadwal($tanggal, $waktu, $ignoreId = null)
    {
        $tgl_survei_gabungan = $tanggal . ' ' . $waktu . ':00';
        $query = Survei::where('tgl_survei', $tgl_survei_gabungan)
            ->where('status', '!=', 'batal');

        if ($ignoreId) {
            $query->where('id_survei', '!=', $ignoreId);
        }

        if ($query->exists()) {
            return true; // Ada bentrokan
        }
        return false;
    }

    // ================================================================
    // API: Cek Jadwal yang Sudah Terisi
    // ================================================================
    public function cekJadwal(Request $request)
    {
        $tanggal = $request->query('tanggal');
        if (!$tanggal) {
            return response()->json([]);
        }

        $jadwalTerisi = Survei::whereDate('tgl_survei', $tanggal)
            ->where('status', '!=', 'batal')
            ->pluck('tgl_survei')
            ->map(function ($date) {
                return Carbon::parse($date)->format('H:i');
            })
            ->toArray();

        return response()->json($jadwalTerisi);
    }
    // ================================================================
    // ADMIN: Halaman Manajemen Kunjungan (Semua data survei)
    // ================================================================
    public function indexAdmin(Request $request)
    {
        $query = Survei::with(['akun', 'transaksi'])->orderBy('tgl_survei', 'desc');

        // 1. Filter Status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // 2. Filter Tanggal (Awal & Akhir)
        if ($request->filled('tgl_awal')) {
            $query->whereDate('tgl_survei', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tgl_survei', '<=', $request->tgl_akhir);
        }

        // 3. Pencarian Nama/Username Pengguna (Relasi Akun)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereHas('akun', function ($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(username) LIKE ?', ["%{$search}%"]);
            });
        }

        $data_survei = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'data' => $data_survei->map(function ($s) {
                    return [
                        'id_survei' => $s->id_survei,
                        'tgl_survei' => $s->tgl_survei,
                        'ket' => $s->ket,
                        'status' => $s->status,
                        'ket_admin' => $s->ket_admin,
                        'id_transaksi' => $s->id_transaksi,
                        'akun' => [
                            'id_akun' => $s->akun->id_akun ?? null,
                            'nama' => $s->akun->nama ?? 'Akun Terhapus',
                            'no_hp' => $s->akun->no_hp ?? '-'
                        ]
                    ];
                }),
                'pagination' => [
                    'current_page' => $data_survei->currentPage(),
                    'last_page' => $data_survei->lastPage(),
                    'total' => $data_survei->total(),
                    'from' => $data_survei->firstItem(),
                    'to' => $data_survei->lastItem(),
                ],
            ]);
        }

        $data_akun = Akun::where('role', 'pelanggan')
            ->select('id_akun', 'nama', 'username')
            ->get();

        // Siapkan data JSON bawaan agar komponen alpine memiliki data tanpa memanggil API pada load pertama
        $data_survei_json = $data_survei->map(function ($s) {
            return [
                'id_survei' => $s->id_survei,
                'tgl_survei' => $s->tgl_survei,
                'ket' => $s->ket,
                'status' => $s->status,
                'ket_admin' => $s->ket_admin,
                'id_transaksi' => $s->id_transaksi,
                'akun' => [
                    'id_akun' => $s->akun->id_akun ?? null,
                    'nama' => $s->akun->nama ?? 'Akun Terhapus',
                    'no_hp' => $s->akun->no_hp ?? '-'
                ]
            ];
        });

        // Hitung statistik untuk summary/widget (opsional tapi bisa berguna jika user ingin menambah total summary nanti)
        // Namun saat ini survei.blade.php memiliki `counts` perhitungan statis.
        // Kita cukup memberikan variabel saja.
        return view('pages.survei', compact('data_survei', 'data_survei_json', 'data_akun'));
    }

    // ================================================================
    // PELANGGAN: Halaman Jadwal Kunjungan (Semua aktif)
    // ================================================================
    public function indexUser()
    {
        $user = Auth::user();

        $semuaSurvei = Survei::with(['akun', 'transaksi'])
            ->where('id_akun', $user->id_akun)
            ->orderBy('tgl_survei', 'desc')
            ->get();

        $jadwalAktif = $semuaSurvei->filter(function ($survei) {
            return in_array(strtolower($survei->status), ['pending', 'disetujui']);
        });

        $riwayatTerbaru = $semuaSurvei->filter(function ($survei) {
            return in_array(strtolower($survei->status), ['selesai', 'batal']);
        })->take(3); // Menampilkan maksimal 3 riwayat terbaru

        return view('landing.survei', compact('jadwalAktif', 'riwayatTerbaru'));
    }

    // ================================================================
    // PELANGGAN: Halaman Semua Riwayat Survei
    // ================================================================
    public function riwayatUser()
    {
        $user = Auth::user();

        $riwayatSemua = Survei::with(['akun', 'transaksi'])
            ->where('id_akun', $user->id_akun)
            ->whereIn('status', ['selesai', 'batal'])
            ->orderBy('tgl_survei', 'desc')
            ->get();

        return view('landing.riwayat_survei', compact('riwayatSemua'));
    }

    // ================================================================
    // ADMIN: Buat jadwal survei (bisa pilih user)
    // ================================================================
    public function storeAdmin(Request $request)
    {
        $maxDate = Carbon::now()->addDays(7)->toDateString();

        $request->validate([
            'tanggal_survei' => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'waktu_survei' => 'required',
            'ket' => 'nullable|string',
            'id_akun' => 'nullable|exists:akun,id_akun',
            'id_transaksi' => 'nullable|exists:transaksi,id_transaksi',
        ]);

        $id_akun = $request->id_akun ?? Auth::id();

        if ($this->cekBentrokanJadwal($request->tanggal_survei, $request->waktu_survei)) {
            return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        Survei::create([
            'tgl_survei' => $tgl_survei_gabungan,
            'status' => 'pending',
            'ket' => $request->ket,
            'id_akun' => $id_akun,
            'id_transaksi' => $request->id_transaksi ?? null,
        ]);

        // Kirim push notification ke pelanggan yang dijadwalkan
        if ($id_akun != Auth::id()) {
            try {
                $fcm = new FcmService();
                $fcm->sendToUser(
                    $id_akun,
                    '📅 Kunjungan Dijadwalkan',
                    'Admin menjadwalkan kunjungan Anda pada ' . \Carbon\Carbon::parse($tgl_survei_gabungan)->translatedFormat('d M Y H:i') . '.'
                );
            } catch (\Throwable $e) {
                Log::error('FCM notification failed in storeAdmin: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Jadwal kunjungan berhasil dibuat.');
    }

    // ================================================================
    // PELANGGAN: Ajukan kunjungan mandiri (tanpa transaksi)
    // ================================================================
    public function storeUser(Request $request)
    {
        $maxDate = Carbon::now()->addDays(7)->toDateString();

        $request->validate([
            'tanggal_survei' => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'waktu_survei' => 'required',
            'tujuan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        if ($this->cekBentrokanJadwal($request->tanggal_survei, $request->waktu_survei)) {
            return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        $ket_gabungan = '';
        if ($request->tujuan) {
            $tujuanList = [
                '1' => 'Konsultasi Pembelian / Kemitraan',
                '2' => 'Melihat Hewan Kurban',
                '3' => 'Melihat Bibit Ternak',
                '4' => 'Lainnya'
            ];
            $tujuanText = $tujuanList[$request->tujuan] ?? $request->tujuan;
            $ket_gabungan .= "Tujuan: " . $tujuanText . "\n";
        }
        if ($request->catatan) {
            $ket_gabungan .= "Catatan: " . $request->catatan;
        }

        Survei::create([
            'tgl_survei' => $tgl_survei_gabungan,
            'status' => 'pending',
            'ket' => $ket_gabungan ?: null,
            'id_akun' => Auth::id(),
            'id_transaksi' => null,
        ]);

        // Kirim push notification ke admin
        try {
            $fcm = new FcmService();
            $fcm->sendToAllAdmins(
                '📋 Permintaan Kunjungan',
                Auth::user()->nama . ' mengajukan kunjungan pada ' . \Carbon\Carbon::parse($tgl_survei_gabungan)->translatedFormat('d M Y H:i') . '.'
            );
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in storeUser: ' . $e->getMessage());
        }

        return back()->with('success', 'Jadwal kunjungan berhasil diajukan!');
    }

    // ================================================================
    // ADMIN: Update survei (status, keterangan batal, dll)
    // ================================================================
    public function updateAdmin(Request $request, $id)
    {
        $survei = Survei::findOrFail($id);

        $maxDate = Carbon::now()->addDays(7)->toDateString();

        $rules = [
            'tanggal_survei' => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'waktu_survei' => 'required',
            'ket' => 'nullable|string',
            'status' => 'required|in:pending,disetujui,selesai,batal',
            'ket_admin' => 'nullable|string',
        ];

        $request->validate($rules);

        if ($this->cekBentrokanJadwal($request->tanggal_survei, $request->waktu_survei, $id)) {
            return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        $dataUpdate = [
            'tgl_survei' => $tgl_survei_gabungan,
            'ket' => $request->ket,
            'status' => $request->status,
        ];

        // Jika admin batalkan, simpan pesan pembatalan
        if ($request->status === 'batal' && $request->filled('ket_admin')) {
            $dataUpdate['ket_admin'] = $request->ket_admin;
        }

        $survei->update($dataUpdate);

        // Kirim push notification ke pelanggan tentang update survei
        try {
            $fcm = new FcmService();
            $statusMessages = [
                'disetujui' => '✅ Kunjungan Disetujui',
                'selesai'   => '🎉 Kunjungan Selesai',
                'batal'     => '❌ Kunjungan Dibatalkan',
            ];
            if (isset($statusMessages[$request->status])) {
                $bodyMsg = match ($request->status) {
                    'disetujui' => 'Kunjungan Anda pada ' . \Carbon\Carbon::parse($tgl_survei_gabungan)->translatedFormat('d M Y H:i') . ' telah disetujui.',
                    'selesai'   => 'Kunjungan Anda telah selesai. Terima kasih!',
                    'batal'     => 'Kunjungan Anda dibatalkan.' . ($request->filled('ket_admin') ? ' Alasan: ' . $request->ket_admin : ''),
                };
                $fcm->sendToUser($survei->id_akun, $statusMessages[$request->status], $bodyMsg);
            }
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in updateAdmin survei: ' . $e->getMessage());
        }

        return back()->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    // ================================================================
    // PELANGGAN: Update kunjungan mandiri (hanya jika pending)
    // ================================================================
    public function updateUser(Request $request, $id)
    {
        $survei = Survei::where('id_akun', Auth::id())
            ->findOrFail($id); // Allow updating trx-linked too

        // Check 1x24 hours rule
        if (Carbon::parse($survei->tgl_survei)->diffInHours(now(), false) > -24) {
            return back()->withErrors(['survei' => 'Batas waktu perubahan jadwal adalah 1x24 Jam sebelum pelaksanaan.']);
        }

        if (strtolower(trim($survei->status)) !== 'pending' && strtolower(trim($survei->status)) !== 'disetujui') {
            abort(403, 'Anda hanya dapat mengubah jadwal yang masih aktif.');
        }

        $maxDate = Carbon::now()->addDays(7)->toDateString();

        $request->validate([
            'tanggal_survei' => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'waktu_survei' => 'required',
        ]);

        if ($this->cekBentrokanJadwal($request->tanggal_survei, $request->waktu_survei, $id)) {
            return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        $survei->update([
            'tgl_survei' => $tgl_survei_gabungan,
            'status' => 'pending' // Revert to pending for re-approval
        ]);

        return back()->with('success', 'Jadwal kunjungan berhasil diperbarui.');
    }

    // ================================================================
    // ADMIN: Hapus survei
    // ================================================================
    public function deleteAdmin($id)
    {
        $survei = Survei::findOrFail($id);
        $survei->delete();

        return back()->with('success', 'Data kunjungan berhasil dihapus.');
    }

    // ================================================================
    // PELANGGAN: Batalkan kunjungan mandiri (hanya jika pending)
    // ================================================================
    public function deleteUser(Request $request, $id)
    {
        $survei = Survei::where('id_akun', Auth::id())
            ->findOrFail($id);

        // Check 1x24 hours rule
        if (Carbon::parse($survei->tgl_survei)->diffInHours(now(), false) > -24) {
            return back()->withErrors(['survei' => 'Batas waktu pembatalan adalah 1x24 Jam sebelum pelaksanaan.']);
        }

        if (strtolower(trim($survei->status)) !== 'pending' && strtolower(trim($survei->status)) !== 'disetujui') {
            abort(403, 'Anda hanya dapat membatalkan jadwal yang masih aktif.');
        }

        $request->validate([
            'alasan' => 'required|string'
        ]);

        $alasanList = [
            '1' => 'Jadwal Bentrok',
            '2' => 'Sudah Beli di Tempat Lain',
            '3' => 'Berubah Pikiran',
            '4' => 'Lokasi Terlalu Jauh',
            '5' => 'Lainnya'
        ];
        $alasanText = $alasanList[$request->alasan] ?? $request->alasan;

        $ketBatal = "\nAlasan Batal: " . $alasanText;

        $survei->update([
            'status' => 'batal',
            'ket' => $survei->ket . $ketBatal
        ]);

        return back()->with('success', 'Jadwal kunjungan berhasil dibatalkan.');
    }

    // ================================================================
    // PELANGGAN: Ajukan ulang survei terkait transaksi (dari riwayat)
    // ================================================================
    public function ajukanUlang(Request $request, $id)
    {
        $transaksi = Transaksi::where('id_akun', Auth::id())->findOrFail($id);

        // Cek batas survei
        if ($transaksi->batas_survei && Carbon::parse($transaksi->batas_survei)->isPast()) {
            return back()->withErrors(['survei' => 'Batas waktu pengajuan survei (7 hari) telah habis.']);
        }

        $maxDate = Carbon::now()->addDays(7)->toDateString();
        // Jika batas_survei lebih kecil dari maxDate, gunakan batas_survei
        if ($transaksi->batas_survei && Carbon::parse($transaksi->batas_survei)->lt(Carbon::parse($maxDate))) {
            $maxDate = $transaksi->batas_survei;
        }

        $request->validate([
            'tanggal_survei' => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'waktu_survei'   => 'required',
            'ket_survei'     => 'nullable|string',
        ]);

        if ($this->cekBentrokanJadwal($request->tanggal_survei, $request->waktu_survei)) {
            return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        Survei::create([
            'tgl_survei'    => $tgl_survei_gabungan,
            'status'        => 'pending',
            'ket'           => $request->ket_survei ?? 'Pengajuan ulang survei untuk transaksi #TRX-' . $transaksi->id_transaksi,
            'id_akun'       => Auth::id(),
            'id_transaksi'  => $transaksi->id_transaksi,
        ]);

        // Kirim push notification ke admin
        try {
            $fcm = new FcmService();
            $fcm->sendToAllAdmins(
                '🔄 Pengajuan Ulang Survei',
                Auth::user()->nama . ' mengajukan ulang survei untuk transaksi #TRX-' . $transaksi->id_transaksi . '.'
            );
        } catch (\Throwable $e) {
            Log::error('FCM notification failed in ajukanUlang: ' . $e->getMessage());
        }

        return back()->with('success', 'Survei berhasil diajukan ulang!');
    }

    public function successPage($id)
    {
        $survei = Survei::with(['akun', 'transaksi'])->where('id_akun', Auth::id())->findOrFail($id);

        // Sisa waktu 24 jam dari dibuat
        $batasWaktu = Carbon::parse($survei->created_at)->addHours(24);
        $sisaDetik = max(0, Carbon::now()->diffInSeconds($batasWaktu, false));

        return view('landing.pengajuan-survei', compact('survei', 'sisaDetik'));
    }
}
