{{--
|--------------------------------------------------------------------------
| View: resources/views/landing/detail-produk.blade.php
|--------------------------------------------------------------------------
| Halaman Detail Produk Ternak Landing Page Smart-Saka.
| Route: GET /produk/{id}  → LandingController@detailProduk
|
| Data dari Controller:
|   $ternak        : Model Ternak (with jenis_ternak, kamar.kandang)
|   $produkSerupa  : Collection Ternak serupa (max 3)
|   $waNumber      : Nomor WhatsApp
--}}

@extends('layouts.landing')

@section('title', 'Smart-Saka | ' . ($ternak->jenis_ternak->jenis_ternak ?? 'Domba') . ' ' . $ternak->jenis_kelamin)

@section('content')

    {{-- ── NAVBAR ── --}}
    <x-landing.navbar />

    <main class="pt-32 pb-20 max-w-7xl mx-auto px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-12 flex items-center space-x-2 text-sm text-m3-outline font-label uppercase tracking-widest">
            <a class="hover:text-m3-primary transition-colors" href="{{ route('katalog') }}">Katalog</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-m3-primary font-bold">{{ $ternak->jenis_ternak->jenis_ternak ?? '' }} {{ $ternak->jenis_kelamin }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            {{-- Left Column: Image --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="relative group aspect-[4/5] bg-m3-surface-container-low rounded-[2rem] overflow-hidden flex items-center justify-center">
                    <span class="material-symbols-outlined text-[10rem] text-m3-outline-variant/20" style="font-variation-settings: 'FILL' 0, 'wght' 200;">pets</span>
                    <div class="absolute top-6 left-6 flex flex-col gap-3">
                        <span class="bg-m3-primary-container text-m3-on-primary-container px-4 py-1.5 rounded-full text-xs font-bold font-label uppercase tracking-wider">Tersedia</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Details --}}
            <div class="lg:col-span-5 space-y-10">
                <header class="space-y-4">
                    <h1 class="capitalize text-5xl font-extrabold font-headline text-m3-on-surface tracking-tighter leading-tight">
                        {{ $ternak->jenis_ternak->jenis_ternak ?? 'Domba' }} {{ $ternak->jenis_kelamin }}
                    </h1>
                    <div class="flex items-baseline space-x-3">
                        <span class="text-3xl font-bold text-m3-primary font-headline">Rp {{ number_format($ternak->harga, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-sm text-m3-on-surface-variant">ID Ternak: #SKD-{{ str_pad($ternak->id_ternak, 3, '0', STR_PAD_LEFT) }}</p>
                </header>

                {{-- Specs Grid --}}
                <section class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Berat</p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $ternak->berat }} Kg</p>
                    </div>
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Umur</p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $ternak->usia }} Bulan</p>
                    </div>
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Jenis Kelamin</p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $ternak->jenis_kelamin }}</p>
                    </div>
                    @if($ternak->kamar)
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Kandang</p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ $ternak->kamar->kandang->nama_kandang ?? '-' }}</p>
                    </div>
                    @endif
                    <div class="bg-m3-surface-container-low p-5 rounded-2xl space-y-1">
                        <p class="text-[10px] font-bold text-m3-outline uppercase tracking-widest font-label">Kesehatan</p>
                        <p class="text-lg font-bold text-m3-primary font-headline">{{ ucfirst($ternak->status_ternak) }}</p>
                    </div>
                </section>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    @auth
                        <a href="{{ route('transaksi.create') }}?jenis={{ $ternak->id_jenis_ternak }}&kelamin={{ $ternak->jenis_kelamin }}&harga={{ $ternak->harga }}"
                           class="flex-1 bg-m3-primary text-m3-on-primary py-5 rounded-full font-bold font-headline text-lg hover:bg-m3-primary-container transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">shopping_cart</span>
                            Ambil Sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="flex-1 bg-m3-primary text-m3-on-primary py-5 rounded-full font-bold font-headline text-lg hover:bg-m3-primary-container transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">login</span>
                            Login & Beli
                        </a>
                    @endauth
                    <a href="https://wa.me/{{ $waNumber ?? config('smartsaka.wa_number') }}?text={{ urlencode('Halo, saya tertarik dengan domba #SKD-' . str_pad($ternak->id_ternak, 3, '0', STR_PAD_LEFT)) }}"
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
                            <p class="text-[10px] text-m3-outline">Se-Jawa Timur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deskripsi --}}
        <section class="mt-32">
            <div class="bg-m3-surface-container-low p-8 rounded-3xl">
                <h3 class="text-2xl font-bold font-headline text-m3-on-surface mb-4">Deskripsi</h3>
                <p class="text-m3-on-surface-variant leading-relaxed text-lg">
                    {{ $ternak->jenis_ternak->jenis_ternak ?? 'Domba' }} {{ $ternak->jenis_kelamin }} ini merupakan hasil seleksi unggul
                    yang mengedepankan kualitas daging dan ketahanan tubuh. Dipelihara dengan standar bio-security yang ketat di Smart-Saka Farm,
                    memastikan kesehatan optimal sejak lahir.
                </p>
            </div>
        </section>
    </main>

    {{-- Related Products Section --}}
    @if($produkSerupa->isNotEmpty())
    <section class="bg-m3-surface-container-low py-24">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-m3-primary font-bold font-label uppercase tracking-widest text-xs mb-2 block">Pilihan Lainnya</span>
                    <h2 class="text-4xl font-extrabold font-headline tracking-tighter">Domba Serupa</h2>
                </div>
                <a class="text-m3-primary font-bold hover:underline flex items-center gap-2" href="{{ route('katalog') }}">
                    Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($produkSerupa as $serupa)
                <a href="{{ route('produk.detail', $serupa->id_ternak) }}"
                   class="group bg-m3-surface rounded-[2.5rem] overflow-hidden hover:shadow-2xl hover:shadow-m3-primary/5 transition-all duration-500">
                    <div class="aspect-[4/3] overflow-hidden relative bg-m3-surface-container-low flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-m3-outline-variant/20" style="font-variation-settings: 'FILL' 0, 'wght' 200;">pets</span>
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-4 py-1 rounded-full text-xs font-bold text-m3-primary">
                            Rp {{ number_format($serupa->harga / 1000000, 1) }}jt
                        </div>
                    </div>
                    <div class="p-8 space-y-4">
                        <h3 class="text-xl font-bold font-headline group-hover:text-m3-primary transition-colors">
                            {{ $serupa->jenis_ternak->jenis_ternak ?? '' }} {{ $serupa->jenis_kelamin }}
                        </h3>
                        <div class="flex gap-4 text-xs font-label text-m3-outline uppercase tracking-wider">
                            <span>{{ $serupa->berat }} Kg</span>
                            <span>•</span>
                            <span>{{ $serupa->usia }} Bln</span>
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
