## Alur Akses Projectnya
# Exit Form

Web untuk mengelola proses serah-terima pekerjaan karyawan yang akan meninggalkan perusahaan. Web ini menyediakan akses berbeda untuk Admin, Approver, dan Employee.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- Node.js dan npm
- SQLite (konfigurasi awal) atau MySQL

## Instalasi Lokal

Pastikan PHP, Composer, Node.js, dan npm sudah terpasang. Dari PowerShell, jalankan:

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
```

Konfigurasi `.env` menggunakan SQLite secara default. Buat file database lokal sebelum migrasi:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

Jika menggunakan MySQL, ubah `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`, lalu pastikan databasenya sudah dibuat.

Jalankan migrasi dan data demo, build asset, lalu mulai server:

```powershell
php artisan migrate --seed
npm run build
php artisan serve
```

Buka URL yang ditampilkan oleh `artisan serve`, biasanya `http://127.0.0.1:8000`.

Untuk pengembangan frontend dengan Vite, jalankan `npm run dev` di terminal terpisah saat server Laravel aktif.

## Akun Demo

Seeder menyediakan akun berikut. Ketiganya menggunakan password demo `password`:

| Role | Email |
| --- | --- |
| Admin | `admin@exitform.test` |
| Approver | `approver@exitform.test` |
| Employee | `shella@exitform.test` |
**Setiap akun Approver dan Employee itu memasukan email yang dibuat oleh admin**

Akun baru yang dibuat Admin juga mendapat password awal `password`. Password awal ini hanya untuk pengembangan lokal; aplikasi belum menyediakan fitur ganti atau reset password. Jangan gunakan kredensial demo atau password tersebut di produksi.

## Alur Penggunaan

1. Login dengan email akun. Role diambil dari data akun, bukan dipilih melalui URL. Satu akun memiliki satu role; beberapa akun boleh menggunakan role yang sama.
2. Admin mengelola akun melalui menu **User**. Saat membuat Employee, Admin mengisi email, mengatur **Allowed**, dan memilih Approver. Klik NIK pada tabel untuk melihat atau memperbarui data user.
3. Employee login dengan email yang didaftarkan. Form yang dibuat akan terhubung ke profil Employee tersebut.
4. Employee mengisi form lalu memilih **Save as Draft** atau **Submit**. Draft belum masuk antrean Approver.
5. Approver membuka menu **Serah Terima** untuk melihat form Submitted dari Employee yang ditugaskan kepadanya, lalu memilih **Approve** atau **Reject**.
6. Form berstatus Approved dapat dibuka sebagai PDF. Admin dapat membuat form Draft untuk Employee dan menghapus form yang masih Draft.

## Menu dan Batasan Fitur

- **Admin**: User, Exit Clearance, dan Serah Terima.
- **Approver**: Serah Terima untuk form dari Employee yang ditugaskan kepadanya.
- **Employee**: Serah Terima untuk membuat form miliknya.
- **Exit Clearance** saat ini masih berupa placeholder; alur clearance belum tersedia.
- Fitur Reopen untuk mengembalikan form Rejected ke Draft belum tersedia.

## Pengujian

Jalankan test Laravel dengan:

```powershell
php artisan serve
```

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
