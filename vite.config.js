import { defineConfig } from 'vite';
import laravel          from 'laravel-vite-plugin';
import tailwindcss      from '@tailwindcss/vite'; // v4: plugin Vite, bukan PostCSS

export default defineConfig({
    plugins: [
        /*
         * @tailwindcss/vite — Tailwind v4 menggunakan Vite plugin secara native.
         * TIDAK perlu postcss.config.js atau tailwind.config.js.
         * Semua konfigurasi ada di resources/css/app.css via @theme { }
         */
        tailwindcss(),

        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/landing.css',
                'resources/js/landing.js',
            ],
            refresh: true,
        }),
    ],

    build: {
        // Source map hanya di non-production
        sourcemap: process.env.APP_ENV !== 'production',
    },

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
