<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Atur Ulang Kata Sandi | SMART-SAKA</title>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400;1,700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#204e2b",
                        "on-primary": "#ffffff",
                        "primary-container": "#386641",
                        "on-primary-container": "#afe2b3",
                        "primary-fixed": "#bcefc0",
                        "primary-fixed-dim": "#a0d3a5",
                        "secondary": "#805533",
                        "error": "#ba1a1a",
                        "background": "#fef9f2",
                        "on-background": "#1d1c17",
                        "surface": "#fef9f2",
                        "on-surface": "#1d1c17",
                        "surface-variant": "#e6e2db",
                        "on-surface-variant": "#414941",
                        "outline": "#727970",
                        "surface-container-highest": "#e6e2db",
                    },
                    fontFamily: {
                        "noto-serif": ["Noto Serif", "serif"],
                        "manrope": ["Manrope", "sans-serif"],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #fef9f2;
            color: #1d1c17;
            -webkit-font-smoothing: antialiased;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }

        .page-enter {
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #fef9f2; }
        ::-webkit-scrollbar-thumb { background: #c1c9be; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #727970; }
    </style>
</head>
<body class="selection:bg-primary/20">

    <div class="min-h-screen flex flex-col lg:flex-row bg-surface">

        {{-- ==================== KIRI: Visual Panel ==================== --}}
        <section class="hidden lg:flex lg:w-1/2 relative flex-col justify-end p-20 bg-primary-container overflow-hidden h-screen sticky top-0">
            <div class="absolute inset-0 z-0 bg-neutral-900">
                {{-- <img src="{{ asset('images/auth/reset-hero.jpg') }}" alt="Security" class="w-full h-full object-cover opacity-60 mix-blend-overlay"> --}}
                <img src="https://media.istockphoto.com/id/924877410/id/foto/pirang-boy-holding-dan-memeluk-domba.jpg?s=612x612&w=0&k=20&c=MHSQQX1z4Ji1HSJ3-sdaTX6gv1YRUPA9Mv1_UzcFXO8=" alt="Security" class="w-full h-full object-cover opacity-60 mix-blend-overlay">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent"></div>
            </div>

            <div class="relative z-10 space-y-6 page-enter">
                <blockquote class="font-noto-serif text-5xl leading-tight text-white tracking-tight max-w-lg drop-shadow-md">
                    "Keamanan adalah ketenangan di padang rumput."
                </blockquote>
                <div class="flex flex-col space-y-2">
                    <cite class="not-italic text-primary-fixed font-manrope text-[11px] tracking-[0.2em] uppercase font-bold">
                        ~ Smart-Saka Security
                    </cite>
                    <div class="w-12 h-[2px] bg-primary-fixed rounded-full"></div>
                </div>
            </div>
        </section>

        {{-- ==================== KANAN: Form Reset ==================== --}}
        <section class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 lg:p-24 bg-surface overflow-y-auto">
            <div class="w-full max-w-md flex flex-col">

                <header class="mb-10 page-enter">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-primary-container/20 text-primary rounded-2xl mb-6">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">lock_reset</span>
                    </div>
                    <h1 class="font-noto-serif text-3xl md:text-4xl font-bold text-on-surface tracking-tight mb-3">Atur Ulang Sandi</h1>
                    <p class="text-on-surface-variant text-sm leading-relaxed">
                        Silakan masukkan kata sandi baru Anda untuk mengamankan kembali akun Smart-Saka.
                    </p>
                </header>

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-100 border border-red-400 page-enter" role="alert">
                        <span class="material-symbols-outlined text-red-700 mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">error</span>
                        <div class="text-xs text-red-700 leading-relaxed">
                            <strong>Gagal:</strong> {{ $errors->first() }}
                        </div>
                    </div>
                @endif

                {{-- Form Reset Password --}}
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6 page-enter" novalidate>
                    @csrf

                    {{-- Hidden Fields --}}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                    {{-- Password Baru --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1">
                            Kata Sandi Baru <span class="text-error">*</span>
                        </label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors z-10 pointer-events-none">lock</span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Min. 8 karakter"
                                required
                                class="pw-input w-full pl-12 pr-14 py-4 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline/60 focus:ring-2 focus:ring-primary/30 transition-all"
                            >
                            <button type="button" class="pw-toggle absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors z-10">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>

                        <div class="pt-2 px-1" id="strength-bars">
                            <div class="flex gap-1 h-1.5 w-full rounded-full overflow-hidden">
                                <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500"></div>
                                <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500"></div>
                                <div class="strength-bar h-full w-1/3 bg-surface-container-highest transition-all duration-500"></div>
                            </div>
                            <p class="strength-label text-[10px] uppercase tracking-widest mt-2 font-bold text-outline"></p>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-widest text-on-surface-variant ml-1">
                            Konfirmasi Kata Sandi <span class="text-error">*</span>
                        </label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors z-10 pointer-events-none">shield</span>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi kata sandi baru"
                                required
                                class="pw-input w-full pl-12 pr-14 py-4 bg-surface-container-highest border-none rounded-xl text-on-surface placeholder:text-outline/60 focus:ring-2 focus:ring-primary/30 transition-all"
                            >
                            <button type="button" class="pw-toggle absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors z-10">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-6">
                        <button type="submit" class="w-full bg-primary text-on-primary py-4 px-6 rounded-xl font-bold hover:bg-primary-container hover:-translate-y-0.5 active:translate-y-0 transition-all shadow-lg shadow-primary/20">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <footer class="mt-12 flex items-center justify-between border-t border-surface-container-highest pt-8 page-enter">
                    <a href="{{ route('login') }}" class="flex items-center text-sm font-bold text-on-surface-variant hover:text-primary transition-colors group">
                        <span class="material-symbols-outlined text-lg mr-2 group-hover:-translate-x-1 transition-transform">arrow_back</span>
                        Kembali Login
                    </a>
                </footer>
            </div>
        </section>
    </div>

    {{-- Script Interaktif --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Password Visibility Toggle
            const toggleButtons = document.querySelectorAll('.pw-toggle');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const container = this.closest('.relative');
                    const input = container.querySelector('.pw-input');
                    const icon = this.querySelector('.material-symbols-outlined');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.textContent = 'visibility_off';
                    } else {
                        input.type = 'password';
                        icon.textContent = 'visibility';
                    }
                });
            });

            // 2. Password Strength Meter
            const passwordInput = document.getElementById('password');
            const strengthContainer = document.getElementById('strength-bars');

            if (passwordInput && strengthContainer) {
                const strengthLabel = strengthContainer.querySelector('.strength-label');
                const bars = strengthContainer.querySelectorAll('.strength-bar');

                function calculateStrength(password) {
                    let score = 0;
                    if (password.length >= 8) score++;
                    if (password.length >= 12) score++;
                    if (/[A-Z]/.test(password)) score++;
                    if (/[0-9]/.test(password)) score++;
                    if (/[^A-Za-z0-9]/.test(password)) score++;

                    if (score <= 2) return { level: 'weak', score: 1, label: 'Lemah', color: 'bg-error', txt: 'text-error' };
                    if (score <= 3) return { level: 'medium', score: 2, label: 'Sedang', color: 'bg-secondary', txt: 'text-secondary' };
                    return { level: 'strong', score: 3, label: 'Kuat', color: 'bg-primary', txt: 'text-primary' };
                }

                passwordInput.addEventListener('input', () => {
                    const value = passwordInput.value;
                    const result = calculateStrength(value);

                    bars.forEach((bar, index) => {
                        bar.className = 'strength-bar h-full w-1/3 transition-all duration-500 bg-surface-container-highest';
                        if (value.length > 0 && index < result.score) {
                            bar.classList.remove('bg-surface-container-highest');
                            bar.classList.add(result.color);
                        }
                    });

                    if (strengthLabel) {
                        strengthLabel.textContent = value.length > 0 ? `Kekuatan: ${result.label}` : '';
                        strengthLabel.className = `strength-label text-[10px] uppercase tracking-widest mt-2 font-bold ${value.length > 0 ? result.txt : 'text-outline'}`;
                    }
                });
            }
        });
    </script>
</body>
</html>
