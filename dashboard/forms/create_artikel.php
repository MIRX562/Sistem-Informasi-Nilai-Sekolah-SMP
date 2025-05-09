<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Artikel Form</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Judul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="judul" placeholder="Judul" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Isi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="editor" name="isi" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kategori</label>
                        <div class="col-sm-10">
                            <select id="e1" style="width:100%;" name="kategori" required>
                                <?php
                                $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                                while ($data = mysqli_fetch_array($kategori)) {
                                    ?>
                                    <option value="<?php echo $data['kategori_id']; ?>">
                                        <?php echo $data['kategori_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="create-artikel">Create</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor'
    });
</script>