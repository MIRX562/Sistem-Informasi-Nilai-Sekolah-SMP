<?php
// Ambil data detail nilai untuk modal
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$namasiswa = isset($_SESSION['name']) ? $_SESSION['name'] : '';

$detail_nilai = mysqli_query($conn, "SELECT nilai.nilai_id, nilai.nilai_kkm, users.name, 
                                   pelajaran.pelajaran_nama, nilai.uh, nilai.pas, nilai.p5ra, 
                                   nilai.tugas, nilai.kehadiran, nilai.keaktifan, nilai.kekompakan, 
                                   nilai.nilai_akhir
                                   FROM nilai 
                                   INNER JOIN users ON nilai.id=users.id 
                                   INNER JOIN kelas ON users.kelas_id=kelas.kelas_id 
                                   INNER JOIN pelajaran ON nilai.pelajaran_id=pelajaran.pelajaran_id 
                                   INNER JOIN semester ON nilai.semester_id=semester.semester_id 
                                   INNER JOIN tahun ON nilai.tahun_id=tahun.tahun_id 
                                   WHERE users.name='$namasiswa' AND semester.semester_id='$semester' AND tahun.tahun_id='$tahun'
                                   ORDER BY pelajaran.pelajaran_nama ASC");
?>

<div class="col-xs-12 col-md-12">
    <div class="well with-header  with-footer">
        <div class="header bg-blue">
            Nilai
        </div>
        <div class="col-lg-12" style="padding-bottom: 20px;">
            <div class="col-md-6 pull-left">
                <?php
                if (!$conn) {
                    die('Database connection error.');
                }
                $sql = "SELECT users.name, kelas.kelas_nama, pelajaran.pelajaran_nama, semester.semester_nama, tahun.tahun_nama, nilai.nilai_akhir 
                    FROM nilai 
                    INNER JOIN users ON nilai.id=users.id 
                    INNER JOIN kelas ON users.kelas_id=kelas.kelas_id 
                    INNER JOIN pelajaran ON nilai.pelajaran_id=pelajaran.pelajaran_id 
                    INNER JOIN semester ON nilai.semester_id=semester.semester_id 
                    INNER JOIN tahun ON nilai.tahun_id=tahun.tahun_id 
                    WHERE users.name='$namasiswa' AND semester.semester_id='$semester' AND tahun.tahun_id='$tahun'
                    ORDER BY users.name ASC";
                $nilai = mysqli_query($conn, $sql);
                if (!$nilai) {
                    echo '<div class="alert alert-danger">Query error: ' . mysqli_error($conn) . '</div>';
                    return;
                }
                $row = mysqli_fetch_array($nilai);
                ?>
                <table width="100%">
                    <tr>
                        <td width="30%">Nama</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['name']; ?> </td>
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
                    <th width="35%">Mata Pelajaran</th>
                    <th width="15%">KKM</th>
                    <th width="15%">Poin</th>
                    <th width="15%">Keterangan</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $nilai1 = mysqli_query($conn, "SELECT nilai.nilai_id, nilai.nilai_kkm, users.name, kelas.kelas_nama, pelajaran.pelajaran_nama, semester.semester_nama, tahun.tahun_nama, nilai.nilai_akhir 
                                                FROM nilai 
                                                INNER JOIN users ON nilai.id=users.id 
                                                INNER JOIN kelas ON users.kelas_id=kelas.kelas_id 
                                                INNER JOIN pelajaran ON nilai.pelajaran_id=pelajaran.pelajaran_id 
                                                INNER JOIN semester ON nilai.semester_id=semester.semester_id 
                                                INNER JOIN tahun ON nilai.tahun_id=tahun.tahun_id 
                                                WHERE users.name='$namasiswa' AND semester.semester_id='$semester' AND tahun.tahun_id='$tahun'
                                                ORDER BY pelajaran.pelajaran_nama ASC");

                $modalHtml = '';

                while ($data = mysqli_fetch_array($nilai1)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$data['pelajaran_nama']}</td>
                            <td>{$data['nilai_kkm']}</td>
                            <td>{$data['nilai_akhir']}</td>";
                    if ($data['nilai_kkm'] <= $data['nilai_akhir']) {
                        echo "<td class='btn-azure'>Tuntas</td>";
                    } else {
                        echo "<td class='btn-danger'>Tidak Tuntas</td>";
                    }
                    echo "<td>
                            <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#detailModal{$data['nilai_id']}'>
                                Detail
                            </button>
                        </td>
                        </tr>";

                    $detail_query = mysqli_query($conn, "SELECT nilai.nilai_kkm, nilai.uh, nilai.pas, nilai.p5ra, 
                                                                nilai.tugas, nilai.kehadiran, nilai.keaktifan, nilai.kekompakan, 
                                                                nilai.nilai_akhir, pelajaran.pelajaran_nama
                                                                FROM nilai 
                                                                INNER JOIN pelajaran ON nilai.pelajaran_id=pelajaran.pelajaran_id
                                                                WHERE nilai.nilai_id='{$data['nilai_id']}'");
                    $detail_data = mysqli_fetch_array($detail_query);

                    $modalHtml .= '
                    <div class="modal fade" id="detailModal' . $data['nilai_id'] . '" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel' . $data['nilai_id'] . '">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="detailModalLabel' . $data['nilai_id'] . '">Detail Nilai - ' . $data['pelajaran_nama'] . '</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><strong>Breakdown Nilai:</strong></h5>
                                            <table class="table table-bordered">
                                                <tr><td width="30%"><strong>Mata Pelajaran</strong></td><td>' . $detail_data['pelajaran_nama'] . '</td></tr>
                                                <tr><td><strong>KKM</strong></td><td>' . $detail_data['nilai_kkm'] . '</td></tr>
                                                <tr><td><strong>Ulangan Harian (UH)</strong></td><td>' . $detail_data['uh'] . '</td></tr>
                                                <tr><td><strong>Penilaian Akhir Semester (PAS)</strong></td><td>' . $detail_data['pas'] . '</td></tr>';
                    if (!empty($detail_data['p5ra']) && $detail_data['p5ra'] > 0) {
                        $modalHtml .= '<tr><td><strong>P5RA</strong></td><td>' . $detail_data['p5ra'] . '</td></tr>';
                    }
                    $modalHtml .= '
                                                <tr><td><strong>Tugas</strong></td><td>' . $detail_data['tugas'] . '</td></tr>
                                                <tr><td><strong>Kehadiran</strong></td><td>' . $detail_data['kehadiran'] . '</td></tr>
                                                <tr><td><strong>Keaktifan</strong></td><td>' . $detail_data['keaktifan'] . '</td></tr>
                                                <tr><td><strong>Kekompakan</strong></td><td>' . $detail_data['kekompakan'] . '</td></tr>
                                                <tr class="success"><td><strong>Nilai Akhir</strong></td><td><strong>' . $detail_data['nilai_akhir'] . '</strong></td></tr>
                                                <tr><td><strong>Status</strong></td><td>';
                    if ($detail_data['nilai_kkm'] <= $detail_data['nilai_akhir']) {
                        $modalHtml .= '<span class="label label-success">Tuntas</span>';
                    } else {
                        $modalHtml .= '<span class="label label-danger">Tidak Tuntas</span>';
                    }
                    $modalHtml .= '</td></tr>
                                            </table>
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <h6 class="panel-title">Rumus Perhitungan Nilai Akhir:</h6>
                                                </div>
                                                <div class="panel-body">';
                    if (!empty($detail_data['p5ra']) && $detail_data['p5ra'] > 0) {
                        $modalHtml .= '
                                                            <p><strong>Dengan P5RA:</strong></p>
                                                            <p>UH (20%) + PAS (30%) + P5RA (20%) + Tugas (15%) + Kehadiran (5%) + Keaktifan (5%) + Kekompakan (5%)</p>
                                                            <p><small>
                                                                (' . $detail_data['uh'] . ' × 0.20) + 
                                                                (' . $detail_data['pas'] . ' × 0.30) + 
                                                                (' . $detail_data['p5ra'] . ' × 0.20) + 
                                                                (' . $detail_data['tugas'] . ' × 0.15) + 
                                                                (' . $detail_data['kehadiran'] . ' × 0.05) + 
                                                                (' . $detail_data['keaktifan'] . ' × 0.05) + 
                                                                (' . $detail_data['kekompakan'] . ' × 0.05) = 
                                                                <strong>' . $detail_data['nilai_akhir'] . '</strong>
                                                            </small></p>';
                    } else {
                        $modalHtml .= '
                                                            <p><strong>Tanpa P5RA:</strong></p>
                                                            <p>UH (25%) + PAS (35%) + Tugas (20%) + Kehadiran (7.5%) + Keaktifan (6.25%) + Kekompakan (6.25%)</p>
                                                            <p><small>
                                                                (' . $detail_data['uh'] . ' × 0.25) + 
                                                                (' . $detail_data['pas'] . ' × 0.35) + 
                                                                (' . $detail_data['tugas'] . ' × 0.20) + 
                                                                (' . $detail_data['kehadiran'] . ' × 0.075) + 
                                                                (' . $detail_data['keaktifan'] . ' × 0.0625) + 
                                                                (' . $detail_data['kekompakan'] . ' × 0.0625) = 
                                                                <strong>' . $detail_data['nilai_akhir'] . '</strong>
                                                            </small></p>';
                    }
                    $modalHtml .= '
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>';
                    $no++;
                }
                ?>
            </tbody>
        </table>

        <?php
        echo $modalHtml;
        ?>

        <div style="display: flex; gap: 10px; padding-top: 20px;">
            <?php
            // Ambil data pelajaran, kelas, dan user_id untuk tombol cetak PDF
            // Ambil data nilai pertama untuk mendapatkan pelajaran dan kelas
            $first_nilai = mysqli_query($conn, "SELECT nilai.pelajaran_id, users.kelas_id FROM nilai INNER JOIN users ON nilai.id=users.id WHERE users.name='$namasiswa' AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun' LIMIT 1");
            if (!$first_nilai) {
                echo '<div class="alert alert-danger">Query error (cetak PDF): ' . mysqli_error($conn) . '</div>';
                $pelajaran = '';
                $kelas = '';
            } else {
                $first_nilai_data = mysqli_fetch_array($first_nilai);
                $pelajaran = isset($first_nilai_data['pelajaran_id']) ? $first_nilai_data['pelajaran_id'] : '';
                $kelas = isset($first_nilai_data['kelas_id']) ? $first_nilai_data['kelas_id'] : '';
            }
            $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
            // Tampilkan tombol cetak PDF jika data tersedia
            if (!empty($pelajaran) && !empty($kelas) && !empty($semester) && !empty($tahun) && !empty($user_id)) {
                ?>
                <a href="cetak_pdf_nilai.php?pelajaran=<?php echo $pelajaran; ?>&kelas=<?php echo $kelas; ?>&semester=<?php echo $semester; ?>&tahun=<?php echo $tahun; ?>&user_id=<?php echo $user_id; ?>"
                    class="btn btn-success" target="_blank">
                    <i class="fa fa-print"></i> Cetak PDF
                </a>
            <?php } ?>
            <a href="?nilai=tampil" class="btn btn-purple">Kembali</a>
        </div>
        <div style="padding-top: 20px;">
            <table class="table table-hover">
                <thead class="bordered-darkorange">
                    <?php
                    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
                    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
                    $nama = isset($_SESSION['name']) ? $_SESSION['name'] : '';
                    $rata = mysqli_query($conn, "SELECT AVG(nilai.nilai_akhir) AS poin 
                                                                FROM nilai 
                                                                INNER JOIN users ON nilai.id=users.id 
                                                                INNER JOIN kelas ON users.kelas_id=kelas.kelas_id 
                                                                INNER JOIN pelajaran ON nilai.pelajaran_id=pelajaran.pelajaran_id 
                                                                INNER JOIN semester ON nilai.semester_id=semester.semester_id 
                                                                INNER JOIN tahun ON nilai.tahun_id=tahun.tahun_id 
                                                                WHERE users.name='$nama' AND semester.semester_id='$semester' AND tahun.tahun_id='$tahun'                                                
                                                                ORDER BY users.name ASC");
                    if (!$rata) {
                        echo '<div class="alert alert-danger">Query error: ' . mysqli_error($conn) . '</div>';
                        return;
                    }
                    $row1 = mysqli_fetch_array($rata);
                    $poin = ROUND($row1['poin']);
                    ?>
                    <tr>
                        <th width="60%">Rata-rata Nilai</th>
                        <th width="5%" class="text-right">:</th>
                        <th width="35%" class="btn-darkorange"><?php echo $poin; ?></th>
                    </tr>
                </thead>
            </table>
            <div style="padding-top: 20px;margin-bottom: -30px;"></div>
        </div>
    </div>
</div>