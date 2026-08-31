# CATATAN PROGRES DEPLOYMENT — hris-sitompul

> Dokumen ini adalah catatan status deployment untuk dilanjutkan sesi berikutnya.
> Terakhir diperbarui: **2026-08-20 00:00 WIB (malam sesi ke-2)**

---

## 1. Ringkasan Status Keseluruhan

| Tahap | Status | Keterangan |
|---|---|---|
| Aplikasi dibangun (Laravel 13 + Filament 5) | ✅ Selesai | Local dev di `D:\SPSI\sitombung` |
| CI/CD pipeline di GitHub | ✅ Push | `.github/workflows/ci.yml` + `deploy.yml` |
| VPS setup (PHP 8.3, Nginx, MySQL, Node) | ✅ Selesai | via `deploy/setup-server.sh` |
| Clone repo di VPS | ✅ Selesai | `/var/www/hris-sitombung/hris-sitompul` |
| DB + migrate + seed | ✅ Selesai | Seeder produksi bebas Faker |
| Nginx vhost | ✅ Selesai | Port 8081 + `server_name` benar |
| DNS Niagahoster | ✅ Selesai | Nameserver diganti ke Cloudflare |
| Cloudflare (proxy + origin rule) | ✅ Selesai | Origin Rule port 10070, SSL Flexible |
| HTTPS akses | ✅ Selesai | `https://hatopansitompulbdg.or.id` → 302 → `/admin` → login 200 |
| Trusted Proxies | ✅ Selesai | Redirect HTTPS benar (komit `06fc038`) |
| **Login masuk dashboard** | ❌ **BELUM** | **Login sukses tapi kembali ke login page (login loop)** |

---

## 2. Infrastruktur VPS (penting untuk dipahami)

### 2.1 VPS NAT (bukan public IP langsung)

- IP publik: `93.127.129.98`
- IP internal host: `192.168.122.12`
- SSH publik di port **`10062`** → internal `22`
- **Akun SSH**: login sebagai `root` (bukan `deploy`); repo dimiliki `deploy` → perlu `safe.directory` bila pull sebagai root

### 2.2 Port Mapping (panel provider)

```
93.127.129.98:10067  →  192.168.122.12:8888   (mitralink-api Docker)
93.127.129.98:10066  →  192.168.122.12:20128  (9router dashboard/API gateway)
93.127.129.98:10062  →  192.168.122.12:22     (SSH)
93.127.129.98:10070  →  192.168.122.12:8081   (hris-sitombung-web — Nginx Laravel)
```

### 2.3 Container Docker di VPS

| Container | Image | Port internal |
|---|---|---|
| `9router` | `nightwalker8x/n9router:latest` | 20128 |
| `mitralink-api-prod` | `ghcr.io/.../mitralink-service:v0.2.5` | 8888→8080 |
| `mitralink-redis-prod` | `redis:7-alpine` | - |
| `mitralink-postgres-prod` | `postgres:16-alpine` | - |

### 2.4 Arsitektur web saat ini

```
Browser ──HTTPS──> Cloudflare (proxy, SSL Flexible)
                     │ Origin Rule → port 10070
                     ▼
                93.127.129.98:10070  (provider NAT)
                     ▼
                192.168.122.12:8081  (Nginx → PHP-FPM → Laravel)
```

> ⚠️ **JANGAN hapus 9router** — itu pintu masuk web VPS dan dipakai MitraLink.

---

## 3. STATUS TERAKHIR — MASALAH LOGIN LOOP (belum selesai)

### 3.1 Gejala
- Login `admin@sitombung.test` / `password` di `https://hatopansitompulbdg.or.id/admin/login`
- Redirect ke halaman login lagi (tidak masuk dashboard)

### 3.2 Fakta yang sudah diverifikasi
| Cek | Hasil |
|---|---|
| User admin ada | ✅ `YA` |
| Role `super_admin` | ✅ `["super_admin"]` |
| Password `password` | ✅ `BENAR` |
| Log Laravel saat login | Kosong (tidak ada error) |
| `SESSION_DRIVER` | `database` |
| `SESSION_LIFETIME` | `120` |
| `SESSION_ENCRYPT` | `false` |
| `SESSION_PATH` | `/` |
| `SESSION_DOMAIN` | `null` |
| `SESSION_SECURE_COOKIE` | Tidak ada di `.env` (default false) |
| Tabel sessions | Ada, 1 row setelah login (✅ session tersimpan) |

