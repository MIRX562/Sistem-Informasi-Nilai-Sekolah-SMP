<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Edit Guru Form</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">NIP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nip"
                                value="<?php echo $row['nomor_induk']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username"
                                value="<?php echo $row['username']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Status</label>
                        <div class="col-sm-10">
                            <select id="e1" style="width:100%;" name="status" required>
                                <option <?php if ($row['status'] == 'Honorer') {
                                    echo 'selected';
                                } ?>>Honorer</option>
                                <option <?php if ($row['status'] == 'PNS') {
                                    echo 'selected';
                                } ?>>PNS</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kelas</label>
                        <div class="col-sm-10">
                            <select id="e5" style="width:100%;" name="kelas" required>
                                <?php
                                $kelas = mysqli_query($conn, "SELECT * FROM kelas");
                                while ($data = mysqli_fetch_array($kelas)) {
                                    ?>
                                    <option value="<?php echo $data['kelas_id']; ?>" <?php if ($row['kelas_id'] == $data['kelas_id']) {
                                           echo 'selected';
                                       } ?>>
                                        <?php echo $data['kelas_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Telepon</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="telp" value="<?php echo $row['telp']; ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Kelamin</label>
                        <div class="col-sm-10">
                            <select id="e2" style="width:100%;" name="jenis_kelamin" required>
                                <option <?php if ($row['jenis_kelamin'] == 'Laki-laki') {
                                    echo 'selected';
                                } ?>>Laki-laki
                                </option>
                                <option <?php if ($row['jenis_kelamin'] == 'Perempuan') {
                                    echo 'selected';
                                } ?>>Perempuan
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Alamat</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="alamat"
                                required><?php echo $row['alamat']; ?></textarea>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="guru-update">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>