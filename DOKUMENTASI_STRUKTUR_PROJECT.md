# Dokumentasi Arsitektur & Struktur Proyek BHP Poli Gigi

Dokumen ini disusun untuk memudahkan pemahaman mengenai struktur direktori, teknologi yang digunakan, serta pola arsitektur aplikasi (Sistem Inventaris Bahan Habis Pakai Poli Gigi) untuk keperluan demonstrasi atau serah terima proyek.

---

## 1. Tumpukan Teknologi (Tech Stack)

Aplikasi ini dikembangkan menggunakan pendekatan modern dengan perpaduan teknologi berikut:

- **Backend**: PHP 8+ dengan pendekatan **Object-Oriented Programming (OOP)**.
- **Database**: MySQL dengan ekstensi **PDO (PHP Data Objects)** untuk keamanan dari SQL Injection.
- **Frontend (Styling)**: **Tailwind CSS** (via CDN/Compiled) untuk desain antarmuka yang responsif, modern, dan bernuansa premium.
- **Frontend (Interactivity)**: **Alpine.js** dan Vanilla JavaScript untuk menangani animasi UI (dropdown, modal, transisi) dan interaksi DOM ringan.
- **Autoloading**: **Composer** (PSR-4) digunakan untuk memuat file kelas (`classes/`) secara otomatis.

---

## 2. Pola Arsitektur (Architecture Pattern)

Aplikasi ini menggunakan pola **Hybrid Single Page Application (SPA)** berbasis Role (RBAC - _Role Based Access Control_):

1. **Pemisahan Modul per Role**: Terdapat folder khusus untuk setiap _role_ (`admin`, `dokter`, `kepala_klinik`). Keamanan dijaga pada tingkat routing folder; pengguna tidak bisa masuk ke folder _role_ lain.
2. **SPA Routing**: Di dalam masing-masing folder _role_, hanya ada satu file `index.php` sebagai kerangka utama (memuat Sidebar dan Topbar). Konten utama dipanggil secara dinamis (via _include_) ke dalam kontainer berdasarkan parameter URL `?page=...` (misal: `index.php?page=dashboard`).
3. **Struktur Layering (MVC-ish)**:
   - **Controller** (`controllers/`): Bertindak sebagai pengatur alur, menangani validasi input, manajemen session, dan keamanan request.
   - **Manager/Model** (`classes/`): Berisi logika bisnis inti dan interaksi langsung dengan database melalui PDO.
   - **View** (`contents/`): Fokus pada antarmuka pengguna (UI) dan presentasi data tanpa mencampurkan logika database di dalamnya.

---

## 3. Struktur Direktori Utama

Berikut adalah penjelasan mengenai peran masing-masing folder dalam proyek ini:

```text
BHP-Poli-Gigi/
├── admin/                  # Modul eksklusif untuk role Admin
├── dokter/                 # Modul eksklusif untuk role Dokter Gigi
├── kepala_klinik/          # Modul eksklusif untuk role Kepala Klinik
│   ├── index.php           # Entry point aplikasi untuk role tersebut (Layout & Router)
│   ├── components/         # Komponen UI spesifik role (seperti sidebar.php)
│   └── contents/           # File konten halaman spesifik role (dashboard.php, dll)
│
├── api/                    # Endpoint API
│   ├── export.php          # API Engine untuk mengekspor data (PDF, Excel)
│   └── index.php           # API Router sentral untuk request AJAX (bila digunakan)
│
├── assets/                 # Penyimpanan file statis (Front-end)
│   ├── css/style.css       # Custom CSS (override Tailwind, transisi spesifik, dll)
│   ├── js/main.js          # Skrip JavaScript utama global (alert handling, SPA utility)
│   └── img/                # Gambar, logo, dan aset visual
│
├── controllers/           # Logika kendali aplikasi (Intermediary View & Model)
│   ├── AuthController.php  # Validasi & alur proses login/logout
│   ├── BhpController.php   # Validasi data BHP sebelum masuk ke database
│   └── UserController.php  # Kendali aksi manajemen pengguna
│
├── classes/                # Folder inti berisi logika sistem (PHP OOP - PSR-4)
│   ├── Auth.php            # Menangani Autentikasi, Login, Enkripsi Password
│   ├── Database.php        # Wrapper koneksi database PDO (Singleton Pattern)
│   ├── BhpManager.php      # Menangani seluruh proses CRUD Data BHP & Kategori
│   ├── PemakaianManager.php# Menangani alur CRUD pemakaian stok oleh pasien
│   ├── UserManager.php     # Manajemen operasi kelola pengguna (Admin)
│   ├── DashboardManager.php# Mengolah data statistik kompleks untuk Dashboard
│   └── ActivityLog.php     # Sistem perekaman riwayat aktivitas seluruh user otomatis
│
├── components/             # Komponen global (Shared Components) yang bisa dipakai lintas-role
│   └── shared/
│       └── laporan.php     # UI halaman laporan gabungan yang seragam untuk semua role
│
├── config/                 # Konfigurasi aplikasi dasar
│   └── database.php        # Pengaturan kredensial akses Database (DB_HOST, DB_USER, dll)
│
├── process/                # (Opsional/Legacy) Skrip pemrosesan form non-OOP
├── vendor/                 # Kumpulan library eksternal hasil unduhan Composer
├── composer.json           # File manifest Composer untuk PSR-4 autoload mapping
├── Login.php               # Halaman Login Utama bagi seluruh pengguna
└── index.php               # Root redirector (Mengarahkan ke Login atau ke Dashboard role aktif)
```

---

## 4. Keunggulan Sistem untuk Demonstrasi

Saat Anda melakukan demonstrasi, Anda dapat menyoroti poin-poin fitur modern berikut:

1. **Security & Data Integrity**:
   - Penggunaan **PDO Prepared Statements** memastikan sistem aman 100% dari celah SQL Injection.
   - Pengecekan sesi berjenjang: Setiap halaman otomatis mendeteksi apakah _session_ masih aktif dan apakah _role_ sesuai.
2. **Activity Tracker Otomatis**: Kelas `ActivityLog.php` berfungsi layaknya "kotak hitam" (black box) pesawat yang secara otomatis mencatat _SIAPA_ yang melakukan _APA_ dan _KAPAN_, sangat vital untuk audit aplikasi klinis.
3. **UX Transisi Halus (View Transitions)**: Meskipun di-render oleh PHP, aplikasi ini terasa cepat layaknya React/Vue karena optimisasi transisi halaman di `style.css` dan interaksi `Alpine.js` tanpa harus _reload_ halaman secara kasar.
4. **Desain Kode Bersih (Clean Code)**: Peralihan dari gaya pemrograman prosedural lama menuju sistem OOP murni dengan standar **PSR-4 Autoloader**, membuat kode sangat rapi, tidak ada _include_ yang berulang-ulang, dan mudah dikembangkan oleh tim lain ke depannya.
