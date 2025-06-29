<?php
// Dashboard for Orang Tua - Beyond Admin Style
?>
<div class="page-content">
    <div class="page-header position-relative">
        <div class="header-title">
            <h1 class="m-0">Dashboard Orang Tua</h1>
        </div>
        <div class="header-buttons">
            <a class="sidebar-toggler" href="#"><i class="fa fa-arrows-h"></i></a>
            <a class="refresh" id="refresh-toggler" href="#"><i class="glyphicon glyphicon-refresh"></i></a>
            <a class="fullscreen" id="fullscreen-toggler" href="#"><i class="glyphicon glyphicon-fullscreen"></i></a>
        </div>
    </div>
    <div class="page-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-primary fade in">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-user"></i>
                    Selamat datang <strong><?php echo $row['name']; ?></strong> di dashboard orang tua.
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                    <div class="databox-left ">
                        <div class="databox-piechart">
                            <i class="fa fa-user-graduate fa-3x text-primary"></i>
                        </div>
                    </div>
                    <div class="databox-right">
                        <span class="databox-number sky">
                            <?php echo $row['name']; ?>
                        </span>
                        <div class="databox-text darkgray">Nama Siswa</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                    <div class="databox-left ">
                        <div class="databox-piechart">
                            <i class="fa fa-school fa-3x text-success"></i>
                        </div>
                    </div>
                    <div class="databox-right">
                        <span class="databox-number green">
                            <?php
                            $kelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT kelas_nama FROM kelas WHERE kelas_id='{$row['kelas_id']}'"));
                            echo $kelas ? $kelas['kelas_nama'] : '-';
                            ?>
                        </span>
                        <div class="databox-text darkgray">Kelas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="widget radius-bordered">
                    <div class="widget-header bg-primary">
                        <span class="widget-caption"><i class="fa fa-chart-line"></i> Info Nilai Terakhir</span>
                    </div>
                    <div class="widget-body">
                        <?php
                        $nilai = mysqli_query($conn, "SELECT n.*, p.pelajaran_nama, t.tahun_nama, s.semester_nama FROM nilai n LEFT JOIN pelajaran p ON n.pelajaran_id=p.pelajaran_id LEFT JOIN tahun t ON n.tahun_id=t.tahun_id LEFT JOIN semester s ON n.semester_id=s.semester_id WHERE n.id='{$row['id']}' ORDER BY n.tahun_id DESC, n.semester_id DESC LIMIT 5");
                        if (mysqli_num_rows($nilai)) {
                            echo '<div class=\'table-responsive\'><table class=\'table table-bordered table-striped align-middle\'><thead class=\'table-light\'><tr><th>Pelajaran</th><th>Tahun</th><th>Semester</th><th>Nilai Akhir</th></tr></thead><tbody>';
                            while ($n = mysqli_fetch_assoc($nilai)) {
                                echo "<tr><td>{$n['pelajaran_nama']}</td><td>{$n['tahun_nama']}</td><td>{$n['semester_nama']}</td><td class='fw-bold text-center'>{$n['nilai_akhir']}</td></tr>";
                            }
                            echo '</tbody></table></div>';
                        } else {
                            echo '<div class=\'alert alert-info\'>Belum ada nilai.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>