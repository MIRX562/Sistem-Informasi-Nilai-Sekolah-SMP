<?php
$host = "localhost";
$user = "root";
$pass = "asemjowo";
$database = "sims2";

$conn = mysqli_connect($host, $user, $pass, $database);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

// Set UTF-8 encoding
mysqli_set_charset($conn, "utf8");


?>