<?php

use App\Models\Akun;
use App\Models\Keuangan;
use App\Models\Transaksi;
use App\Models\JenisTernak;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed database before each test to get the required lookup data (kecamatan, desa, jenis_ternak, etc.)
    $this->seed(\Database\Seeders\DatabaseSeeder::class);
    
    // Retrieve default seeded admin
    $this->admin = Akun::where('role', 'admin')->first();
});

test('admin can access keuangan index and see correct default stats', function () {
    // Insert some dummy data
    Keuangan::create([
        'ket' => 'Pemasukan Uji',
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 500000,
        'jenis_keuangan' => 'pemasukan',
    ]);

    Keuangan::create([
        'ket' => 'Pengeluaran Uji',
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 200000,
        'jenis_keuangan' => 'pengeluaran',
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('keuangan.index'));

    $response->assertStatus(200);
    $response->assertSee('Rp 500.000'); // Pemasukan
    $response->assertSee('Rp 200.000'); // Pengeluaran
    $response->assertSee('Rp 300.000'); // Saldo Bersih
});

test('admin can add manual keuangan entry', function () {
    $data = [
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 150000,
        'jenis_keuangan' => 'pengeluaran',
        'ket' => 'Gaji Pegawai',
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('keuangan.store'), $data);

    $response->assertRedirect();
    $this->assertDatabaseHas('keuangan', [
        'ket' => 'Gaji Pegawai',
        'nominal' => 150000,
        'jenis_keuangan' => 'pengeluaran',
        'id_transaksi' => null,
    ]);
});

test('admin cannot add manual keuangan entry with future date', function () {
    $data = [
        'tanggal' => Carbon::tomorrow()->toDateString(),
        'nominal' => 150000,
        'jenis_keuangan' => 'pengeluaran',
        'ket' => 'Pakan Ternak',
    ];

    $response = $this->actingAs($this->admin)
        ->from(route('keuangan.index'))
        ->post(route('keuangan.store'), $data);

    $response->assertRedirect(route('keuangan.index'));
    $response->assertSessionHasErrors(['tanggal']);
    $this->assertDatabaseMissing('keuangan', [
        'ket' => 'Pakan Ternak',
    ]);
});

test('admin can edit manual keuangan entry', function () {
    $keuangan = Keuangan::create([
        'ket' => 'Beli Pakan Awal',
        'tanggal' => Carbon::yesterday()->toDateString(),
        'nominal' => 100000,
        'jenis_keuangan' => 'pengeluaran',
        'id_transaksi' => null,
    ]);

    $updateData = [
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 120000,
        'jenis_keuangan' => 'pengeluaran',
        'ket' => 'Beli Pakan Revisi',
    ];

    $response = $this->actingAs($this->admin)
        ->put(route('keuangan.update', $keuangan->id_keuangan), $updateData);

    $response->assertRedirect();
    $this->assertDatabaseHas('keuangan', [
        'id_keuangan' => $keuangan->id_keuangan,
        'ket' => 'Beli Pakan Revisi',
        'nominal' => 120000,
        'tanggal' => Carbon::today()->toDateString(),
    ]);
});

test('admin cannot edit automatic keuangan entry', function () {
    // Create a dummy transaction
    $jenisTernak = JenisTernak::first();
    $transaksi = Transaksi::create([
        'tgl_transaksi' => Carbon::today(),
        'total_jumlah' => 1,
        'total_harga' => 1500000,
        'metode_pembayaran' => 'transfer',
        'status' => 'selesai',
        'id_akun' => $this->admin->id_akun,
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'metode_pengiriman' => 'ambil_sendiri',
        'ongkir' => 0,
    ]);

    $keuangan = Keuangan::create([
        'ket' => 'Pemasukan dari transaksi #TRX-' . $transaksi->id_transaksi,
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 1500000,
        'jenis_keuangan' => 'pemasukan',
        'id_transaksi' => $transaksi->id_transaksi,
    ]);

    $updateData = [
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 2000000,
        'jenis_keuangan' => 'pemasukan',
        'ket' => 'Percobaan Edit Otomatis',
    ];

    $response = $this->actingAs($this->admin)
        ->from(route('keuangan.index'))
        ->put(route('keuangan.update', $keuangan->id_keuangan), $updateData);

    $response->assertRedirect(route('keuangan.index'));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('keuangan', [
        'id_keuangan' => $keuangan->id_keuangan,
        'nominal' => 1500000,
        'ket' => 'Pemasukan dari transaksi #TRX-' . $transaksi->id_transaksi,
    ]);
});

test('transaction completed automatically syncs to keuangan', function () {
    $jenisTernak = JenisTernak::first();
    $transaksi = Transaksi::create([
        'tgl_transaksi' => Carbon::today(),
        'total_jumlah' => 2,
        'total_harga' => 3000000,
        'metode_pembayaran' => 'transfer',
        'status' => 'diproses',
        'id_akun' => $this->admin->id_akun,
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'metode_pengiriman' => 'dikirim',
        'ongkir' => 50000,
    ]);

    // Assign mock sheep details to satisfy verification rule
    $ternakList = \App\Models\Ternak::take(2)->get();
    foreach ($ternakList as $t) {
        \App\Models\DetailTransaksi::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'id_ternak' => $t->id_ternak,
            'sub_jumlah' => 1,
            'sub_total' => $t->harga,
        ]);
    }

    // Update status to selesai via TransaksiController
    $response = $this->actingAs($this->admin)
        ->put(route('transaksi.update', $transaksi->id_transaksi), [
            'status' => 'selesai',
        ]);

    $response->assertRedirect();
    
    // Keuangan entry should be created automatically (nominal = total_harga + ongkir)
    $this->assertDatabaseHas('keuangan', [
        'id_transaksi' => $transaksi->id_transaksi,
        'nominal' => 3050000,
        'jenis_keuangan' => 'pemasukan',
    ]);
});

