# 🔄 Alur CRUD Lengkap — BHP Poli Gigi

Penjelasan alur **Create, Read, Update, Delete** beserta file yang terlibat di setiap langkah.

---

## 🧠 Konsep Dasar Dulu

Setiap aksi di aplikasi ini selalu melewati **4 lapisan** ini:

```
┌─────────────────────────────────────────────────────────┐
│  LAPISAN 1: TAMPILAN (pages/.../contents/*.php)         │
│  → User melihat tabel dan mengisi form di sini          │
├─────────────────────────────────────────────────────────┤
│  LAPISAN 2: PENERIMA FORM (process/*.php)               │
│  → Menerima data POST dari browser                      │
├─────────────────────────────────────────────────────────┤
│  LAPISAN 3: PENGATUR AKSI (controllers/*.php)           │
│  → Menentukan: ini mau tambah? edit? hapus?             │
├─────────────────────────────────────────────────────────┤
│  LAPISAN 4: OLAH DATA (classes/*.php)                   │
│  → Eksekusi SQL ke database MySQL                       │
└─────────────────────────────────────────────────────────┘
```

---

## 📋 CRUD DATA BHP

### ✅ CREATE — Tambah BHP Baru

**Siapa yang bisa:** Admin & Dokter

```
USER klik tombol "+ Tambah BHP"
│
│  File: pages/dokter/contents/data_bhp.php
│  (Modal form muncul — isi nama, kode, kategori, satuan)
│
▼
USER klik "Simpan Barang"
│
│  JavaScript di data_bhp.php kirim data via fetch() ke:
│
▼
process/bhp_process.php
│  Hanya 3 baris kode:
│  - require vendor/autoload.php
│  - new BhpController()
│  - handleRequest()
│
▼
controllers/BhpController.php
│  Baca: $_POST['action'] === 'add_bhp'
│  Cek: user sudah login? role boleh tambah BHP?
│  Panggil: BhpManager::addBhp($_POST)
│
▼
classes/BhpManager.php  ← fungsi addBhp()
│  1. Bersihkan input (trim spasi, dll)
│  2. Validasi: nama tidak boleh kosong
│  3. Cek: kode BHP sudah dipakai belum? (SELECT)
│  4. Eksekusi SQL:
│     INSERT INTO bhp (Nama_bhp, Kode_bhp, id_kategori, id_satuan, ...)
│     VALUES (?, ?, ?, ?, ...)
│  5. Return: ['success' => true, 'message' => 'BHP berhasil ditambahkan']
│
▼
controllers/BhpController.php
│  Catat ke log: ActivityLog::log('Tambah BHP', ...)
│  Kirim: echo json_encode(['success' => true, ...])
│
▼
JavaScript di data_bhp.php
│  Terima JSON response
│  Jika sukses:
│    - Tampilkan toast notifikasi hijau "Berhasil!"
│    - Tutup modal form
│    - Reload halaman (agar tabel refresh)
```

---

### 📖 READ — Tampilkan Daftar BHP

**Siapa yang bisa:** Admin & Dokter

```
USER buka URL: ?page=data_bhp
│
▼
pages/dokter/index.php  (ROUTER)
│  Baca: $_GET['page'] = 'data_bhp'
│  Eksekusi: include 'contents/data_bhp.php'
│
▼
pages/dokter/contents/data_bhp.php  ← bagian atas file PHP
│  1. Load BhpManager
│  2. Baca filter dari URL: keyword, id_kategori, page number
│  3. Panggil: BhpManager::getAllBhp($filter)
│  4. Panggil: BhpManager::countBhp($filter)  (untuk pagination)
│
▼
classes/BhpManager.php  ← fungsi getAllBhp()
│  Eksekusi SQL:
│  SELECT b.*, k.Nama_kategori, s.Nama_satuan,
│         CASE WHEN b.Jumlah <= 0 THEN 'Habis' ...
│  FROM bhp b
│  LEFT JOIN kategori_bhp k ON b.id_kategori = k.id_kategori
│  LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan
│  WHERE (filter jika ada)
│  ORDER BY b.id_bhp DESC
│  LIMIT 15 OFFSET (halaman * 15)
│
▼
pages/dokter/contents/data_bhp.php  ← bagian HTML tabel
│  Loop $bhpList → render baris tabel satu per satu:
│  - Kolom: Kode, Nama, Kategori, Satuan, Stok, Pemakaian, Status, Aksi
│  - Badge status warna: Habis (merah), Menipis (kuning), Aman (hijau)
│
▼
BROWSER menampilkan halaman lengkap:
  header.php + sidebar.php + TABEL BHP + footer.php
```

