<?php

namespace App\Http\Controllers;
use App\Models\kamarModel;
use App\Models\kandangModel;
use App\Models\ternakModel;
use App\Models\jenisTernakModel;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class kamarController extends Controller
{
    public function index()
    {
        $data_kandang = kandangModel::all();
        // Gunakan withCount('ternak') agar jumlah isi kamar ikut terhitung
        $kamar_grouped = kamarModel::with('kandang')->withCount('ternak')->get()->groupBy('id_kandang');

        return view('pages.kamar', compact('data_kandang', 'kamar_grouped'));
    }

    public function showKamar($id)
    {
        $kandang = kandangModel::findOrFail($id);
        $data_kandang = kandangModel::all();

        // Gunakan withCount('ternak') untuk memantau sisa kapasitas
        $kamars = kamarModel::withCount('ternak')->where('id_kandang', $id)->get();

        return view('pages.detail-kamar', compact('kandang', 'kamars', 'data_kandang'));
    }

    public function showTernak($id_kandang, $id_kamar)
    {
        $kandang = kandangModel::findOrFail($id_kandang);
        $kamar = kamarModel::findOrFail($id_kamar);

        $data_ternak = ternakModel::with('jenis_ternak')
                                ->where('id_kamar', $id_kamar)
                                ->orderBy('id_ternak', 'desc')
                                ->get();

        $data_kandang = kandangModel::all();
        $data_kamar = kamarModel::with('kandang')->get();
        $data_jenis = jenisTernakModel::all();

        return view('pages.detail-ternak', compact('kandang', 'kamar', 'data_ternak', 'data_kandang', 'data_kamar', 'data_jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kandang' => 'required|exists:kandang,id_kandang',
            'kapasitas' => 'required|integer|min:1', // Validasi kapasitas kamar baru
            'nomor_kamar' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kamar', 'nomor_kamar')->where(function ($query) use ($request) {
                    return $query->where('id_kandang', $request->id_kandang);
                })
            ],
        ], [
            'nomor_kamar.unique' => 'Nomor kamar ini sudah digunakan di kandang tersebut.'
        ]);

        // Pengecekan apakah kandangnya sudah penuh (jumlah kamarnya melebihi kapasitas kandang)
        $kandang = kandangModel::findOrFail($request->id_kandang);
        $jumlah_kamar_saat_ini = kamarModel::where('id_kandang', $request->id_kandang)->count();

        if ($jumlah_kamar_saat_ini >= $kandang->kapasitas) {
            return back()->withErrors([
                'id_kandang' => 'Gagal menambah! Kandang Nomor ' . $kandang->nomor_kandang . ' sudah penuh (Maksimal ' . $kandang->kapasitas . ' Kamar).'
            ])->withInput();
        }

        kamarModel::create([
            'nomor_kamar' => $request->nomor_kamar,
            'kapasitas' => $request->kapasitas, // Simpan kapasitas kamar
            'id_kandang' => $request->id_kandang,
            // 'status' => 'kosong', // Status default
        ]);

        return back()->with('success', 'Kamar baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kandang' => 'required|exists:kandang,id_kandang',
            'kapasitas' => 'required|integer|min:1', // Validasi edit kapasitas
            // 'status' => 'required|in:kosong,terisi,penuh,karantina', // ENUM baru
            'nomor_kamar' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kamar', 'nomor_kamar')->where(function ($query) use ($request) {
                    return $query->where('id_kandang', $request->id_kandang);
                })->ignore($id, 'id_kamar')
            ],
        ], [
            'nomor_kamar.unique' => 'Nomor kamar ini sudah digunakan di kandang tersebut.'
        ]);

        $kamar = kamarModel::findOrFail($id);

        // Jika pindah kandang, cek kapasitas kandang target
        if ($request->id_kandang != $kamar->id_kandang) {
            $kandang_tujuan = kandangModel::findOrFail($request->id_kandang);
            $jumlah_kamar_tujuan = kamarModel::where('id_kandang', $request->id_kandang)->count();

            if ($jumlah_kamar_tujuan >= $kandang_tujuan->kapasitas) {
                return back()->withErrors([
                    'id_kandang' => 'Gagal memindah! Kandang tujuan (Nomor ' . $kandang_tujuan->nomor_kandang . ') sudah penuh.'
                ])->withInput();
            }
        }

        $kamar->update([
            'nomor_kamar' => $request->nomor_kamar,
            'kapasitas' => $request->kapasitas, // Update kapasitas kamar
            'id_kandang' => $request->id_kandang,
            // 'status' => $request->status,
        ]);

        return back()->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kamar = kamarModel::findOrFail($id);
        $kamar->delete();

        return back()->with('success', 'Data kamar berhasil dihapus.');
    }
}
