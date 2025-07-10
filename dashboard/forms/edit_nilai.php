<?php
// dashboard/forms/edit_nilai.php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once '../config/db.php';

if (!isset($_GET['nilai-edit'])) {
    echo "<div class='alert alert-danger'>ID nilai tidak ditemukan.</div>";
    exit;
}
$nilai_id = intval($_GET['nilai-edit']);
$result = mysqli_query($conn, "SELECT * FROM nilai WHERE nilai_id = '$nilai_id'");
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>Data nilai tidak ditemukan.</div>";
    exit;
}
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uh = intval($_POST['uh']);
    $pas = intval($_POST['pas']);
    $p5ra = isset($_POST['p5ra']) && $_POST['p5ra'] !== '' ? intval($_POST['p5ra']) : null;
    $tugas = intval($_POST['tugas']);
    $kehadiran = intval($_POST['kehadiran']);
    $keaktifan = intval($_POST['keaktifan']);
    $kekompakan = intval($_POST['kekompakan']);

    // Hitung nilai_akhir sesuai rumus
    if (!empty($p5ra) && $p5ra > 0) {
        $nilai_akhir = ($uh * 0.20) + ($pas * 0.30) + ($p5ra * 0.20) +
            ($tugas * 0.15) + ($kehadiran * 0.05) +
            ($keaktifan * 0.05) + ($kekompakan * 0.05);
    } else {
        $nilai_akhir = ($uh * 0.25) + ($pas * 0.35) + ($tugas * 0.20) +
            ($kehadiran * 0.075) + ($keaktifan * 0.0625) + ($kekompakan * 0.0625);
    }
    $nilai_akhir = round($nilai_akhir, 2);

    $sql = "UPDATE nilai SET uh='$uh', pas='$pas', p5ra=" . ($p5ra === null ? 'NULL' : "'$p5ra'") . ", tugas='$tugas', kehadiran='$kehadiran', keaktifan='$keaktifan', kekompakan='$kekompakan', nilai_akhir='$nilai_akhir' WHERE nilai_id='$nilai_id'";
    $update = mysqli_query($conn, $sql);
    if ($update) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href='../tables/nilai2_table.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal update data: " . mysqli_error($conn) . "</div>";
    }
}
?>
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Edit Nilai Form</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai UH</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="uh" value="<?php echo $data['uh']; ?>"
                                min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai PAS</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="pas" value="<?php echo $data['pas']; ?>"
                                min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai P5RA</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="p5ra" value="<?php echo $data['p5ra']; ?>"
                                min="0" max="100">
                            <span class="help-block">Boleh dikosongkan jika tidak ada</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai Tugas</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="tugas" value="<?php echo $data['tugas']; ?>"
                                min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kehadiran</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="kehadiran"
                                value="<?php echo $data['kehadiran']; ?>" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Keaktifan</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="keaktifan"
                                value="<?php echo $data['keaktifan']; ?>" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kekompakan</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="kekompakan"
                                value="<?php echo $data['kekompakan']; ?>" min="0" max="100" required>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="nilai-update">Update</button>
                            <a href="?nilai=tampil" class="btn btn-warning">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>