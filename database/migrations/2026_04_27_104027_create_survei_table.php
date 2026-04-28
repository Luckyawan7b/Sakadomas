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
        Schema::create('survei', function (Blueprint $table) {
            $table->id('id_survei');
            $table->dateTime('tgl_survei');
            $table->enum('status', ['pending', 'disetujui', 'selesai', 'batal'])->default('pending');
            $table->text('ket')->nullable();
            $table->foreignId('id_akun')->constrained('akun', 'id_akun')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei');
    }
};
