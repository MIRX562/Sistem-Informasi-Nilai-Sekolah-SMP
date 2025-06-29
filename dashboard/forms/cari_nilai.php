<?php 
if (isset($_SESSION['access']) && $_SESSION['access'] == 'orang_tua') {
    // Orang tua: show top form and include logic for nilai1_table.php
    if (isset($_POST['cari-nilai'])) {
        // Get selected semester and tahun
        $semester_id = isset($_POST['semester']) ? $_POST['semester'] : '';
        $tahun_id = isset($_POST['tahun']) ? $_POST['tahun'] : '';
        $student_id = $_SESSION['id'];
        // Show nilai table
        include('tables/nilai1_table.php');
        // Show Cetak PDF button
        $pdf_url = basename(__DIR__) . '/../generate_report_pdf.php';
        echo '<form method="get" action="' . $pdf_url . '" target="_blank" style="margin-top:20px;">';
        echo '<input type="hidden" name="student_id" value="' . htmlspecialchars($student_id) . '">';
        echo '<input type="hidden" name="semester_id" value="' . htmlspecialchars($semester_id) . '">';
        echo '<input type="hidden" name="tahun_id" value="' . htmlspecialchars($tahun_id) . '">';
        echo '<button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o"></i> Cetak PDF</button>';
        echo '</form>';
    } else {
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
                        <label class="col-sm-2 control-label no-padding-right">Semester</label>
                        <div class="col-sm-10">
                            <select id="e5" style="width:100%;" name="semester" required>
                                <?php 
                                    $semester  =   mysqli_query($conn, "SELECT * FROM semester");

                                    while ($data=mysqli_fetch_array($semester)) {
                                ?>
                                <option value="<?php echo $data['semester_id']; ?>">
                                    <?php echo $data['semester_nama']; ?></option>
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
                                <option value="<?php echo $data['tahun_id']; ?>"><?php echo $data['tahun_nama']; ?>
                                </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <hr />
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
} else {
    // Guru/Admin: show bottom form and include logic for nilai2_table.php
    if (isset($_POST['submit'])) {
        include('tables/nilai2_table.php');
    } else {
    ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Cari Data Nilai Siswa</h3>
    </div>
    <div class="panel-body">
        <form method="post" action="">
            <div class="form-group">
                <label>Tahun Ajaran</label>
                <select name="tahun" class="form-control" required>
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php 
                        $tahun = mysqli_query($conn, "SELECT * FROM tahun");
                        while($row = mysqli_fetch_array($tahun)) { ?>
                    <option value="<?php echo $row['tahun_id'] ?>"><?php echo $row['tahun_nama'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Semester</label>
                <select name="semester" class="form-control" required>
                    <option value="">Pilih Semester</option>
                    <?php 
                        $semester = mysqli_query($conn, "SELECT * FROM semester");
                        while($row = mysqli_fetch_array($semester)) { ?>
                    <option value="<?php echo $row['semester_id'] ?>"><?php echo $row['semester_nama'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <select name="kelas" class="form-control" required>
                    <option value="">Pilih Kelas</option>
                    <?php 
                        $kelas = mysqli_query($conn, "SELECT * FROM kelas");
                        while($row = mysqli_fetch_array($kelas)) { ?>
                    <option value="<?php echo $row['kelas_id'] ?>"><?php echo $row['kelas_nama'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pelajaran</label>
                <select name="pelajaran" class="form-control" required>
                    <option value="">Pilih Pelajaran</option>
                    <?php 
                        $pelajaran = mysqli_query($conn, "SELECT * FROM pelajaran");
                        while($row = mysqli_fetch_array($pelajaran)) { ?>
                    <option value="<?php echo $row['pelajaran_id'] ?>"><?php echo $row['pelajaran_nama'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Cari Data</button>
        </form>
    </div>
</div>
<?php
    }
}
?>