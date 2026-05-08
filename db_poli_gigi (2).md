-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 07, 2026 at 06:27 PM
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
  `isi_per_stok` int NOT NULL DEFAULT '1',
  `Pemakaian` varchar(12) DEFAULT '0',
  `id_kategori` int UNSIGNED DEFAULT NULL,
  `id_satuan` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bhp`
--

INSERT INTO `bhp` (`id_bhp`, `Kode_bhp`, `Nama_bhp`, `Jumlah`, `isi_per_stok`, `Pemakaian`, `id_kategori`, `id_satuan`) VALUES
(4, 'BHP 02', 'masker', 2, 5, '10', 1, 1),
(7, 'BHP6833', 'Amoc', 2, 5, '10', 2, 2),
(8, 'BHP2922', 'oxygen', 2, 3, '5', 1, 1),
(9, 'BHP8761', 'kapas_steril', 0, 1, '0', NULL, 1);

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
(1, NULL, 'Alat Pelindung'),
(2, 'ANT-772', 'Antibiotik'),
(3, 'PER-725', 'perkap');

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
(18, NULL, 'Admin', 'admin', 'tambah_bhp', 'bhp', 'Testing add_bhp log', '0.0.0.0', '2026-04-26 19:26:23'),
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
(31, 4, 'Fatqul Iman', 'admin', 'hapus_bhp', 'bhp', 'Menghapus BHP ID 3.', '::1', '2026-04-28 12:50:17'),
(32, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-28 14:19:30'),
(33, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-28 15:26:07'),
(34, 4, 'Fatqul Iman', 'admin', 'tambah_bhp', 'bhp', 'Menambahkan BHP: apd (kode: BHP 02).', '::1', '2026-04-28 15:26:35'),
(35, 4, 'Fatqul Iman', 'admin', 'tambah_bhp', 'bhp', 'Menambahkan BHP: ADRENALINE (kode: BHP 001).', '::1', '2026-04-28 15:27:37'),
(36, 4, 'Fatqul Iman', 'admin', 'edit_bhp', 'bhp', 'Mengedit BHP ID 4: masker.', '::1', '2026-04-28 15:45:25'),
(37, 4, 'Fatqul Iman', 'admin', 'hapus_bhp', 'bhp', 'Menghapus BHP ID 5.', '::1', '2026-04-28 15:46:51'),
(38, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-29 00:03:04'),
(39, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-29 00:19:36'),
(40, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-04-29 00:19:49'),
(41, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-04-29 00:47:46'),
(42, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-04-29 00:48:09'),
(43, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-04-29 01:07:10'),
(44, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-04-29 01:07:31'),
(45, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-04-29 13:56:34'),
(46, 6, 'Edo', 'dokter', 'update_profil', 'pengguna', 'Memperbarui data profil.', '::1', '2026-04-29 14:15:33'),
(47, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-04-29 14:29:36'),
(48, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-29 14:29:45'),
(49, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-04-29 14:30:15'),
(50, 7, 'Isan hadi', 'kepala_klinik', 'login', 'auth', 'Isan hadi berhasil masuk ke sistem.', '::1', '2026-04-29 14:30:25'),
(51, 7, 'Isan hadi', 'kepala_klinik', 'logout', 'auth', 'Isan hadi keluar dari sistem.', '::1', '2026-04-29 14:36:16'),
(52, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-04-29 14:36:25'),
(53, 4, 'Fatqul Iman', 'admin', 'update_profil', 'pengguna', 'Memperbarui data profil.', '::1', '2026-04-29 14:36:42'),
(54, 4, 'Fatqul Iman', 'admin', 'ubah_status_pengguna', 'pengguna', 'Mengubah status Admin Utama menjadi nonaktif.', '::1', '2026-04-29 14:37:00'),
(55, 4, 'Fatqul Iman', 'admin', 'hapus_pengguna', 'pengguna', 'Menghapus pengguna: Admin Utama.', '::1', '2026-04-29 14:37:02'),
(56, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-04-30 08:48:47'),
(57, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-04-30 08:50:07'),
(58, 7, 'Isan hadi', 'kepala_klinik', 'login', 'auth', 'Isan hadi berhasil masuk ke sistem.', '::1', '2026-04-30 08:50:47'),
(59, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-01 12:15:00'),
(60, 4, 'Fatqul Iman', 'admin', 'tambah_kategori', 'bhp', 'Menambahkan kategori: Antibiotik.', '::1', '2026-05-01 12:22:35'),
(61, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-05-01 13:32:15'),
(62, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-01 13:32:25'),
(63, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-05-01 13:33:53'),
(64, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-05-01 13:34:01'),
(65, 6, 'Edo', 'dokter', 'tambah_stok_masuk', 'stok', 'Input stok masuk BHP ID 4 sejumlah 1 unit.', '::1', '2026-05-01 13:35:16'),
(66, 6, 'Edo', 'dokter', 'catat_pemakaian', 'stok', 'Mencatat pemakaian BHP tgl 2026-05-01 - 1 item.', '::1', '2026-05-01 13:36:14'),
(67, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-05-01 13:37:39'),
(68, 7, 'Isan hadi', 'kepala_klinik', 'login', 'auth', 'Isan hadi berhasil masuk ke sistem.', '::1', '2026-05-01 13:37:53'),
(69, 7, 'Isan hadi', 'kepala_klinik', 'logout', 'auth', 'Isan hadi keluar dari sistem.', '::1', '2026-05-01 13:39:32'),
(70, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-05-01 13:39:40'),
(71, 6, 'Edo', 'dokter', 'catat_pemakaian', 'stok', 'Mencatat pemakaian BHP tgl 2026-05-01 - 1 item.', '::1', '2026-05-01 13:41:41'),
(72, 6, 'Edo', 'dokter', 'catat_pemakaian', 'stok', 'Mencatat pemakaian BHP tgl 2026-05-01 - 1 item.', '::1', '2026-05-01 13:42:53'),
(73, 6, 'Edo', 'dokter', 'catat_pemakaian', 'stok', 'Mencatat pemakaian BHP tgl 2026-05-01 - 1 item.', '::1', '2026-05-01 13:43:30'),
(74, 6, 'Edo', 'dokter', 'tambah_bhp', 'bhp', 'Menambahkan BHP: ADRENALINE (kode: BHP1281).', '::1', '2026-05-01 14:15:28'),
(75, 6, 'Edo', 'dokter', 'tambah_satuan', 'bhp', 'Menambahkan satuan: Botol.', '::1', '2026-05-01 14:30:32'),
(76, 6, 'Edo', 'dokter', 'tambah_bhp', 'bhp', 'Menambahkan BHP: Amoc (kode: BHP6833).', '::1', '2026-05-01 14:31:08'),
(77, 6, 'Edo', 'dokter', 'tambah_bhp', 'bhp', 'Menambahkan BHP: oxygen (kode: BHP2922).', '::1', '2026-05-01 14:42:24'),
(78, 6, 'Edo', 'dokter', 'catat_pemakaian', 'stok', 'Mencatat pemakaian BHP tgl 2026-05-01 - 1 item.', '::1', '2026-05-01 14:42:59'),
(79, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-05-01 14:58:02'),
(80, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-01 14:58:10'),
(81, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-05 13:47:09'),
(82, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-05-05 13:48:58'),
(83, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-05-05 13:49:07'),
(84, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-05-05 13:53:21'),
(85, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-05 13:53:36'),
(86, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-05-05 13:58:44'),
(87, 7, 'Isan hadi', 'kepala_klinik', 'login', 'auth', 'Isan hadi berhasil masuk ke sistem.', '::1', '2026-05-05 13:58:55'),
(88, 7, 'Isan hadi', 'kepala_klinik', 'logout', 'auth', 'Isan hadi keluar dari sistem.', '::1', '2026-05-05 14:01:09'),
(89, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-05-05 14:01:50'),
(90, 6, 'Edo', 'dokter', 'tambah_kategori', 'bhp', 'Menambahkan kategori: perkap.', '::1', '2026-05-05 14:10:32'),
(91, 6, 'Edo', 'dokter', 'logout', 'auth', 'Edo keluar dari sistem.', '::1', '2026-05-05 14:21:39'),
(92, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-05 14:22:01'),
(93, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-07 10:00:35'),
(94, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-07 14:59:12'),
(95, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '127.0.0.1', '2026-05-07 23:52:22'),
(96, 4, 'Fatqul Iman', 'admin', 'hapus_bhp', 'bhp', 'Menghapus BHP ID 6.', '::1', '2026-05-08 00:26:12'),
(97, 4, 'Fatqul Iman', 'admin', 'login', 'auth', 'Fatqul Iman berhasil masuk ke sistem.', '::1', '2026-05-08 01:06:53'),
(98, 4, 'Fatqul Iman', 'admin', 'tambah_bhp', 'bhp', 'BHP: kapas_steril', '::1', '2026-05-08 01:11:46'),
(99, 4, 'Fatqul Iman', 'admin', 'edit_bhp', 'bhp', 'Edit BHP ID: 5', '::1', '2026-05-08 01:13:16'),
(100, 4, 'Fatqul Iman', 'admin', 'hapus_bhp', 'bhp', 'Hapus BHP ID: 5', '::1', '2026-05-08 01:15:36'),
(101, 4, 'Fatqul Iman', 'admin', 'logout', 'auth', 'Fatqul Iman keluar dari sistem.', '::1', '2026-05-08 01:22:29'),
(102, 6, 'Edo', 'dokter', 'login', 'auth', 'Edo berhasil masuk ke sistem.', '::1', '2026-05-08 01:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `pemakaian_bhp`
--

CREATE TABLE `pemakaian_bhp` (
  `id_pemakaian` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `id_user` int UNSIGNED DEFAULT NULL COMMENT 'Dokter yang mencatat',
  `unit_tindakan` varchar(100) DEFAULT NULL,
  `nama_pasien` varchar(100) DEFAULT NULL,
  `catatan` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Header sesi catatan pemakaian BHP';

--
-- Dumping data for table `pemakaian_bhp`
--

INSERT INTO `pemakaian_bhp` (`id_pemakaian`, `tanggal`, `id_user`, `unit_tindakan`, `nama_pasien`, `catatan`, `created_at`) VALUES
(1, '2026-05-01', 6, NULL, 'Yusky', 's', '2026-05-01 13:36:14'),
(2, '2026-05-01', 6, NULL, 'hadi', NULL, '2026-05-01 13:41:41'),
(3, '2026-05-01', 6, NULL, 'anies', NULL, '2026-05-01 13:42:53'),
(4, '2026-05-01', 6, NULL, 'aksin', NULL, '2026-05-01 13:43:30'),
(5, '2026-05-01', 6, NULL, 'bintang', 'cuma 1 kali', '2026-05-01 14:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `pemakaian_bhp_detail`
--

CREATE TABLE `pemakaian_bhp_detail` (
  `id_detail` int UNSIGNED NOT NULL,
  `id_pemakaian` int UNSIGNED NOT NULL,
  `id_bhp` int UNSIGNED NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `kondisi` enum('habis','sisa') NOT NULL DEFAULT 'habis'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Detail item BHP yang dipakai per sesi catatan';

--
-- Dumping data for table `pemakaian_bhp_detail`
--

INSERT INTO `pemakaian_bhp_detail` (`id_detail`, `id_pemakaian`, `id_bhp`, `jumlah`, `kondisi`) VALUES
(1, 1, 4, 1, 'habis'),
(2, 2, 4, 2, 'habis'),
(3, 3, 4, 2, 'habis'),
(4, 4, 4, 1, 'habis'),
(5, 5, 8, 1, 'habis');

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
(1, 'AMPULE'),
(2, 'Botol');

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id_stok_masuk` int UNSIGNED NOT NULL,
  `id_bhp` int UNSIGNED NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `tanggal_terima` date NOT NULL,
  `tgl_kadaluarsa` date DEFAULT NULL,
  `catatan` text,
  `id_user` int UNSIGNED DEFAULT NULL COMMENT 'User yang menginput',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Riwayat penerimaan / restock barang BHP';

