{{--
|--------------------------------------------------------------------------
| Component: x-landing.navbar
|--------------------------------------------------------------------------
| Navbar fixed dengan Alpine.js untuk mobile menu.
| Scroll shadow & active link highlighting via app.js.
|
| Tidak ada props — konten hardcoded, modifikasi langsung di file ini.
--}}

<nav
    id="navbar"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-cream-50/80 backdrop-blur-md"
    x-data="{ open: false }"
    aria-label="Navigasi utama"
>
    <div class="max-w-7xl mx-auto px-5 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-[4.5rem]">

            {{-- ── Logo ── --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group" aria-label="Smart-Saka — Beranda">
                <div class="w-9 h-9 bg-olive-700 rounded-xl flex items-center justify-center shadow-sm group-hover:bg-olive-600 transition-colors" aria-hidden="true">
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
                        <circle cx="12.5" cy="5.5" r="0.6" fill="#fdfdf8"/>
                        <circle cx="15.5" cy="5.5" r="0.6" fill="#fdfdf8"/>
                    </svg>
                </div>
                <span class="font-serif text-xl text-olive-900 leading-none">Smart-Saka</span>
            </a>

            {{-- ── Desktop Navigation ── --}}
            <ul class="hidden md:flex items-center gap-1" role="list">
                <li><a href="#beranda"    class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Beranda</a></li>
                <li><a href="#tentang"    class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Tentang Kami</a></li>
                <li><a href="#katalog"    class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Katalog Domba</a></li>
                <li><a href="#faq"        class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">FAQ</a></li>
                <li><a href="#testimoni"  class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Testimoni</a></li>
            </ul>

            {{-- ── CTA + Hamburger ── --}}
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('register') }}"
                    class="hidden md:inline-flex items-center gap-2 bg-olive-700 hover:bg-olive-600 text-cream-50 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Daftar Sekarang
                </a>

                {{-- Hamburger — Alpine controlled --}}
                <button
                    class="md:hidden hamburger flex flex-col gap-1.5 p-2"
                    :class="{ 'open': open }"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-menu"
                    aria-label="Toggle menu navigasi"
                >
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Mobile Menu — Alpine x-show ── --}}
    <div
        id="mobile-menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-olive-100 bg-cream-50/98 backdrop-blur-md"
        @click.away="open = false"
        role="navigation"
        aria-label="Menu mobile"
    >
        <div class="px-5 py-4 flex flex-col gap-1">
            <a href="#beranda"   @click="open = false" class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Beranda</a>
            <a href="#tentang"   @click="open = false" class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Tentang Kami</a>
            <a href="#katalog"   @click="open = false" class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Katalog Domba</a>
            <a href="#faq"       @click="open = false" class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">FAQ</a>
            <a href="#testimoni" @click="open = false" class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Testimoni</a>
            <a href="{{ route('register') }}"    @click="open = false" class="mt-2 px-4 py-3 text-sm font-semibold text-center bg-olive-700 text-cream-50 rounded-xl transition-all hover:bg-olive-600">
                Daftar Sekarang
            </a>
        </div>
    </div>
</nav>
