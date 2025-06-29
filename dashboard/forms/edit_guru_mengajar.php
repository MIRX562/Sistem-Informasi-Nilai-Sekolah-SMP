<?php
include '../config/db.php';
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css"
    rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php
if (!isset($_GET['id']) || !isset($_GET['guru_id']) || !isset($_GET['tahun_id']) || !isset($_GET['semester_id'])) {
    echo '<div class="alert alert-danger">ID, Guru, Tahun, atau Semester tidak ditemukan!</div>';
    return;
}
$id = intval($_GET['id']);
$guru_id = intval($_GET['guru_id']);
$tahun_id = intval($_GET['tahun_id']);
$semester_id = intval($_GET['semester_id']);
$q = mysqli_query($conn, "SELECT * FROM guru_mengajar WHERE guru_mengajar_id='$id'");
$data = mysqli_fetch_assoc($q);
if (!$data) {
    echo '<div class="alert alert-danger">Data tidak ditemukan!</div>';
    return;
}
$guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$guru_id'"));
$tahun = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tahun WHERE tahun_id='$tahun_id'"));
$semester = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM semester WHERE semester_id='$semester_id'"));
?>
<!-- Info Card Guru, Tahun, Semester -->
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="alert alert-info" style="margin-bottom:15px;">
        <strong>Guru:</strong> <?php echo htmlspecialchars($guru['name']); ?>
        (<?php echo htmlspecialchars($guru['nomor_induk']); ?>)
        &nbsp; | &nbsp;
        <strong>Tahun Ajaran:</strong> <?php echo htmlspecialchars($tahun['tahun_nama']); ?>
        &nbsp; | &nbsp;
        <strong>Semester:</strong> <?php echo htmlspecialchars($semester['semester_nama']); ?>
    </div>
</div>
<!-- End Info Card -->
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Edit Guru Mengajar</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST">
                    <input type="hidden" name="guru_id" value="<?php echo $guru_id; ?>">
                    <input type="hidden" name="tahun_id" value="<?php echo $tahun_id; ?>">
                    <input type="hidden" name="semester_id" value="<?php echo $semester_id; ?>">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Mata Pelajaran</label>
                        <div class="col-sm-10">
                            <select class="e1" style="width:100%;" name="pelajaran_id" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                <?php
                                $pelajaran = mysqli_query($conn, "SELECT * FROM pelajaran");
                                while ($p = mysqli_fetch_array($pelajaran)) {
                                    $selected = $p['pelajaran_id'] == $data['pelajaran_id'] ? 'selected' : '';
                                    echo "<option value='{$p['pelajaran_id']}' $selected>{$p['pelajaran_nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kelas</label>
                        <div class="col-sm-10">
                            <select class="e1" style="width:100%;" name="kelas_id" required>
                                <option value="">Pilih Kelas</option>
                                <?php
                                $kelas = mysqli_query($conn, "SELECT * FROM kelas");
                                while ($k = mysqli_fetch_array($kelas)) {
                                    $selected = $k['kelas_id'] == $data['kelas_id'] ? 'selected' : '';
                                    echo "<option value='{$k['kelas_id']}' $selected>{$k['kelas_nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Semester</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                value="<?php echo htmlspecialchars($semester['semester_nama']); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="status" required>
                                <option value="aktif" <?php if ($data['status'] == 'aktif')
                                    echo 'selected'; ?>>Aktif
                                </option>
                                <option value="nonaktif" <?php if ($data['status'] == 'nonaktif')
                                    echo 'selected'; ?>>
                                    Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="edit-guru-mengajar">Update</button>
                            <a href="/dashboard/?akademik=guru_mengajar-manage&guru_id=<?php echo $guru_id; ?>&tahun_id=<?php echo $tahun_id; ?>&semester_id=<?php echo $semester_id; ?>"
                                class="btn btn-default">Batal</a>
                        </div>
                    </div>
                </form>
                <?php
                if (isset($_POST['edit-guru-mengajar'])) {
                    $guru_id = $_POST['guru_id'];
                    $pelajaran_id = $_POST['pelajaran_id'];
                    $kelas_id = $_POST['kelas_id'];
                    $tahun_id = $_POST['tahun_id'];
                    $semester_id = $_POST['semester_id'];
                    $status = $_POST['status'];
                    $cek = mysqli_query($conn, "SELECT * FROM guru_mengajar WHERE guru_id='$guru_id' AND pelajaran_id='$pelajaran_id' AND kelas_id='$kelas_id' AND tahun_id='$tahun_id' AND semester_id='$semester_id' AND guru_mengajar_id!='$id'");
                    if (mysqli_num_rows($cek) > 0) {
                        echo '<div class="alert alert-danger">Data guru mengajar sudah ada!</div>';
                    } else {
                        $update = mysqli_query($conn, "UPDATE guru_mengajar SET pelajaran_id='$pelajaran_id', kelas_id='$kelas_id', status='$status' WHERE guru_mengajar_id='$id'");
                        if ($update) {
                            echo '<div class="alert alert-success">Data berhasil diupdate!</div>';
                            echo '<meta http-equiv="refresh" content="1;url=/dashboard/?akademik=guru_mengajar-manage&guru_id=' . $guru_id . '&tahun_id=' . $tahun_id . '&semester_id=' . $semester_id . '">';
                        } else {
                            echo '<div class="alert alert-danger">Gagal update data!</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap',
            width: '100%',
            placeholder: 'Pilih',
            allowClear: true
        });
    });
</script>