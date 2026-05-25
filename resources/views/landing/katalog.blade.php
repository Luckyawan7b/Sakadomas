{{--
|--------------------------------------------------------------------------
| View: resources/views/landing/katalog.blade.php
|--------------------------------------------------------------------------
| Halaman Katalog Produk — Sidebar Filter + Lazy Load + Responsive
| Route: GET /katalog  → LandingController@katalog
--}}

@extends('layouts.landing')

@section('title', 'Katalog Domba & Kambing | Smart-Saka Premium Sheep Farm')

@section('meta_description', 'Temukan koleksi domba Crosstexel, Merino, dan kambing Etawa premium.')

@push('head')
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #3d6700;
            border: 2px solid #fff;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
        }

        input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #3d6700;
            border: 2px solid #fff;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')

    <x-landing.navbar />

    {{-- HERO BANNER --}}
    <section class="relative bg-m3-surface-container-low pt-44 pb-28 overflow-hidden">
        {{-- <div class="absolute inset-0 z-0 bg-cover bg-center opacity-20" style="background-image: url('{{ asset('images/katalog.jpg') }}');"></div> --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('{{ asset('images/katalog.jpg') }}'); background-size: cover; background-position: center;"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 ">
            <nav class="flex items-center space-x-2 text-sm font-label text-m3-on-surface-variant mb-6 tracking-wide">
                <a href="{{ route('home') }}" class="hover:text-m3-primary transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-m3-primary font-semibold">Katalog Domba</span>
            </nav>
            <div class="flex flex-col md:flex-row justify-between items-end">
                <div class="max-w-2xl">
                    <h1 class="text-5xl md:text-7xl font-playfair font-bold text-m3-primary mb-6 leading-tight">Katalog
                        Domba</h1>
                    <p class="text-lg text-m3-on-surface-variant leading-relaxed max-w-xl">
                        Temukan koleksi domba pilihan terbaik dari peternakan kami. Kualitas genetika unggul dan kesehatan
                        terjamin secara alami.
                    </p>
                </div>
                <div class="hidden lg:block">
                    <span class="material-symbols-outlined text-[12rem] text-m3-primary/10 select-none"
                        style="font-variation-settings: 'FILL' 0, 'wght' 200;">pets</span>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT — Alpine.js State --}}
    <section class="max-w-7xl mx-auto px-6 lg:px-8 -mt-10 relative z-20 mb-24"
        x-data='{
        allItems: @json($jenis_ternak),
        searchQuery: "",
        sortBy: "terbaru",
        loadedCount: 9,
        batchSize: 6,
        showFilterDrawer: false,

        // Filter state
        filterBreed: [],
        filterKelamin: [],
        filterUsia: [],
        filterKelas: [],

        // Computed: filtered + sorted items
        get filteredItems() {
            let items = this.allItems;

            // Search
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                items = items.filter(i =>
                    (i.nama_produk || "").toLowerCase().includes(q) ||
                    (i.breed || "").toLowerCase().includes(q) ||
                    (i.kelas_berat || "").toLowerCase().includes(q)
                );
            }

            // Filter breed
            if (this.filterBreed.length) {
                items = items.filter(i => this.filterBreed.includes((i.breed || "").toLowerCase().replace(/[\s()]/g, "")));
            }

            // Filter kelamin
            if (this.filterKelamin.length) {
                items = items.filter(i => {
                    const gender = (i.jenis_kelamin || "").toLowerCase();
                    return this.filterKelamin.some(f => f.toLowerCase() === gender);
                });
            }

            // Filter kelas
            if (this.filterKelas.length) {
                items = items.filter(i => this.filterKelas.includes(i.kelas_berat));
            }

            // Filter usia
            if (this.filterUsia.length) {
                items = items.filter(i => this.filterUsia.includes(i.kategori_usia));
            }

            // Sort
            if (this.sortBy === "harga_asc") items = [...items].sort((a, b) => a.harga - b.harga);
            else if (this.sortBy === "harga_desc") items = [...items].sort((a, b) => b.harga - a.harga);

            return items;
        },

        get visibleItems() {
            return this.filteredItems.slice(0, this.loadedCount);
        },

        get hasMore() {
            return this.loadedCount < this.filteredItems.length;
        },

        loadMore() { this.loadedCount += this.batchSize; },

        resetFilters() {
            this.filterBreed = [];
            this.filterKelamin = [];
            this.filterUsia = [];
            this.filterKelas = [];
            this.searchQuery = "";
            this.sortBy = "terbaru";
            this.loadedCount = 9;
        },

        applyAndClose() {
            this.loadedCount = 9;
            this.showFilterDrawer = false;
        },

        onFilterChange() { this.loadedCount = 9; },

        formatRupiah(n) {
            return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(n);
        },

        getImage(item) {
            let breedKey = (item.breed || "crosstexel").toLowerCase().replace(/[\s()]/g, "");
            // Handle if breed maps to etawa(pe) -> etawape -> etawa
            if (breedKey.includes("etawa")) breedKey = "etawa";

            let usiaKey = "indukan"; // default fallback
            if (item.kategori_usia) {
                const usia = item.kategori_usia.toLowerCase();
                if (usia.includes("anakan") || usia.includes("bibit")) usiaKey = "anakan";
                else if (usia.includes("doro") || usia.includes("muda")) usiaKey = "doro";
                else if (usia.includes("indukan") || usia.includes("dewasa")) usiaKey = "indukan";
            }

            // Generate the dynamic path based on folder structure
            // Added ?v=1.1 for cache busting since images were optimized
            return `/images/${breedKey}/${usiaKey}.webp?v=1.1`;
        }
    }'
        x-init='
        $watch("filterBreed", () => onFilterChange());
        $watch("filterKelamin", () => onFilterChange());
        $watch("filterUsia", () => onFilterChange());
        $watch("filterKelas", () => onFilterChange());
        $watch("searchQuery", () => onFilterChange());
        $watch("sortBy", () => onFilterChange());
    '>

        {{-- SEARCH + SORT BAR --}}
        <div class="bg-m3-surface-container-lowest p-4 sm:p-6 rounded-3xl shadow-[0_20px_50px_rgba(61,103,0,0.08)]">
            <div class="flex flex-col sm:flex-row gap-4">
                {{-- Search --}}
                <div class="flex-1 relative">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-m3-outline">search</span>
                    <input x-model="searchQuery"
                        class="w-full pl-12 pr-4 py-4 bg-m3-surface-container rounded-2xl border-none focus:ring-2 focus:ring-m3-primary-fixed transition-all text-sm"
                        placeholder="Cari jenis domba..." type="text" />
                </div>
                {{-- Sort --}}
                <div class="flex items-center gap-3">
                    <select x-model="sortBy"
                        class="bg-m3-surface-container border-none rounded-2xl py-4 px-4 text-m3-on-surface-variant font-medium text-sm min-w-[180px]">
                        <option value="terbaru">Terbaru</option>
                        <option value="harga_asc">Harga: Terendah</option>
                        <option value="harga_desc">Harga: Tertinggi</option>
                    </select>
                    {{-- Mobile filter button --}}
                    <button @click="showFilterDrawer = true"
                        class="md:hidden flex items-center gap-2 bg-m3-primary text-m3-on-primary px-5 py-4 rounded-2xl font-bold text-sm whitespace-nowrap">
                        <span class="material-symbols-outlined text-lg">tune</span> Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- SIDEBAR + GRID --}}
        <div class="mt-12 flex flex-col md:flex-row gap-10 lg:gap-12">

            {{-- SIDEBAR — Desktop --}}
            <aside class="hidden md:block w-full md:w-1/4 lg:w-[280px] shrink-0">
                <div class="bg-m3-surface-container-low rounded-3xl p-7 sticky top-32">
                    <div class="flex justify-between items-center mb-7">
                        <h3 class="text-lg font-bold text-m3-primary font-headline">Filter</h3>
                        <button @click="resetFilters()"
                            class="text-xs font-medium text-m3-on-surface-variant hover:text-m3-primary underline">Reset</button>
                    </div>

                    {{-- Jenis Domba --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Jenis
                            Domba</p>
                        <div class="space-y-2.5">
                            @foreach (['crosstexel' => 'Cross Texel', 'merino' => 'Merino', 'etawa' => 'Etawa (PE)'] as $key => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $key }}" x-model="filterBreed"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-4 h-4" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kelamin --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Kelamin
                        </p>
                        <div class="space-y-2.5">
                            @foreach (['Jantan', 'Betina'] as $kel)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $kel }}" x-model="filterKelamin"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-4 h-4" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $kel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kategori Usia --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Kategori
                            Usia</p>
                        <div class="space-y-2.5">
                            @foreach (['Anakan/Bibit' => 'Anakan/Bibit (0-5 bln)', 'Doro/Muda' => 'Doro/Muda (6-11 bln)', 'Indukan/Dewasa' => 'Indukan/Dewasa (≥12 bln)'] as $val => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $val }}" x-model="filterUsia"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-4 h-4" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Kelas</p>
                        <div class="space-y-2.5">
                            @foreach (['Standard', 'Medium', 'Super'] as $kelas)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $kelas }}" x-model="filterKelas"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-4 h-4" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $kelas }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            {{-- MOBILE FILTER DRAWER --}}
            <div x-show="showFilterDrawer" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 md:hidden" style="display:none;">
                <div class="absolute inset-0 bg-black/40" @click="showFilterDrawer = false"></div>
                <aside x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="absolute left-0 top-0 bottom-0 w-80 max-w-[85vw] bg-m3-surface overflow-y-auto p-7">
                    <div class="flex justify-between items-center mb-7">
                        <h3 class="text-lg font-bold text-m3-primary font-headline">Filter</h3>
                        <button @click="showFilterDrawer = false"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-m3-surface-container">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    {{-- Jenis Domba --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Jenis
                            Domba</p>
                        <div class="space-y-2.5">
                            @foreach (['crosstexel' => 'Cross Texel', 'merino' => 'Merino', 'etawa' => 'Etawa (PE)'] as $key => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $key }}" x-model="filterBreed"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-5 h-5" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kelamin --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Kelamin
                        </p>
                        <div class="space-y-2.5">
                            @foreach (['Jantan', 'Betina'] as $kel)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $kel }}" x-model="filterKelamin"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-5 h-5" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $kel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kategori Usia --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Kategori
                            Usia</p>
                        <div class="space-y-2.5">
                            @foreach (['Anakan/Bibit' => 'Anakan/Bibit (0-5 bln)', 'Doro/Muda' => 'Doro/Muda (6-11 bln)', 'Indukan/Dewasa' => 'Indukan/Dewasa (≥12 bln)'] as $val => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $val }}" x-model="filterUsia"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-5 h-5" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-7">
                        <p class="text-[10px] font-bold text-m3-on-surface-variant uppercase tracking-widest mb-3">Kelas</p>
                        <div class="space-y-2.5">
                            @foreach (['Standard', 'Medium', 'Super'] as $kelas)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $kelas }}" x-model="filterKelas"
                                        class="rounded border-m3-outline-variant text-m3-primary focus:ring-m3-primary w-5 h-5" />
                                    <span
                                        class="text-sm text-m3-on-surface-variant group-hover:text-m3-primary transition-colors">{{ $kelas }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button @click="resetFilters()"
                            class="flex-1 py-3 border-2 border-m3-outline-variant text-m3-on-surface-variant rounded-full font-bold text-sm">Reset</button>
                        <button @click="applyAndClose()"
                            class="flex-1 py-3 bg-m3-primary text-m3-on-primary rounded-full font-bold text-sm">Terapkan</button>
                    </div>
                </aside>
            </div>

            {{-- PRODUCT GRID --}}
            <div class="flex-1 min-w-0">
                {{-- Count --}}
                <div class="flex justify-between items-center mb-6">
                    <p class="text-m3-on-surface-variant font-medium text-sm">
                        Menampilkan <span class="text-m3-primary font-bold" x-text="visibleItems.length"></span>
                        dari <span class="text-m3-primary font-bold" x-text="filteredItems.length"></span> produk
                    </p>
                    {{-- Active filter tags --}}
                    <div class="hidden sm:flex items-center gap-2 flex-wrap capitalize"
                        x-show="filterBreed.length || filterKelamin.length || filterUsia.length || filterKelas.length">
                        <template x-for="f in filterBreed" :key="'b_' + f">
                            <span class="bg-m3-primary-fixed/40 text-m3-primary text-[10px] font-bold px-3 py-1 rounded-full"
                                x-text="f"></span>
                        </template>
                        <template x-for="f in filterKelamin" :key="'k_' + f">
                            <span class="bg-m3-primary-fixed/40 text-m3-primary text-[10px] font-bold px-3 py-1 rounded-full"
                                x-text="f"></span>
                        </template>
                        <template x-for="f in filterUsia" :key="'u_' + f">
                            <span class="bg-m3-primary-fixed/40 text-m3-primary text-[10px] font-bold px-3 py-1 rounded-full"
                                x-text="f"></span>
                        </template>
                        <template x-for="f in filterKelas" :key="'cl_' + f">
                            <span class="bg-m3-primary-fixed/40 text-m3-primary text-[10px] font-bold px-3 py-1 rounded-full"
                                x-text="f"></span>
                        </template>
                    </div>
                </div>

                {{-- Empty state --}}
                <div x-show="filteredItems.length === 0" class="text-center py-20">
                    <span class="material-symbols-outlined text-6xl text-m3-outline-variant mb-4">inventory_2</span>
                    <h3 class="text-xl font-bold text-m3-on-surface mb-2">Tidak Ditemukan</h3>
                    <p class="text-m3-on-surface-variant mb-6">Coba ubah filter atau kata kunci pencarian.</p>
                    <button @click="resetFilters()"
                        class="px-8 py-3 bg-m3-primary text-m3-on-primary rounded-full font-bold text-sm">Reset
                        Filter</button>
                </div>

                {{-- Cards Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="(item, idx) in visibleItems"
                        :key="item.nama_produk + item.kelas_berat + item.jenis_kelamin + item.harga">
                        <div
                            class="group bg-m3-surface-container-lowest rounded-[2rem] overflow-hidden shadow-[0_10px_30px_rgba(61,103,0,0.04)] transition-all hover:-translate-y-2 hover:shadow-[0_25px_60px_rgba(61,103,0,0.1)]">
                            {{-- Image --}}
                            <div class="relative h-52 overflow-hidden">
                                <img :src="getImage(item)" :alt="item.nama_produk"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    loading="lazy" />
                                <span
                                    class="absolute top-4 left-4 bg-m3-primary-container text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                    x-text="item.kelas_berat"></span>
                                <template x-if="item.stok <= 3">
                                    <span
                                        class="absolute top-4 right-4 bg-m3-error/90 text-white px-3 py-1 rounded-full text-[10px] font-bold">Stok
                                        Terbatas</span>
                                </template>
                            </div>

                            {{-- Content --}}
                            <div class="p-5">
                                <h4 class="capitalize text-lg font-bold text-m3-primary font-headline mb-1 line-clamp-1"
                                    x-text="item.nama_produk"></h4>

                                {{-- Specs: range berat, range usia, kelamin --}}
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    <div class="bg-m3-surface-container-low p-2 rounded-xl text-center">
                                        <span
                                            class="material-symbols-outlined text-m3-primary text-base block">weight</span>
                                        <span class="text-[9px] font-bold text-m3-on-surface-variant leading-tight block"
                                            x-text="item.weight_range || '-'"></span>
                                    </div>
                                    <div class="bg-m3-surface-container-low p-2 rounded-xl text-center">
                                        <span
                                            class="material-symbols-outlined text-m3-primary text-base block">calendar_today</span>
                                        <span class="text-[9px] font-bold text-m3-on-surface-variant leading-tight block"
                                            x-text="item.age_range || '-'"></span>
                                    </div>
                                    <div class="bg-m3-surface-container-low p-2 rounded-xl text-center">
                                        <span class="material-symbols-outlined text-m3-primary text-base block"
                                            x-text="(item.jenis_kelamin || '').toLowerCase() === 'jantan' ? 'male' : 'female'"></span>
                                        <span class="text-[9px] font-bold text-m3-on-surface-variant"
                                            x-text="item.jenis_kelamin"></span>
                                    </div>
                                </div>

                                {{-- Price & CTA --}}
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-xl font-bold text-m3-primary"
                                        x-text="formatRupiah(item.harga)"></span>
                                    <span
                                        class="text-[10px] text-m3-on-surface-variant font-medium bg-m3-surface-container-low px-2 py-0.5 rounded-full"
                                        x-text="'Stok: ' + item.stok"></span>
                                </div>
                                <div class="flex gap-2">
                                    @auth
                                        <a :href="'{{ route('transaksi.create') }}?jenis=' + item.id_jenis +
                                            '&kelamin=' + (item.jenis_kelamin || '').toLowerCase() + '&harga=' + item.harga +
                                            '&kategori=' + encodeURIComponent(item.nama_produk) + '&kelas=' + encodeURIComponent(item.kelas_berat)"
                                            class="flex-1 py-2.5 bg-m3-secondary-container text-m3-on-secondary-container rounded-full font-bold text-sm hover:bg-m3-primary-fixed transition-colors text-center">
                                            Pesan Sekarang
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="flex-1 py-2.5 bg-m3-secondary-container text-m3-on-secondary-container rounded-full font-bold text-sm hover:bg-m3-primary-fixed transition-colors text-center">
                                            Login & Beli
                                        </a>
                                    @endauth
                                    <a :href="'{{ url('/produk') }}/' + item.slug"
                                        class="w-10 h-10 flex items-center justify-center bg-m3-surface-container rounded-full text-m3-on-surface-variant hover:bg-m3-surface-variant transition-colors shrink-0">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Load More --}}
                <div x-show="hasMore" class="mt-14 flex justify-center">
                    <button @click="loadMore()"
                        class="px-10 py-4 bg-m3-primary text-m3-on-primary rounded-full font-bold text-sm shadow-lg hover:bg-m3-primary-container active:scale-95 transition-all flex items-center gap-3">
                        <span class="material-symbols-outlined">expand_more</span>
                        Muat Lebih Banyak
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- TRUST STRIP --}}
    <section class="bg-m3-primary text-m3-on-primary py-16 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                @foreach ([['icon' => 'health_and_safety', 'title' => 'Terjamin Sehat', 'desc' => 'Vaksin lengkap & pantauan dokter rutin'], ['icon' => 'agriculture', 'title' => 'Langsung dari Peternakan', 'desc' => 'Tanpa perantara, harga terbaik'], ['icon' => 'support_agent', 'title' => 'Konsultasi WhatsApp', 'desc' => 'Gratis konsultasi perawatan domba'], ['icon' => 'verified_user', 'title' => '100% Garansi', 'desc' => 'Jaminan kualitas & bibit unggul resmi']] as $trust)
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="w-14 h-14 lg:w-16 lg:h-16 rounded-3xl bg-white/10 flex items-center justify-center mb-4 lg:mb-6 backdrop-blur">
                            <span class="material-symbols-outlined text-2xl lg:text-3xl"
                                style="font-variation-settings: 'FILL' 1;">{{ $trust['icon'] }}</span>
                        </div>
                        <h4 class="font-bold text-sm lg:text-lg mb-1 lg:mb-2">{{ $trust['title'] }}</h4>
                        <p class="text-white/70 text-xs lg:text-sm">{{ $trust['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-landing.footer wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}"
        email="{{ $email ?? config('smartsaka.email') }}" :address="$address ?? config('smartsaka.address')"
        map-src="{{ $mapsSrc ?? config('smartsaka.maps_embed_src') }}" />

@endsection
