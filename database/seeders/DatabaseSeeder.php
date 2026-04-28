<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('jenis_ternak')->insert([
            [
                'id_jenis_ternak' => 1,
                'jenis_ternak' => 'merino'
            ],
            [
                'id_jenis_ternak' => 2,
                'jenis_ternak' => 'crosstexel'
            ],
            [
                'id_jenis_ternak' => 3,
                'jenis_ternak' => 'etawa'
            ], 
        ]);

        DB::table('kandang')->insert([
            [
                'id_kandang' => 1,
                'nomor_kandang' => 1,
                'kapasitas' => 5
            ],
            [
                'id_kandang' => 2,
                'nomor_kandang' => 2,
                'kapasitas' => 10
            ],
            
        ]);

        $this->call([
            KecamatanSeeder::class,
            DesaSeeder::class,
            KamarSeeder::class,
            TernakSeeder::class,
        ]);

        DB::table('akun')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'nama'     => 'Administrator',
                'alamat'   => 'Jl. PB Sudirman, Jember',
                'no_hp'    => '081234567890',
                'email'    => 'luckyawan7b@gmail.com',
                'role'     => 'admin',
                'id_desa'  => 99,
            ],
        ]);

    }
}
