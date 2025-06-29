<?php
include '../config/db.php';
if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">ID tidak ditemukan!</div>';
    return;
}
$id = intval($_GET['id']);
$delete = mysqli_query($conn, "DELETE FROM guru_mengajar WHERE guru_mengajar_id='$id'");
if ($delete) {
    echo '<div class="alert alert-success">Data berhasil dihapus!</div>';
    echo '<meta http-equiv="refresh" content="1;url=/dashboard/?akademik=guru_mengajar">';

} else {
    echo '<div class="alert alert-danger">Gagal menghapus data!</div>';
}