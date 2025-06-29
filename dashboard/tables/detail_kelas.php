<?php
// Ambil kelas_id dari parameter URL
$kelas_id = $_GET['kelas-det'];

// Query untuk mendapatkan informasi kelas
$kelas_query = mysqli_query($conn, "SELECT * FROM kelas WHERE kelas_id = '$kelas_id'");
$kelas_data = mysqli_fetch_array($kelas_query);

// Query untuk mendapatkan daftar siswa dalam kelas tersebut
$siswa_query = mysqli_query($conn, "SELECT users.id, users.nomor_induk, users.name, users.username, users.telp, users.status, users.alamat, users.jenis_kelamin 
                                  FROM users 
                                  WHERE users.kelas_id = '$kelas_id' AND access='orang_tua' 
                                  ORDER BY users.name ASC");

// Hitung jumlah siswa
$jumlah_siswa = mysqli_num_rows($siswa_query);
?>

<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Detail Kelas: <?php echo $kelas_data['kelas_nama']; ?>
        </div>
        
        <!-- Informasi Kelas -->
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-6">
                <h4>Informasi Kelas</h4>
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Nama Kelas</strong></td>
                        <td><?php echo $kelas_data['kelas_nama']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Siswa</strong></td>
                        <td><?php echo $jumlah_siswa; ?> orang</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <h4>Daftar Siswa</h4>
        <?php if ($jumlah_siswa > 0) { ?>
            <table class="table table-hover">
                <thead class="bordered-darkorange">
                    <tr>
                        <th>#</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Username</th>
                        <th>Telepon</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($siswa_data = mysqli_fetch_array($siswa_query)) {
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $siswa_data['nomor_induk']; ?></td>
                            <td><?php echo $siswa_data['name']; ?></td>
                            <td><?php echo $siswa_data['username']; ?></td>
                            <td><?php echo $siswa_data['telp']; ?></td>
                            <td><?php echo $siswa_data['jenis_kelamin']; ?></td>
                            <td><?php echo $siswa_data['status']; ?></td>
                            <td><?php echo $siswa_data['alamat']; ?></td>
                            <td>
                                <a href="?siswa-edit=<?php echo $siswa_data['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="?siswa-del=<?php echo $siswa_data['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info">
                <strong>Info:</strong> Belum ada siswa yang terdaftar di kelas ini.
            </div>
        <?php } ?>

        <div class="footer">
            <a href="?akademik=kelas" class="btn btn-primary">Kembali ke Daftar Kelas</a>
            <a href="?users=siswa-create&kelas_id=<?php echo $kelas_id; ?>" class="btn btn-success">Tambah Siswa ke Kelas Ini</a>
        </div>
    </div>
</div>