test('transaction status changed from selesai deletes keuangan entry', function () {
    $jenisTernak = JenisTernak::first();
    $transaksi = Transaksi::create([
        'tgl_transaksi' => Carbon::today(),
        'total_jumlah' => 1,
        'total_harga' => 1000000,
        'metode_pembayaran' => 'transfer',
        'status' => 'selesai',
        'id_akun' => $this->admin->id_akun,
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'metode_pengiriman' => 'ambil_sendiri',
        'ongkir' => 0,
    ]);

    // Create corresponding keuangan record
    Keuangan::create([
        'ket' => 'Pemasukan dari transaksi #TRX-' . $transaksi->id_transaksi,
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 1000000,
        'jenis_keuangan' => 'pemasukan',
        'id_transaksi' => $transaksi->id_transaksi,
    ]);

    // Admin updates transaction status back to diproses
    $response = $this->actingAs($this->admin)
        ->put(route('transaksi.update', $transaksi->id_transaksi), [
            'status' => 'diproses',
        ]);

    $response->assertRedirect();
    
    // Keuangan entry should be deleted
    $this->assertDatabaseMissing('keuangan', [
        'id_transaksi' => $transaksi->id_transaksi,
    ]);
});

test('transaction deleted deletes keuangan entry', function () {
    $jenisTernak = JenisTernak::first();
    $transaksi = Transaksi::create([
        'tgl_transaksi' => Carbon::today(),
        'total_jumlah' => 1,
        'total_harga' => 1000000,
        'metode_pembayaran' => 'transfer',
        'status' => 'selesai',
        'id_akun' => $this->admin->id_akun,
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'metode_pengiriman' => 'ambil_sendiri',
        'ongkir' => 0,
    ]);

    // Create corresponding keuangan record
    Keuangan::create([
        'ket' => 'Pemasukan dari transaksi #TRX-' . $transaksi->id_transaksi,
        'tanggal' => Carbon::today()->toDateString(),
        'nominal' => 1000000,
        'jenis_keuangan' => 'pemasukan',
        'id_transaksi' => $transaksi->id_transaksi,
    ]);

    // Admin deletes transaction
    $response = $this->actingAs($this->admin)
        ->delete(route('transaksi.delete', $transaksi->id_transaksi));

    $response->assertRedirect();
    
    // Keuangan entry should be deleted
    $this->assertDatabaseMissing('keuangan', [
        'id_transaksi' => $transaksi->id_transaksi,
    ]);
});

