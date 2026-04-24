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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->integer('id_jenis_ternak')->nullable();

            $table->foreign('id_jenis_ternak')->references('id_jenis_ternak')->on('jenis_ternak')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['id_jenis_ternak']);
            $table->dropColumn(['id_jenis_ternak']);
        });
    }
};
