-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Bulan Mei 2025 pada 06.21
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sims2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `artikel`
--

CREATE TABLE `artikel` (
  `artikel_id` int(11) NOT NULL,
  `artikel_judul` varchar(255) NOT NULL,
  `artikel_isi` text NOT NULL,
  `artikel_tgl` datetime NOT NULL,
  `kategori_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `artikel`
--

INSERT INTO `artikel` (`artikel_id`, `artikel_judul`, `artikel_isi`, `artikel_tgl`, `kategori_id`) VALUES
(51, 'aa', '<span>aa<br></span>', '2015-04-29 20:45:44', 3),
(58, 'asda', 'Buku GRATIS !!!<span> Teman-teman saya mau bagi buku gratis nih, syarat nya:<br> 1. Like dan share status ini<br> 2. Invite pin saya 53DEA7F1<span><br> 3. Silakan tulis di kolom komentar dengan impian terbesar teman-teman</span></span> Karena buku nya tinggal satu pcs, maka komentar terakhir yang mendapatkan like dari saya yang akan mendapatkan buku ini. Pemenang akan saya umumkan besok siang<br> Selamat mencoba, semoga anda beruntung <br>', '2015-04-29 01:11:06', 1),
(59, 'Asdasdwdcadscacasc', 'Buku GRATIS !!!<span> Teman-teman saya mau bagi buku gratis nih, syarat nya:<br> 1. Like dan share status ini<br> 2. Invite pin saya 53DEA7F1<span><br> 3. Silakan tulis di kolom komentar dengan impian terbesar teman-teman</span></span> Karena buku nya tinggal satu pcs, maka komentar terakhir yang mendapatkan like dari saya yang akan mendapatkan buku ini. Pemenang akan saya umumkan besok siang<br> Selamat mencoba, semoga anda beruntung <br>', '2015-04-29 01:11:11', 1),
(60, 'asda', 'Buku GRATIS !!!<span> Teman-teman saya mau bagi buku gratis nih, syarat nya:<br> 1. Like dan share status ini<br> 2. Invite pin saya 53DEA7F1<span><br> 3. Silakan tulis di kolom komentar dengan impian terbesar teman-teman</span></span> Karena buku nya tinggal satu pcs, maka komentar terakhir yang mendapatkan like dari saya yang akan mendapatkan buku ini. Pemenang akan saya umumkan besok siang<br> Selamat mencoba, semoga anda beruntung <br>', '2015-04-29 01:11:06', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `galeri_id` int(11) NOT NULL,
  `galeri_nama` varchar(100) NOT NULL,
  `galeri_keterangan` text NOT NULL,
  `galeri_link` text NOT NULL,
  `galeri_tgl` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `galeri`
--

INSERT INTO `galeri` (`galeri_id`, `galeri_nama`, `galeri_keterangan`, `galeri_link`, `galeri_tgl`) VALUES
(31, 'Ary', 'Ary', 'uploads/C360_2015-01-03-14-20-25-428.jpg', '0000-00-00 00:00:00'),
(32, 'asd', 'asd', 'uploads/C360_2015-01-03-14-20-52-982.jpg', '0000-00-00 00:00:00'),
(34, 'ggg', 'ggg', 'uploads/C360_2015-01-03-15-51-19-682.jpg', '0000-00-00 00:00:00'),
(36, 's', 'ss', 'uploads/C360_2015-01-03-15-50-31-526.jpg', '0000-00-00 00:00:00'),
(38, 'dfsdf', 'fsdf', 'uploads/C360_2014-07-13-19-27-14-435.jpg', '0000-00-00 00:00:00'),
(39, 'dsfsd', 'fsfsdfsdf', 'uploads/20140826_090837.jpg', '0000-00-00 00:00:00'),
(40, 'bbb', 'bb', 'uploads/20140826_091057.jpg', '0000-00-00 00:00:00'),
(41, 'ASdasdasd', 'asdasd', 'uploads/apple_vnbwfjei.jpg', '2015-03-27 23:02:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL,
  `kategori_nama` varchar(50) NOT NULL,
  `kategori_deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `kategori_nama`, `kategori_deskripsi`) VALUES
(1, 'Hot News', 'Berisikan Berita Terpanas'),
(2, 'School News', 'Berisikan Berita Terupdate Sekolah'),
(3, 'Puisi', 'Tulisan Bertemakan Puisi'),
(4, 'Cerpen', 'Kumpulan Cerita Pendek');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `kelas_nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`kelas_id`, `kelas_nama`) VALUES
(1, 'X-1'),
(2, 'X-2'),
(3, 'X-3'),
(4, 'X-4'),
(5, 'X-5'),
(6, 'X-6');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `nilai_id` int(11) NOT NULL,
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
  `nilai_akhir` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`nilai_id`, `id`, `pelajaran_id`, `tahun_id`, `semester_id`, `nilai_kkm`, `uh`, `pas`, `p5ra`, `tugas`, `kehadiran`, `keaktifan`, `kekompakan`, `nilai_akhir`) VALUES
(134, 31, 1, 1, 1, 70, 0, 0, 0, 0, 0, 0, 0, 0),
(135, 31, 2, 1, 1, 70, 0, 0, 0, 0, 0, 0, 0, 0),
(136, 31, 3, 1, 1, 70, 0, 0, 0, 0, 0, 0, 0, 0),
(137, 31, 1, 1, 1, 70, 0, 0, 0, 0, 0, 0, 0, 0),
(140, 31, 1, 1, 1, 70, 80, 85, 80, 0, 90, 85, 75, 70),
(141, 31, 1, 1, 1, 70, 80, 85, 80, 0, 90, 85, 75, 70),
(142, 31, 1, 1, 1, 70, 1, 2, 3, 0, 5, 6, 7, 2),
(143, 31, 1, 1, 1, 70, 1, 2, 3, 4, 5, 6, 7, 3),
(144, 31, 1, 1, 1, 70, 80, 95, 90, 89, 85, 85, 75, 88),
(145, 31, 1, 2, 1, 70, 90, 85, 88, 80, 90, 80, 75, 85);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelajaran`
--

