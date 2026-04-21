{{--
|--------------------------------------------------------------------------
| Component: x-landing.footer
|--------------------------------------------------------------------------
| Footer lengkap: brand, quick links, kontak, resources, peta Google Maps.
|
| Props:
|   - $waNumber : Nomor WhatsApp
|   - $email    : Alamat email kontak
|   - $address  : Alamat fisik (array: ['street', 'city', 'province', 'zip'])
|   - $mapSrc   : URL embed Google Maps iframe
|   - $socials  : array of ['platform', 'href', 'label'] (optional)
--}}

@props([
    'waNumber' => null,
    'email'    => 'hello@smart-saka.id',
    'address'  => [
        'street'   => 'Jl. Sakadomas',
        'city'     => 'Jember',
        'province' => 'Jawa Timur',
        'zip'      => '68122',
    ],
    'mapSrc'   => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4683.124794140477!2d113.54920807575692!3d-8.30150428351655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd68500610e7aa1%3A0xf216182a92934673!2sSAKADOMAS!5e1!3m2!1sid!2sid!4v1776224100672!5m2!1sid!2sid',
    'socials'  => null,
])

@php
    $waNumber = $waNumber ?? config('smartsaka.wa_number');
    $socialLinks = $socials ?? [
        ['platform' => 'facebook',  'href' => '#', 'label' => 'Facebook Smart-Saka'],
        ['platform' => 'instagram', 'href' => '#', 'label' => 'Instagram Smart-Saka'],
        ['platform' => 'whatsapp',  'href' => "https://wa.me/{$waNumber}", 'label' => 'WhatsApp Smart-Saka'],
        ['platform' => 'linkedin',  'href' => '#', 'label' => 'LinkedIn Smart-Saka'],
    ];

    // Social icon SVG paths
    $socialIcons = [
        'facebook'  => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
        'instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z',
        'whatsapp'  => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z',
        'linkedin'  => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
    ];
@endphp

