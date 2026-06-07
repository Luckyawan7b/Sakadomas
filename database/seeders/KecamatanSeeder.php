<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/kecamatan.json'));
        $kecamatans = json_decode($json, true);
        
        $data = array_map(function ($item) {
            return [
                'id_kecamatan'   => $item['id_kecamatan'],
                'nama_kecamatan' => $item['nama_kecamatan'],
            ];
        }, $kecamatans);

        DB::table('kecamatan')->insert($data);
    }
}
