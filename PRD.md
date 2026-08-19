# PRD — Sistem Manajemen Karyawan (SITOMBUNG HRM)

| Field | Value |
|---|---|
| **Nama Produk** | SITOMBUNG — Sistem Manajemen Karyawan |
| **Versi Dokumen** | 1.0 |
| **Tanggal** | 2026-08-18 |
| **Status** | Draft untuk Review |
| **Platform** | Web (Responsive, desktop-first) |

---

## 1. Ringkasan Eksekutif

SITOMBUNG adalah sistem manajemen karyawan (Employee Management System / HRM) berbasis web yang mengelola seluruh siklus hidup data kepegawaian: **data master (departemen & jabatan), profil karyawan, absensi, cuti & izin, penggajian, serta laporan**. Sistem dibangun di atas **Laravel 13** dengan panel admin **Filament 5** agar pengembangan cepat, konsisten, dan aman (RBAC), dengan tetap mengikuti best practice Laravel modern (Pest testing, Form Requests, Policies, Queues, Laravel Pint, Laravel Sail).

Sistem ditujukan untuk menggantikan pengelolaan data karyawan berbasis spreadsheet/manual menjadi sistem terpusat yang auditable, terstruktur, dan dapat diekspor ke laporan.

---

## 2. Tujuan & Sasaran (Goals)

1. **Sentralisasi data karyawan** — satu sumber kebenaran (single source of truth) untuk seluruh data kepegawaian.
2. **Efisiensi operasional HR** — mengurangi pekerjaan manual, duplikasi, dan risiko kehilangan data.
3. **Kontrol akses berbasis peran (RBAC)** — setiap pengguna hanya mengakses data sesuai perannya.
4. **Audit trail lengkap** — seluruh perubahan data tercatat (siapa, kapan, apa) untuk kepatuhan dan investigasi.
5. **Laporan akurat & dapat diekspor** — mendukung pengambilan keputusan manajemen.

### Sasaran Terukur (KPIs)
- Waktu input data karyawan baru < 5 menit (dengan validasi otomatis).
- 100% data karyawan aktif terkelola dalam sistem dalam 3 bulan setelah go-live.
- 100% transaksi absensi/cuti tercatat otomatis tanpa catatan manual ganda.
- Waktu penyusunan laporan < 10 menit (sebelumnya bisa 1–2 hari).

---

## 3. Stakeholders & Persona

| Persona | Deskripsi | Kebutuhan Utama |
|---|---|---|
| **Super Admin** | Pengelola sistem & infrastruktur | Kelola pengguna, peran, izin; konfigurasi master; lihat semua data |
| **HR Admin** | Staf HRD | CRUD karyawan, kelola absensi/cuti/penggajian, input & approve data |
| **Manager / Atasan** | Pimpinan unit | Persetujuan cuti, lihat laporan tim, review kinerja |
| **Karyawan** | Pegawai umum | Lihat profil sendiri, pengajuan cuti, riwayat gaji, status absensi |

---

## 4. Ruang Lingkup

### 4.1 In Scope
- Manajemen pengguna, peran & izin (RBAC)
- Manajemen departemen & jabatan
- Manajemen profil karyawan (pribadi, kepegawaian, pendidikan, keluarga, dokumen)
- Absensi & kehadiran (pencatatan manual / import)
- Cuti & izin (pengajuan, approval workflow, saldo cuti)
- Penggajian dasar (komponen gaji, slip gaji, history)
- Dashboard ringkasan (widget statistik)
- Laporan & ekspor (CSV/Excel/PDF)
- Audit trail & activity log
- Notifikasi in-app

### 4.2 Out of Scope (fase berikutnya)
- Payroll terintegrasi penuh dengan perhitungan PPh/BJPS otomatis
- Integrasi mesin fingerprint / biometric
- Rekrutmen & onboarding, training, appraisal penilaian kinerja (KPI scoring)
- Self-service mobile app (dapat berupa PWA di fase 2)
- SSO / integrasi ERP eksternal

---

## 5. Fitur & Functional Requirements

### 5.1 Autentikasi & Otorisasi (RBAC)

