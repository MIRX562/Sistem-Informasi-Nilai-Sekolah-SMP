<?php
include '../config/db.php';
if (!isset($_GET['tahun_id'])) {
    echo '<div class="alert alert-danger">Tahun ID tidak ditemukan!</div>';
    exit;
}
$tahun_id = intval($_GET['tahun_id']);
// Set all tahun to nonaktif first
mysqli_query($conn, "UPDATE tahun SET status='nonaktif'");
// Set selected tahun_id to aktif
mysqli_query($conn, "UPDATE tahun SET status='aktif' WHERE tahun_id='$tahun_id'");
// Set all guru_mengajar to nonaktif first
mysqli_query($conn, "UPDATE guru_mengajar SET status='nonaktif'");
// Set guru_mengajar for selected tahun_id to aktif
mysqli_query($conn, "UPDATE guru_mengajar SET status='aktif' WHERE tahun_id='$tahun_id'");
// Use meta refresh for compatibility with other core scripts
?>
<meta http-equiv="refresh" content="0;url=/dashboard/?akademik=tahun&set_active_success=1">
<?php
exit();