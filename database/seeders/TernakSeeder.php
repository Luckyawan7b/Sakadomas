<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class TernakSeeder extends Seeder
{
    public function run()
    {
        // 1. Baca file aturan value.json
        $valueJson = File::get(public_path('json/value.json'));
        $klasifikasi = json_decode($valueJson, true)['ternak_klasifikasi'];

        // Map id_jenis_ternak ke nama breed sesuai di value.json
        $mapJenisTernak = [
            1 => 'Merino',
            2 => 'Cross Texel',
            3 => 'Etawa (PE)'
        ];

        // 2. Baca file data json terpisah
        $dataTernakPath = database_path('data/data_ternak_60.json');
        $dataMonitoringPath = database_path('data/data_monitoring_60.json');
        
        if (!File::exists($dataTernakPath) || !File::exists($dataMonitoringPath)) {
            $this->command->error("File data_ternak_60.json atau data_monitoring_60.json tidak ditemukan!");
            return;
        }
        
        $dataTernak = json_decode(File::get($dataTernakPath), true);
        $dataMonitoring = json_decode(File::get($dataMonitoringPath), true);

        // --- TAMBAHAN LOGIKA KAMAR ---
        // Ambil semua kamar yang ada di database
        $kamars = DB::table('kamar')->get();
        $kuotaKamar = [];

        // Buat "tiket" kuota berdasarkan kapasitas masing-masing kamar
        foreach ($kamars as $kamar) {
            for ($k = 0; $k < $kamar->kapasitas; $k++) {
                $kuotaKamar[] = $kamar->id_kamar;
            }
        }

        // Acak urutan tiket agar ternak tersebar secara random di berbagai kamar
        shuffle($kuotaKamar);
        // ------------------------------

        // Mapping ID Lama ke ID Baru
        $mapOldToNewIdTernak = [];

        // Insert Ternak
        foreach ($dataTernak as $ternakData) {
            // Handle nilai kosong/inkonsisten
            $jenisKelamin = isset($ternakData['jenis_kelamin']) ? ucfirst(strtolower($ternakData['jenis_kelamin'])) : 'Jantan';
            if (!in_array($jenisKelamin, ['Jantan', 'Betina'])) {
                $jenisKelamin = 'Jantan'; // default jika aneh
            }

            $idJenisTernak = isset($ternakData['id_jenis_ternak']) ? (int)$ternakData['id_jenis_ternak'] : 1;
            $usiaSekarang = isset($ternakData['usia']) ? (int)$ternakData['usia'] : 0;
            $beratSekarang = isset($ternakData['berat']) ? (int)$ternakData['berat'] : 0;
            $breedName = $mapJenisTernak[$idJenisTernak] ?? 'Merino';

            // Temukan harga yang sesuai klasifikasi value.json
            $harga = 0;
            foreach ($klasifikasi as $breed) {
                if ($breed['breed_name'] === $breedName) {
                    foreach ($breed['age_categories'] as $cat) {
                        $isMatchAge = false;
                        if ($cat['category_name'] == 'Anakan/Bibit' && $usiaSekarang <= 5) {
                            $isMatchAge = true;
                        } elseif ($cat['category_name'] == 'Doro/Muda' && $usiaSekarang >= 6 && $usiaSekarang <= 11) {
                            $isMatchAge = true;
                        } elseif ($cat['category_name'] == 'Indukan/Dewasa' && $usiaSekarang >= 12) {
                            $isMatchAge = true;
                        }

                        if ($isMatchAge) {
                            $lastClass = null;
                            foreach ($cat['weight_classes'] as $kelasBerat) {
                                $lastClass = $kelasBerat;
                                if ($beratSekarang >= $kelasBerat['min_weight'] && $beratSekarang <= $kelasBerat['max_weight']) {
                                    $harga = $kelasBerat['prices'][$jenisKelamin] ?? 0;
                                    break;
                                }
                            }
                            // Jika berat melebihi kelas tertinggi, gunakan harga kelas tertinggi
                            // Jika berat kurang dari kelas terendah, gunakan harga kelas terendah
                            if ($harga == 0 && $lastClass) {
                                if ($beratSekarang > $lastClass['max_weight']) {
                                    $harga = $lastClass['prices'][$jenisKelamin] ?? 0;
                                } else {
                                    $harga = $cat['weight_classes'][0]['prices'][$jenisKelamin] ?? 0;
                                }
                            }
                            break;
                        }
                    }
                    break;
                }
            }

            // Fallback jika tidak match kondisi apapun
            if ($harga == 0) {
                $harga = 1000000;
            }

            // Ambil 1 tiket kamar
            $assignedIdKamar = count($kuotaKamar) > 0 ? array_pop($kuotaKamar) : null;

            // Handle date null
            $lastUpdate = !empty($ternakData['last_update']) ? $ternakData['last_update'] : Carbon::now()->toDateString();
            $lastMonitor = !empty($ternakData['last_monitor']) ? $ternakData['last_monitor'] : Carbon::now()->toDateString();

            // Status handling
            $statusTernak = !empty($ternakData['status_ternak']) ? strtolower($ternakData['status_ternak']) : 'sehat';
            $statusJual = !empty($ternakData['status_jual']) ? strtolower($ternakData['status_jual']) : 'siap jual';
            if ($statusJual == 'tersedia') {
                $statusJual = 'siap jual';
            }

            // Insert ke tabel ternak
            $idTernakBaru = DB::table('ternak')->insertGetId([
                'jenis_kelamin'   => strtolower($jenisKelamin),
                'usia'            => $usiaSekarang,
                'berat'           => $beratSekarang,
                'harga'           => $harga,
                'status_ternak'   => $statusTernak,
                'status_jual'     => $statusJual,
                'last_update'     => $lastUpdate,
                'last_monitor'    => $lastMonitor,
                'id_jenis_ternak' => $idJenisTernak,
                'id_kamar'        => $assignedIdKamar,
            ], 'id_ternak');

            // Simpan mapping id_ternak lama (dari JSON) ke id_ternak baru (dari database)
            $oldIdTernak = $ternakData['id_ternak'] ?? null;
            if ($oldIdTernak) {
                $mapOldToNewIdTernak[$oldIdTernak] = $idTernakBaru;
            }
        }

        // Insert Monitoring
        $monitoringToInsert = [];
        foreach ($dataMonitoring as $mon) {
            $oldId = $mon['id_ternak'] ?? null;
            
            if ($oldId && isset($mapOldToNewIdTernak[$oldId])) {
                $tglMon = !empty($mon['tgl_monitoring']) ? $mon['tgl_monitoring'] : Carbon::now()->toDateString();
                
                $monitoringToInsert[] = [
                    'id_ternak'      => $mapOldToNewIdTernak[$oldId],
                    'usia'           => isset($mon['usia']) ? (int)$mon['usia'] : 0,
                    'berat'          => isset($mon['berat']) ? (int)$mon['berat'] : 0,
                    'penyakit'       => !empty($mon['penyakit']) ? $mon['penyakit'] : null,
                    'tgl_monitoring' => $tglMon,
                ];
            }
        }

        // Insert dalam jumlah batch/chunk untuk menghindari batas limit param query (chunk size 1000)
        foreach (array_chunk($monitoringToInsert, 1000) as $chunk) {
            DB::table('monitoring')->insert($chunk);
        }
    }
}
