<?php
include '../config/db.php';
if (!isset($_GET['tahun_id']) || !isset($_GET['semester_id'])) {
    echo '<div class="alert alert-warning">Pilih tahun ajaran dan semester terlebih dahulu.</div>';
    return;
}
$tahun_id = intval($_GET['tahun_id']);
$semester_id = intval($_GET['semester_id']);
// Fetch tahun and semester info
$tahun = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tahun WHERE tahun_id='$tahun_id'"));
$semester = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM semester WHERE semester_id='$semester_id'"));
?>
<!-- Info Card Tahun & Semester -->
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="alert alert-info" style="margin-bottom:15px;">
        <strong>Tahun Ajaran:</strong> <?php echo htmlspecialchars($tahun['tahun_nama']); ?>
        &nbsp; | &nbsp;
        <strong>Semester:</strong> <?php echo htmlspecialchars($semester['semester_nama']); ?>
    </div>
</div>
<!-- End Info Card -->
<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Daftar Guru
        </div>
        <table class="table table-hover table-striped">
            <thead class="bordered-darkorange">
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>NIP</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $guru = mysqli_query($conn, "SELECT * FROM users WHERE access='guru' ORDER BY name ASC");
                while ($row = mysqli_fetch_assoc($guru)) {
                    echo '<tr>';
                    echo '<td>' . $no++ . '</td>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nomor_induk']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                    echo '<td><a href="?akademik=guru_mengajar-manage&tahun_id=' . $tahun_id . '&semester_id=' . $semester_id . '&guru_id=' . $row['id'] . '" class="btn btn-xs btn-info"><i class="fa fa-tasks"></i> Manage Mengajar</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>