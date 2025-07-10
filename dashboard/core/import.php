<?php
include "../config/db.php";

if (isset($_POST['import_type']) && isset($_FILES['import_file'])) {
    $importType = $_POST['import_type'];
    $file = $_FILES['import_file'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== 'csv') {
        echo "<script>alert('File harus berformat CSV!'); window.location='?data=import';</script>";
        exit;
    }

    $handle = fopen($fileTmp, 'r');
    if ($handle === false) {
        echo "<script>alert('Gagal membuka file!'); window.location='?data=import';</script>";
        exit;
    }

    $row = 0;
    $success = 0;
    $fail = 0;
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        $row++;
        if ($row == 1)
            continue; // skip header
        switch ($importType) {
            case 'guru':
                // CSV: nomor_induk,username,password,name,status,telp,jenis_kelamin,alamat,pelajaran_id
                $nomor_induk = mysqli_real_escape_string($conn, $data[0]);
                $username = mysqli_real_escape_string($conn, $data[1]);
                $password = password_hash($data[2], PASSWORD_DEFAULT);
                $name = mysqli_real_escape_string($conn, $data[3]);
                $status = mysqli_real_escape_string($conn, $data[4]);
                $telp = mysqli_real_escape_string($conn, $data[5]);
                $jenis_kelamin = mysqli_real_escape_string($conn, $data[6]);
                $alamat = mysqli_real_escape_string($conn, $data[7]);
                $pelajaran_id = !empty($data[8]) ? intval($data[8]) : 'NULL';
                $access = 'guru';
                $query = "INSERT INTO users (nomor_induk, name, username, password, telp, alamat, status, jenis_kelamin, access, pelajaran_id) VALUES ('$nomor_induk', '$name', '$username', '$password', '$telp', '$alamat', '$status', '$jenis_kelamin', '$access', $pelajaran_id)";
                break;
            case 'siswa':
                // CSV: nomor_induk,username,password,name,telp,status,jenis_kelamin,alamat,kelas_id
                $nomor_induk = mysqli_real_escape_string($conn, $data[0]);
                $username = mysqli_real_escape_string($conn, $data[1]);
                $password = password_hash($data[2], PASSWORD_DEFAULT);
                $name = mysqli_real_escape_string($conn, $data[3]);
                $telp = mysqli_real_escape_string($conn, $data[4]);
                $status = mysqli_real_escape_string($conn, $data[5]);
                $jenis_kelamin = mysqli_real_escape_string($conn, $data[6]);
                $alamat = mysqli_real_escape_string($conn, $data[7]);
                $kelas_id = !empty($data[8]) ? intval($data[8]) : 'NULL';
                $access = 'siswa';
                $query = "INSERT INTO users (nomor_induk, name, username, password, telp, alamat, status, jenis_kelamin, kelas_id, access) VALUES ('$nomor_induk', '$name', '$username', '$password', '$telp', '$alamat', '$status', '$jenis_kelamin', $kelas_id, '$access')";
                break;
            case 'nilai':
                // CSV: guru_mengajar_id,id (siswa_id),pelajaran_id,tahun_id,semester_id,nilai_kkm,uh,pas,p5ra,tugas,kehadiran,keaktifan,kekompakan,nilai_akhir
                $guru_mengajar_id = !empty($data[0]) ? intval($data[0]) : 'NULL';
                $id = !empty($data[1]) ? intval($data[1]) : 'NULL';
                $pelajaran_id = !empty($data[2]) ? intval($data[2]) : 'NULL';
                $tahun_id = !empty($data[3]) ? intval($data[3]) : 'NULL';
                $semester_id = !empty($data[4]) ? intval($data[4]) : 'NULL';
                $nilai_kkm = !empty($data[5]) ? intval($data[5]) : 'NULL';
                $uh = !empty($data[6]) ? intval($data[6]) : 'NULL';
                $pas = !empty($data[7]) ? intval($data[7]) : 'NULL';
                $p5ra = !empty($data[8]) ? intval($data[8]) : 'NULL';
                $tugas = !empty($data[9]) ? intval($data[9]) : 'NULL';
                $kehadiran = !empty($data[10]) ? intval($data[10]) : 'NULL';
                $keaktifan = !empty($data[11]) ? intval($data[11]) : 'NULL';
                $kekompakan = !empty($data[12]) ? intval($data[12]) : 'NULL';
                $nilai_akhir = !empty($data[13]) ? intval($data[13]) : 'NULL';
                $query = "INSERT INTO nilai (guru_mengajar_id, id, pelajaran_id, tahun_id, semester_id, nilai_kkm, uh, pas, p5ra, tugas, kehadiran, keaktifan, kekompakan, nilai_akhir) VALUES ($guru_mengajar_id, $id, $pelajaran_id, $tahun_id, $semester_id, $nilai_kkm, $uh, $pas, $p5ra, $tugas, $kehadiran, $keaktifan, $kekompakan, $nilai_akhir)";
                break;
            case 'guru_mengajar':
                // CSV: guru_id,pelajaran_id,kelas_id,tahun_id,semester_id,status
                $guru_id = !empty($data[0]) ? intval($data[0]) : 'NULL';
                $pelajaran_id = !empty($data[1]) ? intval($data[1]) : 'NULL';
                $kelas_id = !empty($data[2]) ? intval($data[2]) : 'NULL';
                $tahun_id = !empty($data[3]) ? intval($data[3]) : 'NULL';
                $semester_id = !empty($data[4]) ? intval($data[4]) : 'NULL';
                $status = isset($data[5]) ? mysqli_real_escape_string($conn, $data[5]) : 'aktif';
                $query = "INSERT INTO guru_mengajar (guru_id, pelajaran_id, kelas_id, tahun_id, semester_id, status) VALUES ($guru_id, $pelajaran_id, $kelas_id, $tahun_id, $semester_id, '$status')";
                break;
            case 'kelas':
                // CSV: kelas_nama
                $kelas_nama = mysqli_real_escape_string($conn, $data[0]);
                $query = "INSERT INTO kelas (kelas_nama) VALUES ('$kelas_nama')";
                break;
            case 'pelajaran':
                // CSV: pelajaran_nama
                $pelajaran_nama = mysqli_real_escape_string($conn, $data[0]);
                $query = "INSERT INTO pelajaran (pelajaran_nama) VALUES ('$pelajaran_nama')";
                break;
            default:
                $query = '';
        }
        if (!empty($query)) {
            if (mysqli_query($conn, $query)) {
                $success++;
            } else {
                $fail++;
            }
        }
    }
    fclose($handle);
    echo "<script>alert('Import selesai! Berhasil: $success, Gagal: $fail'); window.location='?data=import';</script>";
    exit;
}
?>