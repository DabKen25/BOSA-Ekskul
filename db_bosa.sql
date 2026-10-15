-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2026 at 08:00 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bosa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` int(11) NOT NULL,
  `no_ekskul` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `nis` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status` char(1) NOT NULL COMMENT 'H=Hadir, I=Izin, S=Sakit, A=Alpa',
  `keterangan` varchar(255) DEFAULT NULL,
  `id_ekskul` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absensi`, `no_ekskul`, `tgl`, `id_guru`, `nis`, `nama`, `status`, `keterangan`, `id_ekskul`, `timestamp`) VALUES
(1, 0, '2026-01-20', NULL, 23769, 'Benedictus Christian Dio', 'H', '', 1, '2026-01-18 18:18:58'),
(2, 0, '2026-01-20', NULL, 23777, 'Brandon Alessandro Johnson', 'H', '', 1, '2026-01-18 18:18:58'),
(3, 0, '2026-01-20', NULL, 23781, 'Carolina Mereditha Putri', 'H', '', 1, '2026-01-18 18:18:58'),
(4, 0, '2026-01-20', NULL, 23876, 'Joylien Wijaya', 'I', 'Menonton Konser', 1, '2026-01-18 18:18:58'),
(5, 0, '2026-01-20', NULL, 23890, 'Lie Sherla Artalyta Prabowo', 'H', '', 1, '2026-01-18 18:18:59'),
(6, 0, '2026-01-20', NULL, 23899, 'Maria Aurel Abigail', 'I', 'Tes SIM', 1, '2026-01-18 18:18:59'),
(7, 0, '2026-01-20', NULL, 23903, 'Michael Kenzi Darenth Soetrisno', 'H', '', 1, '2026-01-18 18:18:59'),
(8, 0, '2026-01-20', NULL, 23982, 'Afghan Sosrosoegondo', 'H', '', 1, '2026-01-18 18:18:59'),
(9, 0, '2026-01-20', NULL, 24013, 'Beia Apratihata', 'H', '', 1, '2026-01-18 18:18:59'),
(10, 0, '2026-01-20', NULL, 24015, 'Benedictus Evan Pratama Putra', 'S', 'Demam', 1, '2026-01-18 18:18:59'),
(11, 0, '2026-01-27', NULL, 23769, 'Benedictus Christian Dio', 'H', '', 1, '2026-01-18 18:22:31'),
(12, 0, '2026-01-27', NULL, 23777, 'Brandon Alessandro Johnson', 'H', '', 1, '2026-01-18 18:22:31'),
(13, 0, '2026-01-27', NULL, 23781, 'Carolina Mereditha Putri', 'H', '', 1, '2026-01-18 18:22:31'),
(14, 0, '2026-01-27', NULL, 23876, 'Joylien Wijaya', 'A', '', 1, '2026-01-18 18:22:31'),
(15, 0, '2026-01-27', NULL, 23890, 'Lie Sherla Artalyta Prabowo', 'A', '', 1, '2026-01-18 18:22:31'),
(16, 0, '2026-01-27', NULL, 23899, 'Maria Aurel Abigail', 'H', '', 1, '2026-01-18 18:22:31'),
(17, 0, '2026-01-27', NULL, 23903, 'Michael Kenzi Darenth Soetrisno', 'H', '', 1, '2026-01-18 18:22:31'),
(18, 0, '2026-01-27', NULL, 23982, 'Afghan Sosrosoegondo', 'H', '', 1, '2026-01-18 18:22:31'),
(19, 0, '2026-01-27', NULL, 24013, 'Beia Apratihata', 'H', '', 1, '2026-01-18 18:22:31'),
(20, 0, '2026-01-27', NULL, 24015, 'Benedictus Evan Pratama Putra', 'H', '', 1, '2026-01-18 18:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ekstrakulikuler`
--

