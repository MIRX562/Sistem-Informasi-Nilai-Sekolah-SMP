<?php
// Dashboard for Guru - Beyond Admin Style
?>
<div class="page-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success fade in">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <i class="fa-fw fa fa-chalkboard-teacher"></i>
                Selamat datang <strong><?php echo $row['name']; ?></strong> di dashboard guru.
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-6 col-xs-12">
            <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                <div class="databox-left ">
                    <div class="databox-piechart">
                        <i class="glyphicon glyphicon-home fa-3x text-success"></i>
                    </div>
                </div>
                <div class="databox-right">
                    <span class="databox-number green">
                        <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM guru_mengajar WHERE guru_id='$id' AND status='aktif'")); ?>
                    </span>
                    <div class="databox-text darkgray">Kelas Diampu</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xs-12">
            <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                <div class="databox-left ">
                    <div class="databox-piechart">
                        <i class="glyphicon glyphicon-book fa-3x text-info"></i>
                    </div>
                </div>
                <div class="databox-right">
                    <span class="databox-number blue">
                        <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT pelajaran_id FROM guru_mengajar WHERE guru_id='$id' AND status='aktif'")); ?>
                    </span>
                    <div class="databox-text darkgray">Mata Pelajaran</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xs-12">
            <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                <div class="databox-left ">
                    <div class="databox-piechart">
                        <i class="glyphicon glyphicon-user fa-3x text-primary"></i>
                    </div>
                </div>
                <div class="databox-right">
                    <span class="databox-number sky">
                        <?php
                        $kelas = mysqli_query($conn, "SELECT kelas_id FROM guru_mengajar WHERE guru_id='$id' AND status='aktif'");
                        $kelas_ids = [];
                        while ($k = mysqli_fetch_assoc($kelas))
                            $kelas_ids[] = $k['kelas_id'];
                        if (count($kelas_ids)) {
                            $kelas_in = implode(',', $kelas_ids);
                            echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE kelas_id IN ($kelas_in) AND access='orang_tua'"));
                        } else {
                            echo 0;
                        }
                        ?>
                    </span>
                    <div class="databox-text darkgray">Siswa Diampu</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="widget radius-bordered">
                <div class="widget-header bg-green">
                    <span class="widget-caption"><i class="fa fa-user"></i> Info Akun Guru</span>
                </div>
                <div class="widget-body">
                    <dl class="dl-horizontal">
                        <dt>Nama</dt>
                        <dd><?php echo $row['name']; ?></dd>
                        <dt>Nomor Induk</dt>
                        <dd><?php echo $row['nomor_induk']; ?></dd>
                        <dt>Status</dt>
                        <dd><?php echo $row['status']; ?></dd>
                        <dt>Jenis Kelamin</dt>
                        <dd><?php echo $row['jenis_kelamin']; ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget radius-bordered">
                <div class="widget-header bg-blue">
                    <span class="widget-caption"><i class="fa fa-lightbulb"></i> Tips</span>
                </div>
                <div class="widget-body">
                    <ul class="mb-0 ps-3">
                        <li>Periksa jadwal mengajar Anda secara berkala.</li>
                        <li>Input nilai siswa tepat waktu.</li>
                        <li>Gunakan fitur pencarian untuk menemukan data siswa dengan cepat.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>