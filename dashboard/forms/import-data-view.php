<?php
// Only handle the form display, not the logic
?>
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Data Import Tool</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data"
                    action="?data=import">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Import Type</label>
                        <div class="col-sm-10">
                            <select id="import_type" style="width:100%;" name="import_type" required>
                                <option value="">-- Select Import Type --</option>
                                <option value="guru">Guru</option>
                                <option value="siswa">Siswa</option>
                                <option value="nilai">Nilai</option>
                                <option value="guru_mengajar">Guru Mengajar</option>
                                <option value="kelas">Kelas</option>
                                <option value="pelajaran">Pelajaran</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Import File (CSV)</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="import_file" required>
                            <small class="form-text text-muted">Only CSV files are supported.</small>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Import Data</button>
                        </div>
                    </div>
                </form>
                <div class="mt-4">
                    <h2>Template CSV</h2>
                    <p>Download template untuk tiap tipe:</p>
                    <ul>
                        <li><a href="templates/guru_template.csv" class="btn btn-link" download>Guru Template</a></li>
                        <li><a href="templates/siswa_template.csv" class="btn btn-link" download>Siswa Template</a></li>
                        <li><a href="templates/nilai_template.csv" class="btn btn-link" download>Nilai Template</a></li>
                        <li><a href="templates/guru_mengajar_template.csv" class="btn btn-link" download>Guru Mengajar
                                Template</a></li>
                        <li><a href="templates/kelas_template.csv" class="btn btn-link" download>Kelas Template</a></li>
                        <li><a href="templates/pelajaran_template.csv" class="btn btn-link" download>Pelajaran
                                Template</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>