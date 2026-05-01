-- ============================================================
-- MIGRATION: Profil & Pengaturan User (MySQL 8.0 compatible)
-- ============================================================

-- Tambah kolom No_telp ke tabel user
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA='db_poli_gigi' AND TABLE_NAME='user' AND COLUMN_NAME='No_telp');
SET @sql = IF(@col=0, 'ALTER TABLE `user` ADD COLUMN `No_telp` VARCHAR(20) DEFAULT NULL AFTER `Email`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Tambah kolom Jenis_kelamin
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA='db_poli_gigi' AND TABLE_NAME='user' AND COLUMN_NAME='Jenis_kelamin');
SET @sql = IF(@col=0, "ALTER TABLE `user` ADD COLUMN `Jenis_kelamin` ENUM('Laki-laki','Perempuan') DEFAULT NULL AFTER `No_telp`", 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Tambah kolom Tanggal_bergabung
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA='db_poli_gigi' AND TABLE_NAME='user' AND COLUMN_NAME='Tanggal_bergabung');
SET @sql = IF(@col=0, 'ALTER TABLE `user` ADD COLUMN `Tanggal_bergabung` DATE DEFAULT NULL AFTER `Jenis_kelamin`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Tambah kolom Foto
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA='db_poli_gigi' AND TABLE_NAME='user' AND COLUMN_NAME='Foto');
SET @sql = IF(@col=0, 'ALTER TABLE `user` ADD COLUMN `Foto` VARCHAR(255) DEFAULT NULL AFTER `Tanggal_bergabung`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Tabel preferensi notifikasi per user
CREATE TABLE IF NOT EXISTS `user_preferensi` (
  `id_preferensi`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user`              INT UNSIGNED NOT NULL,
  `notif_stok_kurang`    TINYINT(1)   NOT NULL DEFAULT 1,
  `notif_laporan_harian` TINYINT(1)   NOT NULL DEFAULT 0,
  `updated_at`           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_preferensi`),
  UNIQUE KEY `uq_user_pref` (`id_user`),
  CONSTRAINT `fk_pref_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