| ID | Requirement | Prioritas |
|---|---|---|
| FR-01 | Login/Logout dengan email & password (session, proteksi brute-force & rate limiting). | P0 |
| FR-02 | Fitur reset/lupa password (email) dan verifikasi email. | P1 |
| FR-03 | Peran (roles): **Super Admin, HR Admin, Manager, Karyawan** dengan izin (permissions) per-modul. | P0 |
| FR-04 | Panel Filament hanya dapat diakses oleh pengguna dengan role/izin yang sesuai (`FilamentUser` + `strictAuthorization`). | P0 |
| FR-05 | Pengaturan: aktif/nonaktif akun; karyawan nonaktif tidak bisa login. | P1 |

### 5.2 Dashboard

| ID | Requirement | Prioritas |
|---|---|---|
| FR-10 | Widget ringkasan: total karyawan aktif, karyawan kontrak habis bulan ini, absensi hari ini (hadir/izin/cuti/alpha), saldo cuti pending. | P0 |
| FR-11 | Grafik tren: rekrutmen per bulan, komposisi karyawan per departemen & status kepegawaian. | P1 |
| FR-12 | Konten dashboard menyesuaikan peran (Manager/Karyawan hanya melihat lingkupnya). | P1 |

### 5.3 Master: Departemen

| ID | Requirement | Prioritas |
|---|---|---|
| FR-20 | CRUD departemen: `kode`, `nama`, `parent (sub-departemen)`, `kepala departemen (relasi ke karyawan)`, `deskripsi`, `aktif`. | P0 |
| FR-21 | Kode departemen unik & tidak dapat diubah jika sudah dipakai data. | P1 |
| FR-22 | Hapus departemen dibatasi (soft delete) jika masih memiliki karyawan aktif. | P1 |

### 5.4 Master: Jabatan / Posisi

| ID | Requirement | Prioritas |
|---|---|---|
| FR-30 | CRUD jabatan: `kode`, `nama`, `tingkat (level)`, `departemen terkait`, `deskripsi`, `aktif`. | P0 |
| FR-31 | Validasi: nama jabatan unik per tingkat; prevent delete jika dipakai karyawan. | P1 |

### 5.5 Manajemen Karyawan (fitur inti)

| ID | Requirement | Prioritas |
|---|---|---|
| FR-40 | CRUD karyawan: data pribadi, kontak, alamat, NIK, tempat/tanggal lahir, jenis kelamin, agama, status pernikahan, foto. | P0 |
| FR-41 | Data kepegawaian: `no_pegawai (NIP)` otomatis & unik, tanggal bergabung, status (Tetap/Kontrak/Magang), tanggal selesai kontrak, departemen, jabatan, atasan langsung. | P0 |
| FR-42 | Riwayat jabatan/mutasi (jabatan, departemen, periode, catatan). | P1 |
| FR-43 | Pendidikan: jenjang, institusi, jurusan, tahun lulus, IPK. | P1 |
| FR-44 | Keluarga: pasangan, anak (nama, hubungan, tanggal lahir). | P2 |
| FR-45 | Dokumen: scan KTP, KK, ijazah, kontrak, SK (upload file). | P1 |
| FR-46 | Status karyawan: aktif / cuti / nonaktif / keluar (resign, pensiun, PHK) dengan tanggal efektif & alasan. | P0 |
| FR-47 | Filter & pencarian lanjutan (nama, NIP, departemen, jabatan, status). | P0 |
| FR-48 | Ekspor data karyawan (Excel/CSV) sesuai filter. | P1 |
| FR-49 | Usia kerja & masa kerja dihitung otomatis. | P2 |

### 5.6 Absensi & Kehadiran

| ID | Requirement | Prioritas |
|---|---|---|
| FR-60 | Pencatatan absensi per tanggal: Hadir, Izin, Cuti, Sakit (dengan keterangan), Alpha/bolos. | P0 |
| FR-61 | Import absensi massal (Excel) untuk integrasi mesin fingerprint fase berikutnya. | P1 |
| FR-62 | Rekap absensi per bulan per karyawan: total hadir, sakit, izin, cuti, alpha, keterlambatan. | P0 |
| FR-63 | Prevent duplikasi entri absen pada tanggal yang sama (kecuali ada izin khusus). | P1 |
| FR-64 | Karyawan hanya dapat melihat absensi miliknya; HR/Manager melihat lingkupnya. | P0 |

### 5.7 Cuti & Izin (Approval Workflow)

