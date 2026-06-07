<?php

use App\Models\Akun;
use App\Models\Ternak;
use App\Models\JenisTernak;
use App\Models\Monitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\DatabaseSeeder::class);
    $this->admin = Akun::where('role', 'admin')->first();
});

test('tambah catatan monitoring otomatis menyinkronkan profil ternak', function () {
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();

    $ternak = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 3,
        'berat' => 12,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 1200000,
        'last_update' => Carbon::now()->subMonths(3),
        'last_monitor' => Carbon::now()->subMonths(3)->toDateString(),
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('monitoring.store'), [
            'id_ternak' => $ternak->id_ternak,
            'tgl_monitoring' => Carbon::now()->toDateString(),
            'berat' => 35,
            'penyakit' => 'Sakit Kudis',
        ]);

    $response->assertRedirect();
    
    // Pastikan monitoring tersimpan
    $this->assertDatabaseHas('monitoring', [
        'id_ternak' => $ternak->id_ternak,
        'berat' => 35,
        'usia' => 6,
        'penyakit' => 'Sakit Kudis',
    ]);

    // Pastikan profil ternak sinkron
    $ternak = $ternak->fresh();
    expect((int)$ternak->usia)->toEqual(6);
    expect((float)$ternak->berat)->toEqual(35.0);
    expect($ternak->status_ternak)->toEqual('sakit');
    expect($ternak->last_monitor)->toEqual(Carbon::now()->toDateString());
});

test('edit catatan monitoring terbaru memperbarui profil ternak', function () {
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();

    $ternak = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 3,
        'berat' => 12,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 1200000,
        'last_update' => Carbon::now()->subMonths(3),
        'last_monitor' => Carbon::now()->subMonths(3)->toDateString(),
    ]);

    // Tambah monitoring
    $monitor = Monitor::create([
        'id_ternak' => $ternak->id_ternak,
        'tgl_monitoring' => Carbon::now()->toDateString(),
        'usia' => 6,
        'berat' => 30,
        'penyakit' => '',
    ]);
    
    // Sync manual agar profil awal sinkron
    \App\Http\Controllers\MonitorController::syncTernakDariMonitoring($ternak->id_ternak);

    expect((float)$ternak->fresh()->berat)->toEqual(30.0);

    // Edit data monitoring tersebut (koreksi berat menjadi 36 kg)
    $response = $this->actingAs($this->admin)
        ->put(route('monitoring.update', $monitor->id_monitoring), [
            'id_ternak' => $ternak->id_ternak,
            'tgl_monitoring' => Carbon::now()->toDateString(),
            'berat' => 36,
            'penyakit' => 'Batuk',
            'usia' => 6,
        ]);

    $response->assertRedirect();

    // Pastikan record di database terupdate
    $this->assertDatabaseHas('monitoring', [
        'id_monitoring' => $monitor->id_monitoring,
        'berat' => 36,
        'penyakit' => 'Batuk',
    ]);

    // Pastikan profil ternak ikut terupdate
    $ternak = $ternak->fresh();
    expect((float)$ternak->berat)->toEqual(36.0);
    expect($ternak->status_ternak)->toEqual('sakit');
});

test('mengubah tanggal monitoring pada edit menyesuaikan usia log secara relatif', function () {
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();

    $ternak = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 3,
        'berat' => 12,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 1200000,
        'last_update' => Carbon::now()->subMonths(3),
        'last_monitor' => Carbon::now()->subMonths(3)->toDateString(),
    ]);

    // Buat data monitoring tanggal hari ini (usia 6 bulan)
    $monitor = Monitor::create([
        'id_ternak' => $ternak->id_ternak,
        'tgl_monitoring' => Carbon::now()->toDateString(),
        'usia' => 6,
        'berat' => 30,
        'penyakit' => '',
    ]);

    // Ubah tanggal monitoring menjadi 2 bulan kemudian (usia relatif bertambah 2)
    $response = $this->actingAs($this->admin)
        ->put(route('monitoring.update', $monitor->id_monitoring), [
            'id_ternak' => $ternak->id_ternak,
            'tgl_monitoring' => Carbon::now()->addMonths(2)->toDateString(),
            'berat' => 35,
            'penyakit' => '',
            'usia' => 6,
        ]);

    $response->assertRedirect();

    // Usia harus menjadi 8 (6 + 2)
    $this->assertDatabaseHas('monitoring', [
        'id_monitoring' => $monitor->id_monitoring,
        'usia' => 8,
    ]);

    $ternak = $ternak->fresh();
    expect((int)$ternak->usia)->toEqual(8);
});

test('edit ganti ternak pada log menyinkronkan kedua profil ternak', function () {
    $merino = JenisTernak::where('jenis_ternak', 'merino')->first();

    $ternak1 = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 3,
        'berat' => 10,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 1200000,
        'last_update' => Carbon::now()->subMonths(3),
    ]);

    $ternak2 = Ternak::create([
        'id_jenis_ternak' => $merino->id_jenis_ternak,
        'jenis_kelamin' => 'jantan',
        'usia' => 4,
        'berat' => 15,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'harga' => 1200000,
        'last_update' => Carbon::now()->subMonths(3),
    ]);

    // Buat monitoring untuk ternak 1
    $monitor = Monitor::create([
        'id_ternak' => $ternak1->id_ternak,
        'tgl_monitoring' => Carbon::now()->toDateString(),
        'usia' => 5,
        'berat' => 20,
        'penyakit' => 'Sakit',
    ]);
    \App\Http\Controllers\MonitorController::syncTernakDariMonitoring($ternak1->id_ternak);

    expect((float)$ternak1->fresh()->berat)->toEqual(20.0);
    expect($ternak1->fresh()->status_ternak)->toEqual('sakit');

    // Pindahkan log monitoring ke ternak 2 melalui edit
    $response = $this->actingAs($this->admin)
        ->put(route('monitoring.update', $monitor->id_monitoring), [
            'id_ternak' => $ternak2->id_ternak,
            'tgl_monitoring' => Carbon::now()->toDateString(),
            'berat' => 25,
            'penyakit' => '',
            'usia' => 5,
        ]);

    $response->assertRedirect();

    // Profil ternak 1 harus ter-rollback karena tidak punya log monitoring lagi
    $ternak1 = $ternak1->fresh();
    expect($ternak1->last_monitor)->toBeNull();
    expect($ternak1->status_ternak)->toEqual('sehat'); // kembali sehat

    // Profil ternak 2 harus ter-sync dengan data log monitoring
    $ternak2 = $ternak2->fresh();
    expect((float)$ternak2->berat)->toEqual(25.0);
    expect((int)$ternak2->usia)->toEqual(5);
    expect($ternak2->status_ternak)->toEqual('sehat');
});

test('rute delete monitoring tidak tersedia dan mengembalikan 404 atau 405', function () {
    $response = $this->actingAs($this->admin)
        ->delete('/monitoring/1');

    // Karena route tidak didefinisikan lagi, method delete ke URL tersebut akan mengembalikan 404 MethodNotAllowed/NotFound
    expect($response->status())->toBeIn([404, 405]);
});
