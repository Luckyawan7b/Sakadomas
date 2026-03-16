<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ternakModel;
use App\Models\kamarModel;
use App\Models\kandangModel;
use App\Models\jenisTernakModel;
use Carbon\Carbon;

class ternakController extends Controller
{
    // public function index()
    // {
    //     $data_ternak = ternakModel::with(['kamar.kandang', 'jenis_ternak'])
    //                               ->orderBy('id_ternak', 'desc')
    //                               ->get();

    //     $data_kamar = kamarModel::with('kandang')->get();
    //     $data_jenis = jenisTernakModel::all();

    //     return view('pages.ternak', compact('data_ternak', 'data_kamar', 'data_jenis'));
    // }

    public function index(Request $request)
    {
        $query = ternakModel::with(['kamar.kandang', 'jenis_ternak'])
                            ->orderBy('id_ternak', 'desc');

        // 1. Filter Jenis Ternak
        if ($request->filled('id_jenis_ternak') && $request->id_jenis_ternak !== 'semua') {
            $query->where('id_jenis_ternak', $request->id_jenis_ternak);
        }

        // 2. Filter Rentang Usia (Bulan)
        if ($request->filled('usia_min')) {
            $query->where('usia', '>=', $request->usia_min);
        }
        if ($request->filled('usia_max')) {
            $query->where('usia', '<=', $request->usia_max);
        }

        // 3. Filter Rentang Berat (Kg)
        if ($request->filled('berat_min')) {
            $query->where('berat', '>=', $request->berat_min);
        }
        if ($request->filled('berat_max')) {
            $query->where('berat', '<=', $request->berat_max);
        }

        // 4. Filter Status Kesehatan
        if ($request->filled('status_ternak') && $request->status_ternak !== 'semua') {
            $query->where('status_ternak', $request->status_ternak);
        }

        // 5. Filter Status Penjualan
        if ($request->filled('status_jual') && $request->status_jual !== 'semua') {
            $query->where('status_jual', $request->status_jual);
        }

        $data_ternak = $query->paginate(10);

        $data_kandang = kandangModel::all();
        $data_kamar = kamarModel::with('kandang')->get();
        $data_jenis = jenisTernakModel::all();

        return view('pages.ternak', compact('data_ternak', 'data_kandang', 'data_kamar', 'data_jenis'));
    }

public function store(Request $request)
    {
        if ($request->id_kamar === 'kosong' || $request->id_kamar === '') {
            $request->merge(['id_kamar' => null]);
        }

        $request->validate([
            'id_jenis_ternak' => 'required',
            'id_kamar' => 'nullable|exists:kamar,id_kamar',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'usia' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'status_ternak' => 'required|in:sehat,sakit,hamil',
            'status_jual' => 'required|in:tidak dijual,siap jual,booking,terjual',
        ]);

        if ($request->id_kamar) {
            $kamar = kamarModel::findOrFail($request->id_kamar);
            $jumlah_isi_kamar = ternakModel::where('id_kamar', $request->id_kamar)->count();

            if ($jumlah_isi_kamar >= $kamar->kapasitas) {
                return back()->withErrors([
                    'id_kamar' => 'Gagal! Kamar ' . $kamar->nomor_kamar . ' sudah penuh kapasitasnya (' . $kamar->kapasitas . ' Ekor).'
                ])->withInput();
            }
        }

        ternakModel::create([
            'id_jenis_ternak' => $request->id_jenis_ternak,
            'id_kamar' => $request->id_kamar,
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'status_ternak' => $request->status_ternak,
            'status_jual' => $request->status_jual,
            'last_update' => \Carbon\Carbon::now(),
        ]);

        return back()->with('success', 'Data ternak baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if ($request->id_kamar === 'kosong' || $request->id_kamar === '') {
            $request->merge(['id_kamar' => null]);
        }

        $request->validate([
            'id_jenis_ternak' => 'required',
            'id_kamar' => 'nullable|exists:kamar,id_kamar',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'usia' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'status_ternak' => 'required|in:sehat,sakit,hamil',
            'status_jual' => 'required|in:tidak dijual,siap jual,booking,terjual',
        ]);

        $ternak = ternakModel::findOrFail($id);

        if ($request->id_kamar && $request->id_kamar != $ternak->id_kamar) {
            $kamar_tujuan = kamarModel::findOrFail($request->id_kamar);
            $jumlah_isi_tujuan = ternakModel::where('id_kamar', $request->id_kamar)->count();

            if ($jumlah_isi_tujuan >= $kamar_tujuan->kapasitas) {
                return back()->withErrors([
                    'id_kamar' => 'Gagal memindah! Kamar tujuan (' . $kamar_tujuan->nomor_kamar . ') sudah penuh.'
                ])->withInput();
            }
        }

        $ternak->update([
            'id_jenis_ternak' => $request->id_jenis_ternak,
            'id_kamar' => $request->id_kamar,
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'status_ternak' => $request->status_ternak,
            'status_jual' => $request->status_jual,
            'last_update' => \Carbon\Carbon::now(),
        ]);

        return back()->with('success', 'Data ternak berhasil diperbarui.');
    }

    public function delete($id)
    {
        $ternak = ternakModel::findOrFail($id);
        $ternak->delete();

        return back()->with('success', 'Data ternak berhasil dihapus.');
    }
}