| ID | Requirement | Prioritas |
|---|---|---|
| FR-70 | Master jenis cuti: `nama`, `kuota tahunan (hari)`, `dibayar/tidak`, `maksimum pengajuan`. | P0 |
| FR-71 | Pengajuan cuti oleh karyawan: tanggal mulai/selesai, alasan, lampiran; otomatis menghitung jumlah hari kerja. | P0 |
| FR-72 | Approval workflow: **Karyawan → Atasan (Manager) → HR Admin**. Notifikasi di tiap tahap. | P0 |
| FR-73 | Saldo cuti tahunan: kuota di-reset per tahun, berkurang saat cuti disetujui. | P0 |
| FR-74 | Status: Draft, Menunggu, Disetujui, Ditolak, Dibatalkan. Ditolak/Dibatalkan mengembalikan saldo. | P0 |
| FR-75 | Pengajuan ditolak wajib mengisi alasan. | P1 |
| FR-76 | Prevent pengajuan cuti melebihi saldo & bentrok tanggal. | P1 |

### 5.8 Penggajian (Dasar)

| ID | Requirement | Prioritas |
|---|---|---|
| FR-80 | Master komponen gaji: `nama`, `tipe (tunjangan/potongan)`, `jumlah tetap`. | P1 |
| FR-81 | Setup gaji karyawan: gaji pokok, tunjangan, potongan per karyawan dengan periode berlaku. | P1 |
| FR-82 | Generate slip gaji bulanan (per periode) berdasarkan komponen & absensi (potongan alpha). | P1 |
| FR-83 | Karyawan melihat slip gaji sendiri; HR mengelola semua. | P1 |
| FR-84 | Ekspor rekap penggajian per periode (Excel/PDF). | P2 |

### 5.9 Audit Trail & Log

| ID | Requirement | Prioritas |
|---|---|---|
| FR-90 | Seluruh aksi CRUD pada data sensitif (karyawan, gaji, cuti, absensi) tercatat: user, aksi, model, atribut lama→baru, waktu, IP. | P0 |
| FR-91 | Log dapat difilter & dilihat hanya oleh Super Admin/HR. | P1 |

### 5.10 Notifikasi

| ID | Requirement | Prioritas |
|---|---|---|
| FR-100 | Notifikasi in-app (Filament Notification): permintaan cuti baru, status approval berubah, kontrak hampir habis, pengingat absensi. | P1 |
| FR-101 | Notifikasi email untuk approval & reset password (via queue). | P1 |

---

## 6. User Stories Prioritas (Contoh)

1. Sebagai **HR Admin**, saya dapat membuat data karyawan baru dengan NIP otomatis sehingga data langsung tercatat tanpa duplikasi.
2. Sebagai **Karyawan**, saya dapat mengajukan cuti dan memantau status approval tanpa menunggu proses manual.
3. Sebagai **Manager**, saya dapat menyetujui/menolak cuti bawahan dan melihat rekap tim.
4. Sebagai **Super Admin**, saya dapat membatasi akses HR hanya ke modul tertentu melalui role & permission.
5. Sebagai **HR Admin**, saya dapat mengekspor rekap absensi bulanan untuk diserahkan ke pihak terkait.

---

## 7. Non-Functional Requirements (NFR)

| Aspek | Requirement |
|---|---|
| **Performa** | Halaman list utama < 1,5 detik (TTFB < 500ms pada data ≤ 50.000 karyawan); query memakai eager loading & pagination; indeks pada kolom filter umum. |
| **Keamanan** | HTTPS wajib; hash password (bcrypt/argon2id); proteksi CSRF; rate limiting login & API; validasi input server-side (Form Request); mass-assignment protection; RBAC + `strictAuthorization`. |
| **Keandalan** | Soft delete untuk data master & karyawan; job queue (default database) untuk email/notifikasi; backup database terjadwal. |
| **Ketersediaan** | Target 99,5% uptime. |
| **Usability** | UI konsisten via Filament; label Bahasa Indonesia; pesan validasi jelas; aksesibilitas dasar (keyboard navigation). |
| **Kompatibilitas** | Browser modern terbaru (Chrome, Edge, Firefox); responsif untuk tablet. |
| **Skalabilitas** | Cache (database/Redis) untuk master data; siap scale-out dengan queue worker & Redis. |
| **Maintainability** | Kode ber-strict types; Laravel Pint (code style); Pest tests; dokumentasi teknis ringkas. |

---

