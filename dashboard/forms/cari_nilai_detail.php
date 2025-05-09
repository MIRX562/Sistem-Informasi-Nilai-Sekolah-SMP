<?php 
    if (isset($_POST['cari-nilai'])) {
        $semester = $_POST['semester'];
        $tahun = $_POST['tahun'];

        $sql = mysqli_query($conn, "SELECT nilai.*, users.name, pelajaran.pelajaran_nama 
                                  FROM nilai 
                                  INNER JOIN users ON nilai.id=users.id 
                                  INNER JOIN pelajaran ON nilai.pelajaran_id=pelajaran.pelajaran_id 
                                  WHERE nilai.semester_id='$semester' 
                                  AND nilai.tahun_id='$tahun' 
                                  AND users.id='" . $_SESSION['id'] . "'
                                  ORDER BY pelajaran.pelajaran_nama ASC");
?>

<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="well with-header  with-footer">
        <div class="header bg-blue">
            Data Nilai
        </div>
        <table class="table table-hover">
            <thead class="bordered-darkorange">
                <tr>
                    <th>#</th>
                    <th>Mata Pelajaran</th>
                    <th>KKM</th>
                    <th>UH</th>
                    <th>PAS</th>
                    <th>P5RA</th>
                    <th>Tugas</th>
                    <th>Kehadiran</th>
                    <th>Keaktifan</th>
                    <th>Kekompakan</th>
                    <th>Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>                                        
                <?php
                    $no = 1;
                    while ($data = mysqli_fetch_array($sql)) {
                        // Calculate final grade
                        $nilai_akhir = 0;
                        
                        // Convert values to float for calculation
                        $uh = floatval($data['uh']);
                        $pas = floatval($data['pas']);
                        $p5ra = floatval($data['p5ra']);
                        $tugas = floatval($data['tugas']);
                        $kehadiran = floatval($data['kehadiran']);
                        $keaktifan = floatval($data['keaktifan']);
                        $kekompakan = floatval($data['kekompakan']);

                        // Calculate final grade based on whether P5RA exists
                        if (!empty($data['p5ra']) && $p5ra > 0) {
                            // With P5RA
                            $nilai_akhir = ($uh * 0.20) + ($pas * 0.30) + ($p5ra * 0.20) + 
                                         ($tugas * 0.15) + ($kehadiran * 0.05) + 
                                         ($keaktifan * 0.05) + ($kekompakan * 0.05);
                        } else {
                            // Without P5RA
                            $nilai_akhir = ($uh * 0.25) + ($pas * 0.35) + ($tugas * 0.20) + 
                                         ($kehadiran * 0.075) + ($keaktifan * 0.0625) + 
                                         ($kekompakan * 0.0625);
                        }
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['pelajaran_nama']; ?></td>
                    <td><?php echo $data['nilai_kkm']; ?></td>
                    <td><?php echo $data['uh']; ?></td>
                    <td><?php echo $data['pas']; ?></td>
                    <td><?php echo $data['p5ra']; ?></td>
                    <td><?php echo $data['tugas']; ?></td>
                    <td><?php echo $data['kehadiran']; ?></td>
                    <td><?php echo $data['keaktifan']; ?></td>
                    <td><?php echo $data['kekompakan']; ?></td>
                    <td><?php echo number_format($nilai_akhir, 2); ?></td>
                </tr>
                <?php
                        $no++;
                    }
                ?>
            </tbody>
        </table>

        <div class="footer">
            <a href="index.php" class="btn btn-darkorange">Kembali</a>
        </div>
    </div>
</div>
<?php
    }else{
?>
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Cari Nilai Form</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Mata Pelajaran</label>
                        <div class="col-sm-10">
                            <select id="e2" style="width:100%;" name="pelajaran" required>
                                <?php 
                                    $pelajaran  =   mysqli_query($conn, "SELECT * FROM pelajaran");

                                    while ($data=mysqli_fetch_array($pelajaran)) {
                                ?>
                                <option value="<?php echo $data['pelajaran_id']; ?>"><?php echo $data['pelajaran_nama']; ?></option>
                                <?php
                                    }
                                ?>                                                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kelas</label>
                        <div class="col-sm-10">
                            <select id="e4" style="width:100%;" name="kelas" required>
                                <?php 
                                    $kelas  =   mysqli_query($conn, "SELECT * FROM kelas");

                                    while ($data=mysqli_fetch_array($kelas)) {
                                ?>
                                <option value="<?php echo $data['kelas_id']; ?>"><?php echo $data['kelas_nama']; ?></option>
                                <?php
                                    }
                                ?>                                                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Semester</label>
                        <div class="col-sm-10">
                            <select id="e5" style="width:100%;" name="semester" required>
                                <?php 
                                    $semester  =   mysqli_query($conn, "SELECT * FROM semester");

                                    while ($data=mysqli_fetch_array($semester)) {
                                ?>
                                <option value="<?php echo $data['semester_id']; ?>"><?php echo $data['semester_nama']; ?></option>
                                <?php
                                    }
                                ?>                                                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Tahun Ajaran</label>
                        <div class="col-sm-10">
                            <select id="e1" style="width:100%;" name="tahun" required>
                                <?php 
                                    $tahun  =   mysqli_query($conn, "SELECT * FROM tahun");

                                    while ($data=mysqli_fetch_array($tahun)) {
                                ?>
                                <option value="<?php echo $data['tahun_id']; ?>"><?php echo $data['tahun_nama']; ?></option>
                                <?php
                                    }
                                ?>                                                                
                            </select>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="cari-nilai">Cari</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    }
?>