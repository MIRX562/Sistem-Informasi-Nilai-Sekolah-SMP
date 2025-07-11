<div class="col-xs-12 col-md-12">
    <div class="well with-header  with-footer">
        <div class="header bg-blue">
            Guru
        </div>
        <table class="table table-hover">
            <thead class="bordered-darkorange">
                <tr>
                    <th>#</th>
                    <th>NIP</th>
                    <th>Name</th>
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
                $guru = mysqli_query($conn, "SELECT users.id, users.nomor_induk, users.name, users.username, users.telp, users.status, users.alamat, users.jenis_kelamin FROM users WHERE access='guru' ORDER BY users.name ASC");

                if (!$guru) {
                    echo '<tr><td colspan="9">Query error: ' . mysqli_error($conn) . '</td></tr>';
                } else {
                    while ($data = mysqli_fetch_array($guru)) {
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['nomor_induk']; ?></td>
                            <td><?php echo $data['name']; ?></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['telp']; ?></td>
                            <td><?php echo $data['status']; ?></td>
                            <td><?php echo $data['alamat']; ?></td>
                            <td><?php echo $data['jenis_kelamin']; ?></td>
                            <td>
                                <a href="?guru-edit=<?php echo $data['id']; ?>" class="btn btn-success">Edit</a>
                                <a href="?guru-del=<?php echo $data['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php
                        $no++;
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="footer">
            <a href="?users=guru-create" class="btn btn-primary">Create</a>
        </div>
    </div>
</div>