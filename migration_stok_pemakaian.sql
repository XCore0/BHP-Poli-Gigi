-- ============================================================
-- MIGRATION: Stok Masuk & Pemakaian BHP
-- Database  : db_poli_gigi
-- Date      : 2026-04-29
-- ============================================================

-- --------------------------------------------------------
-- Tabel: stok_masuk
-- Menyimpan riwayat penerimaan / restok barang BHP
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stok_masuk` (
  `id_stok_masuk`  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `id_bhp`         INT UNSIGNED    NOT NULL,
  `jumlah`         INT             NOT NULL DEFAULT 1,
  `tanggal_terima` DATE            NOT NULL,
  `supplier`       VARCHAR(100)    DEFAULT NULL,
  `tgl_kadaluarsa` DATE            DEFAULT NULL,
  `catatan`        TEXT            DEFAULT NULL,
  `id_user`        INT UNSIGNED    DEFAULT NULL COMMENT 'User yang menginput',
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_stok_masuk`),
  KEY `fk_stok_bhp`  (`id_bhp`),
  KEY `fk_stok_user` (`id_user`),
  CONSTRAINT `fk_stok_bhp`  FOREIGN KEY (`id_bhp`)   REFERENCES `bhp`  (`id_bhp`)   ON DELETE CASCADE,
  CONSTRAINT `fk_stok_user` FOREIGN KEY (`id_user`)  REFERENCES `user` (`id_user`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Riwayat penerimaan / restock barang BHP';


-- --------------------------------------------------------
-- Tabel: pemakaian_bhp
-- Header / sesi catatan pemakaian BHP oleh dokter
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pemakaian_bhp` (
  `id_pemakaian`  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `tanggal`       DATE          NOT NULL,
  `id_user`       INT UNSIGNED  DEFAULT NULL COMMENT 'Dokter yang mencatat',
  `unit_tindakan` VARCHAR(100)  DEFAULT NULL,
  `lokasi`        VARCHAR(100)  DEFAULT NULL,
  `nama_pasien`   VARCHAR(100)  DEFAULT NULL,
  `catatan`       TEXT          DEFAULT NULL,
  `created_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pemakaian`),
  KEY `fk_pemakaian_user` (`id_user`),
  CONSTRAINT `fk_pemakaian_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Header sesi catatan pemakaian BHP';


-- --------------------------------------------------------
-- Tabel: pemakaian_bhp_detail
-- Detail item BHP yang dipakai per sesi
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pemakaian_bhp_detail` (
  `id_detail`    INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `id_pemakaian` INT UNSIGNED  NOT NULL,
  `id_bhp`       INT UNSIGNED  NOT NULL,
  `jumlah`       INT           NOT NULL DEFAULT 1,
  `kondisi`      ENUM('habis','sisa') NOT NULL DEFAULT 'habis',
  PRIMARY KEY (`id_detail`),
  KEY `fk_detail_pemakaian` (`id_pemakaian`),
  KEY `fk_detail_bhp`       (`id_bhp`),
  CONSTRAINT `fk_detail_pemakaian` FOREIGN KEY (`id_pemakaian`) REFERENCES `pemakaian_bhp`        (`id_pemakaian`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_bhp`       FOREIGN KEY (`id_bhp`)       REFERENCES `bhp`                   (`id_bhp`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Detail item BHP yang dipakai per sesi catatan';
