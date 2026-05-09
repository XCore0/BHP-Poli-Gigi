# 📂 Penjelasan Lengkap Setiap File — BHP Poli Gigi

Panduan ini menjelaskan **fungsi setiap file** secara detail agar mudah dipahami pemula.

---

## 📁 ROOT (Folder Utama)

### `index.php`
**Pintu masuk pertama website.**
Saat buka `localhost/BHP-Poli-Gigi`, file ini yang pertama dijalankan.
- Cek apakah user sudah login (via PHP session)
- Jika belum login → redirect ke halaman login
- Jika sudah login → arahkan ke dashboard sesuai role:
  - `admin` → `pages/admin/index.php`
  - `dokter` → `pages/dokter/index.php`
  - `kepala_klinik` → `pages/kepala_klinik/index.php`

### `composer.json`
**Daftar library PHP yang dipakai project.**
Seperti "daftar belanjaan" — memberi tahu Composer library apa saja yang perlu diunduh.
Isinya antara lain: dompdf (generate PDF), phpspreadsheet (generate Excel).

### `composer.lock`
**Kunci versi library.**
Mencatat versi PASTI setiap library agar semua orang mendapat versi yang sama.
> ⚠️ Jangan diubah manual!

### `db_poli_gigi.sql`
**File backup database.**
Berisi perintah SQL untuk membuat ulang semua tabel database dari awal.
Bisa di-import lewat phpMyAdmin.

### `migration_stok_pemakaian.sql`
**Script SQL tambahan.**
Membuat tabel `stok_masuk` dan `pemakaian_bhp` beserta `pemakaian_bhp_detail`.

### `migration_profil_pengaturan.sql`
**Script SQL tambahan.**
Menambah kolom foto profil, pengaturan, dan kolom-kolom baru ke tabel `user`.

---

## 📁 `config/`

### `config/database.php`
**Pengaturan koneksi ke MySQL.**
Menyimpan: nama host, nama database, username, password.
Menggunakan pola **Singleton** — koneksi ke database hanya dibuat SATU kali meskipun dipanggil berkali-kali. Lebih efisien.
Semua file PHP yang butuh akses database memanggil:
```php
$db = Database::getInstance()->getConnection();
```

---

## 📁 `classes/`

Folder ini adalah **otak aplikasi**. Semua logika bisnis (cara data diolah) ada di sini.

### `classes/Auth.php`
**Mengurus semua hal terkait login, logout, dan hak akses.**

| Fungsi | Tugasnya |
|---|---|
| `login($email, $pass)` | Cek email+password di database, simpan ke session jika cocok |
| `logout()` | Hapus semua session (user keluar) |
| `isLoggedIn()` | Cek apakah user sedang login — return true/false |
| `requireRole($role)` | Paksa role tertentu, redirect ke login jika role tidak sesuai |
| `getCurrentUser()` | Ambil array data user yang sedang login |
| `getRole()` | Ambil role user (admin / dokter / kepala_klinik) |

### `classes/BhpManager.php`
**Semua operasi data BHP di database.**

| Fungsi | Tugasnya |
|---|---|
| `getAllBhp($filter)` | Ambil semua BHP (dengan join ke kategori & satuan) |
| `getBhpById($id)` | Ambil 1 data BHP berdasarkan ID |
| `addBhp($data)` | Simpan BHP baru ke tabel `bhp` |
| `editBhp($id, $data)` | Update data BHP yang sudah ada |
| `deleteBhp($id)` | Hapus BHP (cek dulu apakah masih ada relasi) |
| `countBhp($filter)` | Hitung total BHP (dipakai untuk pagination) |

### `classes/StokMasukManager.php`
**Mengurus pencatatan penerimaan stok BHP dari supplier.**

| Fungsi | Tugasnya |
|---|---|
| `getAllStokMasuk($filter)` | Ambil semua riwayat stok masuk |
| `addStokMasuk($data, $userId)` | Simpan stok masuk + update `Jumlah` di tabel bhp + simpan `isi_per_stok` |
| `deleteStokMasuk($id)` | Hapus catatan + kembalikan stok ke jumlah sebelumnya |
| `countStokMasuk()` | Hitung total record (untuk pagination) |

