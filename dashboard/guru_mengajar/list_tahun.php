<?php
include '../config/db.php';
$tahun_id = isset($_GET['tahun_id']) ? $_GET['tahun_id'] : '';
$semester_id = isset($_GET['semester_id']) ? $_GET['semester_id'] : '';
$show_warning = false;
if (isset($_GET['submit_tahun_semester'])) {
    if (empty($tahun_id) || empty($semester_id)) {
        $show_warning = true;
    } else {
        // Redirect to list_guru with tahun_id and semester_id
        echo '<meta http-equiv="refresh" content="0;url=?akademik=guru_mengajar-list_guru&tahun_id=' . $tahun_id . '&semester_id=' . $semester_id . '">';
        exit;
    }
}
?>
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Pilih Tahun Ajaran & Semester</span>
        </div>
        <div class="widget-body">
            <?php if ($show_warning): ?>
                <div class="alert alert-warning">Pilih tahun ajaran dan semester terlebih dahulu.</div>
            <?php endif; ?>
            <form method="GET" class="form-inline">
                <input type="hidden" name="akademik" value="guru_mengajar">
                <div class="form-group">
                    <label for="tahun_id">Tahun Ajaran</label>
                    <select class="form-control" name="tahun_id" id="tahun_id" required>
                        <option value="">Pilih Tahun</option>
                        <?php
                        $tahun = mysqli_query($conn, "SELECT * FROM tahun ORDER BY tahun_nama DESC");
                        while ($t = mysqli_fetch_array($tahun)) {
                            $selected = ($tahun_id == $t['tahun_id']) ? 'selected' : '';
                            echo "<option value='{$t['tahun_id']}' $selected>{$t['tahun_nama']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" style="margin-left:10px;">
                    <label for="semester_id">Semester</label>
                    <select class="form-control" name="semester_id" id="semester_id" required>
                        <option value="">Pilih Semester</option>
                        <?php
                        $semester = mysqli_query($conn, "SELECT * FROM semester ORDER BY semester_nama ASC");
                        while ($s = mysqli_fetch_array($semester)) {
                            $selected = ($semester_id == $s['semester_id']) ? 'selected' : '';
                            echo "<option value='{$s['semester_id']}' $selected>Semester {$s['semester_nama']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="submit_tahun_semester" class="btn btn-primary" style="margin-left:10px;">Pilih</button>
            </form>
        </div>
    </div>
</div>