---

### ✏️ UPDATE — Edit Data BHP

**Siapa yang bisa:** Admin & Dokter

```
USER klik tombol ✏️ (Edit) di baris BHP tertentu
│
│  File: pages/dokter/contents/data_bhp.php
│  JavaScript fungsi editBhp(data):
│    - Ambil data BHP dari atribut onclick (data sudah ada di HTML)
│    - Isi semua input di modal form dengan data tersebut
│    - Ubah judul modal jadi "Edit BHP"
│    - Tampilkan modal
│
▼
USER ubah data → klik "Simpan Barang"
│
│  JavaScript kirim data via fetch() ke:
│
▼
process/bhp_process.php
│  (sama seperti CREATE)
│
▼
controllers/BhpController.php
│  Baca: $_POST['action'] === 'edit_bhp'
│  Baca: $_POST['id_bhp'] (ID yang diedit)
│  Cek: user sudah login? role boleh edit?
│  Panggil: BhpManager::editBhp($id, $_POST)
│
▼
classes/BhpManager.php  ← fungsi editBhp()
│  1. Validasi: nama tidak boleh kosong
│  2. Cek: kode baru sudah dipakai BHP lain? (SELECT)
│  3. Eksekusi SQL:
│     UPDATE bhp
│     SET Nama_bhp=?, Kode_bhp=?, id_kategori=?, id_satuan=?
│     WHERE id_bhp=?
│  4. Return: ['success' => true, 'message' => 'BHP berhasil diperbarui']
│
▼
JavaScript di data_bhp.php
│  Jika sukses → toast "Berhasil diperbarui!" → reload halaman
```

---

### 🗑️ DELETE — Hapus BHP

**Siapa yang bisa:** Admin & Dokter

```
USER klik tombol 🗑️ (Hapus) di baris BHP
│
│  File: pages/dokter/contents/data_bhp.php
│  JavaScript fungsi deleteBhp(id, nama):
│    - Tampilkan konfirmasi: "Yakin hapus [nama]?"
│    - Jika user klik "Ya" → kirim fetch() ke process/bhp_process.php
│
▼
process/bhp_process.php
│
▼
controllers/BhpController.php
│  Baca: $_POST['action'] === 'delete_bhp'
│  Panggil: BhpManager::deleteBhp($id)
│
▼
classes/BhpManager.php  ← fungsi deleteBhp()
│  1. Cek: apakah BHP masih ada di stok_masuk atau pemakaian_bhp_detail?
│     (Jika masih ada → TOLAK dengan pesan error)
│  2. Jika aman → Eksekusi SQL:
│     DELETE FROM bhp WHERE id_bhp = ?
│  3. Return: ['success' => true] atau ['success' => false, 'message' => '...']
│
▼
JavaScript di data_bhp.php
│  Jika sukses → toast "Berhasil dihapus!" → hapus baris dari tabel (tanpa reload)
│  Jika gagal → toast merah "Tidak bisa dihapus: masih ada riwayat pemakaian"
```

---

## 📦 CRUD STOK MASUK

### ✅ CREATE — Input Stok Masuk Baru

