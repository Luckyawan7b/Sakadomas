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
        Schema::create('ternak', function (Blueprint $table) {
            $table->id('id_ternak');
            $table->enum('jenis_kelamin', ['jantan', 'betina'])->nullable();
            $table->integer('usia');
            $table->integer('berat');
            $table->integer('harga');
            $table->enum('status_ternak', ['sehat', 'sakit', 'hamil', 'mati'])->default('sehat');
            $table->enum('status_jual', ['tidak dijual', 'siap jual', 'booking', 'terjual'])->default('tidak dijual');
            $table->date('last_update');
            $table->date('last_monitor')->nullable();
            $table->foreignId('id_kamar')->nullable()->constrained('kamar', 'id_kamar')->onDelete('set null');
            $table->foreignId('id_jenis_ternak')->constrained('jenis_ternak', 'id_jenis_ternak')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ternak');
    }
};
