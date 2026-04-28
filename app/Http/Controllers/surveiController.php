<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\surveiModel;
use App\Models\akunModel;
use App\Models\transaksiModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class surveiController extends Controller
{
    // ================================================================
    // HELPER: Cek Bentrokan Jadwal
    // ================================================================
    private function cekBentrokanJadwal($tanggal, $waktu, $ignoreId = null)
    {
        $tgl_survei_gabungan = $tanggal . ' ' . $waktu . ':00';
        $query = surveiModel::where('tgl_survei', $tgl_survei_gabungan)
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

        $jadwalTerisi = surveiModel::whereDate('tgl_survei', $tanggal)
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
        $data_survei = surveiModel::with(['akun', 'transaksi'])->orderBy('tgl_survei', 'desc')->get();
        $data_akun = akunModel::where('role', 'pelanggan')
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

        $data_survei = surveiModel::with(['akun'])
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

        surveiModel::create([
            'tgl_survei' => $tgl_survei_gabungan,
            'status' => 'pending',
            'ket' => $request->ket,
            'id_akun' => $id_akun,
            'id_transaksi' => $request->id_transaksi ?? null,
        ]);

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

        surveiModel::create([
            'tgl_survei' => $tgl_survei_gabungan,
            'status' => 'pending',
            'ket' => $request->ket,
            'id_akun' => Auth::id(),
            'id_transaksi' => null,
        ]);

        return back()->with('success', 'Jadwal kunjungan berhasil diajukan!');
    }

    // ================================================================
    // ADMIN: Update survei (status, keterangan batal, dll)
    // ================================================================
    public function updateAdmin(Request $request, $id)
    {
        $survei = surveiModel::findOrFail($id);

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

        return back()->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    // ================================================================
    // PELANGGAN: Update kunjungan mandiri (hanya jika pending)
    // ================================================================
    public function updateUser(Request $request, $id)
    {
        $survei = surveiModel::where('id_akun', Auth::id())
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
        $survei = surveiModel::findOrFail($id);
        $survei->delete();

        return back()->with('success', 'Data kunjungan berhasil dihapus.');
    }

    // ================================================================
    // PELANGGAN: Batalkan kunjungan mandiri (hanya jika pending)
    // ================================================================
    public function deleteUser($id)
    {
        $survei = surveiModel::where('id_akun', Auth::id())
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
        $transaksi = transaksiModel::where('id_akun', Auth::id())->findOrFail($id);

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

        surveiModel::create([
            'tgl_survei'    => $tgl_survei_gabungan,
            'status'        => 'pending',
            'ket'           => $request->ket_survei ?? 'Pengajuan ulang survei untuk transaksi #TRX-' . $transaksi->id_transaksi,
            'id_akun'       => Auth::id(),
            'id_transaksi'  => $transaksi->id_transaksi,
        ]);

        return back()->with('success', 'Survei berhasil diajukan ulang!');
    }
}
