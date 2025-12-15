# WarungPOS - Aplikasi POS untuk UMKM Kuliner

Aplikasi Point of Sale (POS) berbasis web untuk UMKM kuliner seperti warkop, cafe kecil, dan warung makan. Dibangun dengan Laravel 12 + PHP 8.3 + MySQL.

## ✨ Fitur Utama

- **Autentikasi & Role**: Login/Register dengan role Owner dan Kasir
- **Master Data**: CRUD Kategori dan Produk/Menu
- **POS Screen**: Layar kasir yang cepat dengan 2-kolom layout
- **Transaksi**: Dine-in/Takeaway, diskon, pajak, service charge
- **Pembayaran**: Cash (dengan kembalian) dan QRIS
- **Laporan**: Dashboard dengan Chart.js, riwayat transaksi, filter tanggal
- **UI Modern**: Dark sidebar, cards rounded, responsive untuk tablet

## 📋 Persyaratan Sistem

- PHP >= 8.3
- Composer 2.x
- Node.js >= 18
- MySQL 8.0+

## 🚀 Instalasi

### 1. Clone atau Download Project

```bash
cd c:\laragon\www\warung-pos
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warung_pos
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database

Buat database baru dengan nama `warung_pos` di MySQL.

### 6. Jalankan Migration & Seeder

```bash
php artisan migrate:fresh --seed
```

### 7. Link Storage (untuk upload gambar)

```bash
php artisan storage:link
```

### 8. Compile Assets

```bash
npm run dev
```

### 9. Jalankan Server

```bash
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

## 👤 Akun Demo

| Email | Password | Role | Akses |
|-------|----------|------|-------|
| owner@demo.com | password | Owner | Dashboard Admin + POS |
| kasir@demo.com | password | Kasir | POS Screen |

## 📱 Penggunaan

### Owner (Admin)
1. Login dengan akun owner
2. Akses Dashboard untuk melihat statistik dan grafik
3. Kelola Kategori dan Produk di menu Master Data
4. Lihat Riwayat Transaksi dengan filter tanggal

### Kasir
1. Login dengan akun kasir
2. Langsung masuk ke layar POS
3. Klik produk untuk menambahkan ke keranjang
4. Atur quantity, diskon, pajak jika diperlukan
5. Klik Bayar (atau tekan F2) untuk checkout
6. Pilih metode pembayaran (Cash/QRIS)
7. Proses pembayaran dan cetak struk

### Keyboard Shortcuts (POS)
- **F2**: Buka modal pembayaran
- **F3**: Fokus ke search produk

## 📁 Struktur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Controller untuk admin panel
│   │   └── Pos/            # Controller untuk POS kasir
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   └── Requests/           # Form request validation
├── Models/                 # Eloquent models
└── Services/
    └── OrderCalculationService.php

resources/views/
├── admin/                  # Views untuk admin panel
├── layouts/
│   ├── admin.blade.php     # Layout admin dengan sidebar
│   └── pos.blade.php       # Layout POS khusus kasir
└── pos/                    # Views untuk POS screen
```

## 🔧 Konfigurasi Tambahan (Opsional)

### Mengubah Nama Toko
Edit file `resources/views/pos/receipt.blade.php` untuk mengubah nama dan alamat toko di struk.

### Mengubah Pajak/Service Default
Edit nilai default di `resources/views/pos/index.blade.php` pada bagian input tax_percent dan service_percent.

## 🛠️ Development

```bash
# Watch mode untuk development
npm run dev

# Build untuk production
npm run build
```

## 📝 Lisensi

MIT License

---

Dibuat dengan ❤️ menggunakan Laravel 12
