<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart-Saka') | Pilihan Cerdas Penikmat Domba</title>

    {{-- Google Fonts: Noto Serif & Manrope --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400;1,700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    {{--
        Vite — Tailwind v4 dikompilasi via @tailwindcss/vite plugin.
        Tidak ada tailwind.config.js. Semua token ada di resources/css/app.css.
        Tidak ada Alpine CDN — auth pages tidak butuh Alpine.
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="selection:bg-primary/20 bg-background text-on-background antialiased">

    @yield('content')

    @stack('scripts')

</body>
</html>
