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
        Schema::create('jarak', function (Blueprint $table) {
            $table->id('id_jarak');
            $table->foreignId('id_desa')->constrained('desa', 'id_desa')->onDelete('cascade');
            $table->integer('jarak_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jarak');
    }
};
