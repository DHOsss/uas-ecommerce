# Aplikasi E-Commerce - UAS Back End

Proyek UAS mata kuliah Back End Development menggunakan Laravel, PHP, dan MySQL. Aplikasi ini merupakan sistem manajemen e-commerce sederhana yang mencakup pengelolaan produk, pesanan, customer, pembayaran, dan fitur pendukung lainnya.

---

## Anggota Kelompok

| Anggota | Fitur yang Dikerjakan |
|---|---|
| [Nama Anggota 1] | Produk & Kategori |
| [Nama Anggota 2] | Pesanan & Item Pesanan |
| [Nama Anggota 3] | Customer & Keranjang Belanja |
| [Nama Anggota 4] | Pembayaran & Voucher |
| [Nama Anggota 5] | Ulasan & Supplier |

---

## Teknologi yang Digunakan

- **PHP** 8.x
- **Laravel** 11.x
- **MySQL**
- **HTML / Blade Template**

---

## Fitur Aplikasi

| No | Fitur | Deskripsi |
|---|---|---|
| 1 | Produk | Kelola data produk (nama, harga, stok, kategori) |
| 2 | Kategori | Kelola kategori produk |
| 3 | Pesanan | Kelola pesanan dari customer |
| 4 | Item Pesanan | Detail produk dalam setiap pesanan |
| 5 | Customer | Kelola data customer |
| 6 | Keranjang | Keranjang belanja per customer |
| 7 | Pembayaran | Kelola pembayaran pesanan |
| 8 | Voucher | Kelola voucher diskon |
| 9 | Ulasan | Ulasan dan rating produk dari customer |
| 10 | Supplier | Kelola data supplier produk |

---

## Cara Menjalankan Aplikasi

### 1. Clone Repository

```bash
git clone <url-repository>
cd <nama-folder>
```

### 2. Install Dependensi

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses: **http://localhost:8000**

---

## Struktur Database

| Tabel | Deskripsi |
|---|---|
| `products` | Data produk |
| `categories` | Data kategori produk |
| `orders` | Data pesanan |
| `order_items` | Detail item dalam pesanan |
| `customers` | Data customer |
| `carts` | Keranjang belanja |
| `payments` | Data pembayaran |
| `vouchers` | Data voucher diskon |
| `reviews` | Ulasan produk |
| `suppliers` | Data supplier |

---

## Struktur Folder Utama

```
app/
├── Http/Controllers/     # Semua controller (10 controller)
└── Models/               # Semua model (10 model)

resources/views/          # Semua tampilan (Blade)
├── categories/
├── carts/
├── customers/
├── order_items/
├── orders/
├── payments/
├── products/
├── reviews/
├── suppliers/
└── vouchers/

database/migrations/      # File migrasi database
routes/web.php            # Definisi semua route
 
