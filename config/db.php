<?php
<<<<<<< HEAD
mysql_connect("localhost:3306", "root", "") or die("Gagal Koneksi");
mysql_select_db("sims2") or die("Tidak ada Database");
?>
=======
$host = "localhost";
$user = "root";
$pass = "";
$db   = "eraapor";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
>>>>>>> 9c6c95edbcccb84bd1ebe2f42224492e0500262d
