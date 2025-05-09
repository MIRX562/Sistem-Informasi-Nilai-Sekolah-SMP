<?php
<<<<<<< HEAD
require_once('../config/db.php');
//Login Proses
if (isset($_POST['signin'])) {
	$user = $_POST['username'];
	$pass = $_POST['password'];

	$hasil = mysql_query("SELECT * FROM users WHERE username='$user' AND password='$pass'");
	$data = mysql_fetch_array($hasil);

	$id = $data['id'];
	$no_induk = $data['nomor_induk'];
	$username = $data['username'];
	$password = $data['password'];
	$name = $data['name'];
	$foto = $data['foto'];
	$access = $data['access'];

	if ($user == $username && $pass = $password) {
		session_start();
		$_SESSION['id'] = $id;
		$_SESSION['username'] = $username;
		$_SESSION['name'] = $name;
		$_SESSION['foto'] = $foto;
		$_SESSION['access'] = $access;

		header('Location: ../dashboard/index.php');
=======
	require_once('../config/db.php');

	// Cek apakah tombol signin ditekan
	if(isset($_POST['signin'])){
		
		// Menghindari SQL Injection
		$user = mysqli_real_escape_string($conn, $_POST['username']);
		$pass = mysqli_real_escape_string($conn, $_POST['password']); // Mencegah SQL Injection

		// Query untuk mencari user
		$query = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
		$result = mysqli_query($conn, $query);

		// Periksa apakah user ditemukan
		if ($result && mysqli_num_rows($result) > 0) {
			$data = mysqli_fetch_assoc($result); // Ambil data user

			// Login sukses -> Simpan sesi
			session_start();
			$_SESSION['id']			=	$data['id'];
			$_SESSION['username']	=	$data['username'];
			$_SESSION['name']		=	$data['name'];
			$_SESSION['foto']  		=	$data['foto'];
			$_SESSION['access']		=	$data['access'];

			// Redirect ke halaman utama
			header('Location: ../dashboard/index.php');
			exit();
		} else {
			// Jika login gagal
			echo "Username atau password salah!";
		}

		// Tutup koneksi
		mysqli_close($conn);
>>>>>>> 9c6c95edbcccb84bd1ebe2f42224492e0500262d
	}
}
?>
