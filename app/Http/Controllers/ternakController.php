<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ternakModel;
use App\Models\kamarModel;
use App\Models\kandangModel;
use App\Models\jenisTernakModel;
use Illuminate\Support\Facades\File;
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

        // 1. Filter Lokasi Kandang (Baru)
        if ($request->filled('id_kandang') && $request->id_kandang !== 'semua') {
            if ($request->id_kandang === 'kosong') {
                // Jika mencari 'kosong', berarti id_kamar-nya null
                $query->whereNull('id_kamar');
            } else {
                // Jika mencari kandang tertentu, cari lewat relasi kamar
                $query->whereHas('kamar', function ($q) use ($request) {
                    $q->where('id_kandang', $request->id_kandang);
                });
            }
        }

        // 2. Filter Jenis Ternak
        if ($request->filled('id_jenis_ternak') && $request->id_jenis_ternak !== 'semua') {
            $query->where('id_jenis_ternak', $request->id_jenis_ternak);
        }

        // 3. Filter Rentang Usia (Bulan)
        if ($request->filled('usia_min')) {
            $query->where('usia', '>=', $request->usia_min);
        }
        if ($request->filled('usia_max')) {
            $query->where('usia', '<=', $request->usia_max);
        }

        // 4. Filter Rentang Berat (Kg)
        if ($request->filled('berat_min')) {
            $query->where('berat', '>=', $request->berat_min);
        }
        if ($request->filled('berat_max')) {
            $query->where('berat', '<=', $request->berat_max);
        }

        // 5. Filter Status Kesehatan
        if ($request->filled('status_ternak') && $request->status_ternak !== 'semua') {
            $query->where('status_ternak', $request->status_ternak);
        }

        // 6. Filter Status Penjualan
        if ($request->filled('status_jual') && $request->status_jual !== 'semua') {
            $query->where('status_jual', $request->status_jual);
        }

        // 7. Filter Jenis Kelamin
        if ($request->filled('jenis_kelamin') && $request->jenis_kelamin !== 'semua') {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        // Pencarian berdasarkan ID Ternak dari form Search Input "q"
        if ($request->filled('q')) {
            $query->where('id_ternak', 'like', '%' . $request->q . '%');
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
            // 'harga' => 'required|numeric|min:0',
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

        $hargaOtomatis = $this->hitungHargaOtomatis(
            $request->id_jenis_ternak,
            $request->usia,
            $request->berat,
            $request->jenis_kelamin
        );


        ternakModel::create([
            'id_jenis_ternak' => $request->id_jenis_ternak,
            'id_kamar' => $request->id_kamar,
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
            'berat' => $request->berat,
            // 'harga' => $request->harga,
            'harga' => $hargaOtomatis,
            'status_ternak' => $request->status_ternak,
            'status_jual' => $request->status_jual,
            'last_update' => \Carbon\Carbon::now(),
        ]);

        return back()->with('success', 'Data ternak baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // 1. Jika dikirim kosong dari dropdown, ubah jadi null
        if ($request->id_kamar === 'kosong' || $request->id_kamar === '') {
            $request->merge(['id_kamar' => null]);
        }

        // 2. Validasi Input Dasar
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

        // 3. LOGIKA BARU: Cek kapasitas HANYA jika pindah kamar
        if ($request->id_kamar && $request->id_kamar != $ternak->id_kamar) {
            $kamar_tujuan = kamarModel::findOrFail($request->id_kamar);
            $jumlah_isi_tujuan = ternakModel::where('id_kamar', $request->id_kamar)->count();

            // Jika jumlah penghuni di kamar tujuan sudah mencapai batasnya
            if ($jumlah_isi_tujuan >= $kamar_tujuan->kapasitas) {
                return back()->withErrors([
                    'id_kamar' => 'Gagal memindah! Kamar tujuan (' . $kamar_tujuan->nomor_kamar . ') sudah penuh.'
                ])->withInput();
            }
        }

        // 4. Update Data jika Lolos Validasi
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

    public function detail($id)
    {
        $ternak = ternakModel::with(['kamar.kandang', 'jenis_ternak'])->findOrFail($id);

        // 1. Ambil Data Riwayat Penyakit (Hanya yang sakit)
        $riwayat_penyakit = \App\Models\monitorModel::where('id_ternak', $id)
                            ->whereNotNull('penyakit')
                            ->where('penyakit', '!=', '')
                            ->orderBy('tgl_monitoring', 'desc')
                            ->get();

        // 2. Ambil Semua Data Monitoring untuk Grafik
        $monitoring = \App\Models\monitorModel::where('id_ternak', $id)
                            ->orderBy('usia', 'asc')
                            ->get();

        // 3. Baca File JSON Standar Pertumbuhan
        $jsonPath = public_path('json/sheep_growth_data.json');
        $idealData = [];

        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);

            // Mapping nama dari Database ke kunci JSON Anda
            $mapJenis = [
                'merino' => 'Merino',
                'crosstexel' => 'Cross Texel',
                'etawa' => 'Etawa (PE)'
            ];

            $namaJenisDb = strtolower($ternak->jenis_ternak->jenis_ternak ?? '');
            $keyJson = $mapJenis[$namaJenisDb] ?? null;

            if ($keyJson && isset($jsonData[$keyJson])) {
                $idealData = $jsonData[$keyJson];
            }
        }

        // 4. Siapkan Array untuk Grafik (X = Umur Bulan, Y1 = Ideal, Y2 = Aktual)
        $chartLabels = [];
        $chartIdeal = [];
        $chartAktual = [];

        $kelaminKey = $ternak->jenis_kelamin == 'jantan' ? 'weight_male_kg' : 'weight_female_kg';

        // Petakan data monitoring aktual berdasarkan usianya
        $actualMap = [];
        foreach($monitoring as $m) {
            $actualMap[$m->usia] = $m->berat;
        }

        // Memasukkan berat saat ini juga ke dalam map aktual
        $actualMap[$ternak->usia] = $ternak->berat;

        // Racik datanya (Maksimal 24 Bulan sesuai JSON)
        foreach($idealData as $row) {
            $bulan = $row['month'];
            $chartLabels[] = $bulan . " Bln";
            $chartIdeal[] = $row[$kelaminKey];

            // Jika ada data aktual di bulan tersebut, masukkan. Jika tidak, biarkan kosong (null)
            $chartAktual[] = isset($actualMap[$bulan]) ? $actualMap[$bulan] : null;
        }

        return view('pages.grafik-ternak', compact('ternak', 'riwayat_penyakit', 'chartLabels', 'chartIdeal', 'chartAktual'));
    }

    private function hitungHargaOtomatis($id_jenis_ternak, $usia, $berat, $jenis_kelamin)
    {
        $jsonPath = public_path('json/value.json');
        if (!File::exists($jsonPath)) return 0;

        $data = json_decode(File::get($jsonPath), true);
        $jenisTernak = \App\Models\jenisTernakModel::find($id_jenis_ternak);
        $namaJenis = $jenisTernak ? $jenisTernak->jenis_ternak : '';

        // Mapping nama jenis dari DB ke JSON
        $mapJenis = [
            'crosstexel' => 'Cross Texel',
            'merino' => 'Merino',
            'etawa' => 'Etawa (PE)'
        ];
        $searchJenis = $mapJenis[strtolower($namaJenis)] ?? $namaJenis;

        foreach ($data['ternak_klasifikasi'] as $breed) {
            if ($breed['breed_name'] === $searchJenis) {
                // Tentukan kategori usia
                $kategoriUsia = '';
                if ($usia <= 5) $kategoriUsia = 'Anakan/Bibit';
                elseif ($usia <= 11) $kategoriUsia = 'Doro/Muda';
                else $kategoriUsia = 'Indukan/Dewasa';

                foreach ($breed['age_categories'] as $ageCat) {
                    if ($ageCat['category_name'] === $kategoriUsia) {
                        foreach ($ageCat['weight_classes'] as $wClass) {
                            // Cek apakah berat masuk dalam rentang kelas
                            if ($berat >= $wClass['min_weight'] && $berat <= $wClass['max_weight']) {
                                $keyKelamin = ucfirst(strtolower($jenis_kelamin));
                                return $wClass['prices'][$keyKelamin] ?? 0;
                            }
                        }
                    }
                }
            }
        }
        return 0; // Kembalikan 0 jika tidak ditemukan kecocokan
    }


}
