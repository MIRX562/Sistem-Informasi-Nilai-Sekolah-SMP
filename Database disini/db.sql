-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sims2
-- ------------------------------------------------------
-- Server version	9.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `guru_mengajar`
--

DROP TABLE IF EXISTS `guru_mengajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guru_mengajar` (
  `guru_mengajar_id` int NOT NULL AUTO_INCREMENT,
  `guru_id` int NOT NULL,
  `pelajaran_id` int NOT NULL,
  `kelas_id` int NOT NULL,
  `tahun_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`guru_mengajar_id`),
  UNIQUE KEY `unique_guru_mengajar` (`guru_id`,`pelajaran_id`,`kelas_id`,`tahun_id`,`semester_id`),
  KEY `guru_id` (`guru_id`),
  KEY `pelajaran_id` (`pelajaran_id`),
  KEY `kelas_id` (`kelas_id`),
  KEY `tahun_id` (`tahun_id`),
  KEY `semester_id` (`semester_id`),
  CONSTRAINT `fk_guru_mengajar_guru` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_pelajaran` FOREIGN KEY (`pelajaran_id`) REFERENCES `pelajaran` (`pelajaran_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun` (`tahun_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `kelas_id` int NOT NULL AUTO_INCREMENT,
  `kelas_nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`kelas_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nilai`
--

DROP TABLE IF EXISTS `nilai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai` (
  `nilai_id` int NOT NULL AUTO_INCREMENT,
  `guru_mengajar_id` int DEFAULT NULL,
  `id` int DEFAULT NULL,
  `pelajaran_id` int DEFAULT NULL,
  `tahun_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `nilai_kkm` int DEFAULT NULL,
  `uh` int DEFAULT NULL,
  `pas` int DEFAULT NULL,
  `p5ra` int DEFAULT NULL,
  `tugas` int DEFAULT NULL,
  `kehadiran` int DEFAULT NULL,
  `keaktifan` int DEFAULT NULL,
  `kekompakan` int DEFAULT NULL,
  `nilai_akhir` int DEFAULT NULL,
  PRIMARY KEY (`nilai_id`),
  KEY `siswa_id` (`id`),
  KEY `pelajaran_id` (`pelajaran_id`),
  KEY `tahunajaran_id` (`tahun_id`),
  KEY `semester_id` (`semester_id`),
  KEY `guru_mengajar_id` (`guru_mengajar_id`),
  CONSTRAINT `fk_nilai_guru_mengajar` FOREIGN KEY (`guru_mengajar_id`) REFERENCES `guru_mengajar` (`guru_mengajar_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_nilai_pelajaran` FOREIGN KEY (`pelajaran_id`) REFERENCES `pelajaran` (`pelajaran_id`),
  CONSTRAINT `fk_nilai_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`),
  CONSTRAINT `fk_nilai_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun` (`tahun_id`),
  CONSTRAINT `fk_nilai_users` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pelajaran`
--

DROP TABLE IF EXISTS `pelajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pelajaran` (
  `pelajaran_id` int NOT NULL AUTO_INCREMENT,
  `pelajaran_nama` varchar(50) NOT NULL,
  PRIMARY KEY (`pelajaran_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sekolah`
--

DROP TABLE IF EXISTS `sekolah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sekolah` (
  `sekolah_id` int NOT NULL AUTO_INCREMENT,
  `sekolah_nama` varchar(100) DEFAULT NULL,
  `sekolah_alamat` text,
  `sekolah_telp` varchar(16) DEFAULT NULL,
  `sekolah_visi` text,
  `sekolah_misi` text,
  PRIMARY KEY (`sekolah_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `semester`
--

DROP TABLE IF EXISTS `semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semester` (
  `semester_id` int NOT NULL AUTO_INCREMENT,
  `semester_nama` int NOT NULL,
  PRIMARY KEY (`semester_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `semester` (`semester_id`, `semester_nama`) VALUES
(1, 1),
(2, 2);
--
-- Table structure for table `tahun`
--

DROP TABLE IF EXISTS `tahun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tahun` (
  `tahun_id` int NOT NULL AUTO_INCREMENT,
  `tahun_nama` varchar(20) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'nonaktif',
  PRIMARY KEY (`tahun_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomor_induk` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `telp` varchar(16) DEFAULT NULL,
  `alamat` text,
  `status` varchar(25) DEFAULT NULL,
  `jenis_kelamin` varchar(25) DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `access` varchar(30) DEFAULT NULL,
  `pelajaran_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_id` (`kelas_id`),
  KEY `pelajaran_id` (`pelajaran_id`),
  CONSTRAINT `fk_users_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`),
  CONSTRAINT `fk_users_pelajaran` FOREIGN KEY (`pelajaran_id`) REFERENCES `pelajaran` (`pelajaran_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `users` (`nomor_induk`, `name`, `username`, `password`, `telp`, `alamat`, `status`, `jenis_kelamin`, `kelas_id`, `access`, `pelajaran_id`) VALUES
(NULL, 'Administrator', 'admin', '$2y$10$1ZXHCQ/21Ylk5QK3GP2mHOfpnjwlgbYawk6F.iKuOzQBNr6SLtZMG', '085761167894', 'Padang', 'PNS', 'Perempuan', NULL, 'admin', 1)
