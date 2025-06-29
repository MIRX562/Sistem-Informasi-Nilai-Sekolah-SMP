<?php
include "config/db.php";

// Delete admin
if (isset($_GET['admin-del'])) {
	$id = mysqli_real_escape_string($conn, $_GET['admin-del']);

	$query = mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND access='admin'");

	if ($query) {
		echo "<script>alert('Data berhasil dihapus!'); window.location='?users=admin';</script>";
	} else {
		echo "<script>alert('Data gagal dihapus!'); window.location='?users=admin';</script>";
	}
}

// Delete guru
if (isset($_GET['guru-del'])) {
	$id = mysqli_real_escape_string($conn, $_GET['guru-del']);

	$query = mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND access='guru'");

	if ($query) {
		echo "<script>alert('Data berhasil dihapus!'); window.location='?users=guru';</script>";
	} else {
		echo "<script>alert('Data gagal dihapus!'); window.location='?users=guru';</script>";
	}
}

// Delete siswa
if (isset($_GET['siswa-del'])) {
	$id = mysqli_real_escape_string($conn, $_GET['siswa-del']);

	$query = mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND access='orang_tua'");

	if ($query) {
		echo "<script>alert('Data berhasil dihapus!'); window.location='?users=siswa';</script>";
	} else {
		echo "<script>alert('Data gagal dihapus!'); window.location='?users=siswa';</script>";
	}
}

// Delete nilai
if (isset($_GET['nilai-del'])) {
	$id = mysqli_real_escape_string($conn, $_GET['nilai-del']);

	$query = mysqli_query($conn, "DELETE FROM nilai WHERE nilai_id='$id'");

	if ($query) {
		echo "<script>alert('Data berhasil dihapus!'); window.location='index.php?nilai=tampil';</script>";
	} else {
		echo "<script>alert('Data gagal dihapus!');window.location='index.php?nilai=tampil';</script>";
	}
}

// Delete artikel
if (isset($_GET['artikel-del'])) {
	$id = mysqli_real_escape_string($conn, $_GET['artikel-del']);

	$query = mysqli_query($conn, "DELETE FROM artikel WHERE artikel_id='$id'");

	if ($query) {
		echo "<script>alert('Data berhasil dihapus!'); window.location='?artikel=list';</script>";
	} else {
		echo "<script>alert('Data gagal dihapus!'); window.location='?artikel=list';</script>";
	}
}

// Delete kategori
if (isset($_GET['kategori-del'])) {
	$id = mysqli_real_escape_string($conn, $_GET['kategori-del']);

	$query = mysqli_query($conn, "DELETE FROM kategori WHERE kategori_id='$id'");

	if ($query) {
		echo "<script>alert('Data berhasil dihapus!'); window.location='?artikel=kategori';</script>";
	} else {
		echo "<script>alert('Data gagal dihapus!'); window.location='?artikel=kategori';</script>";
	}
}

// Delete file
if (isset($_GET['file-del'])) {
	$id = mysqli_real_escape_string($conn, $_GET['file-del']);

	// Get file path before deleting record
	$result = mysqli_query($conn, "SELECT file_link FROM file WHERE file_id='$id'");
	$row = mysqli_fetch_array($result);
	$file_path = "../files/" . $row['file_link'];

	// Delete file from server
	if (file_exists($file_path)) {
		unlink($file_path);
	}

	// Delete record from database
	$query = mysqli_query($conn, "DELETE FROM file WHERE file_id='$id'");

	if ($query) {
		echo "<script>alert('File berhasil dihapus!'); window.location='?modul=download';</script>";
	} else {
		echo "<script>alert('File gagal dihapus!'); window.location='?modul=download';</script>";
	}
}

// Delete sekolah
if (isset($_GET['sekolah-del'])) {
    $id = mysqli_real_escape_string($conn, $_GET['sekolah-del']);

    $query = mysqli_query($conn, "DELETE FROM sekolah WHERE sekolah_id='$id'");

    if ($query) {
        echo "<script>alert('Data sekolah berhasil dihapus!'); window.location='index.php?akademik=sekolah';</script>";
    } else {
        echo "<script>alert('Data sekolah gagal dihapus!'); window.location='index.php?akademik=sekolah';</script>";
    }
}

// Delete kelas
if (isset($_GET['kelas-del'])) {
    $id = mysqli_real_escape_string($conn, $_GET['kelas-del']);

    $query = mysqli_query($conn, "DELETE FROM kelas WHERE kelas_id='$id'");

    if ($query) {
        echo "<script>alert('Data kelas berhasil dihapus!'); window.location='index.php?akademik=kelas';</script>";
    } else {
        echo "<script>alert('Data kelas gagal dihapus!'); window.location='index.php?akademik=kelas';</script>";
    }
}

// Delete pelajaran
if (isset($_GET['pelajaran-del'])) {
    $id = mysqli_real_escape_string($conn, $_GET['pelajaran-del']);

    $query = mysqli_query($conn, "DELETE FROM pelajaran WHERE pelajaran_id='$id'");

    if ($query) {
        echo "<script>alert('Data pelajaran berhasil dihapus!'); window.location='index.php?akademik=pelajaran';</script>";
    } else {
        echo "<script>alert('Data pelajaran gagal dihapus!'); window.location='index.php?akademik=pelajaran';</script>";
    }
}

// Delete tahun
if (isset($_GET['tahun-del'])) {
    $id = mysqli_real_escape_string($conn, $_GET['tahun-del']);

    $query = mysqli_query($conn, "DELETE FROM tahun WHERE tahun_id='$id'");

    if ($query) {
        echo "<script>alert('Data tahun berhasil dihapus!'); window.location='index.php?akademik=tahun';</script>";
    } else {
        echo "<script>alert('Data tahun gagal dihapus!'); window.location='index.php?akademik=tahun';</script>";
    }
}

?>