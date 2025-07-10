<?php
//Admin Edit 
if (isset($_GET['admin-edit'])) {
	$adminEdit = intval($_GET['admin-edit']);
	$id = mysqli_real_escape_string($conn, $_GET['admin-edit']);

	if (isset($_POST['admin-update'])) {
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$telp = mysqli_real_escape_string($conn, $_POST['telp']);
		$status = mysqli_real_escape_string($conn, $_POST['status']);
		$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
		$kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);

		$admin = mysqli_query($conn, "UPDATE users 
                            SET `name` = '$name', `username` = '$username', `telp` = '$telp', 
                                `alamat` = '$alamat', `status` = '$status', `jenis_kelamin` = '$kelamin'
                            WHERE id = '$id'");
		if ($admin) {
			echo "<meta http-equiv='refresh' content='0;URL=?users=admin'>";
		}
	}

	$dataadmin = mysqli_query($conn, "SELECT * FROM users WHERE id=$id") or die("Error: " . mysqli_error($conn));
	$row = mysqli_fetch_array($dataadmin);
}
?>

<?php
//Guru Edit
if (isset($_GET['guru-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['guru-edit']);

	if (isset($_POST['guru-update'])) {
		$nip = mysqli_real_escape_string($conn, $_POST['nip']);
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$telp = mysqli_real_escape_string($conn, $_POST['telp']);
		$status = mysqli_real_escape_string($conn, $_POST['status']);
		$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
		$kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);

		$guru = mysqli_query($conn, "UPDATE users 
                            SET `nomor_induk` = '$nip', `name` = '$name', `username` = '$username', `telp` = '$telp', 
                                `alamat` = '$alamat', `status` = '$status', `jenis_kelamin` = '$kelamin'
                            WHERE id = '$id'");
		if ($guru) {
			echo "<meta http-equiv='refresh' content='0;URL=?users=guru'>";
		}
	}

	$dataguru = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
	$row = mysqli_fetch_array($dataguru);
}
?>

<?php
//Siswa Edit
if (isset($_GET['siswa-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['siswa-edit']);

	if (isset($_POST['siswa-update'])) {
		$nis = mysqli_real_escape_string($conn, $_POST['nis']);
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$telp = mysqli_real_escape_string($conn, $_POST['telp']);
		$status = mysqli_real_escape_string($conn, $_POST['status']);
		$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
		$kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
		$kelas = mysqli_real_escape_string($conn, $_POST['kelas']);

		$siswa = mysqli_query($conn, "UPDATE users 
                            SET `nomor_induk` = '$nis', `name` = '$name', `username` = '$username', `telp` = '$telp', 
                                `alamat` = '$alamat', `status` = '$status', `jenis_kelamin` = '$kelamin', `kelas_id` = '$kelas'
                            WHERE id = '$id'");
		if ($siswa) {
			echo "<meta http-equiv='refresh' content='0;URL=?users=siswa'>";
		}
	}

	$datasiswa = mysqli_query($conn, "SELECT *, kelas.kelas_id, kelas.kelas_nama FROM users INNER JOIN kelas ON users.kelas_id=kelas.kelas_id WHERE id=$id");
	$row = mysqli_fetch_array($datasiswa);
}
?>
<?php
//Kelas Edit
if (isset($_GET['kelas-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['kelas-edit']);

	if (isset($_POST['kelas-update'])) {
		$kelasnama = mysqli_real_escape_string($conn, $_POST['kelas']);

		$kelas = mysqli_query($conn, "UPDATE kelas SET `kelas_nama` = '$kelasnama' WHERE kelas_id = '$id'");
		if ($kelas) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=kelas'>";
		}
	}

	$datakelas = mysqli_query($conn, "SELECT * FROM kelas WHERE kelas_id=$id");
	$row = mysqli_fetch_array($datakelas);
}
?>
<?php
//Tahun Ajaran Edit
if (isset($_GET['tahun-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['tahun-edit']);

	if (isset($_POST['tahun-update'])) {
		$tahunnama = mysqli_real_escape_string($conn, $_POST['tahun']);

		$tahun = mysqli_query($conn, "UPDATE tahun SET `tahun_nama` = '$tahunnama' WHERE tahun_id = '$id'");
		if ($tahun) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=tahun'>";
		}
	}

	$datatahun = mysqli_query($conn, "SELECT * FROM tahun WHERE tahun_id=$id");
	$row = mysqli_fetch_array($datatahun);
}
?>
<?php
//Mata Pelajaran Edit
if (isset($_GET['pelajaran-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['pelajaran-edit']);

	if (isset($_POST['pelajaran-update'])) {
		$pelajarannama = mysqli_real_escape_string($conn, $_POST['pelajaran']);

		$pelajaran = mysqli_query($conn, "UPDATE pelajaran SET `pelajaran_nama` = '$pelajarannama' WHERE pelajaran_id = '$id'");
		if ($pelajaran) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=pelajaran'>";
		}
	}

	$datapelajaran = mysqli_query($conn, "SELECT * FROM pelajaran WHERE pelajaran_id=$id");
	$row = mysqli_fetch_array($datapelajaran);
}
?>
<?php
//Kategori Edit
if (isset($_GET['kategori-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['kategori-edit']);

	if (isset($_POST['kategori-update'])) {
		$kategorinama = mysqli_real_escape_string($conn, $_POST['nama']);
		$kategorideskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

		$kategori = mysqli_query($conn, "UPDATE kategori SET `kategori_nama` = '$kategorinama', `kategori_deskripsi` = '$kategorideskripsi' WHERE kategori_id = '$id'");
		if ($kategori) {
			echo "<meta http-equiv='refresh' content='0;URL=?artikel=kategori'>";
		}
	}

	$datakategori = mysqli_query($conn, "SELECT * FROM kategori WHERE kategori_id=$id");
	$row = mysqli_fetch_array($datakategori);
}
?>
<?php
//Data Sekolah Edit
if (isset($_GET['sekolah-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['sekolah-edit']);

	if (isset($_POST['sekolah-update'])) {
		$nama = mysqli_real_escape_string($conn, $_POST['nama']);
		$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
		$telp = mysqli_real_escape_string($conn, $_POST['telp']);
		$visi = mysqli_real_escape_string($conn, $_POST['visi']);
		$misi = mysqli_real_escape_string($conn, $_POST['misi']);

		$sekolah = mysqli_query($conn, "UPDATE sekolah 
                            SET `sekolah_nama` = '$nama', `sekolah_alamat` = '$alamat', `sekolah_telp` = '$telp', `sekolah_visi` = '$visi', `sekolah_misi` = '$misi' 
                            WHERE sekolah_id = '$id'");

		if ($sekolah) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=sekolah'>";
		}
	}

	$datasekolah = mysqli_query($conn, "SELECT * FROM sekolah WHERE sekolah_id=$id");
	$row = mysqli_fetch_array($datasekolah);
}
?>
<?php
//Nilai Edit
if (isset($_GET['nilai-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['nilai-edit']);

	if (isset($_POST['nilai-update'])) {
		$kkm = mysqli_real_escape_string($conn, $_POST['kkm']);
		$nilaipoin = mysqli_real_escape_string($conn, $_POST['poin']);

		$nilai = mysqli_query($conn, "UPDATE nilai SET `nilai_kkm` = '$kkm', `nilai_poin` = '$nilaipoin' WHERE nilai_id = '$id'");
		if ($nilai) {
			echo "<meta http-equiv='refresh' content='0;URL=?nilai=tampil'>";
		}
	}

	$datanilai = mysqli_query($conn, "SELECT * FROM nilai WHERE nilai_id=$id");
	$row = mysqli_fetch_array($datanilai);
}
?>
<?php
//Artikel Edit
if (isset($_GET['artikel-edit'])) {
	$id = mysqli_real_escape_string($conn, $_GET['artikel-edit']);

	if (isset($_POST['update-artikel'])) {
		$judul = mysqli_real_escape_string($conn, $_POST['judul']);
		$isi = mysqli_real_escape_string($conn, $_POST['isi']);
		$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

		$artikel = mysqli_query($conn, "UPDATE artikel
                            SET `artikel_judul` = '$judul', `artikel_isi` = '$isi', `artikel_tgl` = now(), `kategori_id` = '$kategori' 
                            WHERE artikel_id = '$id'");

		if ($artikel) {
			echo "<meta http-equiv='refresh' content='0;URL=?artikel=list'>";
		}
	}

	$dataartikel = mysqli_query($conn, "SELECT * FROM artikel WHERE artikel_id=$id");
	$row = mysqli_fetch_array($dataartikel);
}
?>
<?php
// Profile
if (isset($_GET['profile'])) {
	$id = mysqli_real_escape_string($conn, $_GET['profile']);

	$dataprofile = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
	$row = mysqli_fetch_array($dataprofile);
}
?>
<?php
// Password Change
if (isset($_GET['change'])) {
	$id = mysqli_real_escape_string($conn, $_GET['change']);

	if (isset($_POST['change'])) {
		$new = mysqli_real_escape_string($conn, $_POST['new']);

		$password = mysqli_query($conn, "UPDATE users 
                            SET `password` = '$new'
                            WHERE id = '$id'");

		if ($password) {
			echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
		}
	}

	$datapassword = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
	$row = mysqli_fetch_array($datapassword);
}
?>
<?php

// Update admin
if (isset($_POST['admin-update'])) {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
	$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

	$query = mysqli_query($conn, "UPDATE users SET 
                                username='$username',
                                name='$name',
                                telp='$telp',
                                jenis_kelamin='$jenis_kelamin',
                                alamat='$alamat'
                                WHERE id='$id' AND access='admin'");

	if ($query) {
		echo "<script>alert('Data berhasil diupdate!'); window.location='?users=admin';</script>";
	} else {
		echo "<script>alert('Data gagal diupdate!'); window.location='?users=admin';</script>";
	}
}

// Update guru
if (isset($_POST['guru-update'])) {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$nip = mysqli_real_escape_string($conn, $_POST['nip']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
	$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

	$query = mysqli_query($conn, "UPDATE users SET 
                                nomor_induk='$nip',
                                username='$username',
                                name='$name',
                                telp='$telp',
                                status='$status',
                                jenis_kelamin='$jenis_kelamin',
                                alamat='$alamat'
                                WHERE id='$id' AND access='guru'");

	if ($query) {
		echo "<script>alert('Data berhasil diupdate!'); window.location='?users=guru';</script>";
	} else {
		echo "<script>alert('Data gagal diupdate!'); window.location='?users=guru';</script>";
	}
}

// Update siswa
if (isset($_POST['siswa-update'])) {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$nis = mysqli_real_escape_string($conn, $_POST['nis']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$telp = mysqli_real_escape_string($conn, $_POST['telp']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
	$jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
	$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

	$query = mysqli_query($conn, "UPDATE users SET 
                                nomor_induk='$nis',
                                username='$username',
                                name='$name',
                                telp='$telp',
                                status='$status',
                                kelas_id='$kelas',
                                jenis_kelamin='$jenis_kelamin',
                                alamat='$alamat'
                                WHERE id='$id' AND access='siswa'");

	if ($query) {
		echo "<script>alert('Data berhasil diupdate!'); window.location='?users=siswa';</script>";
	} else {
		echo "<script>alert('Data gagal diupdate!'); window.location='?users=siswa';</script>";
	}
}

// Update nilai
if (isset($_POST['nilai-update'])) {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$kkm = mysqli_real_escape_string($conn, $_POST['kkm']);
	$uh = mysqli_real_escape_string($conn, $_POST['uh']);
	$pas = mysqli_real_escape_string($conn, $_POST['pas']);
	$p5ra = !empty($_POST['p5ra']) ? mysqli_real_escape_string($conn, $_POST['p5ra']) : NULL;
	$tugas = mysqli_real_escape_string($conn, $_POST['tugas']);
	$kehadiran = mysqli_real_escape_string($conn, $_POST['kehadiran']);
	$keaktifan = mysqli_real_escape_string($conn, $_POST['keaktifan']);
	$kekompakan = mysqli_real_escape_string($conn, $_POST['kekompakan']);

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

	$query = mysqli_query($conn, "UPDATE nilai SET 
                                nilai_kkm='$kkm',
                                uh='$uh',
                                pas='$pas',
                                p5ra=" . ($p5ra ? "'$p5ra'" : "NULL") . ",
                                tugas='$tugas',
                                kehadiran='$kehadiran',
                                keaktifan='$keaktifan',
                                kekompakan='$kekompakan',
                                nilai_akhir='$nilai_akhir'
                                WHERE nilai_id='$id'");

	if ($query) {
		echo "<script>alert('Data berhasil diupdate!'); window.location='?nilai=tampil';</script>";
	} else {
		echo "<script>alert('Data gagal diupdate!'); window.location='?nilai=tampil';</script>";
	}
}

// Update artikel
if (isset($_POST['artikel-update'])) {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$judul = mysqli_real_escape_string($conn, $_POST['judul']);
	$isi = mysqli_real_escape_string($conn, $_POST['isi']);
	$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

	$query = mysqli_query($conn, "UPDATE artikel SET 
                                artikel_judul='$judul',
                                artikel_isi='$isi',
                                kategori_id='$kategori'
                                WHERE artikel_id='$id'");

	if ($query) {
		echo "<script>alert('Data berhasil diupdate!'); window.location='?artikel=list';</script>";
	} else {
		echo "<script>alert('Data gagal diupdate!'); window.location='?artikel=list';</script>";
	}
}

// Update kategori
if (isset($_POST['kategori-update'])) {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$nama = mysqli_real_escape_string($conn, $_POST['nama']);
	$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

	$query = mysqli_query($conn, "UPDATE kategori SET 
                                kategori_nama='$nama',
                                kategori_desk='$deskripsi'
                                WHERE kategori_id='$id'");

	if ($query) {
		echo "<script>alert('Data berhasil diupdate!'); window.location='?artikel=kategori';</script>";
	} else {
		echo "<script>alert('Data gagal diupdate!'); window.location='?artikel=kategori';</script>";
	}
}

// Update password
if (isset($_POST['password-update'])) {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
	$new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
	$confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

	// Check if old password matches
	$check = mysqli_query($conn, "SELECT password FROM users WHERE id='$id'");
	$row = mysqli_fetch_array($check);

	if (password_verify($old_password, $row['password'])) {
		if ($new_password == $confirm_password) {
			$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
			$query = mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id='$id'");

			if ($query) {
				echo "<script>alert('Password berhasil diupdate!'); window.location='?page=password';</script>";
			} else {
				echo "<script>alert('Password gagal diupdate!'); window.location='?page=password';</script>";
			}
		} else {
			echo "<script>alert('Password baru tidak cocok!'); window.location='?page=password';</script>";
		}
	} else {
		echo "<script>alert('Password lama salah!'); window.location='?page=password';</script>";
	}
}
?>
<?php
//Admin Update
if (isset($_POST['admin-update'])) {
	$id = $_GET['admin-edit'];
	$name = $_POST['name'];
	$telp = $_POST['telp'];
	$status = $_POST['status'];
	$alamat = $_POST['alamat'];
	$kelamin = $_POST['jenis_kelamin'];

	$admin = mysqli_query($conn, "UPDATE users SET 
                                name='" . mysqli_real_escape_string($conn, $name) . "',
                                telp='" . mysqli_real_escape_string($conn, $telp) . "',
                                status='" . mysqli_real_escape_string($conn, $status) . "',
                                alamat='" . mysqli_real_escape_string($conn, $alamat) . "',
                                jenis_kelamin='" . mysqli_real_escape_string($conn, $kelamin) . "'
                                WHERE id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($admin) {
		echo "<meta http-equiv='refresh' content='0;URL=?users=admin'>";
	}
}
?>
<?php
//Guru Update
if (isset($_POST['guru-update'])) {
	$id = $_GET['guru-edit'];
	$nip = $_POST['nip'];
	$name = $_POST['name'];
	$telp = $_POST['telp'];
	$status = $_POST['status'];
	$alamat = $_POST['alamat'];
	$kelamin = $_POST['jenis_kelamin'];

	$guru = mysqli_query($conn, "UPDATE users SET 
                                nomor_induk='" . mysqli_real_escape_string($conn, $nip) . "',
                                name='" . mysqli_real_escape_string($conn, $name) . "',
                                telp='" . mysqli_real_escape_string($conn, $telp) . "',
                                status='" . mysqli_real_escape_string($conn, $status) . "',
                                alamat='" . mysqli_real_escape_string($conn, $alamat) . "',
                                jenis_kelamin='" . mysqli_real_escape_string($conn, $kelamin) . "'
                                WHERE id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($guru) {
		echo "<meta http-equiv='refresh' content='0;URL=?users=guru'>";
	}
}
?>
<?php
//Siswa Update
if (isset($_POST['siswa-update'])) {
	$id = $_GET['siswa-edit'];
	$nis = $_POST['nis'];
	$name = $_POST['name'];
	$telp = $_POST['telp'];
	$status = $_POST['status'];
	$alamat = $_POST['alamat'];
	$kelamin = $_POST['jenis_kelamin'];
	$kelas = $_POST['kelas'];

	$siswa = mysqli_query($conn, "UPDATE users SET 
                                nomor_induk='" . mysqli_real_escape_string($conn, $nis) . "',
                                name='" . mysqli_real_escape_string($conn, $name) . "',
                                telp='" . mysqli_real_escape_string($conn, $telp) . "',
                                status='" . mysqli_real_escape_string($conn, $status) . "',
                                alamat='" . mysqli_real_escape_string($conn, $alamat) . "',
                                jenis_kelamin='" . mysqli_real_escape_string($conn, $kelamin) . "',
                                kelas_id='" . mysqli_real_escape_string($conn, $kelas) . "'
                                WHERE id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($siswa) {
		echo "<meta http-equiv='refresh' content='0;URL=?users=siswa'>";
	}
}
?>
<?php
//Kelas Update
if (isset($_POST['kelas-update'])) {
	$id = $_GET['kelas-edit'];
	$kelasnama = $_POST['kelas'];

	$kelas = mysqli_query($conn, "UPDATE kelas SET 
                                kelas_nama='" . mysqli_real_escape_string($conn, $kelasnama) . "'
                                WHERE kelas_id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($kelas) {
		echo "<meta http-equiv='refresh' content='0;URL=?akademik=kelas'>";
	}
}
?>
<?php
//Tahun Update
if (isset($_POST['tahun-update'])) {
	$id = $_GET['tahun-edit'];
	$tahunnama = $_POST['tahun'];

	$tahun = mysqli_query($conn, "UPDATE tahun SET 
                                tahun_nama='" . mysqli_real_escape_string($conn, $tahunnama) . "'
                                WHERE tahun_id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($tahun) {
		echo "<meta http-equiv='refresh' content='0;URL=?akademik=tahun'>";
	}
}
?>
<?php
//Mata Pelajaran Update
if (isset($_POST['pelajaran-update'])) {
	$id = $_GET['pelajaran-edit'];
	$pelajarannama = $_POST['pelajaran'];

	$pelajaran = mysqli_query($conn, "UPDATE pelajaran SET 
                                    pelajaran_nama='" . mysqli_real_escape_string($conn, $pelajarannama) . "'
                                    WHERE pelajaran_id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($pelajaran) {
		echo "<meta http-equiv='refresh' content='0;URL=?akademik=pelajaran'>";
	}
}
?>

<?php
//Kategori Update
if (isset($_POST['kategori-update'])) {
	$id = $_GET['kategori-edit'];
	$kategorinama = $_POST['nama'];
	$kategorideskripsi = $_POST['deskripsi'];

	$kategori = mysqli_query($conn, "UPDATE kategori SET 
                                    kategori_nama='" . mysqli_real_escape_string($conn, $kategorinama) . "',
                                    kategori_deskripsi='" . mysqli_real_escape_string($conn, $kategorideskripsi) . "'
                                    WHERE kategori_id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($kategori) {
		echo "<meta http-equiv='refresh' content='0;URL=?artikel=kategori'>";
	}
}
?>
<?php
//Password Update
if (isset($_POST['change-password'])) {
	$id = $_GET['change'];
	$password = $_POST['password'];

	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	$change = mysqli_query($conn, "UPDATE users SET 
                                password='" . mysqli_real_escape_string($conn, $hashed_password) . "'\n                                WHERE id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($change) {
		echo "<meta http-equiv='refresh' content='0;URL=?my=profile'>";
	}
}
?>
<?php
//Sekolah Update
if (isset($_POST['sekolah-update'])) {
	$id = $_GET['sekolah-edit'];
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$telp = $_POST['telp'];
	$visi = $_POST['visi'];
	$misi = $_POST['misi'];

	$sekolah = mysqli_query($conn, "UPDATE sekolah SET 
                                    sekolah_nama='" . mysqli_real_escape_string($conn, $nama) . "',
                                    sekolah_alamat='" . mysqli_real_escape_string($conn, $alamat) . "',
                                    sekolah_telp='" . mysqli_real_escape_string($conn, $telp) . "',
                                    sekolah_visi='" . mysqli_real_escape_string($conn, $visi) . "',
                                    sekolah_misi='" . mysqli_real_escape_string($conn, $misi) . "'
                                    WHERE sekolah_id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($sekolah) {
		echo "<meta http-equiv='refresh' content='0;URL=?akademik=sekolah'>";
	}
}
?>
<?php
//Nilai Update
if (isset($_POST['nilai-update'])) {
	$id = $_GET['nilai-edit'];
	$kkm = $_POST['kkm'];
	$nilai = $_POST['nilai'];

	$nilaisiswa = mysqli_query($conn, "UPDATE nilai SET 
                                    nilai_kkm='" . mysqli_real_escape_string($conn, $kkm) . "',
                                    nilai_poin='" . mysqli_real_escape_string($conn, $nilai) . "'
                                    WHERE nilai_id='" . mysqli_real_escape_string($conn, $id) . "'");

	if ($nilaisiswa) {
		echo "<meta http-equiv='refresh' content='0;URL=?nilai=tampil'>";
	}
}
?>