### `classes/PemakaianManager.php`
**Mengurus pencatatan pemakaian BHP oleh dokter.**

Tabel yang terlibat ada 2:
- `pemakaian_bhp` — header (tanggal, nama pasien, dokter)
- `pemakaian_bhp_detail` — detail item BHP yang dipakai

| Fungsi | Tugasnya |
|---|---|
| `addPemakaian($data)` | Simpan header + semua detail pemakaian sekaligus |
| `getPemakaianList()` | Ambil riwayat pemakaian |
| `deletePemakaian($id)` | Hapus pemakaian + kembalikan stok BHP |

### `classes/UserManager.php`
**Mengurus data pengguna sistem.**

| Fungsi | Tugasnya |
|---|---|
| `getAllUsers()` | Ambil semua user |
| `addUser($data)` | Tambah user baru (password di-hash otomatis) |
| `editUser($id, $data)` | Update data user |
| `deleteUser($id)` | Hapus user |
| `changePassword($id, $old, $new)` | Ganti password (validasi password lama dulu) |
| `updateFoto($id, $path)` | Update path foto profil |

### `classes/ActivityLog.php`
**Mencatat semua aktivitas penting ke tabel `log_aktivitas`.**
Setiap kali user tambah/edit/hapus data, log otomatis tersimpan berisi:
waktu, nama user, role, aksi apa, detail, IP address.

---

## 📁 `controllers/`

Controller menerima perintah dari form/AJAX, lalu mendelegasikan ke class yang tepat.

### `controllers/AuthController.php`
Menerima POST dari form login → memanggil `Auth::login()` → redirect sesuai hasil.

### `controllers/BhpController.php`
Menerima semua request AJAX untuk data BHP.
Membaca `$_POST['action']`:
- `add_bhp` → panggil `BhpManager::addBhp()`
- `edit_bhp` → panggil `BhpManager::editBhp()`
- `delete_bhp` → panggil `BhpManager::deleteBhp()`

Kirim response JSON ke browser.

### `controllers/StokMasukController.php`
Menerima POST untuk input stok masuk.
Memanggil `StokMasukManager::addStokMasuk()` atau `deleteStokMasuk()`.

### `controllers/PemakaianController.php`
Menerima POST untuk catat pemakaian BHP.
Memanggil `PemakaianManager` untuk simpan header + detail pemakaian.

### `controllers/UserController.php`
Menerima POST untuk kelola data pengguna.
Aksi yang didukung: `add_user`, `edit_user`, `delete_user`, `toggle_status`.

### `controllers/ProfilController.php`
Menerima POST untuk update profil & ganti password.
Memanggil `UserManager::editUser()` dan `changePassword()`.

---

## 📁 `process/`

File-file ini sangat kecil — hanya menjadi **pintu masuk dari form HTML**.

### `process/bhp_process.php`
Endpoint POST untuk form BHP → load autoload → buat `BhpController` → `handleRequest()`

### `process/login_process.php`
Endpoint POST untuk form login → memanggil `AuthController`.

### `process/pemakaian_process.php`
Endpoint POST untuk form catat pemakaian → memanggil `PemakaianController`.

### `process/stok_masuk_process.php`
Endpoint POST untuk form input stok masuk → memanggil `StokMasukController`.

### `process/user_process.php`
Endpoint POST untuk form kelola pengguna → memanggil `UserController`.

### `process/profil_process.php`
Endpoint POST untuk form edit profil & ganti password → memanggil `ProfilController`.

---

## 📁 `api/`

### `api/export.php`
**Generate dan download file PDF atau Excel.**
Dipanggil saat user klik tombol Export di halaman manapun.

Parameter URL yang diterima:
- `?type=pdf` atau `?type=excel`
- `?page=bhp|laporan|stok|pengguna|log`
- `?tgl_mulai=...&tgl_akhir=...&keyword=...`

