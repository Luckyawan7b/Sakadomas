{{--
|--------------------------------------------------------------------------
| Component: x-button
|--------------------------------------------------------------------------
| Tombol aksi utama yang konsisten di seluruh halaman auth.
|
| Props:
|   - $type    : Tipe tombol: 'submit', 'button', 'reset' (default: submit)
|   - $variant : Variant tampilan: 'primary', 'secondary', 'ghost' (default: primary)
|   - $icon    : Material Symbol icon (opsional, tampil di kanan teks)
|   - $full    : Full width (default: true)
|
| Contoh penggunaan:
|   <x-button>Masuk</x-button>
|
|   <x-button variant="secondary" icon="arrow_forward">
|       Kirim Tautan Pemulihan
|   </x-button>
|
|   <x-button variant="ghost" :full="false">
|       Batal
|   </x-button>
--}}

@props([
    'type'    => 'submit',
    'variant' => 'primary',
    'icon'    => null,
    'full'    => true,
])

@php
    $baseClass = 'inline-flex items-center justify-center gap-2 font-bold py-4 px-6 rounded-xl transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';

    $variantClasses = match($variant) {
        'primary'   => 'bg-primary text-on-primary shadow-lg shadow-primary/20 hover:bg-primary-container hover:shadow-primary/30 focus-visible:ring-primary',
        'secondary' => 'bg-surface-container-low border border-outline-variant/30 text-on-surface hover:bg-surface-container focus-visible:ring-outline',
        'ghost'     => 'text-on-surface-variant hover:text-primary hover:bg-surface-container focus-visible:ring-outline',
        default     => 'bg-primary text-on-primary',
    };

    $widthClass = $full ? 'w-full' : '';
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClass $variantClasses $widthClass"]) }}
>
    {{ $slot }}

    @if ($icon)
        <span class="material-symbols-outlined text-sm" aria-hidden="true">{{ $icon }}</span>
    @endif
</button>
