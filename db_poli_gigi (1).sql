-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 28, 2026 at 06:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_poli_gigi`
--

-- --------------------------------------------------------

--
-- Table structure for table `bhp`
--

CREATE TABLE `bhp` (
  `id_bhp` int UNSIGNED NOT NULL,
  `Kode_bhp` varchar(20) DEFAULT NULL,
  `Nama_bhp` varchar(100) NOT NULL,
  `Jumlah` int DEFAULT '0',
  `Pemakaian` varchar(12) NOT NULL,
  `id_kategori` int UNSIGNED DEFAULT NULL,
  `id_satuan` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_bhp`
--

CREATE TABLE `kategori_bhp` (
  `id_kategori` int UNSIGNED NOT NULL,
  `Kode_kategori` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori_bhp`
--

INSERT INTO `kategori_bhp` (`id_kategori`, `Kode_kategori`, `Nama_kategori`) VALUES
(1, NULL, 'Alat Pelindung');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int UNSIGNED NOT NULL,
  `id_user` int UNSIGNED DEFAULT NULL COMMENT 'NULL jika user sudah dihapus',
  `nama_user` varchar(100) NOT NULL COMMENT 'Nama user saat log dibuat',
  `role_user` enum('admin','dokter','kepala_klinik') NOT NULL,
  `aksi` varchar(100) NOT NULL COMMENT 'Jenis tindakan: login, logout, tambah_bhp, dst',
  `kategori` enum('auth','pengguna','bhp','stok','laporan','sistem') NOT NULL DEFAULT 'sistem',
  `detail` text COMMENT 'Deskripsi lengkap tindakan',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IPv4 atau IPv6',
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Rekam jejak seluruh aktivitas pengguna sistem';

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `nama_user`, `role_user`, `aksi`, `kategori`, `detail`, `ip_address`, `waktu`) VALUES
(1, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-22 13:18:58'),
(2, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-22 13:18:58'),
(3, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-22 13:21:48'),
(4, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-22 13:22:18'),
(5, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-22 13:22:18'),
(6, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-22 13:22:30'),
(7, 4, 'Fatqul Iman', 'admin', 'tambah_pengguna', 'pengguna', 'Menambahkan pengguna baru: kepala@poligigi.com (role: kepala_klinik).', '::1', '2026-04-22 13:25:24'),
(8, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-22 13:25:34'),
(9, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-22 13:25:34'),
(10, 7, 'Isan hadi', 'kepala_klinik', 'login', 'auth', 'Isan hadi berhasil masuk ke sistem.', '::1', '2026-04-22 13:25:45'),
(11, 7, 'Isan hadi', 'kepala_klinik', 'logout', 'auth', 'Isan hadi keluar dari sistem.', '::1', '2026-04-22 13:29:40'),
(12, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-22 13:30:07'),
(13, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-23 21:01:35'),
(14, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-23 21:02:07'),
(15, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-23 21:18:26'),
(16, 4, 'Fatqul Iman', 'admin', 'tambah_kategori', 'bhp', 'Menambahkan kategori: Alat Pelindung.', '::1', '2026-04-23 22:48:05'),
(17, 4, 'Fatqul Iman', 'admin', 'tambah_satuan', 'bhp', 'Menambahkan satuan: AMPULE.', '::1', '2026-04-23 22:48:28'),
(18, 1, 'Admin', 'admin', 'tambah_bhp', 'bhp', 'Testing add_bhp log', '0.0.0.0', '2026-04-26 19:26:23'),
(19, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-04-26 19:29:47'),
(20, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-04-26 19:30:07'),
(21, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-26 19:30:13'),
(22, 4, 'Fatqul Iman', 'admin', 'tambah_bhp', 'bhp', 'Menambahkan BHP: ADRENALINE (kode: BHP 001).', '::1', '2026-04-26 19:30:34'),
(23, 4, 'Fatqul Iman', 'admin', 'edit_bhp', 'bhp', 'Mengedit BHP ID 2: ADRENALINE.', '::1', '2026-04-26 19:30:49'),
(24, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-26 19:59:36'),
(25, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-27 19:17:52'),
(26, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-28 11:15:56'),
(27, 4, 'Fatqul Iman', 'admin', 'hapus_bhp', 'bhp', 'Menghapus BHP ID 1.', '::1', '2026-04-28 12:46:32'),
(28, 4, 'Fatqul Iman', 'admin', 'hapus_bhp', 'bhp', 'Menghapus BHP ID 2.', '::1', '2026-04-28 12:48:09'),
(29, 4, 'Fatqul Iman', 'admin', 'tambah_bhp', 'bhp', 'Menambahkan BHP: apd (kode: bhp 200).', '::1', '2026-04-28 12:49:47'),
(30, 4, 'Fatqul Iman', 'admin', 'edit_bhp', 'bhp', 'Mengedit BHP ID 3: APD.', '::1', '2026-04-28 12:50:11'),
(31, 4, 'Fatqul Iman', 'admin', 'hapus_bhp', 'bhp', 'Menghapus BHP ID 3.', '::1', '2026-04-28 12:50:17');

-- --------------------------------------------------------

--
-- Table structure for table `satuan_bhp`
--

CREATE TABLE `satuan_bhp` (
  `id_satuan` int UNSIGNED NOT NULL,
  `Nama_satuan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `satuan_bhp`
--

INSERT INTO `satuan_bhp` (`id_satuan`, `Nama_satuan`) VALUES
(1, 'AMPULE');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int UNSIGNED NOT NULL,
  `Nama_lengkap` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('admin','dokter','kepala_klinik') NOT NULL,
  `Status_akun` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `Nama_lengkap`, `Email`, `Password`, `Role`, `Status_akun`) VALUES
