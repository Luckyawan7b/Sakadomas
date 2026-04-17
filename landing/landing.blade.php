<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ── SEO ── --}}
    <title>@yield('title', 'Smart-Saka | Peternakan Domba & Kambing Premium Jember')</title>
    <meta name="description" content="@yield('meta_description', 'Pusat pembibitan domba Crosstexel, Merino, dan kambing Etawa premium di Jember. Langsung dari kandang, bersertifikat sehat, siap antar Jawa Timur.')">
    <meta name="keywords"    content="domba premium, kambing etawa, bibit domba, kurban, aqiqah, peternakan jember, smart-saka">
    <meta name="author"      content="Smart-Saka">
    <meta name="robots"      content="index, follow">
    <link  rel="canonical"   href="{{ url()->current() }}">

    {{-- ── Open Graph — tampilan saat link dibagikan via WhatsApp / Facebook ── --}}
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:title"       content="@yield('og_title', 'Smart-Saka | Peternakan Domba & Kambing Premium')">
    <meta property="og:description" content="@yield('og_description', 'Pusat pembibitan domba Crosstexel, Merino, dan kambing Etawa premium di Jember. Bersertifikat sehat, siap antar.')">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-smart-saka.jpg'))">
    <meta property="og:locale"      content="id_ID">
    <meta property="og:site_name"   content="Smart-Saka">

    {{-- ── Twitter Card ── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('og_title', 'Smart-Saka | Peternakan Domba Premium')">
    <meta name="twitter:description" content="@yield('og_description', 'Pusat pembibitan domba premium Jember.')">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/og-smart-saka.jpg'))">

    {{-- ── JSON-LD Structured Data ── --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Smart-Saka",
        "description": "Peternakan domba dan kambing premium di Jember, Jawa Timur",
        "url": "{{ url('/') }}",
        "telephone": "+62812XXXXXXXX",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jl. Sakadomas",
            "addressLocality": "Jember",
            "addressRegion": "Jawa Timur",
            "postalCode": "68122",
            "addressCountry": "ID"
        },
        "geo": { "@type": "GeoCoordinates", "latitude": -8.3015, "longitude": 113.5492 },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
            "opens": "08:00", "closes": "17:00"
        },
        "sameAs": [
            "https://www.instagram.com/smartsaka",
            "https://www.facebook.com/smartsaka"
        ]
    }
    </script>

    {{-- ── Google Fonts ── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{--
        ── Vite (Tailwind v4 + Alpine dari npm) ──
        Alpine di-import di app.js, TIDAK perlu script CDN terpisah.
        @tailwindcss/vite plugin menggantikan postcss config di v4.
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="relative">

    {{-- ── WhatsApp Floating Button ── --}}
    <a
        href="https://wa.me/{{ config('smartsaka.wa_number') }}?text={{ urlencode(config('smartsaka.wa_default_message')) }}"
        target="_blank"
        rel="noopener noreferrer"
        class="wa-float"
        aria-label="Hubungi kami via WhatsApp"
    >
        <svg class="wa-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span class="wa-label">Chat Kami</span>
    </a>

    {{-- ── Toast Notification ── --}}
    <div
        id="toast"
        role="status"
        aria-live="polite"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex items-center gap-3 bg-olive-800 text-cream-50 text-sm font-medium px-5 py-3.5 rounded-2xl shadow-2xl translate-y-20 opacity-0 transition-all duration-300 pointer-events-none"
    >
        <svg id="toast-icon" class="w-5 h-5 text-olive-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span id="toast-msg">Berhasil!</span>
    </div>

    @yield('content')

    @stack('scripts')

</body>
</html>
