<?php

use App\Models\Akun;
use App\Models\Ternak;
use App\Models\JenisTernak;
use App\Models\Monitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed database sebelum setiap test untuk mendapatkan data master (kecamatan, desa, jenis_ternak, dll)
    $this->seed(\Database\Seeders\DatabaseSeeder::class);
    
    // Ambil user yang di-seed
    $this->admin = Akun::where('role', 'admin')->first();
    $this->pelanggan = Akun::where('role', 'pelanggan')->first();

    // Backup file value.json agar pengujian tidak merusak file aslinya
    $this->jsonPath = public_path('json/value.json');
    $this->jsonBackup = null;
    if (File::exists($this->jsonPath)) {
        $this->jsonBackup = File::get($this->jsonPath);
    }
});

afterEach(function () {
    // Kembalikan file value.json ke keadaan semula
    if ($this->jsonBackup !== null) {
        File::put($this->jsonPath, $this->jsonBackup);
    }
});

test('guest tidak dapat mengakses halaman harga katalog', function () {
    $response = $this->get(route('ternak.harga.index'));
    $response->assertRedirect('/login');
});

test('pelanggan tidak dapat mengakses halaman harga katalog', function () {
    $response = $this->actingAs($this->pelanggan)
        ->get(route('ternak.harga.index'));
    $response->assertRedirect('/');
    $response->assertSessionHas('error');
});

test('admin dapat mengakses halaman harga katalog', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('ternak.harga.index'));
    $response->assertStatus(200);
    $response->assertSee('Harga Katalog');
});

test('admin dapat memperbarui harga katalog (mengubah value.json dan database)', function () {
    // Ambil JenisTernak Merino
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();
    
    // Buat data ternak Merino siap jual (jantan, usia 3 bulan [Anakan/Bibit], berat 22 kg [Super])
    $ternak = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 3,
        'berat' => 22,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 1800000,
        'last_update' => Carbon::now(),
    ]);

    // Berdasarkan value.json default, Merino Anakan/Bibit Super Jantan harganya adalah 1.800.000
    expect((int)$ternak->fresh()->harga)->toEqual(1800000);

    // Ambil data asli untuk payload
    $data = json_decode(File::get($this->jsonPath), true);
    $pricesPayload = [];

    foreach ($data['ternak_klasifikasi'] as $bIdx => $breed) {
        foreach ($breed['age_categories'] as $aIdx => $ageCat) {
            foreach ($ageCat['weight_classes'] as $cIdx => $wClass) {
                $pricesPayload[$bIdx][$aIdx][$cIdx] = [
                    'Jantan' => $wClass['prices']['Jantan'],
                    'Betina' => $wClass['prices']['Betina'],
                ];
            }
        }
    }

    // Ubah harga Merino (Index 1) -> Anakan/Bibit (Index 0) -> Super (Index 2) -> Jantan menjadi 2.500.000
    $newPrice = 2500000;
    $pricesPayload[1][0][2]['Jantan'] = $newPrice;

    $response = $this->actingAs($this->admin)
        ->post(route('ternak.harga.update'), [
            'prices' => $pricesPayload
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Cek apakah file value.json terupdate
    $updatedData = json_decode(File::get($this->jsonPath), true);
    expect((int)$updatedData['ternak_klasifikasi'][1]['age_categories'][0]['weight_classes'][2]['prices']['Jantan'])->toEqual($newPrice);

    // Cek apakah database juga ikut terupdate via mass update
    expect((int)$ternak->fresh()->harga)->toEqual($newPrice);
});

test('admin dapat menjalankan sinkronisasi harga manual', function () {
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();
    
    // Buat data ternak dengan harga yang tidak sesuai (misal diubah manual/salah logic sebelumnya)
    $ternak = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 3,
        'berat' => 22,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 100000, // Harga salah
        'last_update' => Carbon::now(),
    ]);

    expect((int)$ternak->fresh()->harga)->toEqual(100000);

    // Jalankan Sinkronisasi
    $response = $this->actingAs($this->admin)
        ->post(route('ternak.harga.sync'));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Harga harus kembali normal sesuai value.json default (1.800.000)
    expect((int)$ternak->fresh()->harga)->toEqual(1800000);
});

test('tambah dan edit data ternak menghitung harga secara otomatis', function () {
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();

    // 1. Tambah Data Ternak baru via controller
    $response = $this->actingAs($this->admin)
        ->post(route('ternak.store'), [
            'id_jenis_ternak' => $merino->id_jenis_ternak,
            'jenis_kelamin' => 'jantan',
            'usia' => 3,
            'berat' => 22, // Super
            'status_ternak' => 'sehat',
            'status_jual' => 'siap jual',
        ]);

    $response->assertRedirect();
    
    $ternak = Ternak::latest('id_ternak')->first();
    // Harga otomatis diset ke 1.800.000
    expect((int)$ternak->harga)->toEqual(1800000);

    // 2. Edit Data Ternak via controller
    // Ubah berat menjadi 12 kg (Standard, range 10-14 kg, Jantan harga = 1.200.000)
    $response = $this->actingAs($this->admin)
        ->put(route('ternak.update', $ternak->id_ternak), [
            'id_jenis_ternak' => $merino->id_jenis_ternak,
            'jenis_kelamin' => 'jantan',
            'usia' => 3,
            'berat' => 12,
            'status_ternak' => 'sehat',
            'status_jual' => 'siap jual',
        ]);

    $response->assertRedirect();
    expect((int)$ternak->fresh()->harga)->toEqual(1200000);
});

test('catatan monitoring baru memperbarui profil fisik & menghitung ulang harga ternak', function () {
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();

    // Buat data ternak awal
    $ternak = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 3,          // Anakan/Bibit
        'berat' => 12,        // Standard (1.200.000)
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 1200000,
        'last_update' => Carbon::now()->subMonths(3), // Set 3 bulan lalu agar monitor bisa menghitung selisih usia
        'last_monitor' => Carbon::now()->subMonths(3)->toDateString(),
    ]);

    expect((int)$ternak->harga)->toEqual(1200000);

    // Simpan data monitoring baru 3 bulan setelahnya
    // Berat bertambah menjadi 35 kg -> Doro/Muda Super Jantan harga = 2.300.000
    $response = $this->actingAs($this->admin)
        ->post(route('monitoring.store'), [
            'id_ternak' => $ternak->id_ternak,
            'tgl_monitoring' => Carbon::now()->toDateString(),
            'berat' => 35,
            'penyakit' => '',
        ]);

    $response->assertRedirect();
    
    // Pastikan record monitoring tersimpan
    $this->assertDatabaseHas('monitoring', [
        'id_ternak' => $ternak->id_ternak,
        'berat' => 35,
        'usia' => 6,
    ]);

    // Pastikan profil & harga ternak terupdate
    $ternak = $ternak->fresh();
    expect((int)$ternak->usia)->toEqual(6);
    expect((float)$ternak->berat)->toEqual(35.0);
    expect((int)$ternak->harga)->toEqual(2300000);
});
