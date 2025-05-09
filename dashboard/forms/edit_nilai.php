<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Edit Nilai Form</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">KKM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kkm" value="<?php echo $row['nilai_kkm']; ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai UH</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="uh" value="<?php echo $row['uh']; ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai PAS</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="pas" value="<?php echo $row['pas']; ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai P5RA</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="p5ra" value="<?php echo $row['p5ra']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nilai Tugas</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="tugas" value="<?php echo $row['tugas']; ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kehadiran</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="kehadiran"
                                value="<?php echo $row['kehadiran']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Keaktifan</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="keaktifan"
                                value="<?php echo $row['keaktifan']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kekompakan</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="kekompakan"
                                value="<?php echo $row['kekompakan']; ?>" required>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="nilai-update">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>