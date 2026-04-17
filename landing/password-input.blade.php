{{--
|--------------------------------------------------------------------------
| Component: x-password-input
|--------------------------------------------------------------------------
| Input password dengan tombol visibility toggle dan strength meter opsional.
|
| Props:
|   - $name          : Nama field (wajib)
|   - $label         : Teks label (wajib)
|   - $placeholder   : Placeholder teks
|   - $icon          : Material Symbol icon kiri (default: lock)
|   - $required      : Apakah field wajib diisi (default: false)
|   - $showStrength  : Tampilkan strength meter (default: false)
|   - $autocomplete  : Autocomplete value (default: current-password)
|
| Contoh penggunaan:
|   <x-password-input
|       name="password"
|       label="Kata Sandi"
|       :required="true"
|       :show-strength="true"
|       autocomplete="new-password"
|   />
--}}

@props([
    'name'         => 'password',
    'label'        => 'Kata Sandi',
    'placeholder'  => '••••••••',
    'icon'         => 'lock',
    'required'     => false,
    'showStrength' => false,
    'autocomplete' => 'current-password',
])

@php
    $inputId  = 'input-' . $name;
    $hasError = $errors->has($name);
@endphp

<div class="space-y-2">
    <label
        for="{{ $inputId }}"
        class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1"
    >
        {{ $label }}
        @if ($required)
            <span class="text-error ml-0.5" aria-hidden="true">*</span>
        @endif
    </label>

    <div class="relative group">
        <span
            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors z-10 pointer-events-none"
            aria-hidden="true"
        >{{ $icon }}</span>

        <input
            type="password"
            id="{{ $inputId }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if ($required) required aria-required="true" @endif
            @if ($hasError) aria-describedby="{{ $inputId }}-error" aria-invalid="true" @endif
            class="pw-input w-full pl-12 pr-14 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/30 transition-all text-on-surface placeholder:text-outline/60 {{ $hasError ? 'ring-2 ring-error/50' : '' }}"
        >

        <button
            type="button"
            class="pw-toggle absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors z-10"
            aria-label="Tampilkan atau sembunyikan kata sandi"
            aria-pressed="false"
        >
            <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
        </button>
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

    @if ($showStrength)
        {{-- Password Strength Meter - driven by JS (app.js) --}}
        <div class="pt-2 px-1" aria-live="polite" aria-atomic="true">
            <div class="flex gap-1 h-1.5 w-full rounded-full overflow-hidden" role="progressbar" aria-label="Kekuatan kata sandi" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500 rounded-full"></div>
                <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500 rounded-full"></div>
                <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500 rounded-full"></div>
            </div>
            <p class="strength-label text-[10px] uppercase tracking-widest mt-2 font-bold text-outline"></p>
        </div>
    @endif
</div>
