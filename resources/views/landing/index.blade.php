{{--
|--------------------------------------------------------------------------
| View: resources/views/landing/index.blade.php
|--------------------------------------------------------------------------
| Halaman utama Landing Page Smart-Saka.
| Route: GET /  → LandingController@index
|
| Data dari Controller:
|   $products      : Collection produk dari DB (atau array statis)
|   $testimonials  : Collection testimoni aktif
|   $faqs          : Collection FAQ aktif
|   $waNumber      : Nomor WhatsApp dari config('smartsaka.wa_number')
--}}

@extends('layouts.landing')

@section('title', 'Smart-Saka | Peternakan Domba & Kambing Premium Jember')

@section('meta_description',
    'Pusat pembibitan domba Crosstexel, Merino, dan kambing Etawa premium di Jember, Jawa
    Timur. 500+ ekor terjual per tahun. Bersertifikat sehat, siap antar ke seluruh Jawa Timur.')

@section('og_title', 'Smart-Saka | Peternakan Domba Premium Jember')
@section('og_description',
    'Domba Crosstexel, Merino & Kambing Etawa Premium. Siap kurban, aqiqah & bibit unggul.
    Langsung dari kandang kami, bersertifikat sehat.')
@section('og_image', asset(config('smartsaka.og_image', 'images/og-smart-saka.jpg')))

@section('content')

    {{-- ── 1. NAVBAR ── --}}
    <x-landing.navbar />

    {{-- ── 2. HERO SECTION ── --}}
    <x-landing.hero wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}"
        hero-image="{{ asset('images/landing/hero2.jpg') }}" />


    {{-- ══════════════════════════════════════════════ TENTANG KAMI ══ --}}
    {{-- <section id="tentang" class="py-24 lg:py-32 bg-cream-50 relative overflow-hidden" aria-labelledby="tentang-heading">
        <div class="absolute right-0 top-0 w-1/3 h-full dot-grid opacity-20 pointer-events-none" aria-hidden="true"></div>

        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex justify-center mb-4">
                <div class="section-badge reveal" aria-label="Bagian tentang kami">Tentang Kami</div>
            </div>

            <div class="grid lg:grid-cols-5 gap-10 lg:gap-16 items-start">

                <div class="lg:col-span-2 reveal-left">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl overflow-hidden aspect-square shadow-md col-span-2">
                            <img src="{{ asset('images/landing/about-1.jpg') }}"
                                alt="Padang rumput peternakan Smart-Saka Jember"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                loading="lazy" width="700" height="700">
                        </div>
                        <div class="rounded-2xl overflow-hidden aspect-square shadow-md">
                            <img src="{{ asset('images/landing/about-2.jpg') }}" alt="Domba Merino di Smart-Saka"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                loading="lazy" width="400" height="400">
                        </div>
                        <div class="rounded-2xl overflow-hidden aspect-square shadow-md">
                            <img src="{{ asset('images/landing/about-3.jpg') }}" alt="Detail bulu domba berkualitas premium"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                loading="lazy" width="400" height="400">
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3 reveal-right">
                    <h2 id="tentang-heading" class="font-serif text-4xl lg:text-5xl text-olive-900 leading-tight mb-6">
                        Dedikasi Kami Untuk<br>
                        <span class="italic text-bark-600">Ternak Berkualitas</span>
                    </h2>

                    <p class="text-olive-700/80 text-base lg:text-lg leading-relaxed mb-5">
                        Di <strong class="text-olive-800">Smart-Saka</strong>, kami berdedikasi untuk membesarkan domba dan
                        kambing dengan standar tertinggi. Pakan bernutrisi, kandang bersih, dan pengawasan dokter hewan
                        rutin menjadi prioritas utama kami setiap harinya.
                    </p>
                    <p class="text-olive-700/70 text-base leading-relaxed mb-8">
                        Berlokasi di Jember, Jawa Timur, peternakan kami menggunakan metode pemeliharaan modern yang ramah
                        lingkungan — memastikan setiap hewan tumbuh sehat, bahagia, dan siap memenuhi kebutuhan Anda, mulai
                        dari bibit ternak, kurban, hingga aqiqah.
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-8" role="list" aria-label="Keunggulan perawatan hewan">
                        @foreach ([['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Vaksinasi Rutin', 'desc' => 'Semua hewan divaksin sesuai jadwal'], ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'Perawatan Penuh Kasih', 'desc' => 'Dibesarkan dengan cinta dan perhatian'], ['icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3', 'title' => 'Pakan Fermentasi', 'desc' => 'Formula nutrisi tinggi khusus'], ['icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z', 'title' => 'Konsultasi Gratis', 'desc' => 'Tim siap membantu Anda']] as $item)
                            <div class="flex items-start gap-3" role="listitem">
                                <div class="w-8 h-8 rounded-lg bg-olive-100 flex items-center justify-center shrink-0 mt-0.5"
                                    aria-hidden="true">
                                    <svg class="w-4 h-4 text-olive-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $item['icon'] }}" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-olive-800">{{ $item['title'] }}</p>
                                    <p class="text-xs text-olive-600 mt-0.5">{{ $item['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="#katalog"
                        class="inline-flex items-center gap-2 text-olive-700 font-semibold border-b-2 border-olive-300 hover:border-olive-600 pb-0.5 transition-colors group"
                        aria-label="Pelajari lebih lanjut tentang katalog kami">
                        Pelajari Lebih Lanjut
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section> --}}


    {{-- ══════════════════════════════════════════════ KATALOG PRODUK ══ --}}
    <section id="katalog" class="py-24 lg:py-32 bg-olive-950/[0.03]" aria-labelledby="katalog-heading">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="text-center mb-14 reveal">
                <div class="section-badge mb-4 mx-auto w-fit">Katalog</div>
                <h2 id="katalog-heading" class="font-serif text-4xl lg:text-5xl text-olive-900 mb-4">
                    Koleksi Ternak Terbaik Kami
                </h2>
                <p class="text-olive-700/70 text-base max-w-xl mx-auto">
                    Dipilih dengan teliti, dibesarkan dengan standar terbaik. Temukan ternak ideal untuk kebutuhan Anda.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-7" role="list" aria-label="Daftar produk ternak">
                {{--
                    LOOPING DATA DARI CONTROLLER:
                    Di LandingController, kirimkan:
                        $products = Product::active()->ordered()->get();

                    Contoh loop dinamis:
                    @foreach ($products as $index => $product)
                        <x-landing.product-card
                            :title="$product->name"
                            :description="$product->description"
                            :price="'Rp ' . number_format($product->price, 0, ',', '.')"
                            :price-raw="$product->price"
                            :image="asset('images/products/' . $product->image)"
                            :badge="$product->badge"
                            :badge-color="$product->badge_color ?? 'olive'"
                            :category="$product->category"
                            :delay="($index * 0.07) . 's'"
                            :featured="$product->is_featured"
                            wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}"
                        />
                    @endforeach
                --}}

                <div role="listitem">
                    <x-landing.product-card title="Domba Crosstexel"
                        description="Postur besar, daging tebal berkualitas premium. Cocok untuk kurban, aqiqah & investasi ternak."
                        price="Rp 3.500.000" price-raw="3500000"
                        image="{{ asset('images/landing/product-crosstexel.jpg') }}" badge="Best Seller" badge-color="olive"
                        category="Jantan" delay="0.05s" wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />
                </div>

                <div role="listitem">
                    <x-landing.product-card title="Domba Merino Premium"
                        description="Wol halus berkualitas internasional, badan proporsional, dan genetik unggul untuk pengembangbiakan."
                        price="Rp 4.200.000" price-raw="4200000" image="{{ asset('images/landing/product-merino.jpg') }}"
                        badge="Premium" badge-color="bark" category="Betina" delay="0.12s"
                        wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />
                </div>

                <div role="listitem">
                    <x-landing.product-card title="Kambing Etawa Super"
                        description="Produksi susu tinggi, postur tegap, dan mudah beradaptasi. Pilihan terbaik untuk usaha susu kambing."
                        price="Rp 2.800.000" price-raw="2800000" image="{{ asset('images/landing/product-etawa1.jpg') }}"
                        badge="Unggulan" badge-color="cream" category="Perah" delay="0.19s"
                        wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />
                </div>

                <div role="listitem">
                    <x-landing.product-card title="Domba Garut"
                        description="Adaptasi iklim tropis sangat baik, daging lezat, dan harga terjangkau untuk kebutuhan kurban."
                        price="Rp 2.100.000" price-raw="2100000" image="{{ asset('images/landing/product-garut.jpg') }}"
                        category="Lokal" delay="0.26s" wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />
                </div>

                <div role="listitem">
                    <x-landing.product-card title="Bibit Domba Unggul"
                        description="Bibit berkualitas tinggi dari indukan pilihan, ideal untuk memulai atau mengembangkan usaha ternak."
                        price="Rp 1.200.000" price-raw="1200000" image="{{ asset('images/landing/product-bibit.jpg') }}"
                        badge="Bibit" badge-color="green" category="Anak" delay="0.33s"
                        wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />
                </div>

                <div role="listitem">
                    <x-landing.product-card title="Paket Aqiqah & Kurban"
                        description="Layanan lengkap mulai pemilihan hewan, pemotongan, hingga pengantaran ke lokasi Anda. Harga transparan, proses amanah."
                        price="Hubungi Kami" price-raw="0" image="{{ asset('images/landing/product-aqiqah.jpg') }}"
                        delay="0.40s" :featured="true" wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />
                </div>

            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════ KEUNGGULAN ══ --}}
    {{-- <section id="keunggulan" class="py-24 lg:py-32 bg-cream-50" aria-labelledby="keunggulan-heading">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex justify-center mb-4">
                <div class="section-badge reveal">Keunggulan</div>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <div class="reveal-left relative">
                    <div class="rounded-[36px] overflow-hidden shadow-2xl aspect-[4/3]">
                        <img src="{{ asset('images/landing/keunggulan-main.jpg') }}"
                            alt="Pemandangan peternakan Smart-Saka yang asri" class="w-full h-full object-cover"
                            loading="lazy" width="800" height="600">
                        <div class="absolute inset-0 bg-gradient-to-t from-olive-900/40 to-transparent pointer-events-none rounded-[36px]"
                            aria-hidden="true"></div>
                    </div>
                    <div
                        class="absolute bottom-5 left-5 right-5 bg-white/95 backdrop-blur-sm rounded-2xl px-5 py-4 shadow-lg flex items-center gap-4">
                        <div class="flex -space-x-2" aria-hidden="true">
                            <div
                                class="w-9 h-9 rounded-full bg-olive-200 border-2 border-white flex items-center justify-center text-sm">
                                👨</div>
                            <div
                                class="w-9 h-9 rounded-full bg-bark-200 border-2 border-white flex items-center justify-center text-sm">
                                👩</div>
                            <div
                                class="w-9 h-9 rounded-full bg-olive-300 border-2 border-white flex items-center justify-center text-sm">
                                👨</div>
                            <div
                                class="w-9 h-9 rounded-full bg-olive-600 border-2 border-white flex items-center justify-center text-cream-50 text-xs font-bold">
                                +</div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-olive-800">Bergabunglah dengan komunitas</p>
                            <p class="text-xs text-olive-600">200+ peternak aktif bersama kami</p>
                        </div>
                    </div>
                    <div class="absolute -z-10 -top-6 -left-6 w-40 h-40 rounded-full border-[14px] border-olive-100 pointer-events-none"
                        aria-hidden="true"></div>
                </div>

                <div class="reveal-right">
                    <h2 id="keunggulan-heading"
                        class="font-serif text-4xl lg:text-5xl text-olive-900 leading-tight mb-12">
                        Mengapa Memilih<br>
                        <span class="italic text-bark-600">Peternakan Kami?</span>
                    </h2>

                    <div class="grid grid-cols-2 gap-5" role="list">
                        @foreach ([['emoji' => '💉', 'title' => '100% Sehat & Vaksinasi', 'desc' => 'Setiap hewan divaksin dan dicek kesehatan secara berkala oleh dokter hewan.'], ['emoji' => '🌾', 'title' => 'Pakan Fermentasi Berkualitas', 'desc' => 'Formula pakan khusus bernutrisi tinggi yang mendukung pertumbuhan optimal.'], ['emoji' => '🚚', 'title' => 'Siap Antar ke Lokasi', 'desc' => 'Layanan pengiriman aman dan terpercaya ke seluruh wilayah Jawa Timur.'], ['emoji' => '📚', 'title' => 'Pendampingan Beternak', 'desc' => 'Konsultasi dan bimbingan teknis beternak gratis untuk semua pelanggan kami.']] as $feature)
                            <div class="feature-card bg-olive-50 rounded-2xl p-5 border border-olive-100/60"
                                role="listitem">
                                <div class="feature-icon w-11 h-11 bg-olive-100 rounded-xl flex items-center justify-center text-xl mb-4"
                                    aria-hidden="true">{{ $feature['emoji'] }}</div>
                                <h3 class="font-semibold text-olive-800 mb-1.5 text-sm">{{ $feature['title'] }}</h3>
                                <p class="text-xs text-olive-600/80 leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section> --}}


    {{-- ══════════════════════════════════════════════ FAQ (BARU) ══ --}}
    <x-landing.faq :faqs="$faqs ?? null" wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}" />


    {{-- ══════════════════════════════════════════════ TESTIMONI ══ --}}
    {{-- <section id="testimoni" class="py-24 lg:py-32 bg-olive-950/[0.03] overflow-hidden"
        aria-labelledby="testimoni-heading">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex justify-center mb-4">
                <div class="section-badge reveal">Testimoni</div>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

    <div class="reveal-left">
        <h2 id="testimoni-heading" class="font-serif text-4xl lg:text-5xl text-olive-900 leading-tight mb-10">
            Apa Kata<br>
            <span class="italic text-bark-600">Pelanggan Kami?</span>
        </h2>
        <x-landing.testimonial-slider :testimonials="$testimonials ?? null" />
    </div>

    <div class="reveal-right hidden lg:grid grid-cols-2 grid-rows-2 gap-4 h-[480px]" aria-hidden="true">
        <div class="rounded-2xl overflow-hidden row-span-2">
            <img src="{{ asset('images/landing/testi-collage-1.jpg') }}" alt=""
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
        </div>
        <div class="rounded-2xl overflow-hidden">
            <img src="{{ asset('images/landing/testi-collage-2.jpg') }}" alt=""
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
        </div>
        <div class="rounded-2xl overflow-hidden">
            <img src="{{ asset('images/landing/testi-collage-3.jpg') }}" alt=""
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
        </div>
    </div>
    </div>
    </div>
    </section> --}}


    {{-- ══════════════════════════════════════════════ NEWSLETTER ══ --}}
    {{-- <section id="newsletter" class="py-20 lg:py-28 bg-olive-900 relative overflow-hidden"
        aria-labelledby="newsletter-heading"> --}}
    {{-- <div class="absolute inset-0 dot-grid opacity-10 pointer-events-none" aria-hidden="true">

        </div>
        <div class="absolute top-0 left-0 w-96 h-96 bg-olive-800 rounded-full -translate-x-1/2 -translate-y-1/2 pointer-events-none"
            aria-hidden="true">
        </div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-olive-800 rounded-full translate-x-1/3 translate-y-1/3 pointer-events-none"
            aria-hidden="true">
        </div> --}}

    {{-- <div class="relative z-10 max-w-7xl mx-auto px-5 lg:px-8"> --}}
    {{-- <div class="grid lg:grid-cols-2 gap-12 items-center"> --}}

    {{-- Left CTA --}}
    {{-- <div class="reveal-left">
                    <div class="section-badge bg-olive-800 text-olive-200 mb-6">Newsletter</div>
                    <h2 id="newsletter-heading" class="font-serif text-4xl lg:text-5xl text-cream-50 leading-tight mb-4">
                        Dapatkan Info Harga<br>
                        <span class="italic text-cream-300">&amp; Penawaran Spesial</span>
                    </h2>
                    <p class="text-cream-200/70 text-base leading-relaxed mb-8 max-w-md">
                        Daftarkan email Anda dan jadilah yang pertama mendapatkan notifikasi stok baru, promo musiman
                        kurban, dan tips beternak dari kami.
                    </p>

                    <form id="nl-form" class="flex flex-col sm:flex-row gap-3 max-w-md" novalidate>
                        @csrf
                        <label for="nl-email" class="sr-only">Alamat email Anda</label>
                        <input type="email" id="nl-email" name="email" placeholder="Masukkan alamat email Anda"
                            class="nl-input flex-1 bg-white/10 border border-white/20 text-cream-50 placeholder-cream-200/50 px-5 py-3.5 rounded-xl text-sm focus:bg-white/15 transition-all"
                            required autocomplete="email">
                        <button type="submit"
                            class="bg-cream-200 hover:bg-white text-olive-900 font-bold text-sm px-6 py-3.5 rounded-xl transition-all whitespace-nowrap shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-cream-200 focus-visible:ring-offset-2 focus-visible:ring-offset-olive-900">
                            Subscribe
                        </button>
                    </form>
                    <p class="text-cream-200/50 text-xs mt-3">Tidak ada spam. Bisa unsubscribe kapan saja.</p>
                </div> --}}

    {{-- Right Photo Collage --}}
    {{-- <div class="reveal-right grid grid-cols-2 gap-4" aria-hidden="true">
                    <div class="rounded-2xl overflow-hidden aspect-square">
                        <img src="{{ asset('images/landing/nl-1.jpg') }}" alt=""
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            loading="lazy">
                    </div>
                    <div class="rounded-2xl overflow-hidden aspect-square mt-8">
                        <img src="{{ asset('images/landing/nl-2.jpg') }}" alt=""
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            loading="lazy">
                    </div>
                    <div class="rounded-2xl overflow-hidden aspect-square -mt-6">
                        <img src="{{ asset('images/landing/nl-3.jpg') }}" alt=""
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            loading="lazy">
                    </div>
                    <div class="rounded-2xl overflow-hidden aspect-square">
                        <img src="{{ asset('images/landing/nl-4.jpg') }}" alt=""
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            loading="lazy">
                    </div>
                </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </section> --}}



    {{-- ══════════════════════════════════════════════ FOOTER / KONTAK ══ --}}
    {{-- Nilai bisnis dibaca dari config/smartsaka.php via LandingController --}}
    <x-landing.footer
        wa-number="{{ $waNumber ?? config('smartsaka.wa_number') }}"
        email="{{ $email ?? config('smartsaka.email') }}"
        :address="$address ?? config('smartsaka.address')"
        map-src="{{ $mapsSrc ?? config('smartsaka.maps_embed_src') }}"
    />

@endsection
