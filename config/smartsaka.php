<?php

return [
    'wa_number' => env('SMARTSAKA_WA_NUMBER', '6281234567890'),

    'wa_default_message' => env('SMARTSAKA_WA_DEFAULT_MESSAGE', 'Halo Smart-Saka! Saya ingin tanya produk/layanan.'),

    'email' => env('SMARTSAKA_EMAIL', 'info@smartsaka.id'),

    // Perbaikan Address: Ubah jadi array agar bisa diakses per bagian
    'address' => [
        'street'   => env('SMARTSAKA_STREET', 'Jl. Sakadomas'),
        'city'     => env('SMARTSAKA_CITY', 'Jember'),
        'province' => env('SMARTSAKA_PROVINCE', 'Jawa Timur'),
        'zip'      => env('SMARTSAKA_ZIP', '68122'),
    ],

    'maps_embed_src' => env('SMARTSAKA_MAPS_EMBED_SRC', ''),

    'og_image' => env('SMARTSAKA_OG_IMAGE', 'images/landing/about-1.jpg'),

    // Perbaikan Socials: Ubah format menjadi array of arrays
    'socials' => [
        [
            'platform' => 'instagram',
            'href'     => env('SMARTSAKA_INSTAGRAM', '#'),
            'label'    => 'Instagram Smart-Saka'
        ],
        [
            'platform' => 'facebook',
            'href'     => env('SMARTSAKA_FACEBOOK', '#'),
            'label'    => 'Facebook Smart-Saka'
        ],
        [
            'platform' => 'tiktok',
            'href'     => env('SMARTSAKA_TIKTOK', '#'),
            'label'    => 'TikTok Smart-Saka'
        ],
        [
            'platform' => 'youtube',
            'href'     => env('SMARTSAKA_YOUTUBE', '#'),
            'label'    => 'YouTube Smart-Saka'
        ],
    ],
];