### 3.3 Langkah diagnosa yang BELUM dijalankan (lanjut besok)

**A. Cek apakah session berisi user_id (auth):**
```bash
cd /var/www/hris-sitombung/hris-sitompul
php artisan tinker --execute="echo json_encode(DB::table('sessions')->select('id','user_id','ip_address','last_activity')->get());"
```
- `user_id` terisi → login sukses, masalah di restore session/cookie
- `user_id` null → masalah di proses login itu sendiri

**B. Debug dari browser (DevTools → Network):**
1. Buka `https://hatopansitompulbdg.or.id/admin/login` dengan F12 terbuka
2. Login, perhatikan request POST login:
   - Status response (302/200?)
   - Response headers: **apakah ada `Set-Cookie: laravel_session=...`?**
   - Redirect ke mana?
3. Request berikutnya: apakah **cookie session terkirim** di Request Headers?

**C. Perbaikan yang mungkin (sesuai hasil A/B):**
- Cookie tidak terkirim → force `SESSION_SECURE_COOKIE=false`:
  ```bash
  echo "SESSION_SECURE_COOKIE=false" >> .env
  php artisan config:clear && php artisan config:cache
  sudo systemctl restart php8.3-fpm
  ```
- Cache Cloudflare menyimpan halaman login → coba **incognito** + purge cache Cloudflare
- Masalah Livewire/session blocking → cek `config/session.php` atau log `storage/logs/laravel.log` saat login (hapus log dulu supaya yang terlihat fresh)

### 3.4 Catatan lain yang relevan
- `APP_URL=https://hatopansitompulbdg.or.id` (sudah benar)
- Trusted Proxies `at: '*'` sudah aktif (redirect https benar)
- `APP_DEBUG` di VPS kemungkinan masih `true` (untuk diagnosa) — setelah beres, set `false`

---

## 4. Yang SUDAH DILAKUKAN (kronologi)

### 4.1 GitHub (commit di `main`)
- `057289d` first commit
- `5177e57` feat: ci/cd pipeline + deploy docs
- `1b425f5` fix: split production seeder (faker-free)
- `097b45d` chore: add package-lock.json
- `c4511de` fix: trust Cloudflare proxies (awalnya salah pakai konstanta)
- `06fc038` fix: use Request header constants (BENAR, sudah di VPS)

### 4.2 VPS — aplikasi & Nginx
- Path project: `/var/www/hris-sitombung/hris-sitompul`
- Vhost: `/etc/nginx/sites-available/hris-sitombung` → symlink di `sites-enabled`
  - `server_name hatopansitompulbdg.or.id www.hatopansitompulbdg.or.id;`
  - `root /var/www/hris-sitombung/hris-sitompul/public;`
  - `listen 80; listen 8081;` (+ `[::]:`)
  - **BUKAN default_server** (bentrok dengan mitralink)
- PHP-FPM user = `www-data` → **storage & bootstrap/cache harus `chown www-data`** (ini fix penting yang membuat login page 500 → 200)
- DB: `hris_db`, user `hris_user`, password pola `hris-sitompul...`
- `.env`: `APP_ENV=production`, `APP_URL=https://hatopansitompulbdg.or.id`, session config benar
- Migrate+fresh seed SUKSES (produksi, bebas Faker)

### 4.3 Cloudflare
- Nameserver: `gordon.ns.cloudflare.com` + `sunny.ns.cloudflare.com` (di Niagahoster)
- A record `@` dan `www` → `93.127.129.98` (proxied)
- **Origin Rules**: 2 rule (root + www) → destination port `10070`
- SSL/TLS mode: **Flexible** (Cloudflare→origin HTTP; browser tetap HTTPS)
- Domain live: `https://hatopansitompulbdg.or.id` → `/admin` → login page 200

