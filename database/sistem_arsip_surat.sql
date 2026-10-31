-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2026 at 04:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengarsipan_surat`
--

-- --------------------------------------------------------

--
-- Table structure for table `arsip_surat`
--

CREATE TABLE `arsip_surat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `surat_id` int(10) UNSIGNED NOT NULL,
  `tahun_arsip` year(4) NOT NULL,
  `lokasi_arsip` varchar(180) NOT NULL DEFAULT 'Arsip Digital',
  `keterangan` varchar(500) DEFAULT NULL,
  `archived_by` int(10) UNSIGNED NOT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `arsip_surat`
--

INSERT INTO `arsip_surat` (`id`, `surat_id`, `tahun_arsip`, `lokasi_arsip`, `keterangan`, `archived_by`, `archived_at`) VALUES
(1, 2, '2026', 'Arsip Digital / Surat Masuk', 'Tindak lanjut undangan telah selesai.', 1, '2026-10-09 04:00:00'),
(2, 4, '2026', 'Arsip Digital / Surat Masuk', 'Pemeriksaan laporan telah selesai.', 1, '2026-10-07 08:45:00'),
(3, 6, '2026', 'Arsip Digital / Surat Keluar', 'Surat balasan selesai dikirim.', 1, '2026-10-09 07:00:00'),
(4, 7, '2026', 'Arsip Digital / Surat Keluar', 'Surat pemberitahuan berhasil dikirim.', 1, '2026-10-08 08:00:00'),
(5, 9, '2026', 'Arsip Digital / Surat Keluar', 'Surat tugas selesai diterbitkan.', 1, '2026-10-06 07:30:00'),
(6, 18, '2026', 'Arsip Digital / Surat Masuk', 'Status surat diperbarui menjadi Selesai.', 1, '2026-10-20 07:17:17'),
(7, 15, '2026', 'Arsip Digital / Surat Keluar', 'Status surat diperbarui menjadi Selesai.', 1, '2026-10-20 07:19:13'),
(8, 13, '2026', 'Arsip Digital / Surat Keluar', 'Status surat diperbarui menjadi Selesai.', 1, '2026-10-20 07:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `disposisi_surat`
--

CREATE TABLE `disposisi_surat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `surat_id` int(10) UNSIGNED NOT NULL,
  `ditujukan_kepada` varchar(150) NOT NULL,
  `instruksi` text NOT NULL,
  `tanggal_disposisi` datetime NOT NULL DEFAULT current_timestamp(),
  `batas_waktu` date DEFAULT NULL,
  `status_tindak_lanjut` enum('Belum Ditindaklanjuti','Dalam Proses','Selesai') NOT NULL DEFAULT 'Belum Ditindaklanjuti',
  `catatan_tindak_lanjut` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `id` int(10) UNSIGNED NOT NULL,
  `jenis_surat` enum('Masuk','Keluar') NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_diterima` date DEFAULT NULL,
  `pengirim` varchar(200) NOT NULL DEFAULT '',
  `tujuan` varchar(200) NOT NULL DEFAULT '',
  `perihal` varchar(250) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `uploaded_file` varchar(255) DEFAULT NULL,
  `status` varchar(40) NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id`, `jenis_surat`, `nomor_surat`, `tanggal_surat`, `tanggal_diterima`, `pengirim`, `tujuan`, `perihal`, `deskripsi`, `uploaded_file`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Masuk', '001/SM/X/2026', '2026-10-08', '2026-10-09', 'Dinas Pendidikan', 'Bagian Umum', 'Permohonan Data', 'Permohonan data administrasi untuk koordinasi.', NULL, 'Dalam Proses', 2, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(2, 'Masuk', '002/SM/X/2026', '2026-10-07', '2026-10-08', 'Universitas ABC', 'Bagian Umum', 'Undangan Rapat', 'Undangan rapat koordinasi antarinstansi.', NULL, 'Selesai', 1, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(3, 'Masuk', '003/SM/X/2026', '2026-10-06', '2026-10-07', 'PT Maju Bersama', 'Bagian Umum', 'Kerja Sama', 'Dokumen awal penawaran kerja sama.', NULL, 'Menunggu Disposisi', 2, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(4, 'Masuk', '004/SM/X/2026', '2026-10-05', '2026-10-06', 'Kantor Kecamatan', 'Bagian Umum', 'Laporan Kegiatan', 'Laporan pelaksanaan kegiatan bulanan.', NULL, 'Selesai', 1, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(5, 'Masuk', '005/SM/X/2026', '2026-10-04', '2026-10-05', 'CV Sejahtera', 'Bagian Umum', 'Permohonan Izin', 'Permohonan izin penggunaan ruangan.', NULL, 'Diterima', 2, 2, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(6, 'Keluar', '001/SK/X/2026', '2026-10-09', NULL, 'Bagian Umum', 'Dinas Pendidikan', 'Surat Balasan', 'Balasan atas surat permohonan data.', NULL, 'Selesai', 1, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(7, 'Keluar', '002/SK/X/2026', '2026-10-08', NULL, 'Bagian Umum', 'Universitas ABC', 'Pemberitahuan', 'Pemberitahuan kegiatan koordinasi.', NULL, 'Selesai', 2, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(8, 'Keluar', '003/SK/X/2026', '2026-10-07', NULL, 'Bagian Umum', 'PT Maju Bersama', 'Undangan', 'Undangan pertemuan kerja sama.', NULL, 'Dalam Proses', 1, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(9, 'Keluar', '004/SK/X/2026', '2026-10-06', NULL, 'Bagian Umum', 'Kantor Kecamatan', 'Surat Tugas', 'Surat tugas pelaksanaan kegiatan.', NULL, 'Selesai', 1, 1, '2026-10-20 06:07:07', '2026-10-20 06:07:07'),
(10, 'Keluar', '005/SK/X/2026', '2026-10-05', NULL, 'Bagian Umum', 'CV Sejahtera', 'Permohonan', 'Surat permohonan administrasi.', NULL, 'Dalam Proses', 2, 1, '2026-10-20 06:07:07', '2026-10-20 07:20:00'),
(11, 'Masuk', '006/SM/2026', '2026-10-20', '2026-10-29', 'Bagian Protokol', 'Bagian Umum', 'Berita acara', '', 'c72f0d146808afdca58b5c2a3bf9d262816e.pdf', 'Diterima', 2, 2, '2026-10-20 06:56:20', '2026-10-20 06:57:00'),
(12, 'Masuk', '008/SM/2026', '2026-10-30', '2026-11-19', 'PT. Indah Bangun', 'Bagian Umum', 'Pembelian Sarana', '', NULL, 'Diterima', 2, 2, '2026-10-20 06:58:46', '2026-10-20 06:58:46'),
(13, 'Keluar', '007/SM/2026', '2026-10-20', NULL, 'Bagian Umum', 'BAPEDDA', 'Jadwal kegiatan', '', NULL, 'Selesai', 2, 1, '2026-10-20 07:00:12', '2026-10-20 07:19:46'),
(14, 'Keluar', '011/SM/2026', '2026-11-24', NULL, 'Bagian Umum', 'Staf Ahli', 'Jadwal Kegiatan Selasa bersih', '', NULL, 'Dalam Proses', 2, 1, '2026-10-20 07:01:45', '2026-10-20 07:19:32'),
(15, 'Keluar', '006/SM/2026', '2026-10-20', NULL, 'Bagian Umum', 'BAPEDDA', 'Berita acara', '', NULL, 'Selesai', 2, 1, '2026-10-20 07:03:11', '2026-10-20 07:19:13'),
(16, 'Keluar', '012/SM/2026', '2026-10-20', NULL, 'Bagian Umum', 'Staf Ahli', 'Ringkasan Gaji', '', NULL, 'Dalam Proses', 2, 1, '2026-10-20 07:04:36', '2026-10-20 07:18:53'),
(17, 'Masuk', '020/SM/2026', '2026-10-30', '2026-11-06', 'Bagian Umum Loby', 'Bagian Umum', 'Recap Absensi Pegawai', '', NULL, 'Menunggu Disposisi', 2, 1, '2026-10-20 07:06:32', '2026-10-20 07:18:40'),
(18, 'Masuk', '013/SM/2026', '2026-10-20', '2026-10-20', 'Bagian Protokol', 'Bagian Umum', 'Jadwal Kegiatan bersih', '', NULL, 'Selesai', 2, 1, '2026-10-20 07:07:08', '2026-10-20 07:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `surat_status_logs`
--

CREATE TABLE `surat_status_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `surat_id` int(10) UNSIGNED NOT NULL,
  `status_lama` varchar(40) DEFAULT NULL,
  `status_baru` varchar(40) NOT NULL,
  `catatan` varchar(500) DEFAULT NULL,
  `changed_by` int(10) UNSIGNED NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_status_logs`
--

INSERT INTO `surat_status_logs` (`id`, `surat_id`, `status_lama`, `status_baru`, `catatan`, `changed_by`, `changed_at`) VALUES
(1, 1, NULL, 'Diterima', 'Surat masuk dicatat ke sistem.', 2, '2026-10-09 02:10:00'),
(2, 1, 'Diterima', 'Menunggu Disposisi', 'Surat diteruskan untuk menunggu disposisi.', 1, '2026-10-09 03:15:00'),
(3, 1, 'Menunggu Disposisi', 'Dalam Proses', 'Tindak lanjut telah dimulai.', 1, '2026-10-10 01:45:00'),
(4, 2, NULL, 'Diterima', 'Surat masuk dicatat ke sistem.', 1, '2026-10-08 03:00:00'),
(5, 2, 'Diterima', 'Menunggu Disposisi', 'Menunggu arahan pimpinan.', 1, '2026-10-08 03:30:00'),
(6, 2, 'Menunggu Disposisi', 'Dalam Proses', 'Surat sedang ditindaklanjuti.', 1, '2026-10-08 06:15:00'),
(7, 2, 'Dalam Proses', 'Selesai', 'Tindak lanjut selesai.', 1, '2026-10-09 04:00:00'),
(8, 3, NULL, 'Diterima', 'Surat masuk dicatat ke sistem.', 2, '2026-10-07 07:20:00'),
(9, 3, 'Diterima', 'Menunggu Disposisi', 'Menunggu arahan pimpinan.', 1, '2026-10-08 02:00:00'),
(10, 4, NULL, 'Diterima', 'Surat masuk dicatat ke sistem.', 1, '2026-10-06 01:30:00'),
(11, 4, 'Diterima', 'Dalam Proses', 'Laporan diperiksa.', 1, '2026-10-06 02:00:00'),
(12, 4, 'Dalam Proses', 'Selesai', 'Pemeriksaan laporan selesai.', 1, '2026-10-07 08:45:00'),
(13, 5, NULL, 'Diterima', 'Surat masuk dicatat ke sistem.', 2, '2026-10-05 03:15:00'),
(14, 6, NULL, 'Draft', 'Surat keluar dicatat sebagai draft.', 1, '2026-10-09 02:00:00'),
(15, 6, 'Draft', 'Dalam Proses', 'Surat disiapkan untuk proses final.', 1, '2026-10-09 03:00:00'),
(16, 6, 'Dalam Proses', 'Selesai', 'Surat balasan selesai dikirim.', 1, '2026-10-09 07:00:00'),
(17, 7, NULL, 'Draft', 'Surat keluar dicatat sebagai draft.', 2, '2026-10-08 02:30:00'),
(18, 7, 'Draft', 'Dalam Proses', 'Surat diproses untuk pengiriman.', 1, '2026-10-08 03:00:00'),
(19, 7, 'Dalam Proses', 'Selesai', 'Surat berhasil dikirim.', 1, '2026-10-08 08:00:00'),
(20, 8, NULL, 'Draft', 'Surat keluar dicatat sebagai draft.', 1, '2026-10-07 01:30:00'),
(21, 8, 'Draft', 'Dalam Proses', 'Menunggu penandatanganan dan pengiriman.', 1, '2026-10-07 03:00:00'),
(22, 9, NULL, 'Draft', 'Surat keluar dicatat sebagai draft.', 1, '2026-10-06 02:15:00'),
(23, 9, 'Draft', 'Dalam Proses', 'Surat diproses untuk pengiriman.', 1, '2026-10-06 03:00:00'),
(24, 9, 'Dalam Proses', 'Selesai', 'Surat tugas selesai diterbitkan.', 1, '2026-10-06 07:30:00'),
(25, 10, NULL, 'Draft', 'Surat keluar dicatat sebagai draft.', 2, '2026-10-05 04:00:00'),
(26, 11, NULL, 'Diterima', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 06:56:20'),
(27, 12, NULL, 'Diterima', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 06:58:46'),
(28, 13, NULL, 'Draft', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 07:00:12'),
(29, 14, NULL, 'Draft', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 07:01:45'),
(30, 15, NULL, 'Draft', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 07:03:11'),
(31, 16, NULL, 'Draft', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 07:04:36'),
(32, 17, NULL, 'Diterima', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 07:06:32'),
(33, 18, NULL, 'Diterima', 'Surat dicatat ke dalam sistem.', 2, '2026-10-20 07:07:08'),
(34, 18, 'Diterima', 'Selesai', 'Status surat diperbarui menjadi Selesai.', 1, '2026-10-20 07:17:17'),
(35, 17, 'Diterima', 'Menunggu Disposisi', 'Status surat diperbarui menjadi Menunggu Disposisi.', 1, '2026-10-20 07:18:40'),
(36, 16, 'Draft', 'Dalam Proses', 'Status surat diperbarui menjadi Dalam Proses.', 1, '2026-10-20 07:18:53'),
(37, 15, 'Draft', 'Selesai', 'Status surat diperbarui menjadi Selesai.', 1, '2026-10-20 07:19:13'),
(38, 14, 'Draft', 'Dalam Proses', 'Status surat diperbarui menjadi Dalam Proses.', 1, '2026-10-20 07:19:32'),
(39, 13, 'Draft', 'Selesai', 'Status surat diperbarui menjadi Selesai.', 1, '2026-10-20 07:19:46'),
(40, 10, 'Draft', 'Dalam Proses', 'Status surat diperbarui menjadi Dalam Proses.', 1, '2026-10-20 07:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(120) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `is_active`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$12$h46hEHuTai.UUwzYo8Nc0eujGxFv/xg5s9uXdvrIS7a2rtGtnYDRq', 'Admin', 1, '2026-10-20 06:07:07'),
(2, 'Pengguna Surat', 'user', '$2y$12$nXeMUd9ZJc/9FT1z7HX1LeZ/edVTKFO8IwmIgmEZ.Une.CnZwpUOy', 'User', 1, '2026-10-20 06:07:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arsip_surat`
--
ALTER TABLE `arsip_surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `surat_id` (`surat_id`),
  ADD KEY `idx_arsip_tahun` (`tahun_arsip`),
  ADD KEY `fk_arsip_user` (`archived_by`);

--
-- Indexes for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_disposisi_surat_tanggal` (`surat_id`,`tanggal_disposisi`),
  ADD KEY `idx_disposisi_status` (`status_tindak_lanjut`),
  ADD KEY `fk_disposisi_created_by` (`created_by`),
  ADD KEY `fk_disposisi_updated_by` (`updated_by`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_jenis_nomor` (`jenis_surat`,`nomor_surat`),
  ADD KEY `idx_surat_type_status` (`jenis_surat`,`status`),
  ADD KEY `idx_surat_date` (`tanggal_surat`),
  ADD KEY `fk_surat_created_by` (`created_by`),
  ADD KEY `fk_surat_updated_by` (`updated_by`);

--
-- Indexes for table `surat_status_logs`
--
ALTER TABLE `surat_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_surat_tanggal` (`surat_id`,`changed_at`),
  ADD KEY `fk_log_user` (`changed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arsip_surat`
--
ALTER TABLE `arsip_surat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `surat_status_logs`
--
ALTER TABLE `surat_status_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arsip_surat`
--
ALTER TABLE `arsip_surat`
  ADD CONSTRAINT `fk_arsip_surat` FOREIGN KEY (`surat_id`) REFERENCES `surat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_arsip_user` FOREIGN KEY (`archived_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `disposisi_surat`
--
ALTER TABLE `disposisi_surat`
  ADD CONSTRAINT `fk_disposisi_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_disposisi_surat` FOREIGN KEY (`surat_id`) REFERENCES `surat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_disposisi_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `surat`
--
ALTER TABLE `surat`
  ADD CONSTRAINT `fk_surat_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_surat_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `surat_status_logs`
--
ALTER TABLE `surat_status_logs`
  ADD CONSTRAINT `fk_log_surat` FOREIGN KEY (`surat_id`) REFERENCES `surat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
