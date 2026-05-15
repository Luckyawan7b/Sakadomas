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
    public function indexAdmin()
    {
        $data_survei = Survei::with(['akun', 'transaksi'])->orderBy('tgl_survei', 'desc')->get();
        $data_akun = Akun::where('role', 'pelanggan')
                              ->select('id_akun', 'nama', 'username')
                              ->get();

        return view('pages.survei', compact('data_survei', 'data_akun'));
    }

    // ================================================================
    // PELANGGAN: Halaman Jadwal Kunjungan (Kunjungan mandiri saja)
    // ================================================================
    public function indexUser()
    {
        $user = Auth::user();

        $data_survei = Survei::with(['akun'])
                        ->where('id_akun', $user->id_akun)
                        ->whereNull('id_transaksi')
                        ->orderBy('tgl_survei', 'desc')
                        ->get();

        return view('pages.kunjungan-user', compact('data_survei'));
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
                $fcm->sendToUser($id_akun,
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
            'ket' => 'nullable|string',
        ]);

        if ($this->cekBentrokanJadwal($request->tanggal_survei, $request->waktu_survei)) {
            return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        Survei::create([
            'tgl_survei' => $tgl_survei_gabungan,
            'status' => 'pending',
            'ket' => $request->ket,
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
                    ->whereNull('id_transaksi')
                    ->findOrFail($id);

        if (strtolower(trim($survei->status)) !== 'pending') {
            abort(403, 'Anda hanya dapat mengubah jadwal yang masih berstatus Pending.');
        }

        $maxDate = Carbon::now()->addDays(7)->toDateString();

        $request->validate([
            'tanggal_survei' => 'required|date|after_or_equal:today|before_or_equal:' . $maxDate,
            'waktu_survei' => 'required',
            'ket' => 'nullable|string',
        ]);

        if ($this->cekBentrokanJadwal($request->tanggal_survei, $request->waktu_survei, $id)) {
            return back()->withErrors(['waktu_survei' => 'Maaf, jadwal pada tanggal dan jam tersebut sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        $survei->update([
            'tgl_survei' => $tgl_survei_gabungan,
            'ket' => $request->ket,
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
    public function deleteUser($id)
    {
        $survei = Survei::where('id_akun', Auth::id())
                    ->whereNull('id_transaksi')
                    ->findOrFail($id);

        if (strtolower(trim($survei->status)) !== 'pending') {
            abort(403, 'Anda hanya dapat membatalkan jadwal yang masih berstatus Pending.');
        }

        $survei->delete();

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


