<?php
session_start();
include "../config/db.php";

if (isset($_POST['login'])) {
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);

	$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
	$cek = mysqli_num_rows($query);
	$row = mysqli_fetch_array($query);

	if ($cek > 0) {
		if (password_verify($password, $row['password'])) {
			$_SESSION['id'] = $row['id'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['access'] = $row['access'];

			header('location:../dashboard/index.php');
		} else {
			header('location:../index.php?pesan=gagal');
		}
	} else {
		header('location:../index.php?pesan=gagal');
	}
}
?>