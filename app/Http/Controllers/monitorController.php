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
        $data_ternak = ternakModel::where('status_ternak', '!=', 'mati')
            ->where('status_jual', '!=', 'terjual')
            ->orderBy('id_ternak', 'asc')->get();

        // Statistics
        $stat_total = monitorModel::count();
        $stat_sakit = monitorModel::whereNotNull('penyakit')->where('penyakit', '!=', '')->count();

        // Ternak yang belum di-monitor bulan ini
        $bulanIni = Carbon::now()->startOfMonth()->toDateString();
        $ternakSudahMonitor = monitorModel::where('tgl_monitoring', '>=', $bulanIni)
            ->distinct('id_ternak')->pluck('id_ternak');
        $ternakBelumMonitor = ternakModel::whereNotIn('id_ternak', $ternakSudahMonitor)
            ->where('status_ternak', '!=', 'mati')
            ->where('status_jual', '!=', 'terjual')
            ->orderBy('id_ternak', 'asc')->get();
        $stat_belum = $ternakBelumMonitor->count();

        return view('pages.monitoring', compact('data_monitoring', 'data_ternak', 'data_kandang', 'data_kamar', 'stat_total', 'stat_sakit', 'stat_belum', 'ternakBelumMonitor'));
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
        
        $tgl_monitoring = Carbon::parse($request->tgl_monitoring);
        
        // Cek apakah ternak sudah dimonitor pada bulan dan tahun yang sama
        $existingMonitor = monitorModel::where('id_ternak', $request->id_ternak)
            ->whereYear('tgl_monitoring', $tgl_monitoring->year)
            ->whereMonth('tgl_monitoring', $tgl_monitoring->month)
            ->first();

        if ($existingMonitor) {
            return redirect()->back()->withInput()->withErrors(['id_ternak' => 'Ternak #ID-' . $request->id_ternak . ' sudah di-monitor pada bulan ' . $tgl_monitoring->translatedFormat('F Y') . '. Silakan edit data riwayat yang sudah ada.']);
        }

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
            
            $harga_baru = $this->hitungHargaOtomatis(
                $ternak->id_jenis_ternak,
                $ternak->usia,
                $request->berat,
                $ternak->jenis_kelamin
            );

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

    private function hitungHargaOtomatis($id_jenis_ternak, $usia, $berat, $jenis_kelamin)
{
    $jsonPath = public_path('json/value.json');
    if (!\Illuminate\Support\Facades\File::exists($jsonPath)) return 0;

    $data = json_decode(\Illuminate\Support\Facades\File::get($jsonPath), true);
    $jenisTernak = \App\Models\jenisTernakModel::find($id_jenis_ternak);
    $namaJenisDb = strtolower($jenisTernak->jenis_ternak ?? '');

    // Mapping nama dari DB ke Breed Name di JSON
    $mapJenis = [
        'crosstexel' => 'Cross Texel',
        'merino' => 'Merino',
        'etawa' => 'Etawa (PE)'
    ];
    $searchJenis = $mapJenis[$namaJenisDb] ?? $namaJenisDb;

    // Tentukan Kategori Usia
    $kategoriUsia = '';
    if ($usia >= 0 && $usia <= 5) {
        $kategoriUsia = 'Anakan/Bibit';
    } elseif ($usia >= 6 && $usia <= 11) {
        $kategoriUsia = 'Doro/Muda';
    } elseif ($usia >= 12) {
        $kategoriUsia = 'Indukan/Dewasa';
    }

    // Pencarian harga final di JSON
    foreach ($data['ternak_klasifikasi'] as $breed) {
        if ($breed['breed_name'] === $searchJenis) {
            foreach ($breed['age_categories'] as $ageCat) {
                if ($ageCat['category_name'] === $kategoriUsia) {
                    foreach ($ageCat['weight_classes'] as $wClass) {
                        // Cek apakah berat masuk dalam rentang kelas
                        if ($berat >= $wClass['min_weight'] && $berat <= $wClass['max_weight']) {
                            // Ambil harga langsung berdasarkan kelamin (tanpa multiplier)
                            $keyKelamin = ucfirst(strtolower($jenis_kelamin));
                            return $wClass['prices'][$keyKelamin] ?? 0;
                        }
                    }
                }
            }
        }
    }

    return 0; // Return 0 jika di luar batas JSON
}
}