## 8. Data Model (Rancangan)

```
users (id, name, email, password, role, is_active, timestamps)
departments (id, kode, nama, parent_id?, kepala_karyawan_id?, deskripsi, aktif)
positions (id, kode, nama, level, department_id?, deskripsi, aktif)
employees (id, user_id?, no_pegawai, nama_lengkap, nik, tempat_lahir, tanggal_lahir,
           jenis_kelamin, agama, status_pernikahan, alamat, no_telp, email_pribadi,
           foto, status_kepegawaian, tanggal_bergabung, tanggal_kontrak_selesai,
           department_id, position_id, atasan_id, tanggal_keluar?, alasan_keluar?
           [soft deletes])
employee_educations (id, employee_id, jenjang, institusi, jurusan, tahun_lulus, ipk)
employee_families (id, employee_id, nama, hubungan, tanggal_lahir)
employee_documents (id, employee_id, tipe, nama_file, path, keterangan)
employee_position_histories (id, employee_id, position_id, department_id, mulai, selesai, catatan)
leave_types (id, nama, kuota_tahunan, dibayar, maks_pengajuan, aktif)
leaves (id, employee_id, leave_type_id, tanggal_mulai, tanggal_selesai, jumlah_hari,
        alasan, lampiran, status, approved_by?, approved_at?, reason_rejected?)
attendances (id, employee_id, tanggal, status [hadir/izin/sakit/cuti/alpha], keterangan, jam_masuk?, jam_keluar?)
salary_components (id, nama, tipe [tunjangan/potongan], jumlah, aktif)
employee_salaries (id, employee_id, component_id, jumlah, berlaku_dari, berlaku_sampai?)
payslips (id, employee_id, periode, total, detail json, status)
activity_logs (id, log_name, causer_id, subject_type, subject_id, description, properties, created_at)
```

### Relasi Utama
- `employees` 1–1 `users` (opsional)
- `employees` N–1 `departments`, N–1 `positions`, N–1 `employees` (atasan)
- `employees` 1–N `employee_educations`, `employee_families`, `employee_documents`, `employee_position_histories`
- `employees` 1–N `leaves`, `attendances`, `employee_salaries`, `payslips`
- `users` 1–N `activity_logs`

---

## 9. Tech Stack

| Layer | Pilihan | Catatan |
|---|---|---|
| **Language** | PHP 8.3+ | Wajib untuk Laravel 13 |
| **Framework** | Laravel 13.x | Rilis terbaru; AI-native primitives, cache/queue database default |
| **Admin Panel** | Filament 5.x | PHP 8.2+/Laravel 11.28+; panel, resources, widgets, notifications |
| **Frontend** | Livewire v4 + Alpine.js + Tailwind CSS v4 | Bundle default Filament; Vite sebagai build tool |
| **Database** | MySQL 8.0 / MariaDB 10.6+ | Indeks pada kolom filter & foreign keys |
| **Cache & Queue** | database (default) → Redis | Meningkat seiring skala |
| **Auth & RBAC** | Laravel Auth + `spatie/laravel-permission` + `bezhansalleh/filament-shield` | Generator roles/permissions |
| **Audit Trail** | `spatie/laravel-activitylog` | Record perubahan model |
| **Upload Dokumen** | Laravel Storage (local/S3) + validasi tipe/ukuran | Filament FileUpload |
| **Testing** | Pest PHP + PHPUnit | Feature & unit tests; factories & seeders |
| **Code Quality** | Laravel Pint, Larastan (opsional) | CI gate |
| **Dev Environment** | Laravel Sail (Docker) | MySQL, Redis, Mailpit |
| **CI/CD** | GitHub Actions | pint --test, tests, deploy (Enoymer/Palzin) |
| **Laporan/Ekspor** | Filament + `maatwebsite/excel` (opsional) atau native CSV | CSV/Excel/PDF |

> **Versi final dipatok saat implementasi** via `composer.json`: `laravel/framework: ^13.0`, `filament/filament: ^5.0`.

---

## 10. Arsitektur & Best Practice