### 4.4 Masalah yang sudah diselesaikan selama sesi
1. Seeder Faker error (`Call to undefined function fake()`) → pisah DatabaseSeeder/DemoDataSeeder
2. `git pull` gagal filemode → `git config core.filemode false` di VPS (dan `safe.directory`)
3. `package-lock.json` untracked bentrok → di-commit ke repo + `git stash -u` di VPS
4. Certbot 404 (dulu) → ternyata 9router pegang port 80 → solusi Cloudflare
5. Login page 500 → `chown www-data:www-data storage bootstrap/cache`
6. Redirect `http://` → Trusted Proxies (komit `06fc038`)

---

## 5. TODOLIST LANJUTAN (besok)

### Prioritas 1 — Fix login loop
- [ ] Jalankan diagnosa A/B (bagian 3.3)
- [ ] Terapkan perbaikan sesuai hasil (SESSION_SECURE_COOKIE / purge cache CF / dll)
- [ ] Konfirmasi login masuk dashboard

### Prioritas 2 — Keamanan
- [ ] Ganti password admin (`admin@sitombung.test` / `password` → ganti)
- [ ] Set `APP_DEBUG=false` di VPS
- [ ] (Opsional) Cloudflare SSL → Full + Always Use HTTPS setelah stabil

### Prioritas 3 — CI/CD & production hardening
- [ ] Buat SSH key deploy (`ssh-keygen` untuk user `deploy`)
- [ ] Isi GitHub Secrets:
  - `VPS_HOST` = `93.127.129.98`
  - `VPS_USER` = `deploy`
  - `VPS_PORT` = `10062`  ← PENTING (NAT)
  - `VPS_SSH_KEY` = private key deploy
  - `VPS_PROJECT_PATH` = `/var/www/hris-sitombung/hris-sitompul` ← path lengkap
- [ ] Setup queue worker Supervisor + cron scheduler
- [ ] Tes `git push` → auto deploy via GitHub Actions

### Prioritas 4 — Opsional
- [ ] Pasang certbot/SSL asli di origin bila mau Full strict (tidak wajib, Flexible sudah cukup)
- [ ] Backup DB terjadwal

---

## 6. Cheat Sheet Perintah Penting

```bash
# SSH ke VPS (NAT port!)
ssh -p 10062 root@93.127.129.98

# Lokasi project
cd /var/www/hris-sitombung/hris-sitompul

# Pull & deploy manual
git config --global --add safe.directory /var/www/hris-sitombung/hris-sitompul
git pull origin main
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan migrate --force
php artisan config:clear && php artisan config:cache
sudo systemctl restart php8.3-fpm

# Tes Nginx internal (dengan simulasi Cloudflare https)
curl -s -H "Host: hatopansitompulbdg.or.id" -H "X-Forwarded-Proto: https" http://127.0.0.1:8081/ -o /dev/null -w "%{redirect_url}\n"

# Cek session tersimpan
php artisan tinker --execute="echo DB::table('sessions')->count();"
```

---

## 7. Akun & Kredensial yang Dipakai

> ⚠️ Catatan ini untuk kamu pribadi — jangan di-commit ke repo publik.

- **Login panel**: `admin@sitombung.test` / `password` (ganti segera setelah login beres)
- **DB VPS**: `hris_db` / `hris_user` / password pola `hris-sitompul...`
- **SSH VPS**: `root@93.127.129.98 -p 10062` (login root; user `deploy` juga ada)
- **Cloudflare**: domain di akun `dash.cloudflare.com` (SSL Flexible, Origin Rule port 10070)
- **Niagahoster**: nameserver sudah diarahkan ke Cloudflare

---

## 8. Status Ringkas untuk Dilaporkan Besok

```
✅ Aplikasi build + CI/CD + VPS setup + DB seed + Cloudflare + HTTPS
✅ https://hatopansitompulbdg.or.id  →  302 → /admin  →  login page 200
❌ LOGIN LOOP: sukses login (user/role/password terverifikasi BENAR,
   session tersimpan di DB) tapi tidak masuk dashboard.
   Diagnosa berhenti di: cek user_id session + Debug Network browser.
```
