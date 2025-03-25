<?php
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
	}
?>