### 10.1 Prinsip
1. **Thin controllers, fat models, focused services** — logika bisnis (mis. generate NIP, hitung saldo cuti, potong saldo) di dalam **Eloquent models / Service classes / Actions**, bukan di controller/resource.
2. **Validation di Form Request** — semua input diverifikasi server-side; aturan didefinisikan di resource Filament (`rules`) dan Form Request.
3. **Authorization via Policies** — setiap model punya policy (`viewAny`, `view`, `create`, `update`, `delete`, `restore`); Filament `->strictAuthorization()` memastikan tidak ada akses yang luput.
4. **Model events / Observers** — untuk efek samping (mis. `EmployeeObserver::created` → buat user + kirim notifikasi). Seeder memakai `WithoutModelEvents` agar tidak trigger efek samping saat seeding.
5. **Soft delete** — karyawan, departemen, jabatan, cuti, payslip; memudahkan restore & audit.
6. **Eager loading & indexing** — relasi di-`with()`, pagination di semua list, index pada `foreign_id`, `tanggal`, `status`.
7. **Queue** — semua email/notifikasi via queue job (`dispatch()->onQueue()`), default driver `database`.
8. **Cache** — master data (departemen, jabatan, jenis cuti) di-cache dengan invalidasi event.

### 10.2 Struktur Direktori yang Disarankan
```
app/
  Actions/            # Business actions (GenerateEmployeeNumber, ApproveLeave, UpdateLeaveBalance)
  Models/             # Eloquent models
  Observations/       # Observers
  Policies/           # Per-model policies
  Filament/
    Resources/        # EmployeeResource, LeaveResource, ...
      Schemas/        # Form & Table schema classes (code-quality tips)
    Pages/
    Widgets/          # Dashboard widgets
  Services/           # PayrollService, ReportService (opsional)
database/
  factories/ migrations/ seeders/   # RolesAndPermissionsSeeder, DemoDataSeeder
tests/  Feature/  Unit/
```

### 10.3 Alur Implementasi Modul Filament
1. `composer require filament/filament:"^5.0"` → `php artisan filament:install --panels`
2. `php artisan make:filament-resource Employee --generate` → sesuaikan form/table.
3. Pasang **Shield** → generate roles/permissions dari resource.
4. Pisahkan skema form/table ke `Schemas/` saat resource mulai besar (reusability).
5. Tambahkan halaman kustom & widgets untuk dashboard, approval page untuk cuti.

---

## 11. Security Checklist

- [ ] Semua akses panel melewati `FilamentUser::canAccessPanel` + `strictAuthorization()`
- [ ] RBAC aktif di semua resource (Shield + Policies)
- [ ] Rate limiting pada login & endpoint publik
- [ ] Upload dokumen divalidasi MIME & ukuran; nama file di-randomize; serving via storage dengan kontrol akses
- [ ] Enkripsi HTTPS; `.env` tidak pernah masuk repo
- [ ] Password hashed (argon2id/bcrypt); audit log tidak boleh dihapus user biasa
- [ ] Form input `disabled` (bukan `readonly`) untuk field yang tidak boleh diubah klien
- [ ] Mass assignment: semua field model di `$guarded`/`$fillable` eksplisit

---

## 12. Testing Strategy

| Jenis | Contoh |
|---|---|
| **Unit** | HITUNG: NIP generator, saldo cuti, perhitungan hari kerja, komponen gaji |
| **Feature** | CRUD employee, workflow approval cuti (peran, otorisasi), restore saldo saat cuti ditolak, RBAC (karyawan tidak akses modul HR), import absensi |
| **Pest + RefreshDatabase** | `tests/Feature/EmployeeTest.php`, `tests/Feature/LeaveWorkflowTest.php` |
| **Factories & Seeders** | `UserFactory`, `EmployeeFactory`, `DatabaseSeeder` berisi data demo |
| **Code Style** | `vendor/bin/pint --test` (wajib hijau di CI) |
| **Static Analysis** | Larastan level 5 (opsional tahap awal) |

Perintah wajib sebelum merge:
```bash
./vendor/bin/pint --test
php artisan test
```

---

## 13. Deployment & DevOps

1. **Lokal** — Laravel Sail: `./vendor/bin/sail up` (MySQL, Redis, Mailpit), `./vendor/bin/sail artisan migrate --seed`.
2. **CI (GitHub Actions)** — jobs: `composer install`, `pint --test`, `php artisan test`, `npm run build`.
3. **Production** — PHP-FPM 8.3 + Nginx, MySQL/Redis managed, queue worker (`php artisan queue:work`), scheduler cron (`php artisan schedule:run`), storage symlink, optimasi (`config:cache`, `route:cache`, `view:cache`).
4. **Backup** — dump database terjadwal (mis. `spatie/laravel-backup`) ke S3.
5. **Monitoring** — log via Laravel logging + error tracker (Sentry/Flares), health check endpoint.

