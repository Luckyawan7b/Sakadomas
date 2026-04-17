# 📋 Smart-Saka Auth — Handoff Documentation

> **Scope:** Module autentikasi (Login, Register, Forgot Password, Reset Password)  
> **Stack:** PHP 8.2+ · Laravel · Blade · **Tailwind CSS v4** · Vite 7 · Axios 1.x  
> **Catatan:** File ini ditulis untuk Tailwind **v4**, bukan v3. Baca bagian perbedaan di bawah dulu.

---

## ⚠️ Tailwind v4 — Perbedaan Kritis dari v3

| v3 (lama) | **v4 (yang dipakai)** |
|---|---|
| `tailwind.config.js` | **Tidak ada** — dihapus total |
| `@tailwind base/components/utilities` | **`@import "tailwindcss"`** (satu baris) |
| `plugins: [require('@tailwindcss/forms')]` | **`@plugin "@tailwindcss/forms"`** di CSS |
| `postcss.config.js` + `autoprefixer` | **`@tailwindcss/vite`** plugin di vite.config.js |
| `theme.extend.colors` di config.js | **`@theme { --color-* }`** di app.css |
| `theme('colors.primary')` di CSS | **`var(--color-primary)`** |

---

## 1. Struktur Folder

```
laravel-project/
│
├── vite.config.js                           ← @tailwindcss/vite (bukan postcss)
│
├── resources/
│   ├── css/
│   │   └── app.css                          ← @import + @theme + @plugin (TIDAK ada tailwind.config.js)
│   ├── js/
│   │   ├── app.js                           ← password toggle, strength meter
│   │   └── bootstrap.js                     ← Axios + CSRF
│   └── views/
│       ├── layouts/
│       │   └── auth.blade.php               ← Master layout (hanya @vite, tanpa CDN Tailwind)
│       ├── components/
│       │   ├── auth-illustration.blade.php  ← Panel kiri bergambar
│       │   ├── auth-header.blade.php        ← Header form (ikon + judul)
│       │   ├── input.blade.php              ← Input + error handling
│       │   ├── password-input.blade.php     ← Password + toggle + strength meter
│       │   └── button.blade.php             ← Tombol (primary/secondary/ghost)
│       └── auth/
│           ├── login.blade.php
│           ├── register.blade.php
│           ├── forgot-password.blade.php
│           └── reset-password.blade.php
│
├── app/Http/Controllers/Auth/
│   ├── RegisteredUserController.php         ← Validasi 7 field kustom Smart-Saka
│   └── SocialiteController.php              ← Google OAuth (opsional)
│
├── database/migrations/
│   └── ..._add_oauth_and_profile_columns_to_users_table.php
│
└── routes/
    └── web.php                              ← Semua named route auth
```

---

## 2. Langkah Integrasi

### Step 1 — Verifikasi package.json

Pastikan `package.json` sudah mengandung dependensi berikut:

```json
"devDependencies": {
    "@tailwindcss/forms": "^0.5.11",
    "@tailwindcss/vite":  "^4.1.12",
    "laravel-vite-plugin": "^2.0.0",
    "tailwindcss": "^4.2.2",
    "vite": "^7.0.4"
},
"dependencies": {
    "axios": "^1.11.0"
}
```

```bash
npm install
```

### Step 2 — Salin `vite.config.js`

Menggunakan `@tailwindcss/vite`, **bukan** PostCSS. Hapus `postcss.config.js` jika ada.

### Step 3 — Salin `resources/css/app.css`

Ini adalah **pengganti `tailwind.config.js`**. Semua warna Material You terdefinisi di `@theme {}`.  
Tidak ada file config terpisah.

### Step 4 — Salin file Blade

Salin semua views ke path yang sesuai di struktur folder. Tidak perlu mengubah class Tailwind di Blade — utility class seperti `bg-primary`, `text-on-surface`, `ring-primary/30` tetap bekerja identik di v4.

### Step 5 — Tambahkan Route

```php
// routes/web.php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/forgot-password',  [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password',        [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms',   'legal.terms')->name('terms');
Route::view('/help',    'help.index')->name('help');
```

### Step 6 — Config WhatsApp & Google OAuth (opsional)

```bash
composer require laravel/socialite
```

Di `.env`:
```
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=https://your-app.test/auth/google/callback
```

### Step 7 — Jalankan Migration

```bash
php artisan migrate
```

Ini menambahkan kolom `google_id`, `avatar`, `username`, `phone`, `kecamatan`, `desa`, `address` ke tabel `users`.

### Step 8 — Build

```bash
npm run dev    # development + hot reload
npm run build  # production
```

---

## 3. Cara Kerja `@theme` di v4 — Singkat

Token yang didefinisikan di `app.css`:
```css
@theme {
    --color-primary: #204e2b;
    --color-on-primary: #ffffff;
    /* ... */
}
```

Langsung tersedia sebagai utility class **tanpa konfigurasi tambahan**:
- `bg-primary` → `background-color: #204e2b`
- `text-on-primary` → `color: #ffffff`
- `ring-primary/30` → ring dengan opacity 30%
- `shadow-primary/20` → shadow dengan opacity 20%
- `hover:bg-primary-container` → bg saat hover

---

## 4. Catatan untuk Developer Backend

- Semua `@csrf` sudah terpasang di form
- Semua field sudah punya `@error()` dan `old()` untuk repopulate
- Password confirmation menggunakan field `password_confirmation` (Laravel rule `confirmed`)
- Hidden field `token` + `email` di reset-password sudah terpasang
- Select Kecamatan/Desa masih hardcoded — ganti dengan data dari DB dan AJAX untuk cascade

---

## 5. Tentang `preview.html`

File `preview.html` adalah file standalone browser (bukan bagian dari Vite build) sehingga **tetap menggunakan Tailwind CDN v3** untuk demo. Ini sudah benar — CDN v4 tidak tersedia sebagai drop-in seperti v3. File ini hanya untuk keperluan presentasi desain, bukan untuk production.

---

## 6. Checklist Sebelum Go-Live

- [ ] Ganti gambar hero di panel kiri dengan foto asli (simpan di `public/images/auth/`)
- [ ] Set `SMARTSAKA_WA_NUMBER` di `.env`
- [ ] Set Google OAuth credentials di `.env` (jika digunakan)
- [ ] Uncomment Google OAuth route di `routes/web.php`
- [ ] Isi data Kecamatan & Desa dari database
- [ ] `npm run build` sukses tanpa error
- [ ] `php artisan migrate` sukses
- [ ] Hapus `tailwind.config.js` dan `postcss.config.js` jika masih tersisa dari setup lama
- [ ] Pastikan tidak ada `<script src="https://cdn.tailwindcss.com">` di layout production

---

*Stack: PHP 8.2+ · Laravel · Tailwind CSS v4 · Vite 7 · Axios 1.x — Smart-Saka Auth Module*