--
-- Dumping data for table `stok_masuk`
--

INSERT INTO `stok_masuk` (`id_stok_masuk`, `id_bhp`, `jumlah`, `tanggal_terima`, `tgl_kadaluarsa`, `catatan`, `id_user`, `created_at`) VALUES
(1, 4, 1, '2026-05-01', '2026-10-14', 's', 6, '2026-05-01 13:35:16'),
(3, 7, 2, '2026-05-01', NULL, 'Stok Awal (Saat pendaftaran barang)', 6, '2026-05-01 14:31:08'),
(4, 8, 2, '2026-05-01', NULL, 'Stok Awal (Saat pendaftaran barang)', 6, '2026-05-01 14:42:24');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int UNSIGNED NOT NULL,
  `Nama_lengkap` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `No_telp` varchar(20) DEFAULT NULL,
  `Jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `Tanggal_bergabung` date DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('admin','dokter','kepala_klinik') NOT NULL,
  `Status_akun` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `Nama_lengkap`, `Email`, `No_telp`, `Jenis_kelamin`, `Tanggal_bergabung`, `Foto`, `Password`, `Role`, `Status_akun`) VALUES
(4, 'Fatqul Iman', 'admin123@poligigi.com', NULL, 'Laki-laki', NULL, NULL, '$2y$10$MhgKyWtLNBmZDqqNcIlLEuW4.WRGVADGtaYLxZgoMFP/tb47he/Bi', 'admin', 'aktif'),
(6, 'Edo', 'dokter@poligigi.com', NULL, 'Laki-laki', NULL, NULL, '$2y$10$M9mzVXRlS5dndNr49fNBBud5i0DFEPEU/aq8KFnKtmDr2kVYZA2hS', 'dokter', 'aktif'),
(7, 'Isan hadi', 'kepala@poligigi.com', NULL, NULL, NULL, NULL, '$2y$10$wbTLfBXy96/w6rticRUcvOBTiI6/TPeZi97Fn0CT19nV.IDkJCtr.', 'kepala_klinik', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `user_preferensi`
--

CREATE TABLE `user_preferensi` (
  `id_preferensi` int UNSIGNED NOT NULL,
  `id_user` int UNSIGNED NOT NULL,
  `notif_stok_kurang` tinyint(1) NOT NULL DEFAULT '1',
  `notif_laporan_harian` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_preferensi`
