<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kandangModel;


class kandangController extends Controller
{
    public function index()
    {
        $data_kandang = kandangModel::all();

        return view('pages.kandang', compact('data_kandang'));
    }

    

    public function store(Request $request)
    {
        $request->validate([
            'nomor_kandang' => 'required|integer|min:1|unique:kandang,nomor_kandang',
            'kapasitas' => 'required|integer|min:1',
        ], [
            'nomor_kandang.unique' => 'Nomor kandang sudah terdaftar, silakan gunakan nomor lain.'
        ]);

        kandangModel::create([
            'nomor_kandang' => $request->nomor_kandang,
            'kapasitas' => $request->kapasitas,
        ]);

        return back()->with('success', 'Data kandang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_kandang' => 'required|integer|min:1|unique:kandang,nomor_kandang,' . $id . ',id_kandang',
            'kapasitas' => 'required|integer|min:1',
        ],['nomor_kandang.unique' => 'Nomor kandang sudah terdaftar, silakan gunakan nomor lain.']);

        $kandang = kandangModel::findOrFail($id);
        $kandang->update([
            'nomor_kandang' => $request->nomor_kandang,
            'kapasitas' => $request->kapasitas,
        ]);

        return back()->with('success', 'Data kandang berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kandang = kandangModel::withCount('kamar')->findOrFail($id);

        if ($kandang->kamar_count > 0) {
            return back()->with('error', 'Gagal menghapus! Kandang masih memiliki ' . $kandang->kamar_count . ' kamar. Hapus kamar terlebih dahulu.');
        }

        $kandang->delete();

        return back()->with('success', 'Data kandang berhasil dihapus.');
    }
}
