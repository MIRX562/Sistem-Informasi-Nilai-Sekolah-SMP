<?php
// Ambil ID pelajaran dari URL
$pelajaran_id = $_GET['pelajaran-det'];

// Query untuk mendapatkan informasi pelajaran
$pelajaran_query = mysqli_query($conn, "SELECT * FROM pelajaran WHERE pelajaran_id = '$pelajaran_id'");
$pelajaran_data = mysqli_fetch_array($pelajaran_query);

// Query untuk mendapatkan daftar guru yang mengajar pelajaran ini
$guru_query = mysqli_query($conn, "SELECT users.id, users.nomor_induk, users.name, users.username, users.telp, users.status, users.alamat, users.jenis_kelamin
                                        FROM users 
                                        WHERE users.access='guru'
                                        ORDER BY users.name ASC");
?>

<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Detail Mata Pelajaran: <?php echo $pelajaran_data['pelajaran_nama']; ?>
        </div>

        <!-- Informasi Pelajaran -->
        <div class="panel panel-default" style="margin-bottom: 20px;">
            <div class="panel-heading">
                <h4>Informasi Mata Pelajaran</h4>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="20%"><strong>ID Pelajaran</strong></td>
                        <td><?php echo $pelajaran_data['pelajaran_id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Pelajaran</strong></td>
                        <td><?php echo $pelajaran_data['pelajaran_nama']; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Daftar Guru yang Mengajar -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Daftar Guru yang Mengajar Mata Pelajaran Ini</h4>
            </div>
            <div class="panel-body">
                <?php if (mysqli_num_rows($guru_query) > 0) { ?>
                    <table class="table table-hover table-striped">
                        <thead class="bordered-darkorange">
                            <tr>
                                <th>#</th>
                                <th>NIP</th>
                                <th>Nama Guru</th>
                                <th>Username</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th>Alamat</th>
                                <th>Jenis Kelamin</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($guru_data = mysqli_fetch_array($guru_query)) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $guru_data['nomor_induk']; ?></td>
                                    <td><?php echo $guru_data['name']; ?></td>
                                    <td><?php echo $guru_data['username']; ?></td>
                                    <td><?php echo $guru_data['telp']; ?></td>
                                    <td><?php echo $guru_data['status']; ?></td>
                                    <td><?php echo $guru_data['alamat']; ?></td>
                                    <td><?php echo $guru_data['jenis_kelamin']; ?></td>
                                    <td>
                                        <a href="?guru-edit=<?php echo $guru_data['id']; ?>"
                                            class="btn btn-success btn-sm">Edit</a>
                                        <a href="?guru-del=<?php echo $guru_data['id']; ?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-warning">
                        <strong>Tidak ada guru yang mengajar mata pelajaran ini.</strong>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="footer">
            <a href="?akademik=pelajaran" class="btn btn-primary">Kembali ke Daftar Pelajaran</a>
        </div>
    </div>
</div>