(1, 'Admin Utama', 'admin@poligigi.com', '$2y$10$11bupY2pFDk5bC6ARk4Nn.9xxYFz2ZC5mAU0yaW1ojFknD720t4Xm', 'admin', 'aktif'),
(4, 'Fatqul Iman', 'admin123@poligigi.com', '$2y$10$MhgKyWtLNBmZDqqNcIlLEuW4.WRGVADGtaYLxZgoMFP/tb47he/Bi', 'admin', 'aktif'),
(6, 'Edo', 'dokter@poligigi.com', '$2y$10$M9mzVXRlS5dndNr49fNBBud5i0DFEPEU/aq8KFnKtmDr2kVYZA2hS', 'dokter', 'aktif'),
(7, 'Isan hadi', 'kepala@poligigi.com', '$2y$10$wbTLfBXy96/w6rticRUcvOBTiI6/TPeZi97Fn0CT19nV.IDkJCtr.', 'kepala_klinik', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bhp`
--
ALTER TABLE `bhp`
  ADD PRIMARY KEY (`id_bhp`),
  ADD UNIQUE KEY `Kode_bhp` (`Kode_bhp`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_satuan` (`id_satuan`);

--
-- Indexes for table `kategori_bhp`
--
ALTER TABLE `kategori_bhp`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `Kode_kategory` (`Kode_kategori`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_user` (`id_user`),
  ADD KEY `idx_waktu` (`waktu`),
  ADD KEY `idx_aksi` (`aksi`),
  ADD KEY `idx_kategori` (`kategori`);

--
-- Indexes for table `satuan_bhp`
--
ALTER TABLE `satuan_bhp`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bhp`
--
ALTER TABLE `bhp`
  MODIFY `id_bhp` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategori_bhp`
--
ALTER TABLE `kategori_bhp`
  MODIFY `id_kategori` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `satuan_bhp`
--
ALTER TABLE `satuan_bhp`
  MODIFY `id_satuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bhp`
--
ALTER TABLE `bhp`
  ADD CONSTRAINT `bhp_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_bhp` (`id_kategori`) ON DELETE SET NULL,
  ADD CONSTRAINT `bhp_ibfk_2` FOREIGN KEY (`id_satuan`) REFERENCES `satuan_bhp` (`id_satuan`) ON DELETE SET NULL;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
