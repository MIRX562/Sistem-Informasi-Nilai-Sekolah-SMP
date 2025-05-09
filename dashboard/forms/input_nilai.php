<?php 
    if (isset($_POST['input-nilai'])) {
        include('tables/input_nilai_table.php');
    }else{
?>
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Form Input Nilai</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST" action="../core/create.php">
                    <input type="hidden" name="crud" value="input_nilai">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Mata Pelajaran</label>
                        <div class="col-sm-10">
                            <select id="e1" style="width:100%;" name="pelajaran" required>
                                <?php 
                                    $pelajaran  =   mysql_query("SELECT * FROM pelajaran");

                                    while ($data=mysql_fetch_array($pelajaran)) {
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
                            <select id="e2" style="width:100%;" name="kelas" required>
                                <?php 
                                    $kelas  =   mysql_query("SELECT * FROM kelas");

                                    while ($data=mysql_fetch_array($kelas)) {
                                ?>
                                <option value="<?php echo $data['kelas_id']; ?>"><?php echo $data['kelas_nama']; ?></option>
                                <?php
                                    }
                                ?>                                                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Siswa</label>
                        <div class="col-sm-10">
                            <select id="e3" style="width:100%;" name="siswa" required>
                                <?php 
                                    $siswa  =   mysql_query("SELECT * FROM siswa");

                                    while ($data=mysql_fetch_array($siswa)) {
                                ?>
                                <option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
                                <?php
                                    }
                                ?>                                                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Semester</label>
                        <div class="col-sm-10">
                            <select id="e4" style="width:100%;" name="semester" required>
                                <?php 
                                    $semester  =   mysql_query("SELECT * FROM semester");

                                    while ($data=mysql_fetch_array($semester)) {
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
                            <select id="e5" style="width:100%;" name="tahun" required>
                                <?php 
                                    $tahun  =   mysql_query("SELECT * FROM tahun");

                                    while ($data=mysql_fetch_array($tahun)) {
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
                        <label class="col-sm-2 control-label no-padding-right">Nilai UH</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="uh" type="number" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai PAS</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="pas" type="number" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai P5RA</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="p5ra" type="number" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai Tugas</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="tugas" type="number" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kehadiran</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="kehadiran" type="number" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Keaktifan</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="keaktifan" type="number" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kekompakan</label>
                        <div class="col-sm-10">
                            <input class="form-control" name="kekompakan" type="number" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
