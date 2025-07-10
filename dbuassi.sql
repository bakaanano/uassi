-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2025 at 06:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbuassi`
--

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `status_kepegawaian` varchar(30) DEFAULT NULL,
  `gaji_pokok` decimal(12,2) DEFAULT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `jabatan`, `status_kepegawaian`, `gaji_pokok`, `password`) VALUES
('EMP001', 'Budi Santoso', 'Staff IT', 'tetap', 8000000.00, '12345678'),
('EMP002', 'Siti Nurfadilah', 'HRD', 'tetap', 9000000.00, 'admin123'),
('EMP003', 'Andika Pratama', 'Marketing', 'kontrak', 6000000.00, 'abcd1234');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai_tunjangan`
--

CREATE TABLE `detail_gaji` (
  `id` int(11) DEFAULT NULL,
  `id_pegawai` varchar(20) NOT NULL,
  'gaji_pokok' decimal(12,2) DEFAULT NULL,
  `id_tunjangan` int(11) DEFAULT NULL,
  `jumlah_diterima` decimal(10,2) DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai_tunjangan`
--

INSERT INTO `pegawai_tunjangan` (`id`, `id_pegawai`,`gaji_pokok` , `id_tunjangan`, `jumlah_diterima`, `tanggal`) VALUES
(1, 'EMP001', 9000000.00 , 1 ,  500000.00 ,'2025-04-01'),
(2, 'EMP001', 9000000.00 , 2 ,  220000.00 ,'2025-04-01'),
(3, 'EMP001', 9000000.00 , 3 , 1000000.00 ,'2025-04-01'),
(4, 'EMP002', 9000000.00 , 1 ,  500000.00 ,'2025-04-01'),
(5, 'EMP002', 9000000.00 , 2 ,  230000.00 ,'2025-04-01'),
(6, 'EMP002', 9000000.00 , 3 , 1200000.00 ,'2025-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id_presensi` int(11) NOT NULL,
  `id_pegawai` varchar(20) DEFAULT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  `status_kehadiran` enum('hadir','izin','cuti','sakit') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id_presensi`, `id_pegawai`, `waktu_masuk`, `waktu_keluar`, `lokasi`, `status_kehadiran`) VALUES
(1, 'EMP001', '2025-04-05 07:55:00', '2025-04-05 17:00:00', 'kantor', 'hadir'),
(2, 'EMP002', '2025-04-05 08:05:00', '2025-04-05 17:05:00', 'kantor', 'hadir'),
(3, 'EMP003', '2025-04-05 08:00:00', '2025-04-05 16:30:00', 'GPS: -6.200000,106.816667', 'hadir'),
(4, 'EMP001', '2025-04-04 08:00:00', '2025-04-04 17:00:00', 'kantor', 'hadir'),
(5, 'EMP002', '2025-04-04 08:10:00', NULL, 'GPS: -6.208000,106.816000', 'izin');

-- --------------------------------------------------------

--
-- Table structure for table `tunjangan`
--

CREATE TABLE `tunjangan` (
  `id_tunjangan` int(11) NOT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `jumlah` decimal(10,2) DEFAULT NULL,
  `ketentuan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tunjangan`
--

INSERT INTO `tunjangan` (`id_tunjangan`, `jenis`, `jumlah`, `ketentuan`) VALUES
(1, 'Transport', 500000.00, 'Rp50.000 per bulan jika hadir >= 20 hari'),
(2, 'Makan', 300000.00, 'Rp10.000 per hari hadir'),
(3, 'Kinerja', 1000000.00, 'Berdasarkan evaluasi kinerja bulanan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indexes for table `pegawai_tunjangan`
--
ALTER TABLE `pegawai_tunjangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `id_tunjangan` (`id_tunjangan`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id_presensi`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `tunjangan`
--
ALTER TABLE `tunjangan`
  ADD PRIMARY KEY (`id_tunjangan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pegawai_tunjangan`
--
ALTER TABLE `pegawai_tunjangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id_presensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tunjangan`
--
ALTER TABLE `tunjangan`
  MODIFY `id_tunjangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pegawai_tunjangan`
--
ALTER TABLE `pegawai_tunjangan`
  ADD CONSTRAINT `pegawai_tunjangan_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`),
  ADD CONSTRAINT `pegawai_tunjangan_ibfk_2` FOREIGN KEY (`id_tunjangan`) REFERENCES `tunjangan` (`id_tunjangan`);

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
