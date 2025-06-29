<?php
include '../config/db.php';
if (!isset($_GET['guru_id']) || !isset($_GET['tahun_id']) || !isset($_GET['semester_id'])) {
    echo '<div class="alert alert-danger">Guru, tahun ajaran, atau semester tidak ditemukan!</div>';
    return;
}
$guru_id = intval($_GET['guru_id']);
$tahun_id = intval($_GET['tahun_id']);
$semester_id = intval($_GET['semester_id']);
$guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$guru_id' AND access='guru'"));
$tahun = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tahun WHERE tahun_id='$tahun_id'"));
$semester = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM semester WHERE semester_id='$semester_id'"));
if (!$guru) {
    echo '<div class="alert alert-danger">Guru tidak ditemukan!</div>';
    return;
}
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
<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Guru: <?php echo htmlspecialchars($guru['name']); ?> (<?php echo htmlspecialchars($guru['nomor_induk']); ?>)
        </div>
        <a href="?akademik=guru_mengajar-create&guru_id=<?php echo $guru_id; ?>&tahun_id=<?php echo $tahun_id; ?>&semester_id=<?php echo $semester_id; ?>"
            class="btn btn-primary" style="margin:10px 0;">Tambah Data Mengajar</a>
        <table class="table table-hover table-striped">
            <thead class="bordered-darkorange">
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($conn, "SELECT gm.*, p.pelajaran_nama, k.kelas_nama, s.semester_nama FROM guru_mengajar gm 
                    JOIN pelajaran p ON gm.pelajaran_id = p.pelajaran_id 
                    JOIN kelas k ON gm.kelas_id = k.kelas_id 
                    JOIN semester s ON gm.semester_id = s.semester_id 
                    WHERE gm.guru_id='$guru_id' AND gm.tahun_id='$tahun_id' AND gm.semester_id='$semester_id' ORDER BY k.kelas_nama, p.pelajaran_nama");
                while ($row = mysqli_fetch_assoc($q)) {
                    echo '<tr>';
                    echo '<td>' . $no++ . '</td>';
                    echo '<td>' . htmlspecialchars($row['pelajaran_nama']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['kelas_nama']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['semester_nama']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                    echo '<td>';
                    echo '<a href="?akademik=guru_mengajar-edit&id=' . $row['guru_mengajar_id'] . '&guru_id=' . $guru_id . '&tahun_id=' . $tahun_id . '&semester_id=' . $semester_id . '" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a> ';
                    echo '<a href="?akademik=guru_mengajar-del&id=' . $row['guru_mengajar_id'] . '&guru_id=' . $guru_id . '&tahun_id=' . $tahun_id . '&semester_id=' . $semester_id . '" class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="fa fa-trash"></i> Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <a href="?akademik=guru_mengajar-list_guru&tahun_id=<?php echo $tahun_id; ?>&semester_id=<?php echo $semester_id; ?>"
            class="btn btn-default">Kembali ke
            Daftar Guru</a>
    </div>
</div>