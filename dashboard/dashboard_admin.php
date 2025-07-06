<?php
// Dashboard for Admin - Beyond Admin Style
?>
<div class="page-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info fade in">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <i class="fa-fw fa fa-user-shield"></i>
                Selamat datang <strong><?php echo $row['name']; ?></strong> di panel administrasi sistem.
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                <div class="databox-left">
                    <div class="databox-piechart">
                        <i class="glyphicon glyphicon-user fa-3x text-info"></i>
                    </div>
                </div>
                <div class="databox-right">
                    <span class="databox-number sky">
                        <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE access='orang_tua'")); ?>
                    </span>
                    <div class="databox-text darkgray">Total Siswa</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                <div class="databox-left">
                    <div class="databox-piechart">
                        <i class="glyphicon glyphicon-briefcase fa-3x text-success"></i>
                    </div>
                </div>
                <div class="databox-right">
                    <span class="databox-number green">
                        <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE access='guru'")); ?>
                    </span>
                    <div class="databox-text darkgray">Total Guru</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                <div class="databox-left">
                    <div class="databox-piechart">
                        <i class="glyphicon glyphicon-home fa-3x text-warning"></i>
                    </div>
                </div>
                <div class="databox-right">
                    <span class="databox-number orange">
                        <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kelas")); ?>
                    </span>
                    <div class="databox-text darkgray">Total Kelas</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="databox databox-graded databox-xlg databox-shadowed bg-white radius-bordered">
                <div class="databox-left">
                    <div class="databox-piechart">
                        <i class="glyphicon glyphicon-book fa-3x text-danger"></i>
                    </div>
                </div>
                <div class="databox-right">
                    <span class="databox-number red">
                        <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pelajaran")); ?>
                    </span>
                    <div class="databox-text darkgray">Mata Pelajaran</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="widget radius-bordered">
                <div class="widget-header bg-blue">
                    <span class="widget-caption"><i class="fa fa-info-circle"></i> Info Sekolah</span>
                </div>
                <div class="widget-body">
                    <?php $sekolah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sekolah LIMIT 1")); ?>
                    <dl class="dl-horizontal">
                        <dt>Nama</dt>
                        <dd><?php echo $sekolah['sekolah_nama']; ?></dd>
                        <dt>Alamat</dt>
                        <dd><?php echo $sekolah['sekolah_alamat']; ?></dd>
                        <dt>Telp</dt>
                        <dd><?php echo $sekolah['sekolah_telp']; ?></dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="widget radius-bordered">
                <div class="widget-header bg-orange">
                    <span class="widget-caption"><i class="fa fa-bullseye"></i> Visi & Misi</span>
                </div>
                <div class="widget-body">
                    <div class="mb-2"><strong>Visi:</strong></div>
                    <div class="mb-3 text-dark"> <?php echo $sekolah['sekolah_visi']; ?> </div>
                    <div class="mb-2"><strong>Misi:</strong></div>
                    <div class="text-dark"> <?php echo $sekolah['sekolah_misi']; ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>