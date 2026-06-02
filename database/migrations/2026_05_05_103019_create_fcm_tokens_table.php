<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_akun')
                  ->constrained('akun', 'id_akun')
                  ->onDelete('cascade');
            $table->text('token');
            $table->string('device_info')->nullable();
            $table->timestamps();
        });

        // Hapus duplikat (kalau migration ini dijalankan ulang / ada data lama)
        DB::statement('
            DELETE FROM fcm_tokens
            WHERE id NOT IN (
                SELECT MAX(id)
                FROM fcm_tokens
                GROUP BY token
            )
        ');

        // Tambahkan unique constraint
        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->unique('token', 'fcm_tokens_token_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fcm_tokens');
    }
};
