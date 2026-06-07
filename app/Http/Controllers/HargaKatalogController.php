<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Ternak;
use App\Models\JenisTernak;

class HargaKatalogController extends Controller
{
    /**
     * Tampilkan halaman manajemen harga katalog.
     */
    public function index()
    {
        $jsonPath = public_path('json/value.json');
        if (!File::exists($jsonPath)) {
            return back()->with('error', 'File katalog harga (value.json) tidak ditemukan.');
        }

        $data = json_decode(File::get($jsonPath), true);

        return view('pages.harga-katalog', compact('data'));
    }

    /**
     * Simpan perubahan harga katalog ke value.json dan update DB.
     */
    public function update(Request $request)
    {
        $request->validate([
            'prices' => 'required|array',
            'prices.*.*.*.Jantan' => 'required|integer|min:0',
            'prices.*.*.*.Betina' => 'required|integer|min:0',
        ]);

        $jsonPath = public_path('json/value.json');
        if (!File::exists($jsonPath)) {
            return back()->with('error', 'File katalog harga (value.json) tidak ditemukan.');
        }

        $data = json_decode(File::get($jsonPath), true);
        $pricesInput = $request->input('prices');

        // Update array data dengan harga baru
        foreach ($data['ternak_klasifikasi'] as $bIdx => &$breed) {
            foreach ($breed['age_categories'] as $aIdx => &$ageCat) {
                foreach ($ageCat['weight_classes'] as $cIdx => &$wClass) {
                    if (isset($pricesInput[$bIdx][$aIdx][$cIdx]['Jantan'])) {
                        $wClass['prices']['Jantan'] = (int) $pricesInput[$bIdx][$aIdx][$cIdx]['Jantan'];
                    }
                    if (isset($pricesInput[$bIdx][$aIdx][$cIdx]['Betina'])) {
                        $wClass['prices']['Betina'] = (int) $pricesInput[$bIdx][$aIdx][$cIdx]['Betina'];
                    }
                }
            }
        }

        // Tulis kembali ke file value.json
        $successWrite = File::put($jsonPath, json_encode($data, JSON_PRETTY_PRINT));
        if ($successWrite === false) {
            return back()->with('error', 'Gagal menulis pembaruan harga ke file value.json.');
        }

        // Jalankan Mass Update ke database
        $this->runMassUpdate($data);

        return back()->with('success', 'Harga katalog berhasil diperbarui dan disinkronkan ke database!');
    }

    /**
     * Sinkronisasi manual database dengan harga di value.json.
     */
    public function sync()
    {
        $jsonPath = public_path('json/value.json');
        if (!File::exists($jsonPath)) {
            return back()->with('error', 'File katalog harga (value.json) tidak ditemukan.');
        }

        $data = json_decode(File::get($jsonPath), true);

        // Jalankan Mass Update ke database
        $this->runMassUpdate($data);

        return back()->with('success', 'Sinkronisasi harga seluruh ternak siap jual berhasil diselesaikan!');
    }

    /**
     * Logika inti untuk pembaruan massal (Mass Update) harga ternak siap jual di database.
     */
    private function runMassUpdate(array $data)
    {
        $allJenis = JenisTernak::all();
        $mapBreedToId = [];
        foreach ($allJenis as $jt) {
            $nameLower = strtolower($jt->jenis_ternak);
            if ($nameLower === 'crosstexel') {
                $mapBreedToId['Cross Texel'] = $jt->id_jenis_ternak;
            } elseif ($nameLower === 'merino') {
                $mapBreedToId['Merino'] = $jt->id_jenis_ternak;
            } elseif ($nameLower === 'etawa') {
                $mapBreedToId['Etawa (PE)'] = $jt->id_jenis_ternak;
            } else {
                $mapBreedToId[$jt->jenis_ternak] = $jt->id_jenis_ternak;
            }
        }

        DB::transaction(function() use ($data, $mapBreedToId) {
            foreach ($data['ternak_klasifikasi'] as $breed) {
                $breedName = $breed['breed_name'];
                $idJenis = $mapBreedToId[$breedName] ?? null;
                if (!$idJenis) continue;

                foreach ($breed['age_categories'] as $ageCat) {
                    $catName = $ageCat['category_name'];

                    // Rentang Usia
                    $minAge = 0;
                    $maxAge = 999;
                    if ($catName === 'Anakan/Bibit') {
                        $minAge = 0;
                        $maxAge = 5;
                    } elseif ($catName === 'Doro/Muda') {
                        $minAge = 6;
                        $maxAge = 11;
                    } elseif ($catName === 'Indukan/Dewasa') {
                        $minAge = 12;
                        $maxAge = 999;
                    }

                    // Ambil Kelas Berat (Standard, Medium, Super)
                    $standardClass = null;
                    $mediumClass = null;
                    $superClass = null;

                    foreach ($ageCat['weight_classes'] as $wClass) {
                        if (strtolower($wClass['class_name']) === 'standard') {
                            $standardClass = $wClass;
                        } elseif (strtolower($wClass['class_name']) === 'medium') {
                            $mediumClass = $wClass;
                        } elseif (strtolower($wClass['class_name']) === 'super') {
                            $superClass = $wClass;
                        }
                    }

                    $genders = ['jantan', 'betina'];
                    foreach ($genders as $gender) {
                        $genderKey = ucfirst($gender);

                        // 1. Standard (berat <= max_weight Standard)
                        if ($standardClass) {
                            $priceStandard = $standardClass['prices'][$genderKey] ?? 0;
                            Ternak::where('id_jenis_ternak', $idJenis)
                                ->where('jenis_kelamin', $gender)
                                ->whereBetween('usia', [$minAge, $maxAge])
                                ->where('berat', '<=', $standardClass['max_weight'])
                                ->where('status_jual', 'siap jual')
                                ->update(['harga' => $priceStandard]);
                        }

                        // 2. Medium (max_weight Standard < berat <= max_weight Medium)
                        if ($mediumClass && $standardClass) {
                            $priceMedium = $mediumClass['prices'][$genderKey] ?? 0;
                            Ternak::where('id_jenis_ternak', $idJenis)
                                ->where('jenis_kelamin', $gender)
                                ->whereBetween('usia', [$minAge, $maxAge])
                                ->where('berat', '>', $standardClass['max_weight'])
                                ->where('berat', '<=', $mediumClass['max_weight'])
                                ->where('status_jual', 'siap jual')
                                ->update(['harga' => $priceMedium]);
                        }

                        // 3. Super (berat > max_weight Medium)
                        if ($superClass && $mediumClass) {
                            $priceSuper = $superClass['prices'][$genderKey] ?? 0;
                            Ternak::where('id_jenis_ternak', $idJenis)
                                ->where('jenis_kelamin', $gender)
                                ->whereBetween('usia', [$minAge, $maxAge])
                                ->where('berat', '>', $mediumClass['max_weight'])
                                ->where('status_jual', 'siap jual')
                                ->update(['harga' => $priceSuper]);
                        }
                    }
                }
            }
        });
    }
}
