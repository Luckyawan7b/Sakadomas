{{--
|--------------------------------------------------------------------------
| Component: x-auth-header
|--------------------------------------------------------------------------
| Header bagian form kanan: ikon, judul halaman, dan deskripsi.
|
| Props:
|   - $icon        : Material Symbol icon (opsional)
|   - $iconFilled  : Pakai FILL=1 pada icon (default: false)
|   - $title       : Judul halaman (h1) - wajib
|   - $description : Teks deskripsi di bawah judul (opsional)
--}}

@props([
    'icon'        => null,
    'iconFilled'  => false,
    'title'       => '',
    'description' => null,
])

<header class="mb-10">
    @if ($icon)
        <div class="inline-flex items-center justify-center w-14 h-14 bg-primary-container/20 text-primary rounded-2xl mb-6" aria-hidden="true">
            <span
                class="material-symbols-outlined text-3xl"
                @if ($iconFilled) style="font-variation-settings: 'FILL' 1;" @endif
            >{{ $icon }}</span>
        </div>
    @endif

    <h1 class="font-noto-serif text-3xl md:text-4xl font-bold text-on-surface tracking-tight mb-3">
        {{ $title }}
    </h1>

    @if ($description)
        <p class="text-on-surface-variant text-sm leading-relaxed font-manrope">
            {{ $description }}
        </p>
    @endif
</header>
