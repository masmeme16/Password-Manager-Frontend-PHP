# Password Manager - Frontend (PHP/HTML/CSS/JS)

README ini menjelaskan cara menjalankan, konfigurasi, dan mem-deploy frontend sederhana yang berkomunikasi dengan API Go Anda di `http://localhost:8080`.

**Ringkasan**
- Stack: PHP (server-side rendering), HTML, CSS, JavaScript
- Lokasi file penting: lihat `File Structure` di bawah
- API backend (Go) harus berjalan di: `http://localhost:8080`

**File Structure (frontend)**

- `index.php` — halaman login
- `register.php` — halaman pendaftaran user
- `dashboard.php` — halaman utama setelah login
- `users.php` — manajemen user (list / edit / delete)
- `pass.php` — manajemen password (create / list / edit / delete). Kini menyertakan `pass_vendor_id`.
- `vendor.php` — manajemen vendor (create / list / delete)
- `api.php` — helper PHP untuk memanggil API (menggunakan cURL). Ubah `base` jika diperlukan.
- `auth.php` — helper session sederhana (login/logout)
- `assets/style.css` — stylesheet dasar
- `assets/app.js` — helper JS kecil (konfirmasi hapus)

Prerequisites
- PHP 7.4+ atau PHP 8.x terpasang pada mesin pengembang
- Go API backend Anda berjalan pada `http://localhost:8080` (sesuai README backend)

Running locally (development)

1. Pastikan Go API berjalan di `http://localhost:8080`.
2. Jalankan PHP built-in server dari folder frontend:

```powershell
cd 'C:\Users\Bgs\Documents\Password-Manager-Frontend-PHP'
php -S localhost:8000
```

3. Buka browser ke `http://localhost:8000/` untuk melihat halaman login.

Configuration / Kustomisasi
- Jika API backend berjalan di host/port lain, buka `api.php` dan ubah variabel `$base` di fungsi `api_request` ke URL yang sesuai.
- Autentikasi saat ini menggunakan pendekatan sederhana: `index.php` mengambil semua user melalui `/api/users` dan mencocokkan `user_username` & `user_password` lalu menyimpan data user ke `$_SESSION`. Jika Anda ingin memakai token (JWT / session token) di masa mendatang, ubah `api.php` untuk mengirim header `Authorization: Bearer <token>` dan perbarui login flow untuk menerima token.

Perilaku terkait `pass_vendor_id`
- `pass.php` sekarang:
  - Mengambil daftar vendor dari endpoint `/api/vendor` dan menampilkan `select` untuk `pass_vendor_id` saat membuat dan mengedit password.
  - Menyertakan `pass_vendor_id` pada payload `POST /api/pass` (create) dan `PUT /api/pass/{id}` (update).
  - Menampilkan nama vendor di tabel password jika tersedia, atau menampilkan ID vendor sebagai fallback.

Mapping halaman frontend ↔ API endpoints (default)

- Create user: `POST /api/users` (dipanggil oleh `register.php`)
- Get all users: `GET /api/users` (dipanggil oleh `index.php`, `users.php`, `pass.php`)
- Create password: `POST /api/pass` (dipanggil oleh `pass.php`)
- Update password: `PUT /api/pass/{id}` (dipanggil oleh `pass.php`)
- Delete password: `DELETE /api/pass/{id}` (dipanggil oleh `pass.php`)
- Create vendor: `POST /api/vendor` (dipanggil oleh `vendor.php`)
- Get vendors: `GET /api/vendor` (dipanggil oleh `vendor.php`, `pass.php`)

Deployment / Menaruh ke GitHub

1. Pastikan repository Git telah diinisialisasi (jika belum):

```powershell
cd 'C:\Users\Bgs\Documents\Password-Manager-Frontend-PHP'
git init
```

2. Buat `.gitignore` sederhana (direkomendasikan) dan tambahkan file yang tidak ingin di-commit, misal session/storage lain:

```
# PHP sessions, OS files
.DS_Store
/vendor/
/node_modules/

# IDE
.vscode/

/.env
```


Notes / Security
- Password saat ini dikirim apa adanya ke backend dan disimpan/ditampilkan kembali karena API backend contoh menyimpan `user_password` secara plain. Sangat disarankan untuk:
  - Menyimpan password user dengan hashing yang aman (backend).
  - Tidak menampilkan password mentah di UI (hanya memungkinkan reset atau tampilkan dengan toggle yang jelas jika memang dibutuhkan).
  - Menambahkan CSRF protection untuk form sensitif (saat memakai session-based auth).
  - Memindahkan autentikasi ke mekanisme token (JWT) atau session server-side yang lebih aman.

Next steps (opsional)
- Tambahkan autentikasi nyata di backend (login endpoint) lalu perbarui `index.php` untuk meminta token.
- Perbaiki UX: gunakan AJAX untuk submit form tanpa reload dan tambahkan validasi client-side.
- Tambah fitur search / filter / pagination untuk daftar panjang.

Kontak / Dukungan
- Jika Anda ingin, saya bisa membantu:
  - Memindahkan login ke token-based auth
  - Membuat AJAX frontend (fetch API) yang lebih interaktif
  - Menulis test script untuk memverifikasi endpoints

---
File README frontend ini dibuat untuk memudahkan integrasi dan deploy di masa mendatang.
