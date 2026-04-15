{{--
|--------------------------------------------------------------------------
| Component: x-auth-illustration
|--------------------------------------------------------------------------
| Reusable left-panel illustration untuk semua halaman auth.
|
| Props:
|   - $imageSrc    : URL gambar latar belakang
|   - $imageAlt    : Alt text gambar (required untuk a11y)
|   - $quote       : Kutipan/tagline yang ditampilkan di atas gambar
|   - $quoteSource : Atribusi kutipan (opsional)
|   - $overlay     : Class overlay gradient (opsional, default: from-primary/90)
|
| Contoh penggunaan:
|   <x-auth-illustration
|       image-src="{{ asset('images/auth/farm-hero.jpg') }}"
|       image-alt="Lahan peternakan domba di pagi hari"
|       quote='"Pilihan Cerdas Penikmat Domba"'
|       quote-source="PT Sakadomas"
|   />
--}}

@props([
    'imageSrc'    => '',
    'imageAlt'    => 'Ilustrasi peternakan domba Smart-Saka',
    'quote'       => '',
    'quoteSource' => null,
    'overlay'     => 'from-primary/90 via-primary/40 to-transparent',
])

<section
    class="hidden md:flex md:w-1/2 lg:w-2/5 relative overflow-hidden bg-neutral-900 h-screen sticky top-0"
    aria-hidden="true"
>
    {{-- Background Image --}}
    <div class="absolute inset-0 z-0">
        <img
            src="{{ $imageSrc }}"
            alt="{{ $imageAlt }}"
            class="w-full h-full object-cover opacity-75"
            loading="lazy"
        >
        <div class="absolute inset-0 bg-gradient-to-t {{ $overlay }}"></div>
    </div>

    {{-- Content Overlay --}}
    <div class="relative z-10 p-16 flex flex-col justify-end h-full w-full page-enter">

        {{-- Slot untuk konten tambahan di atas panel (opsional) --}}
        {{ $header ?? '' }}

        <div class="max-w-lg">
            @if ($quote)
                <blockquote class="font-noto-serif text-4xl lg:text-5xl font-bold italic leading-tight text-white tracking-tight drop-shadow-md mb-6">
                    {{ $quote }}
                </blockquote>
            @endif

            @if ($quoteSource)
                <div class="flex flex-col space-y-2">
                    <cite class="not-italic text-primary-fixed font-manrope text-[11px] tracking-[0.2em] uppercase font-bold">
                        ~ {{ $quoteSource }}
                    </cite>
                    <div class="w-12 h-[2px] bg-primary-fixed" aria-hidden="true"></div>
                </div>
            @endif
        </div>

        {{-- Slot untuk elemen dekoratif bawah (opsional) --}}
        {{ $footer ?? '' }}
    </div>
</section>