CREATE TABLE `tbl_ekstrakulikuler` (
  `id` int(11) NOT NULL,
  `ekstrakulikuler` varchar(100) NOT NULL,
  `guru` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_ekstrakulikuler`
--

INSERT INTO `tbl_ekstrakulikuler` (`id`, `ekstrakulikuler`, `guru`) VALUES
(1, 'Pramuka', 'Drs. Mursetyadi Yuli Sadono'),
(2, 'Jurnalistik', 'Melani Bornok, S.Pd'),
(3, 'Cheers Leader', 'Herjuna Raka Pangestu, S.Ked.'),
(4, 'Modern Dance', 'Nugrah Hastana Putra, S.E.'),
(5, 'Fotografi', 'M. Bintang Purnomo'),
(6, 'Karate', 'Nathanael Andri Sebastian S.E'),
(7, 'Basket Putra', 'Andi Setiyawan, M.Pd'),
(8, 'Basket Putri', 'Dewi Nur Setiyaningsih'),
(9, 'Futsal Putra', 'Yosep Yoga Pranata, S.Pd'),
(10, 'Bulu Tangkis', 'Yokhanan Ardika, M . Pd.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `ekstrakulikuler` varchar(100) NOT NULL,
  `id_ekskul` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_guru`
--

INSERT INTO `tbl_guru` (`id`, `nama`, `id_user`, `ekstrakulikuler`, `id_ekskul`) VALUES
(99922, 'Drs. Mursetyadi Yuli Sadono', NULL, 'Pramuka', NULL),
(99934, 'Melani Bornok, S.Pd', NULL, 'Jurnalistik', NULL),
(99944, 'Herjuna Raka Pangestu, S.Ked.', NULL, 'Cheers Leader', NULL),
(99956, 'M. Bintang Purnomo', NULL, 'Fotografi', NULL),
(99957, 'Nathanael Andri Sebastian S.E', NULL, 'Karate', NULL),
(99958, 'Andi Setiyawan, M.Pd', NULL, 'Basket Putra', NULL),
(99959, 'Dewi Nur Setiyaningsih', NULL, 'Basket Putri', NULL),
(99960, 'Yosep Yoga Pranata, S.Pd', NULL, 'Futsal Putra', NULL),
(99961, 'Yokhanan Ardika, M . Pd.', NULL, 'Bulu Tangkis', NULL),
(99987, 'Nugrah Hastana Putra, S.E.', NULL, 'Modern Dance', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penilaian`
--

CREATE TABLE `tbl_penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `no_ekskul` int(11) NOT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `nis` varchar(50) DEFAULT NULL,
  `nama` varchar(256) NOT NULL,
  `nilai` char(100) NOT NULL,
  `sb_keterangan` text DEFAULT NULL,
  `b_keterangan` text DEFAULT NULL,
  `c_keterangan` text DEFAULT NULL,
  `k_keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `nis` int(11) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `kelas` varchar(6) NOT NULL,
  `id_ekskul` int(11) NOT NULL,
  `Ekstrakurikuler` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`nis`, `nama`, `kelas`, `id_ekskul`, `Ekstrakurikuler`) VALUES
(23769, 'Benedictus Christian Dio', 'XIF2', 1, ''),
(23777, 'Brandon Alessandro Johnson', 'XIF3', 1, ''),
(23781, 'Carolina Mereditha Putri', 'XIF4', 1, ''),
(23876, 'Joylien Wijaya', 'XIIF5', 1, ''),
(23890, 'Lie Sherla Artalyta Prabowo', 'XIIF6', 1, ''),
(23899, 'Maria Aurel Abigail', 'XIIF7', 1, ''),
(23903, 'Michael Kenzi Darenth Soetrisno', 'XIIF8', 1, ''),
(23982, 'Afghan Sosrosoegondo', 'XEI', 1, ''),
(24013, 'Beia Apratihata', 'XEI', 1, ''),
(24015, 'Benedictus Evan Pratama Putra', 'XEI', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `jabatan` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `password`, `nama`, `jabatan`) VALUES
(22, 'KenazPramuka', '$2y$10$PT5lmJYH9CqNpEx2RSEEi.eqQ.oaslzsY9CcPJ7KkHhY/9VJ7kDPq', 'Kenaz Manuel', 'Guru'),
(24, 'BramTK', '$2y$10$1IZYDAwJrnHDoeCSzbxq5eekl09Ayo4nX5NPqS8KBg8R258EKIkQa', 'Pak Bram', 'Tim Kurikulum'),
(25, 'AndriKarate', '$2y$10$b0QWJzuKKZUiolm16khIxOuHsGGaRxpi9HgEwtniwmtG.GuKLLhuW', 'Nathanael Andri', 'Guru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `fk_ekskul_absensi` (`no_ekskul`),
  ADD KEY `fk_siswa_absensi` (`nis`),
  ADD KEY `fk_absensi_ekskul_20251112` (`id_ekskul`),
  ADD KEY `fk_absensi_guru_20251112` (`id_guru`);

--
-- Indexes for table `tbl_ekstrakulikuler`
--
ALTER TABLE `tbl_ekstrakulikuler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_guru_user` (`id_user`),
  ADD KEY `id_ekskul` (`id_ekskul`);

--
-- Indexes for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `fk_penilaian_ekskul` (`no_ekskul`),
  ADD KEY `fk_penilaian_siswa` (`nis`),
  ADD KEY `fk_penilaian_guru` (`id_guru`);

--
-- Indexes for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `fk_siswa_ekskul` (`id_ekskul`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_ekstrakulikuler`
--
ALTER TABLE `tbl_ekstrakulikuler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99988;

--
-- AUTO_INCREMENT for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `nis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24026;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD CONSTRAINT `fk_absensi_ekskul_20251112` FOREIGN KEY (`id_ekskul`) REFERENCES `tbl_ekstrakulikuler` (`id`),
  ADD CONSTRAINT `fk_absensi_ekskul_final` FOREIGN KEY (`id_ekskul`) REFERENCES `tbl_ekstrakulikuler` (`id`),
  ADD CONSTRAINT `fk_absensi_guru` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id`),
  ADD CONSTRAINT `fk_absensi_guru_20251112` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id`),
  ADD CONSTRAINT `fk_absensi_guru_final` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id`),
  ADD CONSTRAINT `fk_absensi_siswa_20251112` FOREIGN KEY (`nis`) REFERENCES `tbl_siswa` (`nis`);

--
-- Constraints for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD CONSTRAINT `fk_guru_user` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id`),
  ADD CONSTRAINT `tbl_guru_ibfk_1` FOREIGN KEY (`id_ekskul`) REFERENCES `tbl_ekstrakulikuler` (`id`);

--
-- Constraints for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD CONSTRAINT `fk_penilaian_ekskul` FOREIGN KEY (`no_ekskul`) REFERENCES `tbl_ekstrakulikuler` (`id`),
  ADD CONSTRAINT `fk_penilaian_guru` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id`);

--
-- Constraints for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD CONSTRAINT `fk_siswa_ekskul` FOREIGN KEY (`id_ekskul`) REFERENCES `tbl_ekstrakulikuler` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
