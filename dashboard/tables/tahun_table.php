<?php
include '../config/db.php';

// Check for success message
if (isset($_GET['set_active_success'])) {
    echo '<div class="alert alert-success">Tahun ajaran aktif berhasil diubah!</div>';
}

// Get active tahun_id from tahun table (using status)
$active_tahun = null;
$res = mysqli_query($conn, "SELECT tahun_id FROM tahun WHERE status='aktif' LIMIT 1");
if ($row = mysqli_fetch_assoc($res)) {
    $active_tahun = $row['tahun_id'];
}
?>
<div class="col-xs-12 col-md-12">
    <div class="well with-header  with-footer">
        <div class="header bg-blue">
            Tahun
        </div>
        <table class="table table-hover">
            <thead class="bordered-darkorange">
                <tr>
                    <th>#</th>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                    <th width="30%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $tahun = mysqli_query($conn, "SELECT * FROM tahun ORDER BY tahun_id ASC");

                while ($data = mysqli_fetch_array($tahun)) {
                    $is_active = ($data['status'] === 'aktif');
                    ?>
                    <tr<?php if ($is_active)
                        echo ' style="background:#e0f7fa; font-weight:bold;"'; ?>>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data['tahun_nama']; ?></td>
                        <td>
                            <?php if ($is_active) {
                                echo '<span class="badge badge-success">Aktif</span>';
                            } else {
                                echo '<span class="badge badge-default">Nonaktif</span>';
                            } ?>
                        </td>
                        <td>
                            <a href="?tahun-edit=<?php echo $data['tahun_id']; ?>" class="btn btn-success">Edit</a>
                            <a href="?tahun-del=<?php echo $data['tahun_id']; ?>" class="btn btn-danger">Delete</a>
                            <a href="?tahun-detail=<?php echo $data['tahun_id']; ?>" class="btn btn-info">Detail</a>
                            <?php if (!$is_active) { ?>
                                <a href="?set_active_tahun=<?php echo $data['tahun_id']; ?>" class="btn btn-warning"
                                    onclick="return confirm('Set tahun ini sebagai aktif?');">Set
                                    Active</a>
                            <?php } ?>
                        </td>
                        </tr>
                        <?php
                        $no++;
                }
                ?>
            </tbody>
        </table>

        <div class="footer">
            <a href="?akademik=tahun-create" class="btn btn-primary">Create</a>
        </div>
    </div>
</div>