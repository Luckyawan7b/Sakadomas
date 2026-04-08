<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\monitorModel;
use App\Models\ternakModel;
use App\Models\kandangModel;
use App\Models\kamarModel;
use Carbon\Carbon;

class monitorController extends Controller
{
    public function index(Request $request)
    {
        $query = monitorModel::join('ternak', 'monitoring.id_ternak', '=', 'ternak.id_ternak')
                             ->select('monitoring.*', 'ternak.id_kamar', 'ternak.id_jenis_ternak')
                             ->orderBy('tgl_monitoring', 'desc');

        if ($request->filled('id_ternak') && $request->id_ternak !== 'semua') {
            $query->where('monitoring.id_ternak', $request->id_ternak);
        }
        if ($request->filled('tgl_awal')) {
            $query->where('tgl_monitoring', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->where('tgl_monitoring', '<=', $request->tgl_akhir);
        }
        if ($request->filled('kondisi') && $request->kondisi !== 'semua') {
            if ($request->kondisi === 'sakit') {
                $query->whereNotNull('penyakit')->where('penyakit', '!=', '');
            } else {
                $query->where(function($q) {
                    $q->whereNull('penyakit')->orWhere('penyakit', '');
                });
            }
        }

        $data_monitoring = $query->paginate(10);

        // Panggil data kandang, kamar, dan ternak untuk dropdown bertingkat
        $data_kandang = kandangModel::all();
        $data_kamar = kamarModel::all();
        $data_ternak = ternakModel::orderBy('id_ternak', 'asc')->get();

        return view('pages.monitoring', compact('data_monitoring', 'data_ternak', 'data_kandang', 'data_kamar'));
    }

    public function store(Request $request)
    {
        // Validasi tanpa kolom 'usia'
        $request->validate([
            'id_ternak' => 'required|exists:ternak,id_ternak',
            'tgl_monitoring' => 'required|date',
            'berat' => 'required|numeric|min:0',
            'penyakit' => 'nullable|string',
        ]);

        $ternak = ternakModel::find($request->id_ternak);
        $usia_baru = $ternak->usia; // Default usia saat ini

        if ($ternak) {

            // Jika tanggal monitoring baru lebih maju, hitung selisih bulannya
            $tanggal_patokan = $ternak->last_monitor ? $ternak->last_monitor : $ternak->last_update;
            $tgl_baru = Carbon::parse($request->tgl_monitoring);
            $tgl_lama = Carbon::parse($tanggal_patokan);

            // Pastikan tanggal baru lebih maju dari tanggal lama
            if ($tgl_baru->greaterThan($tgl_lama)) {

                // Hitung selisih murni dari bulan dan tahun kalender
                $selisihBulan = (($tgl_baru->year - $tgl_lama->year) * 12) + ($tgl_baru->month - $tgl_lama->month);

                // Jika selisih bulan lebih dari 0, tambahkan ke usia
                if ($selisihBulan > 0) {
                    $usia_baru = $ternak->usia + $selisihBulan;
                }
            }

            // Logika Status Kesehatan
            $status_baru = $request->filled('penyakit') ? 'sakit' : 'sehat';
            if ($ternak->status_ternak == 'hamil' && !$request->filled('penyakit')) {
                $status_baru = 'hamil'; // Tetap hamil jika tidak sakit
            }

            // 1. Simpan ke tabel monitoring (dengan usia yang terhitung otomatis)
            monitorModel::create([
                'id_ternak' => $request->id_ternak,
                'tgl_monitoring' => $request->tgl_monitoring,
                'usia' => $usia_baru,
                'berat' => $request->berat,
                'penyakit' => $request->penyakit,
            ]);

            // 2. Update data ke tabel ternak
            $ternak->update([
                'usia' => $usia_baru,
                'berat' => $request->berat,
                'last_monitor' => $request->tgl_monitoring,
                'status_ternak' => $status_baru
            ]);
        }

        return back()->with('success', 'Data monitoring berhasil ditambahkan & Profil ternak diperbarui!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_monitoring' => 'required|date',
            'berat' => 'required|numeric|min:0',
            'penyakit' => 'nullable|string',
        ]);

        $monitor = monitorModel::findOrFail($id);

        $monitor->update([
            'tgl_monitoring' => $request->tgl_monitoring,
            'berat' => $request->berat,
            'penyakit' => $request->penyakit,
        ]);

        return back()->with('success', 'Catatan monitoring berhasil diperbarui.');
    }

    public function delete($id)
    {
        $monitor = monitorModel::findOrFail($id);
        $monitor->delete();

        return back()->with('success', 'Data monitoring berhasil dihapus.');
    }
}
