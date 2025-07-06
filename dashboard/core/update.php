<?php
include '../config/db.php';
if (isset($_GET['set_active_tahun'])) {
    $tahun_id = intval($_GET['set_active_tahun']);
    // Set all tahun to nonaktif first
    $q1 = mysqli_query($conn, "UPDATE tahun SET status='nonaktif'");
    // Set selected tahun_id to aktif
    $q2 = mysqli_query($conn, "UPDATE tahun SET status='aktif' WHERE tahun_id='$tahun_id'");
    // Set all guru_mengajar to nonaktif first
    $q3 = mysqli_query($conn, "UPDATE guru_mengajar SET status='nonaktif'");
    // Set guru_mengajar for selected tahun_id to aktif
    $q4 = mysqli_query($conn, "UPDATE guru_mengajar SET status='aktif' WHERE tahun_id='$tahun_id'");
    if ($q1 && $q2 && $q3 && $q4) {
        echo "<script>window.location='?akademik=tahun&set_active_success=1';</script>";
    } else {
        echo '<div class="alert alert-danger">Gagal mengubah tahun ajaran aktif!</div>';
    }
    exit;
}
?>