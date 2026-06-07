{{--
|--------------------------------------------------------------------------
| Component: x-landing.hero
|--------------------------------------------------------------------------
| Hero section — split layout, headline + stats kiri, foto kanan.
|
| Props:
|   - $waNumber   : Nomor WhatsApp (default: placeholder)
|   - $heroImage  : Path gambar hero (default: asset placeholder)
|
| Ganti konten statis (tagline, stats) langsung di file ini.
| Floating badges bisa di-populate dari config atau hardcode.
--}}

@props([
    'waNumber'  => null,
    'heroImage' => null,
])

<section
    id="beranda"
    class="relative min-h-screen flex items-center overflow-hidden pt-16 bg-cream-50"
    aria-label="Beranda"
>
    {{-- ── Decorative background shapes ── --}}
    <div class="absolute top-0 right-0 w-[55vw] h-[90vh] bg-olive-100/60 rounded-bl-[80px] pointer-events-none" aria-hidden="true"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 dot-grid opacity-30 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-24 left-1/2 w-3 h-3 rounded-full bg-olive-300 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-40 left-1/3 w-2 h-2 rounded-full bg-bark-300 pointer-events-none" aria-hidden="true"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-5 lg:px-8 py-16 md:py-20 grid md:grid-cols-2 gap-12 lg:gap-20 items-center w-full">

        {{-- ── LEFT: Content ── --}}
        <div class="reveal-left">
            <div class="section-badge mb-6" aria-label="Label kategori">
                <span aria-hidden="true">🌿</span> Peternakan Unggulan Indonesia
            </div>

            <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl leading-[1.05] mb-6">
                <span class="hero-gradient-text">Kualitas</span><br>
                <span class="text-olive-900">Terbaik.</span><br>
                <span class="font-serif italic text-bark-600">Genetik</span> <span class="text-olive-900">Unggul.</span>
            </h1>

            <p class="text-olive-700/80 text-base lg:text-lg leading-relaxed mb-8 max-w-md">
                Pusat pembibitan dan penyediaan <strong class="text-olive-800 font-semibold">Domba Crosstexel, Merino</strong>, dan <strong class="text-olive-800 font-semibold">Kambing Etawa</strong> premium untuk kebutuhan ternak, kurban, dan aqiqah. Langsung dari kandang kami ke tangan Anda.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-wrap gap-4">
                <a
                    href="#katalog"
                    class="btn-pulse inline-flex items-center gap-2.5 bg-olive-700 hover:bg-olive-600 text-cream-50 font-semibold px-7 py-3.5 rounded-xl transition-all shadow-lg hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-600 focus-visible:ring-offset-2"
                >
                    Lihat Katalog
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a
                    href="https://wa.me/{{ $waNumber ?? config('smartsaka.wa_number') }}?text={{ urlencode('Halo Smart-Saka! Saya ingin bertanya tentang produk Anda 🐑') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 border-2 border-olive-200 text-olive-800 hover:border-olive-400 font-semibold px-7 py-3.5 rounded-xl transition-all hover:bg-olive-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-olive-400 focus-visible:ring-offset-2"
                >
                    <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp Kami
                </a>
            </div>

            {{-- Stats row --}}
            <div class="flex flex-wrap gap-6 mt-12 pt-8 border-t border-olive-100" role="list" aria-label="Statistik peternakan">
                <div role="listitem">
                    <p class="font-serif text-3xl text-olive-800" aria-label="500 lebih ekor terjual per tahun">500+</p>
                    <p class="text-xs text-olive-600 font-medium mt-0.5">Ekor Terjual / Tahun</p>
                </div>
                <div class="w-px bg-olive-100" aria-hidden="true"></div>
                <div role="listitem">
                    <p class="font-serif text-3xl text-olive-800" aria-label="8 tahun lebih pengalaman">8+</p>
                    <p class="text-xs text-olive-600 font-medium mt-0.5">Tahun Pengalaman</p>
                </div>
                <div class="w-px bg-olive-100" aria-hidden="true"></div>
                <div role="listitem">
                    <p class="font-serif text-3xl text-olive-800" aria-label="100 persen bersertifikat sehat">100%</p>
                    <p class="text-xs text-olive-600 font-medium mt-0.5">Bersertifikat Sehat</p>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Hero Image ── --}}
        <div class="reveal-right relative">
            <div class="relative rounded-[40px] overflow-hidden shadow-2xl aspect-[4/5]">
                <img
                    src="{{ $heroImage ?? asset('images/landing/hero-main.jpg') }}"
                    alt="Peternak Smart-Saka bersama domba di kandang premium Jember"
                    class="w-full h-full object-cover"
                    loading="eager"
                    width="600"
                    height="750"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-olive-900/20 to-transparent pointer-events-none" aria-hidden="true"></div>
            </div>

            {{-- Floating badge bawah --}}
            <div class="animate-float absolute -bottom-5 -left-6 bg-white rounded-2xl shadow-xl px-5 py-3.5 flex items-center gap-3 border border-olive-100" aria-label="Stok domba tersedia">
                <div class="w-10 h-10 bg-olive-100 rounded-xl flex items-center justify-center text-xl" aria-hidden="true">🐑</div>
                <div>
                    <p class="text-xs text-olive-600 font-medium">Stok Tersedia</p>
                    <p class="font-serif text-olive-900 font-bold text-sm">Happy Customer 😊</p>
                </div>
            </div>

            {{-- Floating badge atas --}}
            <div class="animate-float2 absolute -top-4 -right-4 bg-olive-700 text-cream-50 rounded-2xl shadow-xl px-4 py-3 text-center" aria-label="Semua hewan 100 persen sehat dan tervaksin">
                <p class="font-serif text-2xl leading-none">100%</p>
                <p class="text-xs font-semibold mt-0.5 opacity-80">Sehat & Vaksin</p>
            </div>

            {{-- Decorative ring --}}
            <div class="absolute -z-10 -bottom-8 -right-8 w-48 h-48 rounded-full border-[16px] border-olive-100/60 pointer-events-none" aria-hidden="true"></div>
        </div>

    </div>
</section>
