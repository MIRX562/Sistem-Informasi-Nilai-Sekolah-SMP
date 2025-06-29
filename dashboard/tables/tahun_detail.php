<?php
include '../config/db.php';

if (isset($_GET['tahun-detail'])) {
    $tahun_id = $_GET['tahun-detail'];

    // Get tahun ajaran name
    $tahun_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tahun_nama FROM tahun WHERE tahun_id='$tahun_id'"));
    $tahun_nama = $tahun_row ? $tahun_row['tahun_nama'] : $tahun_id;

    // List all classes in this year (with students)
    $kelas_result = mysqli_query($conn, "SELECT DISTINCT k.kelas_id, k.kelas_nama FROM users u JOIN kelas k ON u.kelas_id = k.kelas_id WHERE u.kelas_id IS NOT NULL");

    // List all subjects taught in this year
    $pelajaran_result = mysqli_query($conn, "SELECT DISTINCT p.pelajaran_id, p.pelajaran_nama FROM guru_mengajar gm JOIN pelajaran p ON gm.pelajaran_id = p.pelajaran_id WHERE gm.tahun_id = '$tahun_id'");

    // List all teachers teaching in this year, with subject and class
    $guru_result = mysqli_query($conn, "SELECT u.name AS guru_name, p.pelajaran_nama, k.kelas_nama FROM guru_mengajar gm JOIN users u ON gm.guru_id = u.id JOIN pelajaran p ON gm.pelajaran_id = p.pelajaran_id JOIN kelas k ON gm.kelas_id = k.kelas_id WHERE gm.tahun_id = '$tahun_id' ORDER BY u.name, p.pelajaran_nama, k.kelas_nama");

    // List all students enrolled in this year (by having nilai in this tahun)
    $siswa_result = mysqli_query($conn, "SELECT DISTINCT u.id, u.name, u.nomor_induk, k.kelas_nama FROM nilai n JOIN users u ON n.id = u.id LEFT JOIN kelas k ON u.kelas_id = k.kelas_id WHERE n.tahun_id = '$tahun_id' AND u.access = 'orang_tua' ORDER BY u.name");

    // List all grades recorded in this year
    $nilai_result = mysqli_query($conn, "SELECT n.nilai_id, u.name AS siswa, p.pelajaran_nama, k.kelas_nama, n.semester_id, n.nilai_akhir FROM nilai n JOIN users u ON n.id = u.id JOIN pelajaran p ON n.pelajaran_id = p.pelajaran_id LEFT JOIN kelas k ON u.kelas_id = k.kelas_id WHERE n.tahun_id = '$tahun_id' ORDER BY u.name, p.pelajaran_nama");
    ?>
<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Detail Tahun Ajaran <?php echo htmlspecialchars($tahun_nama); ?>
            <a href="cetak_pdf_tahun.php?tahun_id=<?php echo urlencode($tahun_id); ?>" target="_blank"
                class="btn btn-success btn-sm pull-right" style="margin-top:-5px;">
                <i class="fa fa-file-pdf-o"></i> Cetak PDF
            </a>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4>Kelas yang Aktif</h4>
                <ul>
                    <?php while ($k = mysqli_fetch_assoc($kelas_result)) {
                            echo '<li>' . htmlspecialchars($k['kelas_nama']) . '</li>';
                        } ?>
                </ul>
            </div>
            <div class="col-md-6">
                <h4>Mata Pelajaran yang Diajarkan</h4>
                <ul>
                    <?php while ($p = mysqli_fetch_assoc($pelajaran_result)) {
                            echo '<li>' . htmlspecialchars($p['pelajaran_nama']) . '</li>';
                        } ?>
                </ul>
            </div>
        </div>
        <hr />
        <h4>Guru, Mata Pelajaran, dan Kelas</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                    while ($g = mysqli_fetch_assoc($guru_result)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($g['guru_name']); ?></td>
                    <td><?php echo htmlspecialchars($g['pelajaran_nama']); ?></td>
                    <td><?php echo htmlspecialchars($g['kelas_nama']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr />
        <h4>Daftar Siswa yang Memiliki Nilai Tahun Ini</h4>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                    while ($s = mysqli_fetch_assoc($siswa_result)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($s['nomor_induk']); ?></td>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td><?php echo htmlspecialchars($s['kelas_nama']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr />
        <h4>Rekap Nilai yang Tercatat Tahun Ini</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Semester</th>
                    <th>Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                    while ($n = mysqli_fetch_assoc($nilai_result)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($n['siswa']); ?></td>
                    <td><?php echo htmlspecialchars($n['kelas_nama']); ?></td>
                    <td><?php echo htmlspecialchars($n['pelajaran_nama']); ?></td>
                    <td><?php echo htmlspecialchars($n['semester_id']); ?></td>
                    <td><?php echo htmlspecialchars($n['nilai_akhir']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>