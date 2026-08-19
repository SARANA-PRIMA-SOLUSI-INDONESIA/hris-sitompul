# SITOMBUNG — Sistem Manajemen Karyawan

Sistem manajemen karyawan (HRM) berbasis **Laravel 13** + **Filament 5** dengan modul kepegawaian, absensi, cuti, dan penggajian dasar.

## Tech Stack

| Layer | Teknologi |
|---|---|
| Framework | Laravel 13.x (PHP 8.3+) |
| Admin Panel | Filament 5.x |
| Frontend | Livewire v4, Alpine.js, Tailwind CSS v4, Vite |
| Database | MySQL 8.4 (via Laravel Sail) |
| Cache / Queue | Redis / Database |
| RBAC | spatie/laravel-permission + bezhansalleh/filament-shield |
| Audit Trail | spatie/laravel-activitylog |
| Testing | Pest PHP |
| Code Style | Laravel Pint |

## Prasyarat

- Docker Desktop (dengan WSL2 backend)
- PHP 8.3+ & Composer (untuk menjalankan perintah di luar container)
- Node.js 20+

## Instalasi (Laravel Sail)

```bash
# 1. Jalankan container (MySQL, Redis, Mailpit, app)
docker compose up -d --build

# 2. Install dependensi (jika belum)
docker compose exec laravel.test composer install

# 3. Konfigurasi environment
copy .env.example .env   # lalu sesuaikan DB_DATABASE=sitombung, dll

# 4. Generate app key & migrasi dengan seed
docker compose exec laravel.test php artisan key:generate
docker compose exec laravel.test php artisan migrate:fresh --seed

# 5. Akses panel
#    URL: http://localhost/admin
#    Login: admin@sitombung.test / password
```

Atau gunakan helper Sail:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
```

## Akun Demo

| Role | Email | Password |
|---|---|---|
| Super Admin | admin@sitombung.test | password |
| HR Admin | hr@sitombung.test | password |
| Manager | manager.*@sitombung.test | password |
| Karyawan | karyawan@sitombung.test | password |

## Modul & Fitur

- **Dashboard** — statistik karyawan, kehadiran, cuti pending
- **Data Master** — Departemen, Jabatan
- **Kepegawaian** — Karyawan (NIP otomatis), Absensi, Cuti & Izin (approval workflow), Jenis Cuti
- **Penggajian** — Komponen Gaji, Slip Gaji
- **RBAC** — Role & Permission via Filament Shield
- **Audit Trail** — Activity log untuk data sensitif

## Deployment ke Shared Hosting (cPanel)

> Tidak perlu Docker di server. Project ini sudah siap di-upload sebagai full PHP application.

### 1. Persiapkan Project di Lokal

```bash
# Generate key & build aset (dilakukan sekali di lokal)
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan key:generate
```

### 2. Upload ke cPanel

- **ZIP** seluruh isi project, lalu upload & extract ke `public_html` **atau** subfolder (mis. `public_html/sitombung`).
- Jika berada di subfolder, set document root ke folder `public/` (bagian **Domains → Manage → Document Root** → pilih `/sitombung/public`).
- Pastikan `storage/` & `bootstrap/cache/` writable (klik kanan → **Change Permissions → 775**).

### 3. Buat Database

- cPanel → **MySQL Databases** → buat database + user, lalu assign ALL PRIVILEGES.
- Isi kredensial tersebut ke `.env`.

### 4. Konfigurasi `.env`

```bash
# Di lokal
cp .env.production .env
# Lalu edit: APP_KEY, APP_URL, DB_DATABASE/USERNAME/PASSWORD, MAIL_* sesuai hosting
```

Atau edit `.env` langsung di cPanel (File Manager → Show Hidden Files).

### 5. Jalankan Migrasi & Seed (sekali)

Via **cPanel → Terminal** atau SSH:

```bash
php artisan migrate --force --seed
php artisan storage:link
php artisan optimize
```

> Untuk keamanan, hapus `/docker-compose.yml`, `/compose.yaml`, `.env.production` setelah berhasil deploy.

### 6. Selesai

- Buka `https://domain-anda.com` → otomatis redirect ke `/admin`.
- Login pertama: `admin@sitombung.test` / `password` → **segera ganti password**.

### Catatan Shared Hosting

- **PHP version**: wajib 8.3+ (cPanel → Select PHP Version). Aktifkan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `gd` (untuk upload foto), `zip`, `exif`, `fileinfo`.
- **Queue**: untuk email & notifikasi, atur cron:
  ```cron
  * * * * * cd /home/USER/sitombung && php artisan schedule:run >> /dev/null 2>&1
  ```
  lalu daftarkan di cPanel → **Cron Jobs**. Pastikan `QUEUE_CONNECTION=database`.
- **Backup**: gunakan fitur backup bawaan cPanel untuk database & file.

## Perintah Penting

```bash
# Test
./vendor/bin/pest

# Code style
./vendor/bin/pint
./vendor/bin/pint --test

# Migrasi + seed
docker compose exec laravel.test php artisan migrate:fresh --seed

# Optimasi
docker compose exec laravel.test php artisan optimize
```

## Struktur Direktori Utama

```
app/
  Actions/            # Business actions (GenerateEmployeeNumber, ApproveLeave, LeaveCalculator)
  Filament/Resources/ # Resource per modul (Form/Schema/Table dipisah)
  Models/
  Observers/
  Policies/           # Authorization per model
  Providers/Filament/ # Panel konfigurasi
database/
  factories/ migrations/ seeders/
tests/
  Feature/ Unit/
```

## Deployment

Lihat section Deployment & DevOps di `PRD.md` untuk langkah production (Nginx + PHP-FPM, queue worker, scheduler, backup, monitoring).
