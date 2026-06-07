<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class JarakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/data_ongkir_jember.json'));
        $jaraks = json_decode($json, true);
        
        $data = array_map(function ($item) {
            return [
                'id_jarak' => $item['id_jarak'],
                'id_desa'  => $item['id_desa'],
                'jarak_km' => $item['jarak_km'],
            ];
        }, $jaraks);

        $chunks = array_chunk($data, 100);
        foreach ($chunks as $chunk) {
            DB::table('jarak')->insert($chunk);
        }
    }
}
