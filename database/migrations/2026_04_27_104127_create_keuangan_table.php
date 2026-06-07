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
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id('id_keuangan');
            $table->text('ket')->nullable();
            $table->date('tanggal');
            $table->integer('nominal');
            $table->enum('jenis_keuangan', ['pemasukan', 'pengeluaran']);
            $table->foreignId('id_transaksi')->nullable()->constrained('transaksi', 'id_transaksi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
