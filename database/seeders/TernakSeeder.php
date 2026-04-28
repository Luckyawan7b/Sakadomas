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
        $json = File::get(public_path('json/value.json'));
        $klasifikasi = json_decode($json, true)['ternak_klasifikasi'];

        // Asumsi ID Jenis Ternak di database kamu:
        // 1 = Cross Texel, 2 = Merino, 3 = Etawa (PE)
        $mapJenisTernak = [
            'Cross Texel' => 1,
            'Merino'      => 2,
            'Etawa (PE)'  => 3
        ];

        $jumlahData = 20; // Ganti jika ingin lebih banyak data dummy
        $now = Carbon::now();

        // Array untuk menyimpan penyakit acak
        $daftarPenyakit = ['Virus', 'Scabies', 'Mastitis', 'Stress', 'Kembung', 'Kaki Busuk'];

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

        for ($i = 1; $i <= $jumlahData; $i++) {

            // --- BAGIAN A: BUAT DATA TERNAK ---

            // 2. Acak Ras dan Jenis Kelamin
            $rasAcak = $klasifikasi[array_rand($klasifikasi)];
            $idJenisTernak = $mapJenisTernak[$rasAcak['breed_name']];
            $jenisKelamin = rand(0, 1) ? 'Jantan' : 'Betina';

            // 3. Acak Usia saat ini (Misal antara 1 sampai 24 bulan)
            $usiaSekarang = rand(1, 24);

            // 4. Cari Kategori Usia di JSON yang sesuai dengan usia acak tadi
            $kategoriUsia = null;
            foreach ($rasAcak['age_categories'] as $cat) {
                if ($cat['category_name'] == 'Anakan/Bibit' && $usiaSekarang <= 5) {
                    $kategoriUsia = $cat; break;
                } elseif ($cat['category_name'] == 'Doro/Muda' && $usiaSekarang >= 6 && $usiaSekarang <= 11) {
                    $kategoriUsia = $cat; break;
                } elseif ($cat['category_name'] == 'Indukan/Dewasa' && $usiaSekarang >= 12) {
                    $kategoriUsia = $cat; break;
                }
            }

            // 5. Acak Kelas Berat (Standard / Medium / Super) dari kategori usia tersebut
            $kelasBerat = $kategoriUsia['weight_classes'][array_rand($kategoriUsia['weight_classes'])];

            // 6. Tentukan Berat dan Harga sesuai JSON
            $beratSekarang = rand($kelasBerat['min_weight'], $kelasBerat['max_weight']);
            $harga = $kelasBerat['prices'][$jenisKelamin];

            // --- AMBIL 1 TIKET KAMAR UNTUK TERNAK INI ---
            // array_pop akan mengambil dan menghapus 1 elemen terakhir dari array.
            // Jika array kosong (kamar sudah penuh semua), kita set null.
            $assignedIdKamar = count($kuotaKamar) > 0 ? array_pop($kuotaKamar) : null;

            // 7. Insert ke tabel ternak dan ambil ID-nya
            $idTernak = DB::table('ternak')->insertGetId([
                'jenis_kelamin'   => strtolower($jenisKelamin),
                'usia'            => $usiaSekarang,
                'berat'           => $beratSekarang,
                'harga'           => $harga,
                'status_ternak'   => 'sehat',
                'status_jual'     => 'siap jual',
                'last_update'     => $now->toDateString(),
                'last_monitor'    => $now->toDateString(),
                'id_jenis_ternak' => $idJenisTernak,
                'id_kamar'        => $assignedIdKamar, // <--- DIMASUKKAN DI SINI
            ],  'id_ternak');


            // --- BAGIAN B: BUAT RIWAYAT MONITORING ---

            // Simulasikan berat lahir (antara 2 sampai 4 kg)
            $beratLahir = rand(2, 4);

            // Hitung rata-rata kenaikan berat per bulan
            $kenaikanPerBulan = ($beratSekarang - $beratLahir) / $usiaSekarang;

            $monitoringData = [];

            // Looping dari umur 0 (lahir) sampai umur sekarang
            for ($bulanKe = 0; $bulanKe <= $usiaSekarang; $bulanKe++) {

                // Kalkulasi berat pada bulan tersebut
                $beratSimulasi = round($beratLahir + ($kenaikanPerBulan * $bulanKe));

                // Beri variasi sedikit pada berat di tengah-tengah pertumbuhan agar terlihat natural
                if ($bulanKe > 0 && $bulanKe < $usiaSekarang) {
                    $beratSimulasi += rand(-1, 1);
                }

                // Kalkulasi tanggal mundur
                // Jika umur sekarang 10 bulan, monitoring bulan ke-0 terjadi 10 bulan yang lalu
                $selisihBulan = $usiaSekarang - $bulanKe;
                $tglMonitoring = Carbon::now()->subMonths($selisihBulan)->subDays(rand(0, 5));

                // Acak penyakit (Misal peluang 5% terkena penyakit pada suatu bulan)
                $kenaPenyakit = (rand(1, 100) <= 5);
                $penyakit = $kenaPenyakit && $bulanKe > 0 ? $daftarPenyakit[array_rand($daftarPenyakit)] : null;

                $monitoringData[] = [
                    'id_ternak'      => $idTernak,
                    'usia'           => $bulanKe,
                    'berat'          => $beratSimulasi,
                    'penyakit'       => $penyakit,
                    'tgl_monitoring' => $tglMonitoring->toDateString(),
                ];
            }

            // 8. Insert seluruh history monitoring untuk ternak ini
            DB::table('monitoring')->insert($monitoringData);
        }
    }
}
