# 🐄 SAKADOMAS - Smart Livestock Management System

<p align="center">
  <img src="https://i.postimg.cc/Wp6PjkH2/2.png" alt="SAKADOMAS Logo" width="200px">
</p>

**SAKADOMAS** adalah sistem manajemen peternakan modern yang dirancang untuk mengotomatisasi dan mempermudah pengelolaan ternak, kandang, serta transaksi jual-beli ternak secara efisien dan transparan.

---

## ✨ Fitur Utama

### 👨‍💻 Role: Admin
- **Dashboard Analytics**: Visualisasi data transaksi dan populasi ternak.
- **Manajemen Akun**: Kontrol penuh terhadap data pengguna (pelanggan & staf).
- **Manajemen Kandang & Kamar**: Pengorganisasian infrastruktur peternakan secara hierarkis.
- **Inventori Ternak**: Monitoring data detail setiap hewan ternak, lokasi kandang, dan status kesehatan.
- **Sistem Transaksi**: Pengelolaan pesanan, penugasan ternak ke pembeli, dan rekapitulasi data keuangan.
- **Penjadwalan Kunjungan**: Verifikasi dan pengaturan jadwal survei dari calon pembeli.
- **Monitoring**: Pelacakan kondisi harian operasional peternakan.

### 👥 Role: Pelanggan
- **Landing Page**: Informasi produk dan profil peternakan.
- **Pemesanan Mandiri**: Melakukan pesanan ternak secara langsung melalui sistem.
- **Riwayat Transaksi**: Melacak status pesanan, pembayaran, hingga konfirmasi penerimaan.
- **Upload Bukti Bayar**: Integrasi sistem pembayaran yang transparan.
- **Penjadwalan Kunjungan**: Mengajukan jadwal survei ke lokasi peternakan secara real-time.

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi |
| --- | --- |
| **Framework** | [Laravel 11](https://laravel.com) |
| **Frontend UI** | [TailAdmin](https://tailadmin.com) (Tailwind CSS & Alpine.js) |
| **Database** | MySQL / PostgreSQL |
| **Build Tool** | [Vite](https://vitejs.dev) |
| **Language** | PHP 8.2+, JavaScript |

---

## 🚀 Panduan Instalasi

### 1. Persyaratan Sistem
- **PHP 8.2+**
- **Composer**
- **Node.js 18+ & NPM**
- **MySQL/PostgreSQL**

### 2. Langkah-Langkah Instalasi

```bash
# Clone repositori
git clone https://github.com/Luckyawan7b/Sakadomas.git
cd Sakadomas

# Install dependensi PHP
composer install

# Install dependensi Frontend
npm install

# Salin file environment
cp .env.example .env

# Generate Application Key
php artisan key:generate

# Konfigurasi Database di .env
# Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD sesuai database lokal Anda

# Jalankan Migrasi dan Seeder
php artisan migrate --seed

# Hubungkan Storage
php artisan storage:link
```

### 3. Menjalankan Aplikasi

#### Mode Pengembangan (Development)
Anda dapat menggunakan perintah tunggal jika sudah dikonfigurasi di `composer.json`:

```bash
# Menjalankan server Laravel & Vite secara otomatis
composer run dev
```

Atau jalankan secara terpisah di dua terminal berbeda:

```bash
# Terminal 1: Server Laravel
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev
```

Aplikasi dapat diakses di: `http://localhost:8000`

#### Mode Produksi (Production)
Jika Anda ingin melakukan build asset untuk produksi:

```bash
# Compile asset menggunakan Vite
npm run build
```

---

## 📁 Struktur Folder Utama

- `app/Http/Controllers`: Logika bisnis aplikasi.
- `app/Models`: Definisi skema database dan relasi.
- `resources/views`: Template UI menggunakan Blade & Tailwind CSS.
- `routes/web.php`: Definisi seluruh endpoint aplikasi.
- `database/migrations`: Skema struktur tabel database.

---

<p align="center">
  Dibuat dengan ❤️ oleh Tim SAKADOMAS
</p>

