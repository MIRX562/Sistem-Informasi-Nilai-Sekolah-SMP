<?php
if (isset($_POST['input-nilai'])) {
    include('tables/input_nilai_table.php');
} else {
    $user_id = $_SESSION['id'];

    $user_info = mysqli_query($conn, "SELECT users.*, kelas.kelas_nama, pelajaran.pelajaran_nama 
                                         FROM users 
                                         LEFT JOIN kelas ON users.kelas_id = kelas.kelas_id 
                                         LEFT JOIN pelajaran ON users.pelajaran_id = pelajaran.pelajaran_id 
                                         WHERE users.id = '$user_id' AND (users.access = 'guru' OR users.access = 'admin')");
    $user_data = mysqli_fetch_array($user_info);

    if (!$user_data) {
        echo "<div class='alert alert-danger'>Akses ditolak. Anda tidak memiliki hak untuk mengakses halaman ini.</div>";
        exit;
    }

    $user_access = $user_data['access'];
    ?>
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="widget">
            <div class="widget-header bordered-bottom bordered-lightred">
                <span class="widget-caption">
                    Form Input Nilai - <?php echo $user_data['name']; ?>
                    <?php if ($user_access == 'admin'): ?>
                        <span class="badge badge-admin">ADMIN</span>
                    <?php endif; ?>
                </span>
            </div>

            <div class="alert <?php echo ($user_access == 'admin') ? 'alert-success' : 'alert-info'; ?>"
                style="margin: 15px;">
                <strong>Info Akses:</strong>
                <?php if ($user_access == 'admin'): ?>
                    <i class="fa fa-star"></i> Sebagai Admin, Anda memiliki akses penuh untuk input nilai di semua mata
                    pelajaran dan kelas.
                <?php else: ?>
                    <i class="fa fa-user"></i> Sebagai Guru, Anda hanya dapat input nilai untuk mata pelajaran dan kelas yang
                    Anda ampu.
                <?php endif; ?>
            </div>

            <div class="widget-body">
                <div id="horizontal-form">
                    <form class="form-horizontal" role="form" method="POST" action="">
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">Mata Pelajaran</label>
                            <div class="col-sm-10">
                                <select id="e1" style="width:100%;" name="pelajaran" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    <?php
                                    if ($user_access == 'admin') {
                                        // Admin bisa akses semua mata pelajaran
                                        $pelajaran = mysqli_query($conn, "SELECT pelajaran_id, pelajaran_nama FROM pelajaran ORDER BY pelajaran_nama ASC");
                                    } else {
                                        // Guru hanya pelajaran yang diajar dan aktif (dari guru_mengajar)
                                        $pelajaran = mysqli_query($conn, "SELECT DISTINCT p.pelajaran_id, p.pelajaran_nama 
            FROM pelajaran p
            INNER JOIN guru_mengajar gm ON p.pelajaran_id = gm.pelajaran_id
            WHERE gm.guru_id = '$user_id' AND gm.status = 'aktif'
            ORDER BY p.pelajaran_nama ASC");
                                    }
                                    while ($data = mysqli_fetch_array($pelajaran)) {
                                        ?>
                                        <option value="<?php echo $data['pelajaran_id']; ?>">
                                            <?php echo $data['pelajaran_nama']; ?>
                                        </option>
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
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php
                                    if ($user_access == 'admin') {
                                        // Admin bisa akses semua kelas
                                        $kelas = mysqli_query($conn, "SELECT kelas_id, kelas_nama FROM kelas ORDER BY kelas_nama ASC");
                                    } else {
                                        // Guru hanya kelas yang diajar dan aktif (dari guru_mengajar)
                                        $kelas = mysqli_query($conn, "SELECT DISTINCT k.kelas_id, k.kelas_nama 
            FROM kelas k
            INNER JOIN guru_mengajar gm ON k.kelas_id = gm.kelas_id
            WHERE gm.guru_id = '$user_id' AND gm.status = 'aktif'
            ORDER BY k.kelas_nama ASC");
                                    }
                                    while ($data = mysqli_fetch_array($kelas)) {
                                        ?>
                                        <option value="<?php echo $data['kelas_id']; ?>"><?php echo $data['kelas_nama']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <?php if ($user_access == 'guru'): ?>
                                    <small class="help-block text-muted">
                                        <i class="fa fa-info-circle"></i> Anda hanya dapat memilih kelas yang Anda ajar
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">Semester</label>
                            <div class="col-sm-10">
                                <select id="e4" style="width:100%;" name="semester" required>
                                    <option value="">-- Pilih Semester --</option>
                                    <?php
                                    $semester = mysqli_query($conn, "SELECT * FROM semester ORDER BY semester_nama ASC");

                                    while ($data = mysqli_fetch_array($semester)) {
                                        ?>
                                        <option value="<?php echo $data['semester_id']; ?>">
                                            <?php echo $data['semester_nama']; ?>
                                        </option>
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
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    <?php
                                    $tahun = mysqli_query($conn, "SELECT * FROM tahun ORDER BY tahun_nama DESC");

                                    while ($data = mysqli_fetch_array($tahun)) {
                                        ?>
                                        <option value="<?php echo $data['tahun_id']; ?>"><?php echo $data['tahun_nama']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">KKM <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input class="form-control" name="kkm" type="number" min="0" max="100" step="0.01" required
                                    placeholder="Contoh: 75">
                                <small class="help-block">
                                    <i class="fa fa-info-circle"></i> Masukkan nilai KKM (Kriteria Ketuntasan Minimal)
                                    antara 0-100
                                </small>
                            </div>
                        </div>

                        <!-- Tambahan info untuk admin -->
                        <?php if ($user_access == 'admin'): ?>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        <strong>Perhatian Admin:</strong> Pastikan Anda memilih mata pelajaran dan kelas yang
                                        sesuai sebelum melakukan input nilai.
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="input-nilai" class="btn btn-primary">
                                    <i class="fa fa-table"></i> Tampilkan Tabel Input Nilai
                                </button>
                                <button type="reset" class="btn btn-warning">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                                <small class="help-block text-danger">
                                    <i class="fa fa-asterisk"></i> Field bertanda * wajib diisi
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-admin {
            background-color: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            margin-left: 10px;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }

        .help-block {
            margin-top: 5px;
            font-size: 12px;
        }

        .text-muted {
            color: #6c757d;
        }
    </style>

    <script>
        // JavaScript untuk validasi tambahan
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const pelajaranSelect = document.getElementById('e1');
            const kelasSelect = document.getElementById('e2');

            form.addEventListener('submit', function (e) {
                if (!pelajaranSelect.value || !kelasSelect.value) {
                    e.preventDefault();
                    alert('Harap pilih mata pelajaran dan kelas terlebih dahulu!');
                }
            });
        });
    </script>

    <?php
}
?>