Yang dilakukan:
1. Ambil data dari database sesuai `page`
2. Jika `type=excel` → buat file .xlsx dengan PhpSpreadsheet
3. Jika `type=pdf` → buat HTML → render ke PDF dengan DomPDF
4. **Khusus laporan pemakaian PDF** → template dokumen resmi (kop surat klinik + area tanda tangan)

---

## 📁 `assets/`

### `assets/css/style.css`
CSS tambahan di luar Tailwind. Berisi animasi custom, style scrollbar, dan class utility yang tidak tersedia di Tailwind.

### `assets/js/tailwind-config.js`
**WAJIB di-load sebelum Tailwind CSS.**
Mendefinisikan warna brand (hijau klinik `#006B47`), font family, dan ukuran kustom.
Tanpa file ini, class seperti `bg-brand-500` tidak akan bekerja.

### `assets/js/main.js`
JavaScript global aktif di semua halaman.
Berisi: logika sidebar mobile (buka/tutup), toggle submenu dropdown, animasi transisi halaman, dan fungsi-fungsi umum.

### `assets/js/login.js`
JavaScript khusus halaman login.
Berisi: toggle show/hide password, efek animasi form saat submit.

### `assets/uploads/`
Folder penyimpanan foto profil user yang diupload.
Path foto disimpan di database, file fisiknya ada di folder ini.

---

## 📁 `pages/auth/`

### `pages/auth/login.php`
**Halaman form login.**
Tampilan: form email + password dengan desain premium (gradient, animasi).
Saat submit → POST ke `process/login_process.php`.

### `pages/auth/logout.php`
**Proses logout.**
Hapus semua session → redirect ke halaman login.
Tidak ada tampilan, langsung eksekusi kode.

---

## 📁 `pages/components/`

Komponen UI yang dipakai bersama oleh semua role.

### `pages/components/header.php`
**Navbar bagian atas** yang muncul di semua halaman.
Berisi: logo klinik, judul halaman aktif, nama + avatar user yang login, tombol logout.
Di-include oleh masing-masing `pages/[role]/index.php`.

### `pages/components/footer.php`
**Footer bagian bawah halaman.**
Berisi teks copyright dan versi aplikasi.

### `pages/components/loader.php`
**Animasi loading** yang muncul sebentar saat halaman pertama dibuka.
Menggunakan CSS animation — otomatis hilang setelah halaman selesai dimuat browser.

### `pages/components/shared/laporan.php`
**Halaman laporan pemakaian BHP** — dipakai bersama semua role.
Berisi:
- Header banner hijau dengan tombol Export PDF & Excel
- 3 stats cards (total record, total pasien, jenis BHP)
- Filter tanggal + keyword
- Tabel riwayat pemakaian (7 kolom: tanggal, BHP, jumlah, satuan, kondisi, pasien/unit, dokter)
- Grid kartu per pasien

Di-include oleh: `admin/contents/laporan.php`, `dokter/contents/laporan.php`, `kepala_klinik/contents/laporan.php`

### `pages/components/shared/laporan_stok.php`
**Halaman laporan stok masuk** — read-only.
Berisi tabel semua riwayat penerimaan stok dengan filter tanggal & keyword.
Di-include oleh semua role.

### `pages/components/shared/profil.php`
**Halaman profil pengguna.**
Fitur: ubah nama, email, nomor telepon, upload foto profil.
Dipakai bersama oleh semua role.

### `pages/components/shared/pengaturan.php`
**Halaman pengaturan akun.**
Fitur: ganti password (butuh password lama), preferensi notifikasi.
Dipakai bersama oleh semua role.

---

## 📁 `pages/admin/`

### `pages/admin/index.php`
**ROUTER utama area admin.**
Yang dilakukan (urutan):
1. Cek login & role = `admin` (jika bukan → redirect ke login)
2. Set variabel `$page_title`, `$active_page` berdasarkan `?page=xxx`
3. Load HTML: head + CSS + JS
4. Include `loader.php` (animasi loading)
5. Include `header.php` (navbar atas)
6. Include `sidebar.php` (menu kiri)
7. Include `contents/{$page}.php` (konten utama)
8. Include `footer.php`

