<?php
include "../config/db.php";

// Create admin
if (isset($_POST['create-admin'])) {
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
	$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
	$access = 'admin';

	$query = mysqli_query($conn, "INSERT INTO users (username, password, name, telp, status, jenis_kelamin, alamat, access) 
                                 VALUES ('$username', '$password', '$name', '$telp', '$status', '$jenis_kelamin', '$alamat', '$access')");

	if ($query) {
		echo "<script>alert('Data berhasil ditambahkan!'); window.location='?users=admin';</script>";
	} else {
		echo "<script>alert('Data gagal ditambahkan!'); window.location='?users=admin-create';</script>";
	}
}

// Create guru
if (isset($_POST['create-guru'])) {
	$nip = mysqli_real_escape_string($conn, $_POST['nip']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
	$pelajaran_id = mysqli_real_escape_string($conn, $_POST['pelajaran_id']);
	$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
	$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
	$access = 'guru';

	$query = mysqli_query($conn, "INSERT INTO users (nomor_induk, username, password, name, telp, status, kelas_id, pelajaran_id, jenis_kelamin, alamat, access) 
                                 VALUES ('$nip', '$username', '$password', '$name', '$telp', '$status', '$kelas', '$pelajaran_id', '$jenis_kelamin', '$alamat', '$access')");

	if ($query) {
		echo "<script>alert('Data berhasil ditambahkan!'); window.location='?users=guru';</script>";
	} else {
		echo "<script>alert('Data gagal ditambahkan!'); window.location='?users=guru-create';</script>";
	}
}

// Create siswa
if (isset($_POST['create-siswa'])) {
	$nis = mysqli_real_escape_string($conn, $_POST['nis']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
	$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
	$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
	$access = 'orang_tua';

	$query = mysqli_query($conn, "INSERT INTO users (nomor_induk, username, password, name, telp, status, kelas_id, jenis_kelamin, alamat, access) 
                                 VALUES ('$nis', '$username', '$password', '$name', '$telp', '$status', '$kelas', '$jenis_kelamin', '$alamat', '$access')");

	if ($query) {
		echo "<script>alert('Data berhasil ditambahkan!'); window.location='?users=siswa';</script>";
	} else {
		echo "<script>alert('Data gagal ditambahkan!'); window.location='?users=siswa-create';</script>";
	}
}

// Create nilai
if (isset($_POST['input-nilai'])) {
	if (isset($_POST['name'])) {
		foreach ($_POST['name'] as $key => $val) {
			$id = mysqli_real_escape_string($conn, $_POST['name'][$key]);
			$pelajaran = mysqli_real_escape_string($conn, $_POST['pelajaran'][$key]);
			$semester = mysqli_real_escape_string($conn, $_POST['semester'][$key]);
			$tahun = mysqli_real_escape_string($conn, $_POST['tahun'][$key]);
			$kkm = mysqli_real_escape_string($conn, $_POST['kkm'][$key]);
			$uh = mysqli_real_escape_string($conn, $_POST['uh'][$key]);
			$pas = mysqli_real_escape_string($conn, $_POST['pas'][$key]);
			$p5ra = !empty($_POST['p5ra'][$key]) ? mysqli_real_escape_string($conn, $_POST['p5ra'][$key]) : NULL;
			$tugas = mysqli_real_escape_string($conn, $_POST['tugas'][$key]);
			$kehadiran = mysqli_real_escape_string($conn, $_POST['kehadiran'][$key]);
			$keaktifan = mysqli_real_escape_string($conn, $_POST['keaktifan'][$key]);
			$kekompakan = mysqli_real_escape_string($conn, $_POST['kekompakan'][$key]);

			// Calculate final grade based on whether P5RA exists
			if (!empty($p5ra)) {
				// With P5RA
				$nilai_akhir = ($uh * 0.20) + ($pas * 0.30) + ($p5ra * 0.20) +
					($tugas * 0.15) + ($kehadiran * 0.05) +
					($keaktifan * 0.05) + ($kekompakan * 0.05);
			} else {
				// Without P5RA
				$nilai_akhir = ($uh * 0.25) + ($pas * 0.35) + ($tugas * 0.20) +
					($kehadiran * 0.075) + ($keaktifan * 0.0625) +
					($kekompakan * 0.0625);
			}

			$query = mysqli_query($conn, "INSERT INTO nilai (id, pelajaran_id, semester_id, tahun_id, nilai_kkm, uh, pas, p5ra, tugas, kehadiran, keaktifan, kekompakan, nilai_akhir) 
                                        VALUES ('$id', '$pelajaran', '$semester', '$tahun', '$kkm', '$uh', '$pas', " . ($p5ra ? "'$p5ra'" : "NULL") . ", '$tugas', '$kehadiran', '$keaktifan', '$kekompakan', '$nilai_akhir')");
		}
		if ($query) {
			echo "<script>alert('Data berhasil ditambahkan!'); window.location='?nilai=tampil';</script>";
		} else {
			echo "<script>alert('Data gagal ditambahkan!'); window.location='?nilai=input';</script>";
		}
	}
}

// Create artikel
if (isset($_POST['create-artikel'])) {
	$judul = mysqli_real_escape_string($conn, $_POST['judul']);
	$isi = mysqli_real_escape_string($conn, $_POST['isi']);
	$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
	$tanggal = date('Y-m-d H:i:s');

	$query = mysqli_query($conn, "INSERT INTO artikel (artikel_judul, artikel_isi, artikel_tgl, kategori_id) 
                                 VALUES ('$judul', '$isi', '$tanggal', '$kategori')");

	if ($query) {
		echo "<script>alert('Data berhasil ditambahkan!'); window.location='?artikel=list';</script>";
	} else {
		echo "<script>alert('Data gagal ditambahkan!'); window.location='?artikel=artikel-create';</script>";
	}
}

// Create kategori
if (isset($_POST['create-kategori'])) {
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

	$query = mysqli_query($conn, "INSERT INTO kategori (kategori_nama, kategori_desk) VALUES ('$nama', '$deskripsi')");

	if ($query) {
		echo "<script>alert('Data berhasil ditambahkan!'); window.location='?artikel=kategori';</script>";
	} else {
		echo "<script>alert('Data gagal ditambahkan!'); window.location='?artikel=kategori-create';</script>";
	}
}

// Upload file
if (isset($_POST['upload'])) {
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

	$file = $_FILES['file']['name'];
	$tmp = $_FILES['file']['tmp_name'];
	$path = "../files/" . $file;

	if (move_uploaded_file($tmp, $path)) {
		$query = mysqli_query($conn, "INSERT INTO file (file_nama, file_keterangan, file_link) 
                                    VALUES ('$nama', '$keterangan', '$file')");

		if ($query) {
			echo "<script>alert('File berhasil diupload!'); window.location='?modul=download';</script>";
		} else {
			echo "<script>alert('File gagal diupload!'); window.location='?modul=upload';</script>";
		}
	}
}

// Create sekolah
if (isset($_POST['create-sekolah'])) {
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$visi = mysqli_real_escape_string($conn, $_POST['visi']);
	$misi = mysqli_real_escape_string($conn, $_POST['misi']);

	$query = mysqli_query($conn, "INSERT INTO sekolah (sekolah_nama, sekolah_alamat, sekolah_telp, sekolah_visi, sekolah_misi) 
                                 VALUES ('$nama', '$alamat', '$telp', '$visi', '$misi')");

	if ($query) {
		echo "<script>alert('Data berhasil ditambahkan!'); window.location='index.php?akademik=sekolah';</script>";
	} else {
		echo "<script>alert('Data gagal ditambahkan!'); window.location='?sekolah=create';</script>";
	}
}

// Create tahun
if (isset($_POST['create-tahun'])) {
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);

    $query = mysqli_query($conn, "INSERT INTO tahun (tahun_nama) VALUES ('$tahun')");

    if ($query) {
        echo "<script>alert('Tahun ajaran berhasil ditambahkan!'); window.location='?akademik=tahun';</script>";
    } else {
        echo "<script>alert('Tahun ajaran gagal ditambahkan!'); window.location='?akademik=tahun-create';</script>";
    }
}

?>