```
USER klik "+ Input Stok Masuk"
│
│  File: pages/dokter/contents/stock.php
│  Modal form muncul, berisi:
│    - Pilih BHP (dropdown — onchange tampilkan info isi per stok)
│    - Jumlah stok yang diterima
│    - Isi per stok (misal: 1 box = 10 pcs)
│    - Tanggal terima, supplier, tanggal kadaluarsa, catatan
│
▼
USER klik "Simpan Stok"
│
│  JavaScript fetch() ke:
│
▼
process/stok_masuk_process.php
│
▼
controllers/StokMasukController.php
│  Baca action → Panggil StokMasukManager::addStokMasuk()
│
▼
classes/StokMasukManager.php  ← fungsi addStokMasuk()
│  Dalam satu TRANSAKSI database (jika 1 gagal, semua dibatalkan):
│  
│  1. Validasi input
│  2. Cek BHP ada di database
│  3. Jika isi_per_stok berubah:
│     UPDATE bhp SET isi_per_stok = ? WHERE id_bhp = ?
│  4. Simpan catatan stok masuk:
│     INSERT INTO stok_masuk (id_bhp, jumlah, tanggal_terima, ...)
│  5. Update jumlah stok BHP:
│     UPDATE bhp
│     SET Jumlah = Jumlah + [jumlah],
│         Pemakaian = Pemakaian + [jumlah × isi_per_stok]
│     WHERE id_bhp = ?
│  6. COMMIT (simpan semua perubahan)
│
▼
Hasil: Stok BHP bertambah, riwayat stok masuk tercatat
```

### 🗑️ DELETE — Hapus Catatan Stok Masuk

```
USER klik 🗑️ di baris stok masuk
│
▼
classes/StokMasukManager.php  ← fungsi deleteStokMasuk()
│  Dalam satu TRANSAKSI:
│  1. Ambil data stok yang akan dihapus (jumlah & isi_per_stok)
│  2. Kurangi kembali stok BHP:
│     UPDATE bhp
│     SET Pemakaian = GREATEST(0, Pemakaian - [jumlah × isi_per_stok]),
│         Jumlah = FLOOR(GREATEST(0, Pemakaian - ...) / isi_per_stok)
│     WHERE id_bhp = ?
│  3. Hapus record stok masuk:
│     DELETE FROM stok_masuk WHERE id_stok_masuk = ?
│  4. COMMIT
```

---

## 💉 CRUD PEMAKAIAN BHP

### ✅ CREATE — Catat Pemakaian

```
USER buka halaman Catat Pemakaian (?page=catat)
│
│  File: pages/dokter/contents/catat.php
│  Tampilan: form dengan daftar BHP + qty per item
│
▼
USER isi: nama pasien, unit tindakan, pilih BHP + jumlah → klik "Simpan"
│
▼
process/pemakaian_process.php
│
▼
controllers/PemakaianController.php
│
▼
classes/PemakaianManager.php  ← fungsi addPemakaian()
│  Dalam satu TRANSAKSI:
│  
│  1. Simpan header pemakaian:
│     INSERT INTO pemakaian_bhp
│     (id_user, tanggal, nama_pasien, unit_tindakan, kondisi, catatan)
│     → dapat id_pemakaian (misalnya: 42)
│  
│  2. Loop setiap item BHP yang dipakai:
│     INSERT INTO pemakaian_bhp_detail
│     (id_pemakaian, id_bhp, jumlah, kondisi)
│     VALUES (42, [id_bhp], [jumlah], ...)
│     
│     UPDATE bhp
│     SET Jumlah = Jumlah - [jumlah]
│     WHERE id_bhp = ?
│  
│  3. COMMIT
│
▼
Hasil:
  - Tabel pemakaian_bhp: 1 baris baru (header)
  - Tabel pemakaian_bhp_detail: N baris baru (tiap BHP)
  - Tabel bhp: stok berkurang sesuai pemakaian
```

### 📖 READ — Lihat Laporan Pemakaian

```
USER buka ?page=laporan
│
▼
pages/dokter/contents/laporan.php
│  1 baris: include shared/laporan.php
│
▼
pages/components/shared/laporan.php  ← bagian PHP atas
│  Query 1 — Riwayat detail (join 4 tabel):
│  SELECT p.tanggal, p.nama_pasien, p.unit_tindakan,
│         d.jumlah, d.kondisi,
│         b.Nama_bhp, s.Nama_satuan,
│         u.Nama_lengkap AS nama_dokter
│  FROM pemakaian_bhp_detail d
│  JOIN pemakaian_bhp p ON d.id_pemakaian = p.id_pemakaian
│  JOIN bhp b ON d.id_bhp = b.id_bhp
│  LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan
│  LEFT JOIN user u ON p.id_user = u.id_user
│  WHERE p.tanggal BETWEEN ? AND ?
│  ORDER BY p.created_at DESC
│  LIMIT 10 OFFSET ?
│
│  Query 2 — Ringkasan per pasien (untuk kartu pasien):
│  SELECT p.nama_pasien, b.Nama_bhp, SUM(d.jumlah)
│  FROM pemakaian_bhp p
│  JOIN pemakaian_bhp_detail d ON d.id_pemakaian = p.id_pemakaian
│  GROUP BY p.nama_pasien, b.id_bhp
│
▼
HTML: tampilkan tabel + kartu per pasien
```

