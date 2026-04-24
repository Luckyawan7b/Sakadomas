<?php

/**
 * =============================================================================
 * config/smartsaka.php
 * Smart-Saka — Konfigurasi aplikasi spesifik
 * =============================================================================
 *
 * Cara pakai di Blade:
 *   {{ config('smartsaka.wa_number') }}
 *
 * Cara pakai di PHP:
 *   config('smartsaka.wa_number')
 *
 * Setelah mengubah .env, jalankan:
 *   php artisan config:clear
 * =============================================================================
 */

return [

    // ── WhatsApp ──────────────────────────────────────────────────────────────
    // Format: kode negara + nomor tanpa tanda hubung atau spasi
    // Contoh: 6281234567890 (62 = Indonesia, 81234567890 = nomor lokal)
    'wa_number' => env('SMARTSAKA_WA_NUMBER', '62895700326271'),

    // Pesan default WA untuk tombol floating dan hero CTA
    'wa_default_message' => env(
        'SMARTSAKA_WA_DEFAULT_MESSAGE',
        'Halo Smart-Saka! 🐑 Saya ingin bertanya tentang produk Anda.'
    ),

    // ── Bisnis ────────────────────────────────────────────────────────────────
    'name'     => env('SMARTSAKA_NAME', 'Smart-Saka'),
    'email'    => env('SMARTSAKA_EMAIL', 'hello@smart-saka.id'),
    'phone'    => env('SMARTSAKA_PHONE', '+62812XXXXXXXX'),
    'founded'  => env('SMARTSAKA_FOUNDED', '2016'),

    'address' => [
        'street'   => env('SMARTSAKA_ADDRESS_STREET', 'Jl. Sakadomas'),
        'city'     => env('SMARTSAKA_ADDRESS_CITY', 'Jember'),
        'province' => env('SMARTSAKA_ADDRESS_PROVINCE', 'Jawa Timur'),
        'zip'      => env('SMARTSAKA_ADDRESS_ZIP', '68122'),
        'country'  => 'ID',
    ],

    'coordinates' => [
        'latitude'  => env('SMARTSAKA_LAT', -8.3015),
        'longitude' => env('SMARTSAKA_LNG', 113.5492),
    ],

    // Google Maps embed src — ganti dengan URL dari Google Maps > Share > Embed
    'maps_embed_src' => env(
        'SMARTSAKA_MAPS_SRC',
        'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4683.124794140477!2d113.54920807575692!3d-8.30150428351655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd68500610e7aa1%3A0xf216182a92934673!2sSAKADOMAS!5e1!3m2!1sid!2sid!4v1776224100672!5m2!1sid!2sid'
    ),

    // ── Media Sosial ─────────────────────────────────────────────────────────
    'socials' => [
        'instagram' => env('SMARTSAKA_INSTAGRAM', 'https://www.instagram.com/smartsaka'),
        'facebook'  => env('SMARTSAKA_FACEBOOK',  'https://www.facebook.com/smartsaka'),
        'tiktok'    => env('SMARTSAKA_TIKTOK',    ''),
        'youtube'   => env('SMARTSAKA_YOUTUBE',   ''),
    ],

    // ── SEO ───────────────────────────────────────────────────────────────────
    'og_image' => env('SMARTSAKA_OG_IMAGE', '/images/og-smart-saka.jpg'),

];