--

INSERT INTO `user_preferensi` (`id_preferensi`, `id_user`, `notif_stok_kurang`, `notif_laporan_harian`, `updated_at`) VALUES
(1, 6, 1, 0, '2026-04-29 14:15:49');

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
-- Indexes for table `pemakaian_bhp`
--
ALTER TABLE `pemakaian_bhp`
  ADD PRIMARY KEY (`id_pemakaian`),
  ADD KEY `fk_pemakaian_user` (`id_user`);

--
-- Indexes for table `pemakaian_bhp_detail`
--
ALTER TABLE `pemakaian_bhp_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_pemakaian` (`id_pemakaian`),
  ADD KEY `fk_detail_bhp` (`id_bhp`);

--
-- Indexes for table `satuan_bhp`
--
ALTER TABLE `satuan_bhp`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id_stok_masuk`),
  ADD KEY `fk_stok_bhp` (`id_bhp`),
  ADD KEY `fk_stok_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `user_preferensi`
--
ALTER TABLE `user_preferensi`
  ADD PRIMARY KEY (`id_preferensi`),
  ADD UNIQUE KEY `uq_user_pref` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bhp`
--
ALTER TABLE `bhp`
  MODIFY `id_bhp` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kategori_bhp`
--
ALTER TABLE `kategori_bhp`
  MODIFY `id_kategori` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `pemakaian_bhp`
