<div class="col-xs-12 col-md-12">
    <div class="well with-header  with-footer">
        <div class="header bg-blue">
            Nilai
        </div>
        <div class="col-lg-12" style="padding-bottom: 20px;">
            <div class="col-md-6 pull-left">
                <table width="100%">
                    <tr>
                        <?php
                        $pelajaran = $_POST['pelajaran'];
                        $sql = mysqli_query($conn, "SELECT * FROM pelajaran WHERE pelajaran_id=$pelajaran");
                        $row = mysqli_fetch_array($sql);
                        ?>
                        <td width="30%">Mata Pelajaran</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['pelajaran_nama']; ?> </td>
                    </tr>
                    <tr>
                        <?php
                        $kelas = $_POST['kelas'];
                        $sql = mysqli_query($conn, "SELECT * FROM kelas WHERE kelas_id=$kelas");
                        $row = mysqli_fetch_array($sql);
                        ?>
                        <td width="30%">Kelas</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['kelas_nama']; ?> </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 pull-right">
                <table width="100%">
                    <tr>
                        <?php
                        $semester = $_POST['semester'];
                        $sql = mysqli_query($conn, "SELECT * FROM semester WHERE semester_id=$semester");
                        $row = mysqli_fetch_array($sql);
                        ?>
                        <td width="30%">Semester</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['semester_nama']; ?> </td>
                    </tr>
                    <tr>
                        <?php
                        $tahun = $_POST['tahun'];
                        $sql = mysqli_query($conn, "SELECT * FROM tahun WHERE tahun_id=$tahun");
                        $row = mysqli_fetch_array($sql);
                        ?>
                        <td width="30%">Tahun ajaran</td>
                        <td width="5%"> : </td>
                        <td width="65%"> <?php echo $row['tahun_nama']; ?> </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Alert untuk pesan validasi -->
        <div id="validation-alert" class="alert alert-danger" style="display: none; margin: 50px 0;">
            <strong>Perhatian!</strong> Nilai harus antara 0 sampai 100.
        </div>

        <div class="col-lg-12" style="padding-top: 10px; padding-bottom: 10px;">
            <div class="alert alert-info" style="margin-bottom: 10px;">
                <strong>Catatan:</strong> Kolom <strong>P5RA</strong> tidak wajib diisi.
            </div>
        </div>

        <table class="table table-hover">
            <thead class="bordered-darkorange">
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Nama Siswa</th>
                    <th width="8%">KKM</th>
                    <th width="8%">UH</th>
                    <th width="8%">PAS</th>
                    <th width="8%">P5RA</th>
                    <th width="8%">Tugas</th>
                    <th width="8%">Kehadiran</th>
                    <th width="8%">Keaktifan</th>
                    <th width="8%">Kekompakan</th>
                    <th width="8%">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                <form role="form" method="post" id="formNilai">
                    <?php

                    if (isset($_POST['input-nilai'])) {

                        $no = 1;
                        $kelas = $_POST['kelas'];
                        $pelajaran = $_POST['pelajaran'];
                        $semester = $_POST['semester'];
                        $tahun = $_POST['tahun'];
                        $access = 'orang_tua';
                        $kkm = $_POST['kkm'];
                        $sql = mysqli_query($conn, "SELECT users.id, users.name, users.access, kelas.kelas_id, kelas.kelas_nama 
                                                                        FROM users
                                                                        INNER JOIN kelas ON users.kelas_id=kelas.kelas_id
                                                                        WHERE kelas.kelas_id='$kelas' AND users.access='$access'
                                                                        ");
                        while ($data = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td>
                                    <input type="hidden" class="form-control" name="name[]" id="name[]"
                                        value="<?php echo $data['id']; ?>">
                                    <?php
                                    echo $data['name'];
                                    ?>
                                    <input type="hidden" name="pelajaran[]" id="pelajaran[]"
                                        value="<?php echo "$pelajaran"; ?>">
                                    <input type="hidden" name="semester[]" id="semester[]" value="<?php echo "$semester"; ?>">
                                    <input type="hidden" name="jenis[]" id="jenis[]" value="<?php echo "$jenis"; ?>">
                                    <input type="hidden" name="tahun[]" id="tahun[]" value="<?php echo "$tahun"; ?>">
                                    <input type="hidden" name="kkm[]" id="kkm[]" value="<?php echo "$kkm"; ?>">
                                </td>
                                <td><?php echo $kkm; ?></td>
                                <td>
                                    <input type="number" class="form-control nilai-input" name="uh[]" id="uh[]" min="0"
                                        max="100" onkeyup="validateNilai(this)" onchange="validateNilai(this)" />
                                </td>
                                <td>
                                    <input type="number" class="form-control nilai-input" name="pas[]" id="pas[]" min="0"
                                        max="100" onkeyup="validateNilai(this)" onchange="validateNilai(this)" />
                                </td>
                                <td>
                                    <input type="number" class="form-control nilai-input" name="p5ra[]" id="p5ra[]" min="0"
                                        max="100" onkeyup="validateNilai(this)" onchange="validateNilai(this)" />
                                </td>
                                <td>
                                    <input type="number" class="form-control nilai-input" name="tugas[]" id="tugas[]" min="0"
                                        max="100" onkeyup="validateNilai(this)" onchange="validateNilai(this)" />
                                </td>
                                <td>
                                    <input type="number" class="form-control nilai-input" name="kehadiran[]" id="kehadiran[]"
                                        min="0" max="100" onkeyup="validateNilai(this)" onchange="validateNilai(this)" />
                                </td>
                                <td>
                                    <input type="number" class="form-control nilai-input" name="keaktifan[]" id="keaktifan[]"
                                        min="0" max="100" onkeyup="validateNilai(this)" onchange="validateNilai(this)" />
                                </td>
                                <td>
                                    <input type="number" class="form-control nilai-input" name="kekompakan[]" id="kekompakan[]"
                                        min="0" max="100" onkeyup="validateNilai(this)" onchange="validateNilai(this)" />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="nilai_akhir[]" id="nilai_akhir[]" readonly />
                                </td>
                            </tr>
                            <?php
                            $no++;
                        }
                        ?>
                        <tr>
                            <td colspan="11" align="right" valign="baseline">
                                <button type="submit" class="btn btn-success" name="input-nilai" id="submitBtn">
                                    <span class="fa fa-gear"></span> Proses
                                </button>
                            </td>
                        </tr>
                    </form>
                    <?php
                    }
                    ?>
            </tbody>
        </table>
        <div style="padding-top: 20px;">
            <div style="padding-top: 20px;margin-bottom: -30px;"><a href="?nilai=input"
                    class="btn btn-purple">Kembali</a></div>
        </div>
    </div>
</div>

<style>
    .nilai-invalid {
        border-color: #dc3545 !important;
        background-color: #ffe6e6 !important;
    }

    .nilai-valid {
        border-color: #28a745 !important;
        background-color: #e6ffe6 !important;
    }

    .alert {
        padding: 10px;
        border-radius: 4px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>

<script>
    function validateNilai(input) {
        const value = parseFloat(input.value);
        const alertDiv = document.getElementById('validation-alert');
        const submitBtn = document.getElementById('submitBtn');

        // Reset classes
        input.classList.remove('nilai-invalid', 'nilai-valid');

        // Jika kosong DAN field-nya adalah P5RA -> tetap valid
        if (input.value === '' && input.name === 'p5ra[]') {
            input.classList.add('nilai-valid');
            alertDiv.style.display = 'none';
            checkAllInputs();
            return;
        }

        // Jika kosong untuk field lain, abaikan validasi (tidak dianggap valid/tidak)
        if (input.value === '') {
            alertDiv.style.display = 'none';
            return;
        }

        // Validasi rentang angka
        if (isNaN(value) || value < 0 || value > 100) {
            input.classList.add('nilai-invalid');
            alertDiv.style.display = 'block';
            alertDiv.innerHTML = '<strong>Perhatian!</strong> Nilai harus antara 0 sampai 100. Nilai yang Anda masukkan: ' +
                input.value;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="fa fa-exclamation-triangle"></span> Perbaiki Nilai Dulu';
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-danger');
        } else {
            input.classList.add('nilai-valid');
            checkAllInputs();
        }
    }


    function checkAllInputs() {
        const inputs = document.querySelectorAll('.nilai-input');
        const alertDiv = document.getElementById('validation-alert');
        const submitBtn = document.getElementById('submitBtn');
        let hasInvalid = false;

        inputs.forEach(function (input) {
            if (input.classList.contains('nilai-invalid')) {
                hasInvalid = true;
            }
        });

        if (!hasInvalid) {
            alertDiv.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span class="fa fa-gear"></span> Proses';
            submitBtn.classList.remove('btn-danger');
            submitBtn.classList.add('btn-success');
        }
    }

    // Prevent form submission if there are invalid inputs
    document.getElementById('formNilai').addEventListener('submit', function (e) {
        const invalidInputs = document.querySelectorAll('.nilai-invalid');
        if (invalidInputs.length > 0) {
            e.preventDefault();
            alert('Masih ada nilai yang tidak valid. Silakan perbaiki terlebih dahulu.');
            return false;
        }
    });

    // Prevent typing characters other than numbers
    document.querySelectorAll('.nilai-input').forEach(function (input) {
        input.addEventListener('keypress', function (e) {
            // Allow: backspace, delete, tab, escape, enter, decimal point
            if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
</script>