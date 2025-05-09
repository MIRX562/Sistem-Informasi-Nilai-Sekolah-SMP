<?php
// Database connection parameters
$host = "localhost";
$user = "root";  // Default XAMPP username
$pass = "";      // Default XAMPP password (empty)
$database = "sims2";  // Your database name from the SQL file

// Create connection
$conn = mysqli_connect($host, $user, $pass, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to handle special characters properly
mysqli_set_charset($conn, "utf8");
?>