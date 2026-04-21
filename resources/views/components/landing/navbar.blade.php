{{--
|--------------------------------------------------------------------------
| Component: x-landing.navbar
|--------------------------------------------------------------------------
| Navbar fixed dengan Alpine.js untuk mobile menu.
| Scroll shadow & active link highlighting via app.js.
--}}

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-cream-50/80 backdrop-blur-md"
    x-data="{ open: false }" aria-label="Navigasi utama">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-[4.5rem]">

            {{-- ── Logo ── --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group" aria-label="Smart-Saka — Beranda">
                <div class="w-9 h-9 bg-olive-700 rounded-xl flex items-center justify-center shadow-sm group-hover:bg-olive-600 transition-colors"
                    aria-hidden="true">
                    <svg viewBox="0 0 28 28" class="w-5 h-5 fill-cream-100">
                        <ellipse cx="14" cy="16" rx="9" ry="7" />
                        <circle cx="14" cy="9" r="4.5" />
                        <circle cx="10" cy="11.5" r="3.5" />
                        <circle cx="18" cy="11.5" r="3.5" />
                        <circle cx="12.5" cy="8" r="3" />
                        <circle cx="15.5" cy="8" r="3" />
                        <rect x="10" y="22" width="2.5" height="4" rx="1.2" fill="#3a2415" />
                        <rect x="15.5" y="22" width="2.5" height="4" rx="1.2" fill="#3a2415" />
                        <ellipse cx="14" cy="6" rx="3.5" ry="3" fill="#4f3520" />
                        <circle cx="12.5" cy="5.5" r="0.6" fill="#fdfdf8" />
                        <circle cx="15.5" cy="5.5" r="0.6" fill="#fdfdf8" />
                    </svg>
                </div>
                <span class="font-serif text-xl text-olive-900 leading-none">Smart-Saka</span>
            </a>

            {{-- ── Desktop Navigation ── --}}
            <ul class="hidden md:flex items-center gap-1" role="list">
                <li><a href="/#beranda"
                        class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Beranda</a>
                </li>
                <li><a href="/#tentang"
                        class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Tentang
                        Kami</a></li>
                <li><a href="/#katalog"
                        class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Katalog
                        Domba</a></li>
                <li><a href="/#faq"
                        class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">FAQ</a>
                </li>
                <li><a href="/#testimoni"
                        class="px-4 py-2 text-sm font-medium text-olive-800 hover:text-olive-600 hover:bg-olive-50 rounded-lg transition-all">Testimoni</a>
                </li>
            </ul>

            {{-- ── CTA + Hamburger ── --}}
            <div class="flex items-center gap-3">

                {{-- JIKA BELUM LOGIN --}}
                @guest
                    {{-- <a href="/login"
                        class="hidden md:inline-flex items-center gap-2 bg-olive-700 hover:bg-olive-600 text-cream-50 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4 M10 17l5-5-5-5 M15 12H3"/>
                        </svg>


                        Login
                    </a> --}}
                    <a href="{{ route('login') }}"
                        class="hidden md:inline-flex items-center gap-2 border border-olive-700 text-olive-800 hover:text-olive-700 hover:bg-olive-50 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="hidden md:inline-flex items-center gap-2 bg-olive-700 hover:bg-olive-600 text-cream-50 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md">
                        {{-- <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg> --}}
                        Daftar Sekarang
                    </a>
                @endguest

                {{-- JIKA SUDAH LOGIN (Dropdown Desktop) --}}
                @auth
                    {{-- Gunakan hidden md:block agar sembunyi di HP dan tampil di Desktop --}}
                    <div class="relative hidden md:block" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                        <button @click.prevent="dropdownOpen = !dropdownOpen"
                            class="flex items-center gap-2 text-olive-800 hover:bg-olive-100 px-3 py-2 rounded-xl transition-colors">
                            <span class="block text-sm font-semibold text-right">
                                <span class="block text-olive-900 leading-tight">{{ Auth::user()->nama ?? 'User' }}</span>
                                <span
                                    class="block text-[10px] uppercase tracking-wider text-olive-500">{{ Auth::user()->role ?? 'Guest' }}</span>
                            </span>
                            <svg class="w-5 h-5 transition-transform duration-200 text-olive-600"
                                :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-3 flex w-[260px] flex-col rounded-2xl border border-olive-200 bg-cream-50 p-2 shadow-lg z-50"
                            style="display: none;">

                            @if (Auth::user()->role === 'admin')
                                <a href="/dashboard"
                                    class="flex items-center gap-3 px-3 py-2.5 font-medium text-olive-800 rounded-lg text-sm hover:bg-olive-100 transition-colors">
                                    <svg class="w-5 h-5 text-olive-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                        </path>
                                    </svg>
                                    Dashboard Admin
                                </a>
                            @endif

                            <a href="/profile"
                                class="flex items-center gap-3 px-3 py-2.5 font-medium text-olive-800 rounded-lg text-sm hover:bg-olive-100 transition-colors">
                                <svg class="w-5 h-5 text-olive-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profil Saya
                            </a>

                            <div class="h-px bg-olive-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full gap-3 px-3 py-2.5 font-medium text-red-600 rounded-lg text-sm hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                {{-- Hamburger — Alpine controlled --}}
                <button class="md:hidden hamburger flex flex-col gap-1.5 p-2" :class="{ 'open': open }"
                    @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-menu"
                    aria-label="Toggle menu navigasi">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Mobile Menu — Alpine x-show ── --}}
    <div id="mobile-menu" x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-olive-100 bg-cream-50/98 backdrop-blur-md" @click.away="open = false"
        role="navigation" aria-label="Menu mobile">
        <div class="px-5 py-4 flex flex-col gap-1">
            <a href="/#beranda" @click="open = false"
                class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Beranda</a>
            <a href="/#tentang" @click="open = false"
                class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Tentang
                Kami</a>
            <a href="/#katalog" @click="open = false"
                class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Katalog
                Domba</a>
            <a href="/#faq" @click="open = false"
                class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">FAQ</a>
            <a href="/#testimoni" @click="open = false"
                class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-50 rounded-lg transition-all">Testimoni</a>

            <div class="h-px bg-olive-100 my-2"></div>

            @guest
                <a href="/login" @click="open = false"
                    class="px-4 py-3 text-sm font-semibold text-center bg-olive-700 text-cream-50 rounded-xl transition-all hover:bg-olive-600">
                    Masuk
                </a>
            @endguest

            @auth
                <div class="px-4 py-2">
                    <p class="text-xs font-semibold text-olive-500 uppercase tracking-widest">{{ Auth::user()->role }}</p>
                    <p class="text-base font-bold text-olive-900">{{ Auth::user()->nama }}</p>
                </div>

                @if (Auth::user()->role === 'admin')
                    <a href="/dashboard"
                        class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-100 rounded-lg transition-all">Dashboard
                        Admin</a>
                @endif
                <a href="/profile"
                    class="px-4 py-3 text-sm font-medium text-olive-800 hover:bg-olive-100 rounded-lg transition-all">Profil
                    Saya</a>

                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-lg transition-all">
                        Keluar
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>