---

## 👤 CRUD PENGGUNA (Admin Only)

### ✅ CREATE — Tambah User Baru

```
Admin klik "+ Tambah Pengguna"
│
│  File: pages/admin/contents/pengguna.php
│  Modal form: nama, email, password, role, jenis kelamin, no telp, foto
│
▼
USER submit → JavaScript fetch() ke process/user_process.php
│
▼
controllers/UserController.php
│  action = 'add_user'
│  Panggil: UserManager::addUser($_POST, $_FILES)
│
▼
classes/UserManager.php  ← fungsi addUser()
│  1. Validasi: email unik? password cukup kuat?
│  2. Hash password: password_hash($password, PASSWORD_BCRYPT)
│     (password TIDAK disimpan dalam bentuk aslinya)
│  3. Simpan foto profil ke assets/uploads/ (jika ada)
│  4. INSERT INTO user (Nama_lengkap, Email, Password_hash, Role, ...)
│  5. Return sukses/gagal
```

### ✏️ UPDATE — Edit User

```
classes/UserManager.php  ← fungsi editUser()
│  UPDATE user
│  SET Nama_lengkap=?, Email=?, Role=?, No_telp=?, ...
│  WHERE id_user = ?
│
│  Jika ada foto baru:
│  - Hapus foto lama dari server
│  - Simpan foto baru
│  - Update path di database
```

### 🔐 Ganti Password

```
USER isi: password lama, password baru, konfirmasi
│
▼
controllers/ProfilController.php
│  Panggil: UserManager::changePassword()
│
▼
classes/UserManager.php  ← fungsi changePassword()
│  1. Ambil password_hash user dari database
│  2. Verifikasi: password_verify($passwdLama, $hash)
│     Jika salah → TOLAK
│  3. Hash password baru: password_hash($passwdBaru)
│  4. UPDATE user SET Password_hash = ? WHERE id_user = ?
```

### 🗑️ DELETE — Hapus User

```
classes/UserManager.php  ← fungsi deleteUser()
│  1. Cek: user tidak boleh hapus dirinya sendiri
│  2. Cek: masih ada data relasi? (pemakaian, stok, dll)
│  3. Hapus foto profil dari server (jika ada)
│  4. DELETE FROM user WHERE id_user = ?
```

---

## 🔑 LOGIN & LOGOUT

### Login

```
USER buka localhost/BHP-Poli-Gigi
│
▼
index.php
│  Belum login → redirect ke pages/auth/login.php
│
▼
pages/auth/login.php
│  Tampilkan form email + password
│
▼
USER submit → POST ke process/login_process.php
│
▼
controllers/AuthController.php
│  Panggil: Auth::login($email, $password)
│
▼
classes/Auth.php  ← fungsi login()
│  1. SELECT * FROM user WHERE Email = ? AND Status = 'aktif'
│  2. Jika email tidak ditemukan → ['success' => false]
│  3. password_verify($password, $row['Password_hash'])
│     Jika tidak cocok → ['success' => false]
│  4. Jika cocok:
│     $_SESSION['user_id']   = $row['id_user']
│     $_SESSION['user_role'] = $row['Role']
│     $_SESSION['user_nama'] = $row['Nama_lengkap']
│  5. Return ['success' => true, 'role' => 'dokter']
│
▼
AuthController.php
│  Redirect ke dashboard sesuai role:
│  - admin → /pages/admin/index.php
│  - dokter → /pages/dokter/index.php
│  - kepala_klinik → /pages/kepala_klinik/index.php
```

