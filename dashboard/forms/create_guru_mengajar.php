<?php
include '../config/db.php';
if (!isset($_GET['guru_id']) || !isset($_GET['tahun_id']) || !isset($_GET['semester_id'])) {
    echo '<div class="alert alert-danger">Guru, tahun ajaran, dan semester harus dipilih terlebih dahulu.</div>';
    return;
}
$guru_id = intval($_GET['guru_id']);
$tahun_id = intval($_GET['tahun_id']);
$semester_id = intval($_GET['semester_id']);
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
            <span class="widget-caption">Tambah Guru Mengajar</span>
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
                                while ($data = mysqli_fetch_array($pelajaran)) {
                                    ?>
                                    <option value="<?php echo $data['pelajaran_id']; ?>">
                                        <?php echo $data['pelajaran_nama']; ?>
                                    </option>
                                <?php } ?>
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
                                while ($data = mysqli_fetch_array($kelas)) {
                                    ?>
                                    <option value="<?php echo $data['kelas_id']; ?>"><?php echo $data['kelas_nama']; ?>
                                    </option>
                                <?php } ?>
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
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="create-guru-mengajar">Create</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </div>
                    </div>
                </form>
                <?php
                if (isset($_POST['create-guru-mengajar'])) {
                    $guru_id = $_POST['guru_id'];
                    $pelajaran_id = $_POST['pelajaran_id'];
                    $kelas_id = $_POST['kelas_id'];
                    $tahun_id = $_POST['tahun_id'];
                    $semester_id = $_POST['semester_id'];
                    $status = $_POST['status'];
                    $cek = mysqli_query($conn, "SELECT * FROM guru_mengajar WHERE guru_id='$guru_id' AND pelajaran_id='$pelajaran_id' AND kelas_id='$kelas_id' AND tahun_id='$tahun_id' AND semester_id='$semester_id'");
                    if (mysqli_num_rows($cek) > 0) {
                        echo '<div class="alert alert-danger">Data guru mengajar sudah ada!</div>';
                    } else {
                        $insert = mysqli_query($conn, "INSERT INTO guru_mengajar (guru_id, pelajaran_id, kelas_id, tahun_id, semester_id, status) VALUES ('$guru_id','$pelajaran_id','$kelas_id','$tahun_id','$semester_id','$status')");
                        if ($insert) {
                            echo '<div class="alert alert-success">Data berhasil ditambahkan!</div>';
                            echo '<meta http-equiv="refresh" content="1;url=/dashboard/?akademik=guru_mengajar-manage&guru_id=' . $guru_id . '&tahun_id=' . $tahun_id . '&semester_id=' . $semester_id . '">';
                        } else {
                            echo '<div class="alert alert-danger">Gagal menambah data!</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css"
    rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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