CREATE TABLE `pelajaran` (
  `pelajaran_id` int(11) NOT NULL,
  `pelajaran_nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pelajaran`
--

INSERT INTO `pelajaran` (`pelajaran_id`, `pelajaran_nama`) VALUES
(1, 'MTK'),
(2, 'BIO'),
(3, 'FIS'),
(4, 'BHS'),
(5, 'INGG');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sekolah`
--

CREATE TABLE `sekolah` (
  `sekolah_id` int(11) NOT NULL,
  `sekolah_nama` varchar(100) DEFAULT NULL,
  `sekolah_alamat` text,
  `sekolah_telp` varchar(16) DEFAULT NULL,
  `sekolah_visi` text,
  `sekolah_misi` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sekolah`
--

INSERT INTO `sekolah` (`sekolah_id`, `sekolah_nama`, `sekolah_alamat`, `sekolah_telp`, `sekolah_visi`, `sekolah_misi`) VALUES
(1, 'SMA N 1 Sungayang', 'Sungayang', '546', '4564asdasda', '456asdasdas'),
(10, 'asdaasd', 'asdasd', 'sdasdasd', 'asd', 'asd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `semester`
--

CREATE TABLE `semester` (
  `semester_id` int(11) NOT NULL,
  `semester_nama` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `semester`
--

INSERT INTO `semester` (`semester_id`, `semester_nama`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun`
--

CREATE TABLE `tahun` (
  `tahun_id` int(11) NOT NULL,
  `tahun_nama` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tahun`
--

INSERT INTO `tahun` (`tahun_id`, `tahun_nama`) VALUES
(2, '2024/2025');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nomor_induk` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `telp` varchar(16) DEFAULT NULL,
  `alamat` text,
  `status` varchar(25) DEFAULT NULL,
  `jenis_kelamin` varchar(25) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `access` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nomor_induk`, `name`, `username`, `password`, `telp`, `alamat`, `status`, `jenis_kelamin`, `kelas_id`, `access`) VALUES
(10, NULL, 'Administrator', 'admin', 'admin', '085761167894', 'Padang', 'PNS', 'Perempuan', NULL, 'admin'),
(25, '56', 'siswa', 'siswa1', 'orangtua', '456', 'frghf', 'PNS', 'Perempuan', 4, 'orang_tua'),
(27, '1302116703680001', 'Dra. Yanti. SY', 'guru', 'giri1', '0812345678', 'padang', 'PNS', 'Perempuan', 1, 'guru'),
(29, '1302116808710001', 'Nelly Hidayati S.Ag', 'guru2', 'guru2', '0897627817', 'solok', 'Honorer', 'Perempuan', 1, 'guru'),
(30, '1302090806790001', 'Fadli, S.Si', 'guru3', 'guru3', '081281278379', 'kota solok', 'Honorer', 'Laki-laki', 1, 'guru'),
(31, '1372016311080001', 'Ainiyatul Mardhiyah', '0008878611', 'Ad878611', '0812345678', 'solok', 'Aktif', 'Perempuan', 1, 'orang_tua');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`artikel_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indeks untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`galeri_id`),
  ADD UNIQUE KEY `galeri_nama` (`galeri_nama`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kelas_id`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`nilai_id`),
  ADD KEY `siswa_id` (`id`),
  ADD KEY `pelajaran_id` (`pelajaran_id`),
  ADD KEY `tahunajaran_id` (`tahun_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indeks untuk tabel `pelajaran`
--
ALTER TABLE `pelajaran`
  ADD PRIMARY KEY (`pelajaran_id`);

--
-- Indeks untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`sekolah_id`);

--
-- Indeks untuk tabel `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indeks untuk tabel `tahun`
--
ALTER TABLE `tahun`
  ADD PRIMARY KEY (`tahun_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `kelas_id_2` (`kelas_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `artikel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `galeri`
--
ALTER TABLE `galeri`
  MODIFY `galeri_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `nilai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT untuk tabel `pelajaran`
--
ALTER TABLE `pelajaran`
  MODIFY `pelajaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `sekolah_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `semester`
--
ALTER TABLE `semester`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tahun`
--
ALTER TABLE `tahun`
  MODIFY `tahun_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