<footer id="kontak" class="bg-olive-950 text-cream-200" aria-label="Footer Smart-Saka">

    {{-- ── Main Footer Content ── --}}
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-16 grid grid-cols-2 md:grid-cols-4 gap-10">

        {{-- Brand --}}
        <div class="col-span-2 md:col-span-1">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-9 h-9 bg-olive-600 rounded-xl flex items-center justify-center" aria-hidden="true">
                    <svg viewBox="0 0 28 28" class="w-5 h-5 fill-cream-100">
                        <ellipse cx="14" cy="16" rx="9" ry="7"/>
                        <circle cx="14" cy="9" r="4.5"/>
                        <circle cx="10" cy="11.5" r="3.5"/>
                        <circle cx="18" cy="11.5" r="3.5"/>
                        <circle cx="12.5" cy="8" r="3"/>
                        <circle cx="15.5" cy="8" r="3"/>
                        <rect x="10" y="22" width="2.5" height="4" rx="1.2" fill="#3a2415"/>
                        <rect x="15.5" y="22" width="2.5" height="4" rx="1.2" fill="#3a2415"/>
                        <ellipse cx="14" cy="6" rx="3.5" ry="3" fill="#4f3520"/>
                    </svg>
                </div>
                <span class="font-serif text-xl text-cream-50">Smart-Saka</span>
            </div>
            <p class="text-cream-300/70 text-sm leading-relaxed mb-5">
                Peternakan domba dan kambing premium di Jember, Jawa Timur. Sejak 2016 melayani dengan sepenuh hati.
            </p>
            <nav aria-label="Media sosial Smart-Saka">
                <div class="flex gap-3">
                    @foreach ($socialLinks as $social)
                        @if (isset($socialIcons[$social['platform']]))
                            <a
                                href="{{ $social['href'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-9 h-9 bg-olive-800 hover:bg-olive-600 rounded-lg flex items-center justify-center transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-400"
                                aria-label="{{ $social['label'] }}"
                            >
                                <svg class="w-4 h-4 fill-cream-200" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="{{ $socialIcons[$social['platform']] }}"/>
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </nav>
        </div>

        {{-- Quick Links --}}
        <nav aria-label="Tautan cepat">
            <h3 class="font-semibold text-cream-50 mb-5 text-sm tracking-wider uppercase">Tautan Cepat</h3>
            <ul class="space-y-3">
                <li><a href="#beranda"    class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Beranda</a></li>
                <li><a href="#tentang"    class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Tentang Kami</a></li>
                <li><a href="#katalog"    class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Katalog Domba</a></li>
                <li><a href="#keunggulan" class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Mengapa Kami</a></li>
                <li><a href="#faq"        class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">FAQ</a></li>
                <li><a href="#testimoni"  class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Testimoni</a></li>
            </ul>
        </nav>

        {{-- Contact --}}
        <address aria-label="Informasi kontak Smart-Saka" class="not-italic">
            <h3 class="font-semibold text-cream-50 mb-5 text-sm tracking-wider uppercase">Kontak</h3>
            <ul class="space-y-3">
                <li>
                    <a
                        href="https://wa.me/{{ $waNumber }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-start gap-2.5 text-cream-300/70 hover:text-cream-50 transition-colors"
                        aria-label="Hubungi via WhatsApp: +{{ $waNumber }}"
                    >
                        <svg class="w-4 h-4 text-olive-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-sm">+{{ $waNumber }}</span>
                    </a>
                </li>
                <li>
                    <a
                        href="mailto:{{ $email }}"
                        class="flex items-start gap-2.5 text-cream-300/70 hover:text-cream-50 transition-colors"
                    >
                        <svg class="w-4 h-4 text-olive-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm">{{ $email }}</span>
                    </a>
                </li>
                <li class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-olive-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-cream-300/70 text-sm">
                        {{ $address['street'] }}, {{ $address['city'] }},<br>
                        {{ $address['province'] }} {{ $address['zip'] }}
                    </span>
                </li>
            </ul>
        </address>

        {{-- Resources --}}
        <nav aria-label="Sumber daya">
            <h3 class="font-semibold text-cream-50 mb-5 text-sm tracking-wider uppercase">Sumber Daya</h3>
            <ul class="space-y-3">
                {{-- <li><a href="{{ route('blog.index') }}"  class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Panduan Beternak</a></li> --}}
                {{-- <li><a href="{{ route('blog.index') }}"  class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Blog & Artikel</a></li> --}}
                <li><a href="#katalog"                   class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Cara Pemesanan</a></li>
                <li><a href="#faq"                       class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">FAQ</a></li>
                {{-- <li><a href="{{ route('terms') }}"       class="text-cream-300/70 hover:text-cream-50 text-sm transition-colors">Syarat & Ketentuan</a></li> --}}
            </ul>
        </nav>
    </div>

    {{-- ── Google Maps ── --}}
    <div class="w-full relative" style="height: 360px;" aria-label="Peta lokasi Smart-Saka">
        <iframe
            src="{{ $mapSrc }}"
            style="position:absolute;inset:0;width:100%;height:100%;border:0;"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi Smart-Saka, Jember, Jawa Timur"
        ></iframe>
        {{-- Map overlay label --}}
        <div class="absolute top-4 right-4 z-10 bg-olive-950/90 text-cream-50 px-4 py-2.5 rounded-xl backdrop-blur-sm flex items-center gap-2 shadow-lg" aria-hidden="true">
            <svg class="w-4 h-4 text-olive-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <div>
                <p class="font-semibold text-xs">Lokasi Kami</p>
                <p class="text-cream-300 text-xs opacity-80">SAKADOMAS, Jember</p>
            </div>
        </div>
    </div>

    {{-- ── Copyright ── --}}
    <div class="border-t border-olive-800 px-5 lg:px-8 py-5">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-3">
            <p class="text-cream-300/50 text-xs">
                &copy; {{ date('Y') }} Smart-Saka. Peternakan Premium Jember. All rights reserved.
            </p>
            <nav class="flex gap-5" aria-label="Tautan legal">
                {{-- <a href="{{ route('privacy') }}" class="text-cream-300/50 hover:text-cream-200 text-xs transition-colors">Privacy Policy</a> --}}
                {{-- <a href="{{ route('terms') }}"   class="text-cream-300/50 hover:text-cream-200 text-xs transition-colors">Terms & Conditions</a> --}}
                {{-- <a href="{{ route('blog.index') }}" class="text-cream-300/50 hover:text-cream-200 text-xs transition-colors">Blog</a> --}}
            </nav>
        </div>
    </div>

</footer>
