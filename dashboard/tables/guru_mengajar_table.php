<div class="col-xs-12 col-md-12">
    <div class="well with-header with-footer">
        <div class="header bg-blue">
            Data Guru Mengajar
        </div>
        <table class="table table-hover table-striped">
            <thead class="bordered-darkorange">
                <tr>
                    <th>No</th>
                    <th>Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Tahun</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT gm.*, u.name as guru_nama, p.pelajaran_nama, k.kelas_nama, t.tahun_nama, s.semester_nama FROM guru_mengajar gm
                    JOIN users u ON gm.guru_id = u.id
                    JOIN pelajaran p ON gm.pelajaran_id = p.pelajaran_id
                    JOIN kelas k ON gm.kelas_id = k.kelas_id
                    JOIN tahun t ON gm.tahun_id = t.tahun_id
                    JOIN semester s ON gm.semester_id = s.semester_id
                    ORDER BY t.tahun_nama DESC, s.semester_nama DESC, k.kelas_nama, p.pelajaran_nama, u.name");
                while ($row = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['guru_nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['pelajaran_nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['kelas_nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['tahun_nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['semester_nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="?akademik=guru_mengajar-edit&id=<?php echo $row['guru_mengajar_id']; ?>"
                                class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a>
                            <a href="?akademik=guru_mengajar-del&id=<?php echo $row['guru_mengajar_id']; ?>"
                                class="btn btn-xs btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i
                                    class="fa fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="footer">
            <a href="?akademik=guru_mengajar-create" class="btn btn-primary">Create</a>
        </div>
    </div>
</div>