--
ALTER TABLE `pemakaian_bhp`
  MODIFY `id_pemakaian` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pemakaian_bhp_detail`
--
ALTER TABLE `pemakaian_bhp_detail`
  MODIFY `id_detail` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `satuan_bhp`
--
ALTER TABLE `satuan_bhp`
  MODIFY `id_satuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id_stok_masuk` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_preferensi`
--
ALTER TABLE `user_preferensi`
  MODIFY `id_preferensi` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

--
-- Constraints for table `pemakaian_bhp`
--
ALTER TABLE `pemakaian_bhp`
  ADD CONSTRAINT `fk_pemakaian_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `pemakaian_bhp_detail`
--
ALTER TABLE `pemakaian_bhp_detail`
  ADD CONSTRAINT `fk_detail_bhp` FOREIGN KEY (`id_bhp`) REFERENCES `bhp` (`id_bhp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_pemakaian` FOREIGN KEY (`id_pemakaian`) REFERENCES `pemakaian_bhp` (`id_pemakaian`) ON DELETE CASCADE;

--
-- Constraints for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD CONSTRAINT `fk_stok_bhp` FOREIGN KEY (`id_bhp`) REFERENCES `bhp` (`id_bhp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stok_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `user_preferensi`
--
ALTER TABLE `user_preferensi`
  ADD CONSTRAINT `fk_pref_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
