import { defineConfig } from 'vite';
import laravel          from 'laravel-vite-plugin';
import tailwindcss      from '@tailwindcss/vite'; // v4: Vite plugin, bukan PostCSS

export default defineConfig({
    plugins: [
        tailwindcss(),

        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],

    build: {
        sourcemap: process.env.APP_ENV !== 'production',
    },

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
