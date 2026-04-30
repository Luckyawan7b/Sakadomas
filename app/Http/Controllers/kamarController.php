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
        $kamar_grouped = kamarModel::with('kandang')
            ->withCount('ternak')
            ->withCount(['ternak as ternak_sakit_count' => function ($query) {
                $query->where('status_ternak', 'sakit');
            }])
            ->get()->groupBy('id_kandang');

        return view('pages.kamar', compact('data_kandang', 'kamar_grouped'));
    }

    public function showKamar($id)
    {
        $kandang = kandangModel::findOrFail($id);
        $data_kandang = kandangModel::all();
        $kamars = kamarModel::withCount('ternak')
            ->withCount(['ternak as ternak_sakit_count' => function ($query) {
                $query->where('status_ternak', 'sakit');
            }])
            ->where('id_kandang', $id)->get();

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

        // ✅ FIX: Query dipindah dari Blade ke Controller (MVC compliance)
        $ternak_kosong = ternakModel::with('jenis_ternak')
                                    ->whereNull('id_kamar')
                                    ->where('status_jual', '!=', 'terjual')
                                    ->orderBy('id_ternak', 'asc')
                                    ->get();

        return view('pages.detail-ternak', compact(
            'kandang', 'kamar', 'data_ternak',
            'data_kandang', 'data_kamar', 'data_jenis',
            'ternak_kosong' // ✅ Pass ke view
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kandang' => 'required|exists:kandang,id_kandang',
            'kapasitas' => 'required|integer|min:1',
            'nomor_kamar' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kamar', 'nomor_kamar')->where(function ($query) use ($request) {
                    return $query->where('id_kandang', $request->id_kandang);
                })
            ],
        ], [
            'nomor_kamar.unique' => 'Nomor kamar ini sudah ada didalam kandang.'
        ]);

        $kandang = kandangModel::findOrFail($request->id_kandang);
        $jumlah_kamar_saat_ini = kamarModel::where('id_kandang', $request->id_kandang)->count();

        if ($jumlah_kamar_saat_ini >= $kandang->kapasitas) {
            return back()->withErrors([
                'id_kandang' => 'Gagal menambah! Kandang Nomor ' . $kandang->nomor_kandang . ' sudah penuh (Maksimal ' . $kandang->kapasitas . ' Kamar).'
            ])->withInput();
        }

        kamarModel::create([
            'nomor_kamar' => $request->nomor_kamar,
            'kapasitas' => $request->kapasitas,
            'id_kandang' => $request->id_kandang,
        ]);

        return back()->with('success', 'Kamar baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kandang' => 'required|exists:kandang,id_kandang',
            'kapasitas' => 'required|integer|min:1',
            'nomor_kamar' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('kamar', 'nomor_kamar')->where(function ($query) use ($request) {
                    return $query->where('id_kandang', $request->id_kandang);
                })->ignore($id, 'id_kamar')
            ],
            'status' => 'nullable|string'
        ], [
            'nomor_kamar.unique' => 'Nomor kamar ini sudah digunakan di kandang tersebut.'
        ]);

        $kamar = kamarModel::withCount('ternak')
            ->withCount(['ternak as ternak_sakit_count' => function ($query) {
                $query->where('status_ternak', 'sakit');
            }])->findOrFail($id);
        $old_status = ($kamar->ternak_count > 0 && $kamar->ternak_count == $kamar->ternak_sakit_count) ? 'karantina' : 'aktif';

        if ($request->id_kandang != $kamar->id_kandang) {
            $kandang_tujuan = kandangModel::findOrFail($request->id_kandang);
            $jumlah_kamar_tujuan = kamarModel::where('id_kandang', $request->id_kandang)->count();

            if ($jumlah_kamar_tujuan >= $kandang_tujuan->kapasitas) {
                return back()->withErrors([
                    'id_kandang' => 'Gagal memindah! Kandang tujuan (Nomor ' . $kandang_tujuan->nomor_kandang . ') sudah penuh.'
                ])->withInput();
            }
        }

        $statusInput = $request->status;

        $kamar->update([
            'nomor_kamar' => $request->nomor_kamar,
            'kapasitas' => $request->kapasitas,
            'id_kandang' => $request->id_kandang,
        ]);

        $message = 'Data kamar berhasil diperbarui.';

        if ($statusInput === 'karantina') {
            if ($old_status !== 'karantina') {
                ternakModel::where('id_kamar', $id)->update(['status_ternak' => 'sakit']);
                $message = 'Data kamar berhasil diperbarui. Kamar dikarantina dan semua ternak di dalamnya otomatis diubah menjadi Sakit.';
            }
        } elseif ($statusInput === 'aktif') {
            if ($old_status === 'karantina') {
                ternakModel::where('id_kamar', $id)->update(['status_ternak' => 'sehat']);
                $message = 'Karantina dicabut. Status semua ternak di dalam kamar ini telah dikembalikan menjadi Sehat.';
            }
        }

        return back()->with('success', $message);
    }

    public function delete($id)
    {
        $kamar = kamarModel::withCount('ternak')->findOrFail($id);

        if ($kamar->ternak_count > 0) {
            return back()->with('error', 'Gagal menghapus! Kamar masih berisi ' . $kamar->ternak_count . ' ternak. Kosongkan kamar terlebih dahulu.');
        }

        $kamar->delete();

        return back()->with('success', 'Data kamar berhasil dihapus.');
    }
}
