{{--
|--------------------------------------------------------------------------
| Component: x-input
|--------------------------------------------------------------------------
| Reusable form input dengan label, ikon, dan error handling terintegrasi.
|
| Props:
|   - $name        : Nama field (wajib) - digunakan untuk id, name, @error
|   - $label       : Teks label (wajib)
|   - $type        : Tipe input (default: text)
|   - $placeholder : Placeholder teks
|   - $icon        : Nama Material Symbol untuk ikon kiri (opsional)
|   - $required    : Apakah field wajib diisi (default: false)
|   - $value       : Nilai awal (default: old() untuk repopulate)
|   - $autocomplete: Autocomplete attribute
|
| Contoh penggunaan:
|   <x-input
|       name="email"
|       label="Alamat Email"
|       type="email"
|       icon="mail"
|       placeholder="nama@email.com"
|       :required="true"
|       autocomplete="email"
|   />
--}}

@props([
    'name',
    'label',
    'type'         => 'text',
    'placeholder'  => '',
    'icon'         => null,
    'required'     => false,
    'value'        => null,
    'autocomplete' => 'off',
])

@php
    $inputId    = 'input-' . $name;
    $hasError   = $errors->has($name);
    $inputValue = $value ?? old($name);
@endphp

<div class="space-y-1.5">
    <label
        for="{{ $inputId }}"
        class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1"
    >
        {{ $label }}
        @if ($required)
            <span class="text-error ml-0.5" aria-hidden="true">*</span>
        @endif
    </label>

    <div class="relative group input-focus-effect bg-surface-container-highest rounded-xl transition-all duration-200 {{ $icon ? '' : '' }}">
        @if ($icon)
            <span
                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors z-10 pointer-events-none"
                aria-hidden="true"
            >{{ $icon }}</span>
        @endif

        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ $inputValue }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if ($required) required aria-required="true" @endif
            @if ($hasError) aria-describedby="{{ $inputId }}-error" aria-invalid="true" @endif
            {{ $attributes->merge([
                'class' => 'w-full bg-transparent border-none focus:ring-2 focus:ring-primary/30 transition-all text-on-surface placeholder:text-outline/60 py-4 rounded-xl '
                    . ($icon ? 'pl-12 pr-4' : 'px-5')
                    . ($hasError ? ' ring-2 ring-error/50' : '')
            ]) }}
        >
    </div>

    @error($name)
        <p
            id="{{ $inputId }}-error"
            class="flex items-center gap-1 text-xs text-error mt-1 ml-1"
            role="alert"
        >
            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
            {{ $message }}
        </p>
    @enderror
</div>
