# Sawit Maju Makmur — SCRUM-25 (Self-Assessment Sustainability & Traceability)

> Sebagai Pemasok/Petani Kelapa Sawit, saya ingin mengisi formulir mandiri (self-assessment)
> sustainability dan traceability sehingga tingkat kepatuhan praktik ramah lingkungan di kebun
> saya dapat dievaluasi.

Stack: **Laravel 12 · PHP 8.2+ · SQLite · Blade · CSS hijau-putih**.
Project sudah lengkap kecuali folder `vendor/` (dibuat oleh Composer). File `.env` dan
`database/database.sqlite` sudah disiapkan.

## Cara menjalankan (Windows / PowerShell)

1. Prasyarat: `php -v` (8.2 atau lebih baru) dan `composer -V`.
   Ekstensi aktif di `C:\xampp\php\php.ini` (hapus `;` di depannya): `pdo_sqlite`, `sqlite3`,
   `mbstring`, `openssl`, `fileinfo`, `zip`, `curl`.
2. Masuk ke folder project:
   ```powershell
   cd sawit-scrum25
   ```
3. Pasang dependency:
   ```powershell
   composer install
   ```
4. Jika `.env` tidak ada: `copy .env.example .env` lalu `php artisan key:generate`.
5. Pastikan `database/database.sqlite` ada (file kosong). Jika tidak:
   `New-Item database/database.sqlite -ItemType File`
6. Buat tabel:
   ```powershell
   php artisan migrate
   ```
   (jawab `yes` bila ditanya membuat database SQLite)
7. Jalankan server:
   ```powershell
   php artisan serve
   ```
   Buka http://127.0.0.1:8000

## Uji fitur
1. Klik **Isi Formulir**, isi data kebun, jawab 15 pernyataan, klik **Kirim & Evaluasi**.
2. Halaman hasil menampilkan persentase dan tingkat kepatuhan.
3. Menu **Riwayat** menampilkan semua pengisian; tombol **Hapus** menghapus data.
4. Kirim formulir kosong untuk memastikan validasi muncul.
5. Opsional: `php artisan test`

## Masalah umum
| Gejala | Solusi |
|--------|--------|
| `vendor/autoload.php not found` | Jalankan `composer install` |
| `could not find driver` | Aktifkan `pdo_sqlite` dan `sqlite3` di php.ini, buka ulang terminal |
| `No application encryption key` | `php artisan key:generate` |
| Port 8000 terpakai | `php artisan serve --port=8001` |
| Perubahan `.env` tidak terbaca | `php artisan config:clear` |

## Cara penilaian
Ya = 2 poin, Sebagian = 1 poin, Tidak = 0 poin.
`persentase = total poin / (jumlah pertanyaan x 2) x 100`

| Persentase | Tingkat Kepatuhan |
|-----------|-------------------|
| >= 80% | Tinggi |
| 60-79% | Sedang |
| < 60% | Rendah |

Pertanyaan, poin, dan batas level diatur di `config/assessment.php`.

## File fitur SCRUM-25
```
config/assessment.php
database/migrations/2026_10_26_000001_create_self_assessments_table.php
app/Models/SelfAssessment.php
app/Http/Requests/StoreSelfAssessmentRequest.php
app/Http/Controllers/SelfAssessmentController.php
routes/web.php
resources/views/layouts/app.blade.php
resources/views/assessments/{index,create,show}.blade.php
public/css/sawit.css
.env  (DB_CONNECTION=sqlite, APP_LOCALE=id)
```