### Logout

```
USER klik tombol Logout di header
│
▼
pages/auth/logout.php
│  session_start()
│  session_destroy()  ← hapus SEMUA data session
│  header('Location: /BHP-Poli-Gigi/pages/auth/login.php')
│  exit()
```

### Proteksi Halaman (requireRole)

```
Setiap file pages/[role]/index.php baris paling atas:
│
│  $auth->requireRole('dokter', '/BHP-Poli-Gigi/pages/auth/Login.php')
│
▼
classes/Auth.php  ← fungsi requireRole()
│  1. Cek: $_SESSION ada? User sudah login?
│     Tidak → redirect ke login
│  2. Cek: $_SESSION['user_role'] === role yang diminta?
│     Tidak → redirect ke login
│  3. Jika semua OK → lanjutkan eksekusi halaman
```

---

## 📊 EXPORT DATA (PDF / Excel)

```
USER klik "Export PDF" di halaman laporan
│
│  JavaScript fungsi exportLaporan('pdf'):
│    - Baca filter aktif (tanggal, keyword) dari input form
│    - Buat URL: /BHP-Poli-Gigi/api/export.php?type=pdf&page=laporan&...
│    - window.location.href = url (buka URL tsb)
│
▼
api/export.php
│  1. Cek login (jika belum login → 401)
│  2. Baca: ?type=pdf, ?page=laporan, filter params
│  3. Ambil data dari database via fetchLaporanData()
│  4. Karena type=pdf DAN page=laporan:
│     - Gunakan template HTML dokumen resmi:
│       ┌─────────────────────────────────┐
│       │ KLINIK POLI GIGI               │
│       │ Sistem Informasi BHP           │
│       ├─────────────────────────────────┤
│       │ LAPORAN PEMAKAIAN BHP          │
│       │ Periode: 01 Apr – 30 Apr 2026  │
│       ├─────────────────────────────────┤
│       │ No│Tgl│BHP│Jml│Satuan│Pasien   │
│       │ 1 │...│...│...│......│......   │
│       ├─────────────────────────────────┤
│       │ Mengetahui,    | Dicetak Oleh, │
│       │                |               │
│       │ (__________) | (__________) │
│       └─────────────────────────────────┘
│  5. Render HTML ke PDF pakai DomPDF
│  6. header('Content-Disposition: attachment; filename="laporan.pdf"')
│  7. Kirim file ke browser (langsung download)
```

---

## 📌 Ringkasan File per Fitur

| Fitur | Tampilan | Process | Controller | Manager |
|---|---|---|---|---|
| **Data BHP** | `contents/data_bhp.php` | `bhp_process.php` | `BhpController.php` | `BhpManager.php` |
| **Stok Masuk** | `contents/stock.php` | `stok_masuk_process.php` | `StokMasukController.php` | `StokMasukManager.php` |
| **Pemakaian** | `contents/catat.php` | `pemakaian_process.php` | `PemakaianController.php` | `PemakaianManager.php` |
| **Pengguna** | `contents/pengguna.php` | `user_process.php` | `UserController.php` | `UserManager.php` |
| **Profil** | `shared/profil.php` | `profil_process.php` | `ProfilController.php` | `UserManager.php` |
| **Login** | `auth/login.php` | `login_process.php` | `AuthController.php` | `Auth.php` |
| **Laporan** | `shared/laporan.php` | _(hanya baca)_ | _(tidak ada)_ | _(query langsung)_ |
| **Export** | _(tombol di laporan)_ | _(tidak ada)_ | _(tidak ada)_ | `api/export.php` |

---

## ⚠️ Aturan Validasi Penting

| Data | Validasi |
|---|---|
| Nama BHP | Tidak boleh kosong |
| Kode BHP | Harus unik (tidak boleh duplikat) |
| Jumlah stok | Harus lebih dari 0 |
| Tanggal terima | Tidak boleh kosong |
| Email user | Harus format email valid & unik |
| Password | Minimal 6 karakter (disimpan sebagai hash) |
| Hapus BHP | Ditolak jika masih ada relasi di stok/pemakaian |
| Hapus user | Ditolak jika menghapus akun sendiri |
