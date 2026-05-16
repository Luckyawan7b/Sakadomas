{{--
|--------------------------------------------------------------------------
| View: resources/views/landing/detail-produk.blade.php
|--------------------------------------------------------------------------
| Halaman Detail Tipe Produk (Category-Based) Landing Page Smart-Saka.
| Route: GET /produk/{slug}  → LandingController@detailProduk
|
| Data dari Controller:
|   $produk        : Array data kategori (breed, kelas, range, stok, harga)
|   $produkSerupa  : Collection tipe produk serupa (dari getKatalogProduk)
|   $dummyImages   : Array gambar dummy per breed
|   $waNumber      : Nomor WhatsApp
--}}

@extends('layouts.landing')

@section('title', 'Smart-Saka | ' . $produk['breed'] . ' ' . $produk['jenis_kelamin'] . ' ' . $produk['kelas_berat'])

@section('content')

    {{-- ── NAVBAR ── --}}
    <x-landing.navbar />

    <main class="pt-32 pb-20 max-w-7xl mx-auto px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-12 flex items-center space-x-2 text-sm text-m3-outline font-label uppercase tracking-widest">
            <a class="hover:text-m3-primary transition-colors" href="{{ route('katalog') }}">Katalog</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-m3-primary font-bold capitalize">{{ $produk['breed'] }} {{ $produk['jenis_kelamin'] }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            {{-- Left Column: Image --}}
            <div class="lg:col-span-7 space-y-6">
                @php
                    $breedKey = strtolower(str_replace([' ', '(', ')'], '', $produk['breed'] ?? 'crosstexel'));
                    if (str_contains($breedKey, 'etawa')) $breedKey = 'etawa';

                    $usiaKey = 'indukan';
                    if (isset($produk['kategori_usia'])) {
                        $usia = strtolower($produk['kategori_usia']);
                        if (str_contains($usia, 'anakan') || str_contains($usia, 'bibit')) $usiaKey = 'anakan';
                        elseif (str_contains($usia, 'doro') || str_contains($usia, 'muda')) $usiaKey = 'doro';
                        elseif (str_contains($usia, 'indukan') || str_contains($usia, 'dewasa')) $usiaKey = 'indukan';
                    }
                    $imageSrc = asset("images/{$breedKey}/{$usiaKey}.webp?v=1.1");
                @endphp
                <div class="relative group aspect-[4/5] bg-m3-surface-container-low rounded-[2rem] overflow-hidden">
                    <img src="{{ $imageSrc }}" alt="{{ $produk['breed'] }} {{ $produk['jenis_kelamin'] }}"
                        class="w-full h-full object-cover" loading="eager" />
                    <div class="absolute top-6 left-6 flex flex-col gap-3">
                        <span
                            class="bg-m3-primary-container text-m3-on-primary-container px-4 py-1.5 rounded-full text-xs font-bold font-label uppercase tracking-wider">
                            {{ $produk['kelas_berat'] }}
                        </span>
                        @if ($produk['stok'] > 0)
                            <span
                                class="bg-white/90 backdrop-blur-md text-m3-primary px-4 py-1.5 rounded-full text-xs font-bold font-label">
                                {{ $produk['stok'] }} Ekor Tersedia
                            </span>
                        @else
                            <span
                                class="bg-m3-error/90 text-white px-4 py-1.5 rounded-full text-xs font-bold font-label">
                                Stok Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column: Details --}}
            <div class="lg:col-span-5 space-y-10">
                <header class="space-y-4">
                    <h1
                        class="capitalize text-5xl font-extrabold font-headline text-m3-on-surface tracking-tighter leading-tight">
                        {{ $produk['breed'] }} {{ $produk['jenis_kelamin'] }}
                    </h1>
                    <p class="text-sm text-m3-on-surface-variant font-medium">
                        {{ $produk['kategori_usia'] }} · Kelas {{ $produk['kelas_berat'] }}
                    </p>
                    <div class="flex items-baseline space-x-3">
                        <span
                            class="text-3xl font-bold text-m3-primary font-headline">Rp {{ number_format($produk['harga'], 0, ',', '.') }}</span>
                        <span class="text-sm text-m3-on-surface-variant">/ ekor</span>
                    </div>
                </header>

                {{-- Specs Grid --}}
                <section class="grid grid-cols-2 md:grid-cols-3 gap-6 capitalize">
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Range Berat
                        </p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $produk['weight_range'] }}</p>
                    </div>
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Range Usia
                        </p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $produk['age_range'] }}</p>
                    </div>
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Jenis Kelamin
                        </p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $produk['jenis_kelamin'] }}</p>
                    </div>
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Kelas</p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $produk['kelas_berat'] }}</p>
                    </div>
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Stok</p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $produk['stok'] }} Ekor</p>
                    </div>
                </section>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    @if ($produk['stok'] > 0)
                        @auth
                            <a href="{{ route('transaksi.create') }}?jenis={{ $produk['id_jenis'] }}&kelamin={{ $produk['jenis_kelamin'] }}&harga={{ $produk['harga'] }}"
                                class="flex-1 bg-m3-primary text-m3-on-primary py-5 rounded-full font-bold font-headline text-lg hover:bg-m3-primary-container transition-all flex items-center justify-center gap-3">
                                Pesan Sekarang
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex-1 bg-m3-primary text-m3-on-primary py-5 rounded-full font-bold font-headline text-lg hover:bg-m3-primary-container transition-all flex items-center justify-center gap-3">
                                <span class="material-symbols-outlined">login</span>
                                Login & Beli
                            </a>
                        @endauth
                    @else
                        <button disabled
                            class="flex-1 bg-m3-outline/30 text-m3-on-surface-variant py-5 rounded-full font-bold font-headline text-lg cursor-not-allowed flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">block</span>
                            Stok Habis
                        </button>
                    @endif
                    <a href="https://wa.me/{{ $waNumber ?? config('smartsaka.wa_number') }}?text={{ urlencode('Halo, saya tertarik dengan ' . $produk['breed'] . ' ' . $produk['jenis_kelamin'] . ' kelas ' . $produk['kelas_berat']) }}"
                        target="_blank" rel="noopener"
                        class="flex-1 bg-m3-secondary-container text-m3-on-secondary-container py-5 rounded-full font-bold font-headline text-lg hover:bg-m3-secondary-fixed transition-all flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">forum</span>
                        Tanya Admin
                    </a>
                </div>

                {{-- Trust Badges --}}
                <div class="flex justify-between items-center px-4 py-6 border-t border-m3-outline-variant/20">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-m3-primary text-3xl">verified</span>
                        <div class="leading-none">
                            <p class="text-xs font-bold text-m3-on-surface">Sertifikat Sehat</p>
                            <p class="text-[10px] text-m3-outline">Diperiksa Dokter Hewan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-m3-primary text-3xl">local_shipping</span>
                        <div class="leading-none">
                            <p class="text-xs font-bold text-m3-on-surface">Pengiriman</p>
                            <p class="text-[10px] text-m3-outline">Se-Kabupaten Jember</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deskripsi --}}
        <section class="mt-32">
            <div class="bg-m3-surface-container-low p-8 rounded-3xl">
                <h3 class="text-2xl font-bold font-headline text-m3-on-surface mb-4">Deskripsi</h3>
                <p class="text-m3-on-surface-variant leading-relaxed text-lg capitalize">
                    {{ $produk['breed'] }} {{ $produk['jenis_kelamin'] }} kelas {{ $produk['kelas_berat'] }} merupakan
                    hasil seleksi unggul kategori {{ $produk['kategori_usia'] }}
                    yang mengedepankan kualitas daging dan ketahanan tubuh. Dipelihara dengan standar bio-security yang ketat
                    di Smart-Saka Farm,
                    memastikan kesehatan optimal sejak lahir. Berat berkisar antara {{ $produk['weight_range'] }} dengan
                    rentang usia {{ $produk['age_range'] }}.
                </p>
            </div>
        </section>
    </main>

    {{-- Related Products Section --}}
    @if ($produkSerupa->isNotEmpty())
        <section class="bg-m3-surface-container-low py-24">
            <div class="max-w-7xl mx-auto px-8">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <span
                            class="text-m3-primary font-bold font-label uppercase tracking-widest text-xs mb-2 block">Pilihan
                            Lainnya</span>
                        <h2 class="text-4xl font-extrabold font-headline tracking-tighter">Produk Serupa</h2>
                    </div>
                    <a class="text-m3-primary font-bold hover:underline flex items-center gap-2"
                        href="{{ route('katalog') }}">
                        Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($produkSerupa as $serupa)
                        @php
                            $sBreedKey = strtolower(str_replace([' ', '(', ')'], '', $serupa['breed'] ?? 'crosstexel'));
                            if (str_contains($sBreedKey, 'etawa')) $sBreedKey = 'etawa';

                            $sUsiaKey = 'indukan';
                            if (isset($serupa['kategori_usia'])) {
                                $sUsia = strtolower($serupa['kategori_usia']);
                                if (str_contains($sUsia, 'anakan') || str_contains($sUsia, 'bibit')) $sUsiaKey = 'anakan';
                                elseif (str_contains($sUsia, 'doro') || str_contains($sUsia, 'muda')) $sUsiaKey = 'doro';
                                elseif (str_contains($sUsia, 'indukan') || str_contains($sUsia, 'dewasa')) $sUsiaKey = 'indukan';
                            }
                            $serupaImage = asset("images/{$sBreedKey}/{$sUsiaKey}.webp?v=1.1");
                        @endphp
                        <a href="{{ route('produk.detail', $serupa['slug']) }}"
                            class="group bg-m3-surface rounded-[2.5rem] overflow-hidden hover:shadow-2xl hover:shadow-m3-primary/5 transition-all duration-500">
                            <div class="aspect-[4/3] overflow-hidden relative">
                                <img src="{{ $serupaImage }}" alt="{{ $serupa['nama_produk'] }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    loading="lazy" />
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-4 py-1 rounded-full text-xs font-bold text-m3-primary">
                                    Rp {{ number_format($serupa['harga'] / 1000000, 1) }}jt
                                </div>
                                <div
                                    class="absolute top-4 left-4 bg-m3-primary-container text-m3-on-primary-container px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                    {{ $serupa['kelas_berat'] }}
                                </div>
                            </div>
                            <div class="p-8 space-y-4">
                                <h3
                                    class="text-xl font-bold font-headline group-hover:text-m3-primary transition-colors capitalize">
                                    {{ $serupa['nama_produk'] }}
                                </h3>
                                <div class="flex gap-4 text-xs font-label text-m3-outline uppercase tracking-wider">
                                    <span>{{ $serupa['weight_range'] }}</span>
                                    <span>•</span>
                                    <span>{{ $serupa['jenis_kelamin'] }}</span>
                                    <span>•</span>
                                    <span>Stok: {{ $serupa['stok'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── FOOTER ── --}}
    <x-landing.footer wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />

@endsection