---

## 14. Milestones & Roadmap

| Fase | Durasi | Deliverable |
|---|---|---|
| **M1 — Fondasi** | 1 minggu | Setup Laravel 13 + Sail, Filament panel, RBAC (Shield), auth, baseline theme, Pint & Pest CI |
| **M2 — Data Master** | 1–2 minggu | Resource Departemen & Jabatan, seeders demo, audit log |
| **M3 — Karyawan** | 2–3 minggu | Resource Employee (pribadi, kepegawaian, pendidikan, keluarga, dokumen, riwayat jabatan), NIP otomatis, ekspor |
| **M4 — Absensi** | 1–2 minggu | Resource absensi, rekap bulanan, import Excel, widget dashboard |
| **M5 — Cuti & Izin** | 2 minggu | Workflow approval, saldo tahunan, notifikasi, page approval |
| **M6 — Penggajian** | 2 minggu | Komponen gaji, slip gaji, rekap ekspor |
| **M7 — Laporan & UAT** | 1 minggu | Laporan agregat, penerimaan pengguna, UAT, bug fix, go-live |

**Total estimasi: ± 10–13 minggu.**

---

## 15. Acceptance Criteria (Contoh)

- **AC-1 (RBAC):** Karyawan role tidak dapat membuka halaman/URL resource HR dan menerima 403.
- **AC-2 (NIP):** Pembuatan karyawan baru selalu menghasilkan `no_pegawai` unik tanpa input manual.
- **AC-3 (Cuti):** Saat cuti disetujui, saldo berkurang otomatis; saat ditolak/dibatalkan, saldo kembali; tidak bisa mengajukan melebihi saldo.
- **AC-4 (Absensi):** Tidak ada duplikat absensi per karyawan per tanggal; rekap bulanan akurat secara aritmetika.
- **AC-5 (Audit):** Setiap update karyawan menghasilkan 1 baris activity log dengan diff atribut.
- **AC-6 (Performa):** List karyawan 10.000 record dengan filter dapat dimuat < 2 detik (pagination).
- **AC-7 (Testing):** CI hijau: `pint --test` dan seluruh test Pest lulus.

---

## 16. Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Scope creep (payroll penuh, integrasi mesin absen) | Telat rilis | Fase berikutnya; lock scope M1–M7 |
| Data kotor hasil migrasi spreadsheet | Data tidak akurat | Template import + validasi + data cleansing di M3/M4 |
| Kesalahan perhitungan saldo cuti | Konflik HR | Unit test menyeluruh + rule engine sederhana |
| Karyawan non-teknis kesulitan | Adopsi rendah | Pelatihan, label Bahasa Indonesia, UAT di M7 |
| Dependency (Filament v5 masih rilis baru) | Breaking change | Pin versi, ikuti upgrade guide, kontrak di composer.lock |

---

## 17. Glossary

| Istilah | Definisi |
|---|---|
| **NIP / No. Pegawai** | Nomor induk pegawai, unik, otomatis (format: `SIT-YYYY-NNNN`) |
| **RBAC** | Role-Based Access Control — kontrol akses berbasis peran |
| **Slip Gaji** | Rekap komponen gaji & potongan per karyawan per periode |
| **Saldo Cuti** | Kuota cuti tahunan yang tersisa per karyawan |
| **Alpha** | Absensi tanpa keterangan (bolos) |
| **Soft Delete** | Hapus logis; data tetap tersimpan & bisa di-restore |
| **UAT** | User Acceptance Testing — pengujian penerimaan pengguna |

---

## 18. Lampiran (Referensi Implementasi)

- Install Filament 5: `composer require filament/filament:"^5.0"` → `php artisan filament:install --panels`
- Scaffold resource: `php artisan make:filament-resource Employee --generate`
- Shield (RBAC): `composer require bezhansalleh/filament-shield` → `php artisan shield:install`
- Aktivitas: `composer require spatie/laravel-activitylog`
- Ekspor: CSV native / `maatwebsite/excel`
- Standar kode: `./vendor/bin/pint`, Larastan
- Dev: Laravel Sail (Docker) + Mailpit
