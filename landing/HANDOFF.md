# 📋 Smart-Saka Landing Page — Handoff Documentation

> **Scope:** Landing Page Utama — Opsi 1 "Refined Earthy"  
> **Stack:** PHP 8.2+ · Laravel · Blade · **Tailwind CSS v4** · Vite 7 · **Alpine.js 3 (npm)** · Axios 1.x  
> **Catatan kritis:** Panduan ini ditulis untuk **Tailwind v4** yang berbeda secara arsitektur dari v3. Jangan ikuti tutorial v3.

---

## ⚠️ Perbedaan Tailwind v4 vs v3 — Wajib Baca

| Aspek | v3 (lama) | **v4 (yang dipakai)** |
|---|---|---|
| Konfigurasi | `tailwind.config.js` | **Tidak ada** — semua di `app.css` via `@theme {}` |
| Import CSS | `@tailwind base/components/utilities` | **`@import "tailwindcss"`** (satu baris) |
| Plugin | `plugins: [require(...)]` di config.js | **`@plugin "@tailwindcss/forms"`** di CSS |
| Vite plugin | `postcss.config.js` | **`@tailwindcss/vite`** langsung di `vite.config.js` |
| Warna kustom | `theme.extend.colors` di config.js | **`--color-*`** di dalam `@theme {}` di CSS |
| Referensi warna di CSS | `theme('colors.olive.700')` | **`var(--color-olive-700)`** |
| Alpine.js | CDN atau npm | **npm, di-import di `app.js`** |

---

## 1. Struktur Folder

```
laravel-project/
│
├── vite.config.js                          ← @tailwindcss/vite plugin (BUKAN postcss)
│
├── app/Http/Controllers/
│   └── LandingController.php
│
├── resources/
│   ├── css/
│   │   └── app.css                         ← @import "tailwindcss" + @theme + @plugin
│   ├── js/
│   │   ├── app.js                          ← Alpine.start() + scroll reveal + newsletter
│   │   └── bootstrap.js                    ← Axios + CSRF setup
│   └── views/
│       ├── layouts/
│       │   └── landing.blade.php           ← Master layout (hanya @vite, tanpa Alpine CDN)
│       ├── components/
│       │   └── landing/
│       │       ├── navbar.blade.php        ← Alpine x-data mobile menu
│       │       ├── hero.blade.php
│       │       ├── product-card.blade.php
│       │       ├── faq.blade.php           ← Alpine accordion (BARU)
│       │       ├── testimonial-slider.blade.php  ← Alpine slider
│       │       └── footer.blade.php
│       └── landing/
│           └── index.blade.php
│
└── public/images/landing/                  ← Upload foto nyata di sini
    ├── hero-main.jpg           (800×1000px)
    ├── about-1.jpg             (700×700px)
    ├── about-2.jpg, about-3.jpg (400×400px)
    ├── product-crosstexel.jpg  (600×450px)
    ├── product-merino.jpg, product-etawa.jpg
    ├── product-garut.jpg, product-bibit.jpg, product-aqiqah.jpg
    ├── keunggulan-main.jpg     (800×600px)
    ├── testi-1.jpg, testi-2.jpg, testi-3.jpg  (100×100px, foto wajah)
    ├── testi-collage-1.jpg, testi-collage-2.jpg, testi-collage-3.jpg
    ├── nl-1.jpg, nl-2.jpg, nl-3.jpg, nl-4.jpg
    └── og-smart-saka.jpg       (1200×630px, untuk WhatsApp share preview)
```

---

## 2. Langkah Integrasi

### Step 1 — Verifikasi package.json

Package yang dibutuhkan **sudah ada** di `package.json` yang kamu gunakan:

```json
"devDependencies": {
    "@tailwindcss/forms": "^0.5.11",
    "@tailwindcss/vite": "^4.1.12",     ← kunci untuk v4
    "laravel-vite-plugin": "^2.0.0",
    "tailwindcss": "^4.2.2",
    "vite": "^7.0.4"
},
"dependencies": {
    "alpinejs": "^3.14.9",              ← di-import di app.js, bukan CDN
    "axios": "^1.11.0"
}
```

Jalankan install jika belum:
```bash
npm install
```

### Step 2 — Salin `vite.config.js`

Timpa `vite.config.js` yang ada. File ini menggunakan `@tailwindcss/vite` plugin:

```js
import tailwindcss from '@tailwindcss/vite';  // ← ini yang beda dari v3
```