test('admin can create offline transaction and it syncs to keuangan', function () {
    $jenisTernak = JenisTernak::first();
    
    // Seed some specific healthy and ready-to-sell sheep
    $ternak1 = \App\Models\Ternak::create([
        'jenis_kelamin' => 'jantan',
        'usia' => 12,
        'berat' => 30,
        'harga' => 1500000,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'last_update' => Carbon::now()->toDateString(),
        'last_monitor' => Carbon::now()->toDateString(),
    ]);

    $ternak2 = \App\Models\Ternak::create([
        'jenis_kelamin' => 'jantan',
        'usia' => 13,
        'berat' => 32,
        'harga' => 1600000,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'last_update' => Carbon::now()->toDateString(),
        'last_monitor' => Carbon::now()->toDateString(),
    ]);

    $payload = [
        'id_jenis_ternak'       => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'id_ternak'             => [$ternak1->id_ternak, $ternak2->id_ternak],
        'metode_pembayaran'     => 'cash',
        'metode_pengiriman'     => 'ambil_sendiri',
        'ongkir'                => 0,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('transaksi.store.admin'), $payload);

    $response->assertRedirect(route('transaksi.index'));

    // Assert transaction is created as 'selesai'
    $this->assertDatabaseHas('transaksi', [
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'total_jumlah' => 2,
        'total_harga' => 3100000, // 1500000 + 1600000
        'status' => 'selesai',
        'metode_pengiriman' => 'ambil_sendiri',
        'ongkir' => 0,
    ]);

    $transaksi = Transaksi::where('id_jenis_ternak', $jenisTernak->id_jenis_ternak)
        ->where('jenis_kelamin_pesanan', 'jantan')
        ->latest('id_transaksi')
        ->first();

    // Assert detail transaksi are created
    $this->assertDatabaseHas('detail_transaksi', [
        'id_transaksi' => $transaksi->id_transaksi,
        'id_ternak' => $ternak1->id_ternak,
        'sub_jumlah' => 1,
        'sub_total' => 1500000,
    ]);
    $this->assertDatabaseHas('detail_transaksi', [
        'id_transaksi' => $transaksi->id_transaksi,
        'id_ternak' => $ternak2->id_ternak,
        'sub_jumlah' => 1,
        'sub_total' => 1600000,
    ]);

    // Assert sheep status are updated to 'terjual' and id_kamar is null
    $this->assertDatabaseHas('ternak', [
        'id_ternak' => $ternak1->id_ternak,
        'status_jual' => 'terjual',
        'id_kamar' => null,
    ]);
    $this->assertDatabaseHas('ternak', [
        'id_ternak' => $ternak2->id_ternak,
        'status_jual' => 'terjual',
        'id_kamar' => null,
    ]);

    // Assert Keuangan record is automatically synced
    $this->assertDatabaseHas('keuangan', [
        'id_transaksi' => $transaksi->id_transaksi,
        'nominal' => 3100000,
        'jenis_keuangan' => 'pemasukan',
    ]);
});

test('creating offline transaction does not dispatch ProcessInvoiceEmailJob', function () {
    \Illuminate\Support\Facades\Queue::fake();

    $jenisTernak = JenisTernak::first();
    $ternak = \App\Models\Ternak::create([
        'jenis_kelamin' => 'jantan',
        'usia' => 12,
        'berat' => 30,
        'harga' => 1500000,
        'status_ternak' => 'sehat',
        'status_jual' => 'siap jual',
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'last_update' => Carbon::now()->toDateString(),
        'last_monitor' => Carbon::now()->toDateString(),
    ]);

    $payload = [
        'id_jenis_ternak'       => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'id_ternak'             => [$ternak->id_ternak],
        'metode_pembayaran'     => 'cash',
        'metode_pengiriman'     => 'ambil_sendiri',
        'ongkir'                => 0,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('transaksi.store.admin'), $payload);

    $response->assertRedirect(route('transaksi.index'));

    \Illuminate\Support\Facades\Queue::assertNotPushed(App\Jobs\ProcessInvoiceEmailJob::class);
});

test('completing online transaction dispatches ProcessInvoiceEmailJob', function () {
    \Illuminate\Support\Facades\Queue::fake();

    $jenisTernak = JenisTernak::first();
    $transaksi = Transaksi::create([
        'tgl_transaksi' => Carbon::today(),
        'total_jumlah' => 1,
        'total_harga' => 1000000,
        'metode_pembayaran' => 'transfer',
        'status' => 'diproses',
        'id_akun' => $this->admin->id_akun,
        'id_jenis_ternak' => $jenisTernak->id_jenis_ternak,
        'jenis_kelamin_pesanan' => 'jantan',
        'metode_pengiriman' => 'ambil_sendiri',
        'ongkir' => 0,
    ]);

    $ternak = \App\Models\Ternak::first();
    \App\Models\DetailTransaksi::create([
        'id_transaksi' => $transaksi->id_transaksi,
        'id_ternak' => $ternak->id_ternak,
        'sub_jumlah' => 1,
        'sub_total' => 1000000,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('transaksi.update', $transaksi->id_transaksi), [
            'status' => 'selesai',
        ]);

    $response->assertRedirect();

    \Illuminate\Support\Facades\Queue::assertPushed(App\Jobs\ProcessInvoiceEmailJob::class);
});

