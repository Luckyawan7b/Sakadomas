<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\surveiModel;
use App\Models\akunModel;
use Illuminate\Support\Facades\Auth;

class surveiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $data_survei = surveiModel::with('akun')->orderBy('tgl_survei', 'desc')->get();
            $data_akun = akunModel::where('role', 'user')->get();
        }
        else {
            $data_survei = surveiModel::with('akun')
                            ->where('id_akun', $user->id_akun)
                            ->orderBy('tgl_survei', 'desc')
                            ->get();
            $data_akun = collect([$user]);
        }

        return view('pages.survei', compact('data_survei', 'data_akun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_survei' => 'required|date',
            'waktu_survei' => 'required',
            'ket' => 'nullable|string',
            'id_akun' => 'nullable|exists:akun,id_akun'
        ]);

        $user = Auth::user();

        $id_akun = ($user->role === 'admin' && $request->has('id_akun'))
                    ? $request->id_akun
                    : $user->id_akun;

        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        surveiModel::create([
            'tgl_survei' => $tgl_survei_gabungan,
            'status' => 'pending',
            'ket' => $request->ket,
            'id_akun' => $id_akun
        ]);

        return back()->with('success', 'Jadwal survei berhasil diajukan.');
    }

    public function update(Request $request, $id)
    {
        $survei = surveiModel::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $survei->id_akun !== $user->id_akun) {
            abort(403, 'Anda tidak diizinkan mengubah data ini.');
        }
        if ($user->role !== 'admin' && strtolower(trim($survei->status)) !== 'pending') {
            abort(403, 'Anda hanya dapat mengubah jadwal yang masih berstatus Pending.');
        }

        $rules = [
            'tanggal_survei' => 'required|date',
            'waktu_survei' => 'required',
            'ket' => 'nullable|string',
        ];

        if ($user->role === 'admin') {
            $rules['status'] = 'required|in:pending,disetujui,selesai,batal';
        }

        $request->validate($rules);
        $tgl_survei_gabungan = $request->tanggal_survei . ' ' . $request->waktu_survei . ':00';

        $dataUpdate = [
            'tgl_survei' =>   $tgl_survei_gabungan,
            'ket' => $request->ket,
        ];

        if ($user->role === 'admin' && $request->has('status')) {
            $dataUpdate['status'] = $request->status;
        }

        $survei->update($dataUpdate);

        return back()->with('success', 'Data survei berhasil diperbarui.');
    }

    public function delete($id)
    {
        $survei = surveiModel::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $survei->id_akun !== $user->id_akun) {
            abort(403, 'Anda tidak diizinkan menghapus data ini.');
        }
        if ($user->role !== 'admin' && strtolower(trim($survei->status)) !== 'pending') {
            abort(403, 'Anda hanya dapat membatalkan/menghapus jadwal yang masih berstatus Pending.');
        }

        $survei->delete();

        return back()->with('success', 'Data survei berhasil dibatalkan/dihapus.');
    }
}
