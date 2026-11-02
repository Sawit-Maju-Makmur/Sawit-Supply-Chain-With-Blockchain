<<<<<<< HEAD
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
=======
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> f017a484a6c468e69098875f0dd98e4f15c46ac3
