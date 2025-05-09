<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
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

$pelajaran  =   $_POST['pelajaran'];
$kelas      =   $_POST['kelas'];
$semester   =   $_POST['semester'];
$tahun      =   $_POST['tahun'];

// Cek apakah ada data nilai
$check_query = mysql_query("SELECT COUNT(*) as count FROM nilai 
                           INNER JOIN users ON nilai.id=users.id 
                           WHERE nilai.pelajaran_id='$pelajaran' AND users.kelas_id='$kelas' 
                           AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun'");

$check_result = mysql_fetch_array($check_query);

if ($check_result['count'] == 0) {
    echo "<div class='alert alert-warning'>Tidak ada data nilai yang ditemukan untuk kriteria yang dipilih.</div>";
    echo "<a href='?nilai=tampil' class='btn btn-purple'>Kembali</a>";
    exit;
}

// Ambil informasi pelajaran, kelas, semester, dan tahun
$info_query = mysql_query("SELECT kelas.kelas_nama, pelajaran.pelajaran_nama, semester.semester_nama, tahun.tahun_nama 
                         FROM kelas, pelajaran, semester, tahun
                         WHERE kelas.kelas_id='$kelas' AND pelajaran.pelajaran_id='$pelajaran' 
                         AND semester.semester_id='$semester' AND tahun.tahun_id='$tahun'");

$row = mysql_fetch_array($info_query);
?>

<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Nilai
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
                    $nilai_query = mysql_query("SELECT nilai.nilai_id, nilai.nilai_kkm, users.name, 
                                               nilai.uh, nilai.pas, nilai.p5ra, nilai.tugas, 
                                               nilai.kehadiran, nilai.keaktifan, nilai.kekompakan
                                               FROM nilai 
                                               INNER JOIN users ON nilai.id=users.id 
                                               INNER JOIN kelas ON users.kelas_id=kelas.kelas_id 
                                               WHERE nilai.pelajaran_id='$pelajaran' AND kelas.kelas_id='$kelas' 
                                               AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun'
                                               ORDER BY users.name ASC") or die(mysql_error());

                    while ($data = mysql_fetch_array($nilai_query)) {
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
                        mysql_query("UPDATE nilai SET nilai_akhir = '$nilai_akhir' 
                                    WHERE nilai_id = '{$data['nilai_id']}'") or die(mysql_error());
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
                            echo "Tuntas";
                        } else {
                            echo "Tidak Tuntas";
                        }
                        ?>
                    </td>
                    <td>
                        
                        <a href="?nilai-del=<?php echo $data['nilai_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php
                    $no++;
                    }                                            
                ?>                                                                                
            </tbody>
        </table>

        <div style="padding-top: 20px;">
            <div style="padding-top: 20px;margin-bottom: -30px;"><a href="?nilai=tampil" class="btn btn-purple">Kembali</a></div>                                   
        </div>
    </div>
</div>