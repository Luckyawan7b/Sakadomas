<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->dateTime('tgl_transaksi');
            $table->integer('total_jumlah');
            $table->integer('total_harga');
            $table->enum('metode_pembayaran', ['transfer', 'cash'])->nullable();
            $table->text('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'dikirim', 'selesai', 'batal', 'diproses'])->default('pending');
            $table->dateTime('tgl_dikirim')->nullable();
            $table->date('batas_survei')->nullable();
            $table->boolean('is_survei')->default(false);
            $table->foreignId('id_akun')->constrained('akun', 'id_akun')->onDelete('cascade');
            $table->string('kurir')->nullable();
            $table->string('no_kurir')->nullable();
            $table->enum('jenis_kelamin_pesanan', ['jantan', 'betina']);
            $table->foreignId('id_jenis_ternak')->constrained('jenis_ternak', 'id_jenis_ternak')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
