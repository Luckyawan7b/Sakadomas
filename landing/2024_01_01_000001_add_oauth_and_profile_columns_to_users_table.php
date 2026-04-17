<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * =============================================================================
 * Migration: Tambah kolom Google OAuth ke tabel users
 * =============================================================================
 *
 * Jalankan dengan: php artisan migrate
 *
 * File ini TIDAK mengubah kolom yang sudah ada.
 * Hanya menambahkan kolom baru: google_id, avatar, phone, username,
 * kecamatan, desa, address — sesuai form register Smart-Saka.
 *
 * PERHATIAN: Jika Anda menggunakan Breeze/Jetstream, tabel users sudah ada.
 * Cukup jalankan migration ini untuk menambahkan kolom baru.
 * =============================================================================
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // --- Google OAuth ---
            $table->string('google_id')->nullable()->after('id');
            $table->string('avatar')->nullable()->after('google_id');

            // --- Profil Tambahan (dari form register) ---
            $table->string('username', 50)->nullable()->unique()->after('name');
            $table->string('phone', 20)->nullable()->after('username');

            // --- Alamat ---
            $table->string('kecamatan')->nullable()->after('phone');
            $table->string('desa')->nullable()->after('kecamatan');
            $table->text('address')->nullable()->after('desa');

            // Index untuk pencarian cepat
            $table->index('google_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['google_id']);
            $table->dropColumn([
                'google_id', 'avatar',
                'username', 'phone',
                'kecamatan', 'desa', 'address',
            ]);
        });
    }
};