### `pages/admin/components/sidebar.php`
**Menu navigasi kiri khusus admin.**
Menu yang tersedia:
- Dashboard
- Data BHP (dropdown: Data BHP / Kategori / Satuan)
- Stock Masuk
- Laporan Pemakaian
- Kelola Pengguna
- Profil
- Pengaturan

Highlight menu aktif berdasarkan variabel `$active_page`.

### `pages/admin/contents/dashboard.php`
**Beranda admin.**
Menampilkan:
- Stats cards: total BHP, stok menipis, total user aktif, total pemakaian bulan ini
- Daftar BHP yang stoknya menipis (kurang dari threshold)
- Aktivitas terbaru dari log

### `pages/admin/contents/data_bhp.php`
**Halaman kelola data BHP untuk admin.**
Fitur:
- Tabel semua BHP dengan filter kategori + keyword + pagination
- Kolom: Kode, Nama, Kategori, Satuan, Stok, Pemakaian, Status, Aksi
- Modal tambah BHP (nama, kode, kategori, satuan)
- Modal edit BHP
- Tombol hapus dengan konfirmasi
- Tombol Export PDF / Excel
- Semua aksi dilakukan via JavaScript AJAX (tanpa reload halaman)

### `pages/admin/contents/kategori_bhp.php`
**Halaman kelola kategori BHP.**
Fitur: tabel kategori, modal tambah/edit, hapus kategori.
Kode kategori di-generate otomatis dari nama (misal "Obat Antiseptik" → "OA").

### `pages/admin/contents/satuan_bhp.php`
**Halaman kelola satuan BHP** (Botol, Ampul, Box, Lembar, dll).
Fitur: tabel satuan, modal tambah/edit, hapus satuan.

### `pages/admin/contents/stock.php`
Hanya 1 baris: `include` ke `shared/laporan_stok.php`.
Admin bisa melihat laporan stok masuk.

### `pages/admin/contents/laporan.php`
Hanya 1 baris: `include` ke `shared/laporan.php`.
Admin bisa melihat laporan pemakaian BHP.

### `pages/admin/contents/pengguna.php`
**Halaman kelola pengguna sistem — EKSKLUSIF admin.**
Fitur:
- Tab "Kelola Pengguna": tabel semua user, tambah user (nama, email, password, role, foto), edit, hapus, toggle aktif/nonaktif
- Tab "Log Aktivitas": tabel semua log aktivitas dengan filter
- File ini besar karena berisi banyak modal dan logika kompleks

### `pages/admin/contents/profil.php`
1 baris — `include shared/profil.php`.

### `pages/admin/contents/pengaturan.php`
1 baris — `include shared/pengaturan.php`.

---

## 📁 `pages/dokter/`

### `pages/dokter/index.php`
**ROUTER utama area dokter.**
Sama strukturnya dengan `admin/index.php` tapi:
- Hanya mengizinkan role = `dokter`
- Daftar halaman yang tersedia berbeda (tidak ada kelola pengguna)
- Warna avatar dan label berbeda

### `pages/dokter/components/sidebar.php`
**Menu navigasi kiri khusus dokter.**
Menu: Dashboard, Data BHP, Catat Pemakaian, Stock Masuk, Laporan Pemakaian, Laporan Stok, Profil, Pengaturan.

### `pages/dokter/contents/dashboard.php`
**Beranda dokter.**
Menampilkan: ringkasan stok BHP, BHP yang menipis/habis, riwayat pemakaian terakhir.

### `pages/dokter/contents/data_bhp.php`
**Halaman data BHP untuk dokter.**
Fitur: tabel BHP + filter + pencarian, modal tambah BHP, modal edit BHP, hapus BHP.
Tombol aksi: Edit (biru) dan Hapus (merah) saja.

