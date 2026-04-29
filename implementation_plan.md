# Implementasi OOP, Namespace, dan Autoloading PSR-4

Permintaan Anda membutuhkan perombakan struktur (refactoring) yang cukup besar pada sistem. Tujuannya adalah membuat kode lebih terstruktur, modern, mudah di-_maintain_, dan mengikuti standar industri PHP (PSR-4).

## ⚠️ User Review Required

> [!WARNING]
> Proses transisi ini akan memodifikasi hampir semua file `.php` karena kita akan mengubah cara file-file tersebut saling memanggil (dari `require_once` manual menjadi _namespace_ dan _autoload_ otomatis lewat Composer). Pastikan Anda sudah mem-**backup** folder proyek sebelum kita mulai tahap eksekusi.

## Proposed Changes

---

### 1. Inisialisasi Composer & Setting PSR-4 Autoload
Kita akan menggunakan Composer untuk me-manage autoloading sesuai standar PSR-4.

#### [NEW] `composer.json`
Kita akan mendefinisikan _root namespace_ `App\` untuk proyek ini. Kita petakan direktori sebagai berikut:
- `App\Classes\` ➡️ `classes/`
- `App\Config\` ➡️ `config/`
- `App\Controllers\` ➡️ `controllers/` (Direktori baru untuk menangani logika alur aplikasi menggantikan OOP procedural di folder `process`)

Penerapan autoloader Composer dilakukan dengan perintah `composer dump-autoload`.
Semua file tidak perlu lagi melakukan `require_once 'classes/Auth.php'`, melainkan hanya:
```php
require_once __DIR__ . '/vendor/autoload.php';
use App\Classes\Auth;
```

---

### 2. Penerapan Namespace pada Class yang Sudah Ada
Class-class yang sudah ada akan diberikan blok `namespace` sesuai dengan pemetaannya.

#### [MODIFY] `classes/Auth.php`
- Tambahkan `namespace App\Classes;` di awal file.
- Sesuaikan import class lain, misalnya `use App\Config\Database;` dan `use App\Classes\ActivityLog;`.

#### [MODIFY] `classes/BhpManager.php`
- Tambahkan `namespace App\Classes;`
- Sisipkan `use PDO;` karena PDO adalah class bawaan (global namespace).

#### [MODIFY] `classes/UserManager.php` & `classes/ActivityLog.php`
- Tambahkan `namespace App\Classes;`
- Sesuaikan global imports (misal: `use PDO;`).

#### [MODIFY] `config/database.php`
- Menambahkan `namespace App\Config;`
- Sisipkan `use PDO;` dan `use PDOException;`.

---

### 3. Refactoring Folder `process/` menjadi Controller (Metode OOP Penuh)
Saat ini file di direktori `process/` (`bhp_process.php`, `login_process.php`, dll) masih berisi skrip semi-prosedural. Kita akan ubah logika ini ke dalam paradigma interaksi berbasis Objek (Class Controller).

#### [NEW] `controllers/BhpController.php`
- Membuat class `App\Controllers\BhpController`
- Menyediakan method `handleRequest()` yang otomatis membaca $_POST, dan mendelegasikannya ke `BhpManager`.

#### [NEW] `controllers/UserController.php`
- Membuat class `App\Controllers\UserController` dengan logic serupa.

#### [NEW] `controllers/AuthController.php`
- Membuat class `App\Controllers\AuthController` untuk menangani proses login dan logout.

#### [MODIFY] `process/bhp_process.php`, `process/user_process.php`, `process/login_process.php`
- Kini file ini hanya menjadi __endpoint__ *entry* (script eksekutor tunggal):
```php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Controllers\BhpController;

$controller = new BhpController();
$controller->handleRequest();
```

---

### 4. Updating Use Statements di Frontend / View
Kita juga harus memperbarui inisiasi Class di halaman yang memiliki antarmuka pengguna (`.php` files di folder `/admin/`, `/kepala_klinik/`, dll).

#### [MODIFY] Direktori & File View Utama (Misal: `admin/index.php`, `admin/contents/data_bhp.php`, dll)
- Mengganti `require_once` kustom dengan pemanggilan `vendor/autoload.php`.
- Menambahkan `use App\Classes\Auth;` atau `use App\Classes\BhpManager;` sesuai kebutuhan sebelum instansiasi `new Auth()` atau `new BhpManager()`.

## Open Questions

> [!IMPORTANT]
> 1. Apakah Anda setuju dengan pendekatan pembuatan **Controllers** untuk menggantikan kode prosedural yang ada di folder `process/` sehingga aplikasi benar-benar menerapkan OOP penuh?
> 2. Apakah Anda sudah memiliki `Composer` yang terinstal di _environment_ (Laragon) sistem Anda? (Biasanya Laragon sudah menyediakan `composer` bawaan).

## Verification Plan

### Automated / Command-line Verification
- Menjalankan `composer install` / `composer dump-autoload` untuk memastikan PSR-4 ter-binding dengan baik.
- Menjalankan `php -l` *linting checks* pada beberapa file class secara otomatis.

### Manual Verification
- Tim akan memverifikasi fungsi vital seperti fitur "Login", "Tambah Data BHP", dan "Log Aktivitas". Apabila fungsi-fungsi ini merespon dengan JSON/navigasi yang sukses dan tidak ada error `Class not found`, maka implementasi Autoload Standard berhasil.
