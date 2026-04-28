-- ============================================================
-- SQL: Tabel Log Aktivitas - Sistem BHP Poli Gigi
-- Tambahkan ke database: db_poli_gigi
-- ============================================================

CREATE TABLE `log_aktivitas` (
  `id_log`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user`     INT UNSIGNED NULL COMMENT 'NULL jika user sudah dihapus',
  `nama_user`   VARCHAR(100) NOT NULL COMMENT 'Nama user saat log dibuat',
  `role_user`   ENUM('admin','dokter','kepala_klinik') NOT NULL,
  `aksi`        VARCHAR(100) NOT NULL COMMENT 'Jenis tindakan: login, logout, tambah_bhp, dst',
  `kategori`    ENUM('auth','pengguna','bhp','stok','laporan','sistem') NOT NULL DEFAULT 'sistem',
  `detail`      TEXT NULL COMMENT 'Deskripsi lengkap tindakan',
  `ip_address`  VARCHAR(45) NULL COMMENT 'IPv4 atau IPv6',
  `waktu`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_user`    (`id_user`),
  KEY `idx_waktu`   (`waktu`),
  KEY `idx_aksi`    (`aksi`),
  KEY `idx_kategori`(`kategori`),
  CONSTRAINT `fk_log_user`
    FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Rekam jejak seluruh aktivitas pengguna sistem';

-- ============================================================
-- Contoh data dummy (opsional, hapus jika tidak diperlukan)
-- ============================================================
-- INSERT INTO `log_aktivitas` (id_user, nama_user, role_user, aksi, kategori, detail, ip_address) VALUES
-- (1, 'Admin Utama', 'admin', 'login', 'auth', 'Admin berhasil masuk ke sistem', '127.0.0.1'),
-- (1, 'Admin Utama', 'admin', 'tambah_pengguna', 'pengguna', 'Menambahkan pengguna: dokter@poligigi.com (dokter)', '127.0.0.1');
