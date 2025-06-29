SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

-- Database: sims2

-- Table: kelas
CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL AUTO_INCREMENT,
  `kelas_nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`kelas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `kelas` (`kelas_nama`) VALUES
('X IPA 1'),
('X IPA 2'),
('X IPA 3'),
('X IPS 1'),
('X IPS 2'),
('XI IPA 1'),
('XI IPA 2'),
('XI IPA 3'),
('XI IPS 1'),
('XI IPS 2'),
('XII IPA 1'),
('XII IPA 2'),
('XII IPA 3'),
('XII IPS 1'),
('XII IPS 2');

-- Table: pelajaran
CREATE TABLE `pelajaran` (
  `pelajaran_id` int(11) NOT NULL AUTO_INCREMENT,
  `pelajaran_nama` varchar(50) NOT NULL,
  PRIMARY KEY (`pelajaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pelajaran` (`pelajaran_nama`) VALUES
('Matematika'),
('Bahasa Indonesia'),
('Bahasa Inggris'),
('Fisika'),
('Kimia'),
('Biologi'),
('Sejarah'),
('Geografi'),
('Ekonomi'),
('Sosiologi'),
('PPKN'),
('Pendidikan Agama'),
('Seni Budaya'),
('Pendidikan Jasmani'),
('Prakarya');

-- Table: sekolah
CREATE TABLE `sekolah` (
  `sekolah_id` int(11) NOT NULL AUTO_INCREMENT,
  `sekolah_nama` varchar(100) DEFAULT NULL,
  `sekolah_alamat` text,
  `sekolah_telp` varchar(16) DEFAULT NULL,
  `sekolah_visi` text,
  `sekolah_misi` text,
  PRIMARY KEY (`sekolah_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `sekolah` (`sekolah_nama`, `sekolah_alamat`, `sekolah_telp`, `sekolah_visi`, `sekolah_misi`) VALUES
('SMA Negeri 1 Jakarta', 'Jl. Merdeka No. 123, Jakarta Pusat, DKI Jakarta 10110', '021-3456789', 'Menjadi sekolah unggulan yang menghasilkan lulusan berkualitas, berakhlak mulia, dan berdaya saing global.', 'Menyelenggarakan pendidikan berkualitas, mengembangkan potensi siswa secara optimal, menciptakan lingkungan belajar yang kondusif, dan membangun karakter siswa yang berintegritas.');

-- Table: semester
CREATE TABLE `semester` (
  `semester_id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_nama` int(11) NOT NULL,
  PRIMARY KEY (`semester_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `semester` (`semester_id`, `semester_nama`) VALUES
(1, 1),
(2, 2);

-- Table: tahun
CREATE TABLE `tahun` (
  `tahun_id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_nama` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`tahun_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tahun` (`tahun_nama`) VALUES
('2023/2024'),
('2024/2025'),
('2025/2026');

-- Table: users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_induk` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `telp` varchar(16) DEFAULT NULL,
  `alamat` text,
  `status` varchar(25) DEFAULT NULL,
  `jenis_kelamin` varchar(25) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `access` varchar(30) DEFAULT NULL,
  `pelajaran_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_id` (`kelas_id`),
  KEY `pelajaran_id` (`pelajaran_id`),
  CONSTRAINT `fk_users_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`),
  CONSTRAINT `fk_users_pelajaran` FOREIGN KEY (`pelajaran_id`) REFERENCES `pelajaran` (`pelajaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `nomor_induk`, `name`, `username`, `password`, `telp`, `alamat`, `status`, `jenis_kelamin`, `kelas_id`, `access`, `pelajaran_id`) VALUES
(10, NULL, 'Administrator', 'admin', '$2y$10$1ZXHCQ/21Ylk5QK3GP2mHOfpnjwlgbYawk6F.iKuOzQBNr6SLtZMG', '085761167894', 'Padang', 'PNS', 'Perempuan', NULL, 'admin', 1),
(27, '1302116703680001', 'Dra. Yanti. SY', 'guru', 'giri1', '0812345678', 'padang', 'PNS', 'Perempuan', 1, 'guru', 1),
(29, '1302116808710001', 'Nelly Hidayati S.Ag', 'guru2', 'guru2', '0897627817', 'solok', 'Honorer', 'Perempuan', 1, 'guru', 2),
(30, '1302090806790001', 'Fadli, S.Si', 'guru3', 'guru3', '081281278379', 'kota solok', 'Honorer', 'Laki-laki', 2, 'guru', 3),
(31, '1372016311080001', 'Ainiyatul Mardhiyah', '0008878611', 'Ad878611', '0812345678', 'solok', 'Aktif', 'Perempuan', 1, 'orang_tua', NULL),
(32, '1234567890', 'widia', 'gurumtk', '$2y$10$CrrKg0vI0gxAWyfweWaJ/uc6e', '087654322', 'jl. kenangan', 'PNS', 'Perempuan', 2, 'guru', 4),
(33, '22', 'devi', 'devi', '$2y$10$861qRNTB7gUxDscIEbWfZOHio', '09776543', 'vb', 'Honorer', 'Perempuan', 3, 'guru', NULL),
(34, '44', 'devi', 'devi', '$2y$10$3HZjes3Qf03sehLMl29fm.Agy', '09876543', 'xx', 'Honorer', 'Laki-laki', NULL, 'guru', NULL),
(35, '89', 'devi', 'kk', '$2y$10$zP2PpcuBndEMK.yGlrH2TuZDx', '09876543', '98', 'Honorer', 'Laki-laki', 6, 'guru', 1),
(36, '1234', 'uci', 'uci', '$2y$10$dwoj0XOvt3/3orPImFD/aujDl', '0987654', 'xx', 'Honorer', 'Laki-laki', 1, 'guru', 1),
(37, '1', '1', '1', '$2y$10$2BKwCwSbMXM/BdslLDj6XuxrDv8uytpHKOWM.PGzdzgOGuS8R4F96', '098765432', 'Jl. Raja M Tahir, Teluk Tering, Kec. Batam Kota, Kota Batam, 29461', 'Honorer', 'Laki-laki', 1, 'guru', 1);

-- Table: guru_mengajar
CREATE TABLE `guru_mengajar` (
  `guru_mengajar_id` int(11) NOT NULL AUTO_INCREMENT,
  `guru_id` int(11) NOT NULL,
  `pelajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tahun_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`guru_mengajar_id`),
  KEY `guru_id` (`guru_id`),
  KEY `pelajaran_id` (`pelajaran_id`),
  KEY `kelas_id` (`kelas_id`),
  KEY `tahun_id` (`tahun_id`),
  KEY `semester_id` (`semester_id`),
  UNIQUE KEY `unique_guru_mengajar` (`guru_id`, `pelajaran_id`, `kelas_id`, `tahun_id`, `semester_id`),
  CONSTRAINT `fk_guru_mengajar_guru` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_pelajaran` FOREIGN KEY (`pelajaran_id`) REFERENCES `pelajaran` (`pelajaran_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun` (`tahun_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_guru_mengajar_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `guru_mengajar` (`guru_id`, `pelajaran_id`, `kelas_id`, `tahun_id`, `semester_id`) VALUES
(10, 1, 1, 2, 1),  
(10, 1, 2, 2, 1), 
(10, 1, 3, 2, 1);


-- Table: nilai
CREATE TABLE `nilai` (
  `nilai_id` int(11) NOT NULL AUTO_INCREMENT,
  `guru_mengajar_id` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `pelajaran_id` int(11) DEFAULT NULL,
  `tahun_id` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `nilai_kkm` int(11) DEFAULT NULL,
  `uh` int(12) DEFAULT NULL,
  `pas` int(12) DEFAULT NULL,
  `p5ra` int(12) DEFAULT NULL,
  `tugas` int(12) DEFAULT NULL,
  `kehadiran` int(12) DEFAULT NULL,
  `keaktifan` int(12) DEFAULT NULL,
  `kekompakan` int(12) DEFAULT NULL,
  `nilai_akhir` int(20) DEFAULT NULL,
  PRIMARY KEY (`nilai_id`),
  KEY `siswa_id` (`id`),
  KEY `pelajaran_id` (`pelajaran_id`),
  KEY `tahunajaran_id` (`tahun_id`),
  KEY `semester_id` (`semester_id`),
  KEY `guru_mengajar_id` (`guru_mengajar_id`),
  CONSTRAINT `fk_nilai_users` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_nilai_pelajaran` FOREIGN KEY (`pelajaran_id`) REFERENCES `pelajaran` (`pelajaran_id`),
  CONSTRAINT `fk_nilai_tahun` FOREIGN KEY (`tahun_id`) REFERENCES `tahun` (`tahun_id`),
  CONSTRAINT `fk_nilai_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`),
  CONSTRAINT `fk_nilai_guru_mengajar` FOREIGN KEY (`guru_mengajar_id`) REFERENCES `guru_mengajar` (`guru_mengajar_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `nilai` (`guru_mengajar_id`, `id`, `pelajaran_id`, `tahun_id`, `semester_id`, `nilai_kkm`, `uh`, `pas`, `p5ra`, `tugas`, `kehadiran`, `keaktifan`, `kekompakan`, `nilai_akhir`) VALUES
(1, 31, 1, 2, 1, 75, 85, 88, 82, 90, 95, 85, 80, 86),
(2, 31, 1, 2, 1, 75, 78, 85, 80, 88, 92, 82, 85, 83),
(3, 31, 1, 2, 1, 75, 82, 90, 85, 92, 98, 88, 87, 88);

COMMIT;