
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "eraapor";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


	//fungsi untuk mengkonversi size file
	function formatBytes($bytes, $precision = 2) { 
	    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

	    $bytes = max($bytes, 0); 
	    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
	    $pow = min($pow, count($units) - 1); 

	    $bytes /= pow(1024, $pow); 

	    return round($bytes, $precision) . ' ' . $units[$pow]; 
	} 
?>