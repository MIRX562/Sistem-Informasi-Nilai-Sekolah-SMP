<?php
// Cek akses user terlebih dahulu
$user_id = $_SESSION['id']; 

// Validasi bahwa user yang mengakses adalah guru atau admin
$user_check = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id' AND (access = 'guru' OR access = 'admin')");
if (mysqli_num_rows($user_check) == 0) {
    echo "<div class='alert alert-danger'>Akses ditolak. Anda tidak memiliki hak untuk mengakses halaman ini.</div>";
    exit;
}

// Ambil data user untuk cek access level
$user_data = mysqli_fetch_array($user_check);
$user_access = $user_data['access'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Validasi hak untuk menghapus nilai
    if ($user_access == 'admin') {
        // Admin bisa menghapus nilai manapun
        $validasi_hapus = mysqli_query($conn, "SELECT COUNT(*) as count FROM nilai WHERE nilai_id = '$id'");
    } else {
        // Guru hanya bisa menghapus nilai dari kelas dan pelajaran yang mereka ajar
        $validasi_hapus = mysqli_query($conn, "SELECT COUNT(*) as count 
                                              FROM nilai n
                                              INNER JOIN users u ON n.id = u.id
                                              INNER JOIN users guru ON guru.kelas_id = u.kelas_id 
                                              WHERE n.nilai_id = '$id' 
                                              AND guru.id = '$user_id' 
                                              AND guru.access = 'guru'
                                              AND guru.pelajaran_id = n.pelajaran_id");
    }
    
    $validasi_result = mysqli_fetch_array($validasi_hapus);
    
    if ($validasi_result['count'] == 0) {
        echo "<script>alert('Anda tidak memiliki hak untuk menghapus nilai ini!'); window.location.href='index.php';</script>";
        exit;
    }
    
    $stmt = $koneksi->prepare("DELETE FROM nilai WHERE nilai_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href='index.php';</script>";
    }
    $stmt->close();
}

// Pastikan semua parameter yang dibutuhkan tersedia
if (!isset($_POST['pelajaran']) || !isset($_POST['kelas']) || !isset($_POST['semester']) || !isset($_POST['tahun'])) {
    echo "<div class='alert alert-danger'>Error: Parameter tidak lengkap!</div>";
    echo "<a href='?nilai=tampil' class='btn btn-purple'>Kembali</a>";
    exit;
}

$pelajaran = $_POST['pelajaran'];
$kelas = $_POST['kelas'];
$semester = $_POST['semester'];
$tahun = $_POST['tahun'];

// VALIDASI AKSES berdasarkan level user
if ($user_access == 'guru') {
    // Guru hanya bisa melihat nilai dari kelas dan mata pelajaran yang dia ajar
    $validasi_akses = mysqli_query($conn, "SELECT COUNT(*) as count 
                                          FROM users 
                                          WHERE id = '$user_id' 
                                          AND access = 'guru' 
                                          AND kelas_id = '$kelas' 
                                          AND pelajaran_id = '$pelajaran'");
    $akses_data = mysqli_fetch_array($validasi_akses);

    if ($akses_data['count'] == 0) {
        echo "<div class='alert alert-danger'><strong>Akses Ditolak!</strong> Anda tidak memiliki hak untuk melihat nilai pada kelas dan mata pelajaran yang dipilih.</div>";
        echo "<a href='?nilai=tampil' class='btn btn-purple'>Kembali</a>";
        exit;
    }
}
// Admin tidak perlu validasi akses karena memiliki akses penuh

// Cek apakah ada data nilai
$check_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM nilai 
                           INNER JOIN users ON nilai.id=users.id 
                           WHERE nilai.pelajaran_id='$pelajaran' AND users.kelas_id='$kelas' 
                           AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun'");

$check_result = mysqli_fetch_array($check_query);

if ($check_result['count'] == 0) {
    echo "<div class='alert alert-warning'>Tidak ada data nilai yang ditemukan untuk kriteria yang dipilih.</div>";
    echo "<a href='?nilai=tampil' class='btn btn-purple'>Kembali</a>";
    exit;
}

// Ambil informasi pelajaran, kelas, semester, dan tahun
$info_query = mysqli_query($conn, "SELECT kelas.kelas_nama, pelajaran.pelajaran_nama, semester.semester_nama, tahun.tahun_nama 
                         FROM kelas, pelajaran, semester, tahun
                         WHERE kelas.kelas_id='$kelas' AND pelajaran.pelajaran_id='$pelajaran' 
                         AND semester.semester_id='$semester' AND tahun.tahun_id='$tahun'");

$row = mysqli_fetch_array($info_query);

// Ambil nama user untuk ditampilkan
$user_info = mysqli_query($conn, "SELECT name, access FROM users WHERE id = '$user_id'");
$user_info_data = mysqli_fetch_array($user_info);
?>

<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Nilai - <?php echo $user_info_data['name']; ?> 
            <?php if ($user_access == 'admin') echo '<span class="badge badge-admin">ADMIN</span>'; ?>
        </div>
        
        <!-- Informasi Akses -->
        <div class="alert <?php echo ($user_access == 'admin') ? 'alert-success' : 'alert-info'; ?>" style="margin: 15px;">
            <strong>Info:</strong> 
            <?php if ($user_access == 'admin'): ?>
                Sebagai Admin, Anda memiliki akses penuh untuk melihat dan mengelola nilai semua kelas dan mata pelajaran.
            <?php else: ?>
                Anda hanya dapat melihat dan mengelola nilai untuk kelas dan mata pelajaran yang Anda ajar.
            <?php endif; ?>
        </div>
        
        <div class="col-lg-12" style="padding-bottom: 20px;">
            <div class="col-md-6 pull-left">
                <table width="100%">
                    <tr>
                        <td width="30%">Pelajaran</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['pelajaran_nama']; ?> </td>
                    </tr>
                    <tr>
                        <td width="30%">Kelas</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['kelas_nama']; ?> </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 pull-right">
                <table width="100%">
                    <tr>
                        <td width="30%">Semester</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['semester_nama']; ?> </td>
                    </tr>
                    <tr>
                        <td width="30%">Tahun ajaran</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['tahun_nama']; ?> </td>
                    </tr>
                </table>
            </div>
        </div>
        <table class="table table-hover">
            <thead class="bordered-darkorange">
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Nama</th>
                    <th width="5%">KKM</th>
                    <th width="7%">UH</th>
                    <th width="7%">PAS</th>
                    <th width="7%">P5RA</th>
                    <th width="7%">Tugas</th>
                    <th width="7%">Kehadiran</th>
                    <th width="7%">Keaktifan</th>
                    <th width="7%">Kekompakan</th>
                    <th width="7%">Nilai Akhir</th>
                    <th width="10%">Keterangan</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $nilai_query = mysqli_query($conn, "SELECT nilai.nilai_id, nilai.nilai_kkm, users.name, 
                                               nilai.uh, nilai.pas, nilai.p5ra, nilai.tugas, 
                                               nilai.kehadiran, nilai.keaktifan, nilai.kekompakan
                                               FROM nilai 
                                               INNER JOIN users ON nilai.id=users.id 
                                               INNER JOIN kelas ON users.kelas_id=kelas.kelas_id 
                                               WHERE nilai.pelajaran_id='$pelajaran' AND kelas.kelas_id='$kelas' 
                                               AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun'
                                               ORDER BY users.name ASC") or die(mysqli_error($conn));

                while ($data = mysqli_fetch_array($nilai_query)) {
                    // Hitung nilai_akhir
                    $uh_value = floatval($data['uh']);
                    $pas_value = floatval($data['pas']);
                    $p5ra_value = floatval($data['p5ra']);
                    $tugas_value = floatval($data['tugas']);
                    $kehadiran_value = floatval($data['kehadiran']);
                    $keaktifan_value = floatval($data['keaktifan']);
                    $kekompakan_value = floatval($data['kekompakan']);

                    // Hitung nilai_akhir berdasarkan keberadaan nilai p5ra
                    if (!empty($data['p5ra']) && $p5ra_value > 0) {
                        // Jika p5ra ada
                        $nilai_akhir = ($uh_value * 0.20) + ($pas_value * 0.30) + ($p5ra_value * 0.20) +
                            ($tugas_value * 0.15) + ($kehadiran_value * 0.05) +
                            ($keaktifan_value * 0.05) + ($kekompakan_value * 0.05);
                    } else {
                        // Jika p5ra tidak ada
                        $nilai_akhir = ($uh_value * 0.25) + ($pas_value * 0.35) + ($tugas_value * 0.20) +
                            ($kehadiran_value * 0.075) + ($keaktifan_value * 0.0625) +
                            ($kekompakan_value * 0.0625);
                    }

                    // Bulatkan nilai_akhir ke 2 desimal
                    $nilai_akhir = round($nilai_akhir, 2);

                    // Update nilai_akhir di database
                    mysqli_query($conn, "UPDATE nilai SET nilai_akhir = '$nilai_akhir' 
                                    WHERE nilai_id = '{$data['nilai_id']}'") or die(mysqli_error($conn));
                    ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $data['nilai_kkm']; ?></td>
                        <td><?php echo $data['uh']; ?></td>
                        <td><?php echo $data['pas']; ?></td>
                        <td><?php echo $data['p5ra']; ?></td>
                        <td><?php echo $data['tugas']; ?></td>
                        <td><?php echo $data['kehadiran']; ?></td>
                        <td><?php echo $data['keaktifan']; ?></td>
                        <td><?php echo $data['kekompakan']; ?></td>
                        <td><?php echo $nilai_akhir; ?></td>
                        <td>
                            <?php
                            if ($data['nilai_kkm'] <= $nilai_akhir) {
                                echo "<span class='badge badge-success'>Tuntas</span>";
                            } else {
                                echo "<span class='badge badge-danger'>Tidak Tuntas</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="?nilai-del=<?php echo $data['nilai_id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus nilai siswa <?php echo $data['name']; ?>?')">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
            </tbody>
        </table>

        <div style="display: flex; gap: 10px; padding-top: 20px;">
            <?php
            // Validasi akses untuk tombol cetak PDF
            if ($user_access == 'admin') {
                // Admin bisa cetak semua
                $akses_cetak_count = 1;
            } else {
                // Guru hanya bisa cetak sesuai akses
                $guru_akses_cetak = mysqli_query($conn, "SELECT COUNT(*) as count 
                                                       FROM users 
                                                       WHERE id = '$user_id' 
                                                       AND access = 'guru' 
                                                       AND kelas_id = '$kelas' 
                                                       AND pelajaran_id = '$pelajaran'");
                $akses_cetak_data = mysqli_fetch_array($guru_akses_cetak);
                $akses_cetak_count = $akses_cetak_data['count'];
            }
            
            if ($akses_cetak_count > 0) {
            ?>
            <a href="cetak_pdf_nilai.php?pelajaran=<?php echo $pelajaran; ?>&kelas=<?php echo $kelas; ?>&semester=<?php echo $semester; ?>&tahun=<?php echo $tahun; ?>&user_id=<?php echo $user_id; ?>" 
               class="btn btn-success" target="_blank">
                <i class="fa fa-print"></i> Cetak PDF
            </a>
            <?php } ?>
            
            <a href="?nilai=tampil" class="btn btn-purple">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<style>
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        color: white;
        font-size: 11px;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-admin {
        background-color: #007bff;
        margin-left: 10px;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
</style>