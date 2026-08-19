# Panduan Deploy — hris-sitompul ke VPS

Dokumen ini menjelaskan cara deploy aplikasi **hris-sitompul** (Laravel 13 + Filament 5) ke **VPS** dengan domain dari **Niagahoster**, dilengkapi **CI/CD otomatis via GitHub Actions**.

---

## 1. Arsitektur CI/CD

```
git push origin main
        │
        ▼
┌─────────────────────────────────────┐
│  CI  (GitHub Actions)               │
│  ├── Code Style (Laravel Pint)      │
│  ├── Tests (Pest + MySQL 8.4)       │
│  └── Build Assets (npm run build)   │
└───────────────┬─────────────────────┘
                │ semua job hijau
                ▼
┌─────────────────────────────────────┐
│  CD (Deploy via SSH)                │
│  ├── git pull di VPS                │
│  ├── composer install --no-dev      │
│  ├── npm install && npm run build   │
│  ├── php artisan migrate --force    │
│  ├── config/route/view cache        │
│  └── restart queue + php-fpm        │
└─────────────────────────────────────┘
```

---

## 2. Prasyarat

| Item | Keterangan |
|---|---|
| VPS | Ubuntu 22.04/24.04, minimal 1 vCPU / 1GB RAM (disarankan 2GB) |
| Domain | Dibeli di Niagahoster (atau registrar mana pun) |
| Repo | `github.com/SARANA-PRIMA-SOLUSI-INDONESIA/hris-sitombung` |
| Akses | SSH ke VPS sebagai `root` |

---

## 3. Setup Server VPS (sekali saja)

### 3.1 Login ke VPS

```bash
ssh root@<IP_VPS>
```

### 3.2 Jalankan script setup otomatis

File `deploy/setup-server.sh` ada di repo. Upload & jalankan:

```bash
# ambil script dari repo (setelah clone pertama) ATAU salin manual
sudo bash deploy/setup-server.sh
```

Script akan menginstal otomatis:

- Paket dasar: Nginx, MySQL, Supervisor, Certbot
- **PHP 8.3** + semua ekstensi (`intl`, `dom`, `mbstring`, `gd`, `zip`, `bcmath`, `redis`, dll)
- **Composer**
- **Node.js 20**
- User `deploy` dengan sudo tanpa password
- Direktori project `/var/www/hris-sitombung`

### 3.3 Clone repository

```bash
su - deploy
cd /var/www/hris-sitombung
git clone https://github.com/SARANA-PRIMA-SOLUSI-INDONESIA/hris-sitombung.git .
```

### 3.4 Buat database MySQL

```bash
sudo mysql -uroot -p

# di dalam mysql shell:
CREATE DATABASE hris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hris_user'@'localhost' IDENTIFIED BY 'GANTI_DENGAN_PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON hris_db.* TO 'hris_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3.5 Buat `.env` produksi

```bash
cp .env.example .env
nano .env
```

Sesuaikan minimal ini:

```ini
APP_NAME=SITOMBUNG
APP_ENV=production
APP_KEY=            # kosongkan, nanti di-generate
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hris_db
DB_USERNAME=hris_user
DB_PASSWORD=GANTI_DENGAN_PASSWORD_KUAT

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.domain-anda.com    # atau pakai SMTP Niagahoster
MAIL_PORT=587
MAIL_USERNAME=noreply@domain-anda.com
MAIL_PASSWORD=password_email
MAIL_FROM_ADDRESS="noreply@domain-anda.com"

FILESYSTEM_DISK=public
APP_EMPLOYEE_NUMBER_PREFIX=SIT
```

> **Penting**: `.env` TIDAK ikut ke git (ada di `.gitignore`). Jadi aman dan tidak tertimpa saat deploy.

### 3.6 Install & migrate (pertama kali)

```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan key:generate
php artisan migrate --force --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3.7 Izin direktori

```bash
sudo chown -R deploy:www-data /var/www/hris-sitombung
sudo chmod -R 775 /var/www/hris-sitombung/storage
sudo chmod -R 775 /var/www/hris-sitombung/bootstrap/cache
```

---

## 4. SSH Key untuk GitHub Actions (CD)

Deploy otomatis memakai SSH key (bukan password). Siapkan di VPS:

```bash
su - deploy
ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519 -N ""
cat ~/.ssh/id_ed25519.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

# TAMPILKAN PRIVATE KEY - ini yang nanti dimasukkan ke GitHub Secret
cat ~/.ssh/id_ed25519
```

Simpan output `cat ~/.ssh/id_ed25519` (mulai dari `-----BEGIN OPENSSH PRIVATE KEY-----` sampai `-----END OPENSSH PRIVATE KEY-----`). Jangan dibagikan ke siapa pun.

---

## 5. Konfigurasi Nginx + SSL

### 5.1 Pasang vhost

```bash
sudo cp deploy/nginx.conf /etc/nginx/sites-available/hris-sitombung
sudo nano /etc/nginx/sites-available/hris-sitombung
#    → ganti "domain-anda.com" dengan domain asli
```

