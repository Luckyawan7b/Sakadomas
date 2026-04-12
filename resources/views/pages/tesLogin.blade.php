<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Pastoral - Reset Access</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&amp;family=Manrope:wght@400;500;600&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-container": "#bcefc0",
                        "error-container": "#ffdad6",
                        "surface": "#fdf9f3",
                        "inverse-surface": "#32302c",
                        "secondary-container": "#ffdcc5",
                        "on-surface-variant": "#414941",
                        "on-background": "#1d1c17",
                        "tertiary-container": "#e6e3d4",
                        "surface-container": "#f2eee7",
                        "on-secondary-fixed": "#301400",
                        "primary-fixed": "#00210a",
                        "error": "#ba1a1a",
                        "primary": "#3a6843",
                        "on-primary-fixed-variant": "#22502d",
                        "surface-bright": "#fdf9f3",
                        "on-tertiary-fixed": "#1d1c13",
                        "secondary-fixed-dim": "#f4bb92",
                        "inverse-primary": "#a0d3a5",
                        "on-tertiary-container": "#1d1c13",
                        "on-tertiary": "#ffffff",
                        "surface-container-highest": "#e6e2db",
                        "secondary": "#805635",
                        "surface-container-high": "#ebe7e0",
                        "tertiary": "#5e5d51",
                        "tertiary-fixed": "#e6e3d4",
                        "surface-tint": "#3a6843",
                        "primary-fixed-dim": "#a0d3a5",
                        "on-tertiary-fixed-variant": "#48473c",
                        "tertiary-fixed-dim": "#cac7b8",
                        "surface-container-lowest": "#ffffff",
                        "surface-variant": "#dee5d9",
                        "on-error": "#ffffff",
                        "on-error-container": "#410002",
                        "background": "#fdf9f3",
                        "outline": "#717970",
                        "on-secondary": "#ffffff",
                        "surface-container-low": "#f8f3ed",
                        "on-secondary-container": "#301400",
                        "on-primary-container": "#00210a",
                        "outline-variant": "#c1c9be",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#f8f0e8",
                        "on-secondary-fixed-variant": "#653d1e",
                        "secondary-fixed": "#ffdcc5",
                        "surface-dim": "#dedad3",
                        "on-surface": "#1d1c17",
                        "on-primary-fixed": "#00210a"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Noto Serif"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        .font-serif {
            font-family: 'Noto Serif', serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-surface text-on-surface min-h-screen flex flex-col">
    <!-- Header Suppression -->
    <main class="flex-grow flex flex-col md:flex-row min-h-screen">
        <!-- Left Side: Editorial Content (Remains dark-styled for impact, consistent with request) -->
        <section class="hidden md:flex md:w-1/2 relative overflow-hidden bg-neutral-900">
            <div class="absolute inset-0 z-0">
                <img class="w-full h-full object-cover opacity-70"
                    data-alt="serene sheep farm at sunset with soft golden light bathing the hills and a small flock of woolly sheep grazing peacefully"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBvG4AmQQalFUdvNtjuel4kscvHK7ATN9ufT7DkBzaVKZp0t8ZNDYz2U81wthWEabXS1mi8npBFPRsciIcKJrVfAFPppx-dWaEopkHRDJOQJLYpycaNG98uhMnbqO__e99nJDqfg6w_e5X21xsk-g12ECQyVsnJtawUeh-YEZ5g5JZFKvb46WCbo_FbVqkRBQH7n0xj20DZdjIDArF9qic4Fy_RA7_FNCPFmqrRDhTTu-DU2IufePGx0T2_VBaGdjeifO19pl0mvFM"
                    style="">
                <!-- Tonal Depth Overlay - Darker for readability since the page is now light -->
                <div class="absolute inset-0 bg-gradient-to-tr from-black/80 via-black/20 to-primary-container/10">
                </div>
            </div>
            <div class="relative z-10 p-16 flex flex-col justify-end h-full">
                <div class="max-w-md">
                    <span
                        class="text-primary-fixed-dim font-label tracking-[0.2em] uppercase text-xs mb-6 block drop-shadow-sm"
                        style="">Smart-saka</span>
                    <h1 class="font-serif text-5xl italic tracking-tight leading-tight text-white mb-8 drop-shadow-md"
                        style="">
                        "Pilihan Cerdas Penikmat Domba."
                    </h1>
                    <div class="h-1 w-24 bg-primary-container"></div>
                </div>
            </div>
        </section>
        <!-- Right Side: Interaction Shell (Now Light Mode) -->
        <section class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 bg-surface">
            <div class="w-full max-w-sm">
                <!-- Header Branding -->
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl" style="">🐏</span>
                        <span class="text-2xl font-serif italic text-primary" style="">Smart-Saka</span>
                    </div>
                    <p class="text-on-surface-variant font-body" style="">Reset Password</p>
                </div>
                <!-- Reset Form -->
                <form class="space-y-12">
                    <div class="space-y-6">
                        <!-- Input Field -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-on-surface-variant tracking-wide uppercase"
                                for="email" style="">Alamat e-mail</label>
                            <div class="relative">
                                <input
                                    class="w-full bg-surface-container-high text-on-surface border-none rounded-xl px-4 py-4 focus:ring-1 focus:ring-primary/40 focus:bg-surface-lowest transition-all placeholder:text-on-surface-variant/50"
                                    id="email" name="email" placeholder="jokowi@gmail.com" type="email">
                                <span
                                    class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant/60"
                                    style="">mail</span>
                            </div>
                        </div>
                    </div>
                    <!-- CTA Section -->
                    <div class="space-y-6">
                        <button
                            class="w-full bg-primary text-on-primary font-bold py-4 rounded-full hover:shadow-[0px_10px_20px_rgba(58,104,67,0.2)] transition-all active:scale-95 flex items-center justify-center gap-2"
                            style="" type="submit">
                            <span class="" style="">Kirim Tautan</span>
                            <span class="material-symbols-outlined text-sm" style="">arrow_forward</span>
                        </button>
                        <div class="flex items-center justify-between pt-4">
                            <a class="text-on-surface-variant hover:text-primary text-sm font-medium flex items-center gap-1 transition-colors"
                                href="#" style="">
                                <span class="material-symbols-outlined text-base" style="">arrow_back</span>
                                Masuk</a>
                            <a class="text-on-surface-variant hover:text-primary text-sm font-medium transition-colors"
                                href="#" style="">Butuh Bantuan?</a>
                        </div>
                    </div>
                </form>
                <!-- Tooltip (Light variant) -->
                <div
                    class="mt-24 p-4 rounded-xl bg-primary-container/30 border border-primary-container/50 flex items-start gap-4">
                    <span class="material-symbols-outlined text-primary mt-0.5"
                        style="font-variation-settings: &quot;FILL&quot; 1;">verified_user</span>
                    <div>
                        <p class="text-xs font-bold text-on-primary-container uppercase tracking-widest mb-1"
                            style="">utamakan keamanan</p>
                        <p class="text-xs text-on-surface-variant leading-relaxed" style="">Kami akan mengirimkan
                            tautan ke email terdaftar Anda untuk mengatur ulang kata sandi Anda.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer
        class="bg-surface-container-low border-t border-outline-variant/30 font-sans text-xs uppercase tracking-widest text-on-surface-variant flex flex-col items-center justify-center gap-6 w-full py-12 mt-auto">
        <div class="flex gap-8">
            <a class="hover:text-primary transition-all" href="#" style="">Support</a>
            <a class="hover:text-primary transition-all" href="#" style="">Privacy Policy</a>
            <a class="hover:text-primary transition-all" href="#" style="">Terms of Service</a>
        </div>
        <p class="opacity-60" style="">© 2024 The Rural Editorial. All rights reserved.</p>
    </footer>
</body>

</html>