### `pages/dokter/contents/catat.php`
**Form catat pemakaian BHP per tindakan medis.**
Fitur:
- Pilih satu atau lebih BHP + jumlah yang dipakai
- Isi nama pasien, unit/tindakan, kondisi BHP setelah dipakai, catatan
- Submit → stok BHP otomatis berkurang

### `pages/dokter/contents/stock.php`
**Form input stok masuk BHP + tabel riwayat.**
Fitur:
- Form modal: pilih BHP, isi jumlah, isi per stok (akan update `isi_per_stok` di tabel bhp), tanggal terima, supplier, tanggal kadaluarsa, catatan
- Tabel riwayat stok masuk dengan hapus record
- Saat simpan → stok BHP otomatis bertambah

### `pages/dokter/contents/laporan.php`
1 baris — `include shared/laporan.php`.

### `pages/dokter/contents/laporan_stok.php`
1 baris — `include shared/laporan_stok.php`.

### `pages/dokter/contents/kategori_bhp.php`
Halaman kategori BHP versi dokter (fitur sama dengan admin).

### `pages/dokter/contents/satuan_bhp.php`
Halaman satuan BHP versi dokter.

### `pages/dokter/contents/profil.php`
1 baris — `include shared/profil.php`.

### `pages/dokter/contents/pengaturan.php`
1 baris — `include shared/pengaturan.php`.

---

## 📁 `pages/kepala_klinik/`

### `pages/kepala_klinik/index.php`
**ROUTER utama area kepala klinik.**
Hanya mengizinkan role = `kepala_klinik`. Menu fokus pada laporan (read-only).

### `pages/kepala_klinik/contents/dashboard.php`
**Beranda kepala klinik.**
Menampilkan ringkasan data BHP dan grafik pemakaian — hanya bisa dilihat, tidak bisa edit.

### `pages/kepala_klinik/contents/laporan.php`
1 baris — `include shared/laporan.php`.

### `pages/kepala_klinik/contents/laporan_stok.php`
1 baris — `include shared/laporan_stok.php`.

### `pages/kepala_klinik/contents/log.php`
**Halaman log aktivitas sistem.**
Menampilkan semua aktivitas yang dilakukan semua user: siapa melakukan apa, kapan, dari IP mana.
Bisa filter berdasarkan keyword, kategori aksi, dan role user.

### `pages/kepala_klinik/contents/profil.php`
1 baris — `include shared/profil.php`.

### `pages/kepala_klinik/contents/pengaturan.php`
1 baris — `include shared/pengaturan.php`.

---

## 📁 `vendor/`

> ⚠️ Jangan pernah ubah isi folder ini secara manual!

### `vendor/autoload.php`
**File ajaib yang WAJIB di-include di setiap file PHP.**
Satu baris ini mengaktifkan SEMUA library sekaligus:
```php
require_once __DIR__ . '/../../vendor/autoload.php';
```

### `vendor/dompdf/`
Library untuk generate file PDF dari HTML. Dipakai di `api/export.php`.

### `vendor/phpoffice/phpspreadsheet/`
Library untuk generate file Excel (.xlsx). Dipakai di `api/export.php`.

---

## 🔑 Ringkasan Pola yang Selalu Berulang

Hampir semua fitur mengikuti pola yang sama:

```
[Halaman PHP]          → tampilkan form / tabel
      ↓ (user klik simpan)
[process/xxx.php]      → terima POST dari browser
      ↓
[controllers/Xxx.php]  → routing berdasarkan action
      ↓
[classes/XxxManager]   → operasi database (SQL)
      ↓
[JSON response]        → dikirim balik ke browser
      ↓
[JavaScript]           → tampilkan notif + update tampilan
```

| Suffix file | Artinya |
|---|---|
| `Manager.php` | Yang pegang dan olah data (SQL) |
| `Controller.php` | Yang atur lalu lintas request |
| File di `process/` | Pintu masuk dari form HTML |
| File di `contents/` | Tampilan halaman |
| File di `shared/` | Tampilan yang dipakai bersama semua role |
