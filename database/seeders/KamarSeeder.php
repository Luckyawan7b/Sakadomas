<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KamarSeeder extends Seeder
{
    public function run()
    {

        DB::table('kamar')->insert([
            // --- KAMAR UNTUK KANDANG 1 (Total Kapasitas: 5) ---
            [
                'nomor_kamar' => 1,
                'kapasitas'   => 5,
                'id_kandang'  => 1,
            ],
            [
                'nomor_kamar' => 2,
                'kapasitas'   => 5,
                'id_kandang'  => 1,

            ],
            [
                'nomor_kamar' => 3,
                'kapasitas'   => 5,
                'id_kandang'  => 1,

            ],
            [
                'nomor_kamar' => 4,
                'kapasitas'   => 3,
                'id_kandang'  => 1,

            ],
            [
                'nomor_kamar' => 5,
                'kapasitas'   => 3,
                'id_kandang'  => 1,

            ],

            // --- KAMAR UNTUK KANDANG 2 (Total Kapasitas: 10) ---
            [
                'nomor_kamar' => 1,
                'kapasitas'   => 2,
                'id_kandang'  => 2,

            ],
            [
                'nomor_kamar' => 2,
                'kapasitas'   => 2,
                'id_kandang'  => 2,

            ],
            [
                'nomor_kamar' => 3,
                'kapasitas'   => 2,
                'id_kandang'  => 2,

            ],
            [
                'nomor_kamar' => 4,
                'kapasitas'   => 2,
                'id_kandang'  => 2,

            ],
            [
                'nomor_kamar' => 5,
                'kapasitas'   => 2,
                'id_kandang'  => 2,

            ],
        ]);
    }
}