```bash
sudo ln -s /etc/nginx/sites-available/hris-sitombung /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

### 5.2 SSL gratis (Let's Encrypt)

```bash
sudo certbot --nginx -d domain-anda.com -d www.domain-anda.com
```

Ikuti wizard. Setelah selesai, akses `https://domain-anda.com` otomatis SSL.

---

## 6. Queue Worker (Supervisor)

Email & notifikasi memakai queue. Aktifkan worker:

```bash
sudo cp deploy/hris-queue.conf /etc/supervisor/conf.d/hris-queue.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start hris-queue:*
sudo supervisorctl status
```

---

## 7. Cron untuk Scheduler (opsional, untuk pengingat)

```bash
crontab -e
# tambahkan baris:
* * * * * cd /var/www/hris-sitombung && php artisan schedule:run >> /dev/null 2>&1
```

---

## 8. Domain Niagahoster → VPS

1. Login **Niagahoster** → menu **Domains** → pilih domain kamu → **DNS / Nameserver**.
2. Pilih **DNS Management** (pakai DNS Niagahoster) atau arahkan nameserver.
3. Tambah/ubah **A Record**:

   | Type | Name/Host | Value |
   |---|---|---|
   | A | `@` | `<IP_VPS>` |
   | A | `www` | `<IP_VPS>` |

4. Hapus A record lama yang menunjuk ke shared hosting.
5. Tunggu propagasi DNS (5 menit – 24 jam). Verifikasi:
   ```bash
   ping domain-anda.com          # harus menampilkan IP VPS
   ```

> Jika domain memakai nameserver pihak ketiga (Cloudflare dsb), ubah A record di panel mereka.

---

## 9. Setup GitHub Secrets

Repo GitHub → **Settings → Secrets and variables → Actions → New repository secret**:

| Secret | Isi |
|---|---|
| `VPS_HOST` | IP VPS (contoh `103.101.xxx.xxx`) |
| `VPS_USER` | User SSH untuk deploy: `deploy` |
| `VPS_PORT` | Port SSH: `22` |
| `VPS_SSH_KEY` | Private key dari langkah 4 (isi penuh) |
| `VPS_PROJECT_PATH` | `/var/www/hris-sitombung` |

---

## 10. Branch Protection (disarankan)

Repo → **Settings → Branches → Add branch ruleset/rule** untuk `main`:

- Centang **Require status checks to pass before merging**
- Pilih: `Code Style (Pint)`, `Tests (Pest)`, `Build Assets`

Dengan ini, deploy otomatis hanya terjadi jika semua CI hijau.

---

## 11. Alur Deploy Setiap Update

```bash
# di komputer lokal
git add .
git commit -m "update fitur X"
git push origin main
```

Lalu otomatis:

1. **CI** menjalankan Pint → Pest (dengan MySQL) → Build assets
2. Semua hijau → **CD** SSH ke VPS: `git pull` → composer → migrate → cache → restart worker
3. Aplikasi live di `https://domain-anda.com`

Pantau di repo → **Actions** tab. Kegagalan apa pun langsung terlihat di log.

---

## 12. Rollback

Jika deploy bermasalah, rollback ke commit sebelumnya:

```bash
ssh deploy@<IP_VPS>
cd /var/www/hris-sitombung
git log --oneline -5          # lihat commit
git checkout <commit_hash>    # rollback kode
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear && php artisan optimize
```

> Jangan lupa `git checkout main` setelah diperbaiki, supaya deploy berikutnya tidak konflik.

---

## 13. Troubleshooting

| Masalah | Solusi |
|---|---|
| `500 Server Error` | Cek log: `tail -f /var/www/hris-sitombung/storage/logs/laravel.log` |
| Migrate gagal | Pastikan kredensial DB di `.env` benar, `php artisan config:clear` lalu `config:cache` |
| Aset tidak termuat | `npm install && npm run build` di VPS, lalu `php artisan optimize:clear` |
| Email tidak terkirim | Cek SMTP `.env`, dan status queue: `sudo supervisorctl status hris-queue:*` |
| Deploy SSH gagal | Cek secret `VPS_SSH_KEY` & `VPS_HOST`, coba `ssh deploy@<IP>` manual |
| Permission denied | `sudo chown -R deploy:www-data /var/www/hris-sitombung && sudo chmod -R 775 storage bootstrap/cache` |
| Port 80/443 tidak kebuka | Pastikan firewall: `sudo ufw allow 22,80,443/tcp` (jika pakai UFW) |

---

## 14. Struktur File Deploy di Repo

```
.github/
  workflows/
    ci.yml          # CI: Pint + Pest + Build assets
    deploy.yml      # CD: SSH deploy ke VPS
deploy/
  setup-server.sh   # Script setup server (sekali jalan)
  nginx.conf        # Template vhost Nginx
  hris-queue.conf   # Template Supervisor queue worker
DEPLOY.md           # Dokumen ini
```
