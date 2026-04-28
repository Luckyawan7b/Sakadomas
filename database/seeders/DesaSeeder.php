<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/desa.json'));
        $desas = json_decode($json, true);
        
        $data = array_map(function ($item) {
            return [
                'id_desa'      => $item['id_desa'],
                'nama_desa'    => $item['nama_desa'],
                'id_kecamatan' => $item['id_kecamatan'],
            ];
        }, $desas);

        $chunks = array_chunk($data, 100);
        foreach ($chunks as $chunk) {
            DB::table('desa')->insert($chunk);
        }
    }
}
