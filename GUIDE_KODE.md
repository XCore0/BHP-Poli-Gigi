# Panduan Dokumentasi Kode - Sistem Manajemen Klinik Poli Gigi

Dokumen ini berisi penjelasan mengenai struktur folder, alur kerja kode, dan fungsi-fungsi utama dalam proyek ini. Proyek ini menggunakan arsitektur **Object-Oriented Programming (OOP)** dengan standar **PSR-4 Autoloading** melalui Composer.

---

## 📂 Struktur Direktori

| Direktori | Penjelasan |
| :--- | :--- |
| `classes/` | Berisi logika inti aplikasi (Business Logic) dalam bentuk Class. |
| `config/` | Konfigurasi sistem, seperti koneksi database. |
| `controllers/` | Jembatan antara request user (proses) dan logika bisnis (classes). |
| `process/` | File router sederhana yang menerima data dari form/AJAX dan meneruskannya ke Controller. |
| `components/` | Bagian UI yang dapat digunakan kembali (Header, Sidebar, dll). |
| `assets/` | File statis seperti CSS (`assets/css`) dan JavaScript (`assets/js`). |
| `admin/` | Halaman dashboard dan fitur untuk role Admin. |
| `dokter/` | Halaman dashboard dan fitur untuk role Dokter. |
| `kepala_klinik/` | Halaman dashboard dan fitur untuk role Kepala Klinik. |
| `vendor/` | Library pihak ketiga dan sistem autoloading Composer. |

---

## 🛠️ Penjelasan Fungsi Utama (Core Classes)

### 1. `App\Config\Database` (config/database.php)
Menggunakan pola **Singleton** untuk memastikan hanya ada satu koneksi database yang aktif selama eksekusi program.
- `getInstance()`: Mengambil instance database tunggal.
- `getConnection()`: Mengembalikan objek PDO untuk interaksi database.

### 2. `App\Classes\Auth` (classes/Auth.php)
Menangani semua hal terkait keamanan dan sesi pengguna.
- `login($email, $password)`: Memvalidasi user, mencocokkan hash password (bcrypt), dan membuat session.
- `logout()`: Menghapus session dan mencatat log keluar.
- `requireLogin()`: Memastikan halaman hanya bisa diakses oleh user yang sudah login.
- `requireRole($roles)`: Memastikan user memiliki role tertentu (Admin/Dokter/Kepala Klinik) untuk mengakses halaman.

### 3. `App\Classes\BhpManager` (classes/BhpManager.php)
Mengelola data Bahan Habis Pakai (BHP), Satuan, dan Kategori.
- `getAllBhp()`: Mengambil semua data barang dengan relasi kategori dan satuan.
- `addBhp($data)` / `editBhp($id, $data)`: Menambah atau mengubah data barang.
- `deleteBhp($id)`: Menghapus data barang.
- `getAllSatuan()` / `getAllKategori()`: Mengambil data referensi untuk dropdown.

### 4. `App\Classes\UserManager` (classes/UserManager.php)
Mengelola data pengguna (akun).
- `getAllUsers()`: Mengambil daftar semua user.
- `addUser($data)`: Mendaftarkan user baru dengan password yang otomatis di-hash.
- `toggleStatus($id)`: Mengaktifkan atau menonaktifkan akun user.

### 5. `App\Classes\ActivityLog` (classes/ActivityLog.php)
Mencatat setiap aksi penting ke dalam database untuk keperluan audit.
- `log($userId, $nama, $role, $action, $details)`: Fungsi dasar pencatatan.
- `logLogin()` / `logLogout()`: Helper khusus untuk aksi masuk dan keluar.

---

## 🔄 Alur Kerja Data (Request Flow)

Aplikasi ini menggunakan alur yang terstandarisasi untuk menangani input dari user:

1. **Frontend (UI)**: User mengisi form di halaman (misal: `dokter/contents/stock.php`).
2. **AJAX / Form Submit**: Data dikirim ke file router di folder `process/` (misal: `process/bhp_process.php`).
3. **Controller**: Router memanggil method yang sesuai di `App\Controllers` (misal: `BhpController->handleAdd()`).
4. **Logic & Database**: Controller memanggil fungsi di `App\Classes` (misal: `BhpManager->addBhp()`) yang berinteraksi langsung dengan database melalui PDO.
5. **Response**: Hasil dikembalikan dalam bentuk JSON ke Frontend untuk ditampilkan sebagai notifikasi (SweetAlert2).

---

## 🔐 Keamanan
- **Password Hashing**: Menggunakan `password_hash()` dengan algoritma `PASSWORD_DEFAULT` (Bcrypt).
- **Prepared Statements**: Semua query database menggunakan PDO Prepared Statements untuk mencegah **SQL Injection**.
- **Session Protection**: Setiap file inti mengecek status login menggunakan `Auth->requireLogin()`.

---

## 📦 Autoloading
Proyek ini menggunakan **Composer Autoloader**. Anda tidak perlu melakukan `require` manual ke setiap file class. Cukup pastikan baris berikut ada di awal file utama:
```php
require_once __DIR__ . '/vendor/autoload.php';
```
Dan gunakan namespace yang sesuai:
```php
use App\Classes\Auth;
use App\Classes\BhpManager;
```