**Tidak perlu `postcss.config.js`** — hapus jika ada.

### Step 3 — Salin `resources/css/app.css`

Seluruh konfigurasi Tailwind ada di sini, bukan di config file terpisah:
- `@import "tailwindcss"` — satu baris menggantikan 3 directive v3
- `@plugin "@tailwindcss/forms"` — menggantikan `require('@tailwindcss/forms')`
- `@theme { }` — semua warna, font, animasi kustom

### Step 4 — Salin `resources/js/app.js` dan `bootstrap.js`

Alpine.js di-import dari `node_modules`, **bukan dari CDN**. Pastikan tidak ada tag script CDN Alpine di Blade layout.

### Step 5 — Salin semua file Blade

Salin semua file di `resources/views/` ke project Laravel, ikuti struktur folder.

### Step 6 — Tambahkan Route di `routes/web.php`

```php
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms',   'legal.terms')->name('terms');
Route::get('/blog',     [BlogController::class, 'index'])->name('blog.index');
```

### Step 7 — Config WhatsApp

Buat `config/smartsaka.php`:
```php
<?php
return [
    'wa_number' => env('SMARTSAKA_WA_NUMBER', '6281234567890'),
];
```

Di `.env`:
```
SMARTSAKA_WA_NUMBER=6281234567890
```

### Step 8 — Build

```bash
npm run dev    # hot reload
npm run build  # production
```

---

## 3. Cara Kerja Tailwind v4 — Penjelasan Singkat

### `@theme {}` di `app.css` = `tailwind.config.js`

```css
/* v3 di tailwind.config.js */
theme: {
  extend: {
    colors: { olive: { 700: '#4e5c2e' } }
  }
}

/* v4 di app.css */
@theme {
    --color-olive-700: #4e5c2e;
}
```

Setelah `@theme`, semua token langsung tersedia sebagai utility class:
- `bg-olive-700`, `text-olive-700`, `border-olive-700`
- `hover:bg-olive-600`, `dark:text-olive-300`

### `@plugin` = `require()` di plugins array

```css
/* v3 di tailwind.config.js */
plugins: [require('@tailwindcss/forms')]

/* v4 di app.css */
@plugin "@tailwindcss/forms";
```

### Referensi warna di CSS

```css
/* v3 */
background: theme('colors.olive.700');

/* v4 */
background: var(--color-olive-700);
```

---

## 4. Komponen — Cara Penggunaan

### `<x-landing.product-card>` — Loop dari Controller

```blade
@foreach ($products as $index => $product)
    <x-landing.product-card
        :title="$product->name"
        :description="$product->description"
        :price="'Rp ' . number_format($product->price, 0, ',', '.')"
        :image="asset('images/products/' . $product->image)"
        :badge="$product->badge_label"
        :badge-color="$product->badge_color ?? 'olive'"
        :category="$product->category_label"
        :delay="($index * 0.07) . 's'"
        :featured="(bool) $product->is_featured"
        wa-number="{{ config('smartsaka.wa_number') }}"
    />
@endforeach
```

### `<x-landing.faq>` — FAQ Accordion (Alpine.js)

```blade
<x-landing.faq
    :faqs="$faqs"
    wa-number="{{ config('smartsaka.wa_number') }}"
/>
```

### `<x-landing.testimonial-slider>` — Alpine.js Slider

```blade
<x-landing.testimonial-slider :testimonials="$testimonials" />
```

---

## 5. Checklist Go-Live

- [ ] Upload foto nyata ke `public/images/landing/`
- [ ] Set `SMARTSAKA_WA_NUMBER` di `.env`
- [ ] Update email + alamat di `x-landing.footer`
- [ ] Update nomor telepon di JSON-LD di `landing.blade.php`
- [ ] Update link sosmed di `x-landing.footer`
- [ ] Buat `og-smart-saka.jpg` (1200×630px)
- [ ] `npm run build` berhasil tanpa error
- [ ] Hapus `postcss.config.js` jika masih ada dari setup lama
- [ ] Pastikan tidak ada `tailwind.config.js` — v4 tidak membutuhkannya
- [ ] Pasang Google Analytics 4 via `@stack('head')` di layout
- [ ] `php artisan config:cache && php artisan view:cache`

---

*Stack: PHP 8.2+ · Laravel · Tailwind CSS v4 · Vite 7 · Alpine.js 3 · Axios 1.x — Smart-Saka Frontend Team*
