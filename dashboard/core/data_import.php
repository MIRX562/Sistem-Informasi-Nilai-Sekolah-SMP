<?php
// Database connection configuration
$config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'sims2'
];

// Initialize error and success messages
$errors = [];
$success = [];

// Connect to the database
function connectDB($config)
{
    try {
        $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $importType = isset($_POST['import_type']) ? $_POST['import_type'] : '';

    // Check if a file was uploaded
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] == 0) {
        $file = $_FILES['import_file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileType = $file['type'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check file extension
        $allowedExts = ['csv', 'xlsx', 'xls'];
        if (!in_array($fileExt, $allowedExts)) {
            $errors[] = "File extension not allowed. Please upload a CSV or Excel file.";
        } else {
            // Process the file based on its type and the selected import type
            if ($fileExt == 'csv') {
                processCSVImport($fileTmpName, $importType, $config);
            } else {
                // For Excel files - you'll need a library like PhpSpreadsheet
                $errors[] = "Excel processing requires additional libraries. Please use CSV format.";
            }
        }
    } else {
        $errors[] = "Please select a file to upload.";
    }
}

// Process CSV Import
function processCSVImport($filePath, $importType, $config)
{
    global $errors, $success;

    // Connect to the database
    $conn = connectDB($config);

    // Open the file
    if (($handle = fopen($filePath, "r")) !== FALSE) {
        // Read the header row
        $header = fgetcsv($handle, 1000, ",");

        // Validate headers based on import type
        if (!validateHeaders($header, $importType)) {
            $errors[] = "CSV headers do not match the expected format for " . ucfirst($importType) . ".";
            fclose($handle);
            return;
        }

        // Process rows
        $rowCount = 0;
        $successCount = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rowCount++;

            // Skip empty rows
            $filteredData = array_filter($data);
            if (empty($filteredData)) {
                continue;
            }

            // Import based on the selected type
            $result = importData($conn, $importType, $header, $data);

            if ($result) {
                $successCount++;
            }
        }

        fclose($handle);

        if ($successCount > 0) {
            $success[] = "Successfully imported $successCount out of $rowCount records.";
        } else {
            $errors[] = "No records were imported. Please check your data format.";
        }

        $conn->close();
    } else {
        $errors[] = "Unable to open the file.";
    }
}

// Validate headers based on import type
function validateHeaders($headers, $importType)
{
    // Define expected headers for each import type
    $expectedHeaders = [
        'users' => ['nomor_induk', 'name', 'username', 'password', 'telp', 'alamat', 'status', 'jenis_kelamin', 'kelas_id', 'access'],
        'nilai' => ['id', 'pelajaran_id', 'tahun_id', 'semester_id', 'nilai_kkm', 'uh', 'pas', 'p5ra', 'tugas', 'kehadiran', 'keaktifan', 'kekompakan'],
        'artikel' => ['artikel_judul', 'artikel_isi', 'kategori_id'],
        'galeri' => ['galeri_nama', 'galeri_keterangan', 'galeri_link'],
        'kelas' => ['kelas_nama'],
        'pelajaran' => ['pelajaran_nama'],
        'sekolah' => ['sekolah_nama', 'sekolah_alamat', 'sekolah_telp', 'sekolah_visi', 'sekolah_misi']
    ];

    // Check if import type exists in our defined types
    if (!isset($expectedHeaders[$importType])) {
        return false;
    }

    // Check if all expected headers are present
    foreach ($expectedHeaders[$importType] as $expected) {
        if (!in_array($expected, $headers)) {
            return false;
        }
    }

    return true;
}

// Import data based on type
function importData($conn, $importType, $headers, $data)
{
    global $errors;

    // Create an associative array from headers and data
    $record = array_combine($headers, $data);

    // Prepare SQL statement based on import type
    switch ($importType) {
        case 'users':
            return importUsers($conn, $record);
        case 'nilai':
            return importNilai($conn, $record);
        case 'artikel':
            return importArtikel($conn, $record);
        case 'galeri':
            return importGaleri($conn, $record);
        case 'kelas':
            return importKelas($conn, $record);
        case 'pelajaran':
            return importPelajaran($conn, $record);
        case 'sekolah':
            return importSekolah($conn, $record);
        default:
            $errors[] = "Unknown import type.";
            return false;
    }
}

// Import users data
function importUsers($conn, $record)
{
    // Check for required fields
    if (empty($record['name']) || empty($record['username'])) {
        return false;
    }

    // Sanitize input
    $nomor_induk = $conn->real_escape_string(isset($record['nomor_induk']) ? $record['nomor_induk'] : '');
    $name = $conn->real_escape_string($record['name']);
    $username = $conn->real_escape_string($record['username']);
    $password = $conn->real_escape_string(isset($record['password']) ? $record['password'] : '');
    $telp = $conn->real_escape_string(isset($record['telp']) ? $record['telp'] : '');
    $alamat = $conn->real_escape_string(isset($record['alamat']) ? $record['alamat'] : '');
    $status = $conn->real_escape_string(isset($record['status']) ? $record['status'] : '');
    $jenis_kelamin = $conn->real_escape_string(isset($record['jenis_kelamin']) ? $record['jenis_kelamin'] : '');
    $kelas_id = !empty($record['kelas_id']) ? (int) $record['kelas_id'] : 'NULL';
    $access = $conn->real_escape_string(isset($record['access']) ? $record['access'] : 'orang_tua');

    // Check if user already exists
    $checkSql = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Update existing user
        $sql = "UPDATE users SET 
                nomor_induk = '$nomor_induk',
                name = '$name',
                password = '$password',
                telp = '$telp',
                alamat = '$alamat',
                status = '$status',
                jenis_kelamin = '$jenis_kelamin',
                kelas_id = $kelas_id,
                access = '$access'
                WHERE username = '$username'";
    } else {
        // Insert new user
        $sql = "INSERT INTO users 
                (nomor_induk, name, username, password, telp, alamat, status, jenis_kelamin, kelas_id, access) 
                VALUES 
                ('$nomor_induk', '$name', '$username', '$password', '$telp', '$alamat', '$status', '$jenis_kelamin', $kelas_id, '$access')";
    }

    return $conn->query($sql);
}

// Import nilai data
function importNilai($conn, $record)
{
    // Check for required fields
    if (empty($record['id']) || empty($record['pelajaran_id'])) {
        return false;
    }

    // Sanitize input
    $id = (int) $record['id'];
    $pelajaran_id = (int) $record['pelajaran_id'];
    $tahun_id = (int) $record['tahun_id'];
    $semester_id = (int) $record['semester_id'];
    $nilai_kkm = (int) $record['nilai_kkm'];
    $uh = (int) $record['uh'];
    $pas = (int) $record['pas'];
    $p5ra = (int) $record['p5ra'];
    $tugas = (int) (isset($record['tugas']) ? $record['tugas'] : 0);
    $kehadiran = (int) $record['kehadiran'];
    $keaktifan = (int) $record['keaktifan'];
    $kekompakan = (int) $record['kekompakan'];

    // Calculate nilai_akhir (you can modify this formula based on your requirements)
    $nilai_akhir = round(($uh + $pas + $p5ra + $tugas + $kehadiran + $keaktifan + $kekompakan) / 7);

    // Check if the record already exists
    $checkSql = "SELECT nilai_id FROM nilai 
                WHERE id = $id 
                AND pelajaran_id = $pelajaran_id 
                AND tahun_id = $tahun_id 
                AND semester_id = $semester_id";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Update existing record
        $nilai_id = $result->fetch_assoc()['nilai_id'];
        $sql = "UPDATE nilai SET 
                nilai_kkm = $nilai_kkm,
                uh = $uh,
                pas = $pas,
                p5ra = $p5ra,
                tugas = $tugas,
                kehadiran = $kehadiran,
                keaktifan = $keaktifan,
                kekompakan = $kekompakan,
                nilai_akhir = $nilai_akhir
                WHERE nilai_id = $nilai_id";
    } else {
        // Insert new record
        $sql = "INSERT INTO nilai 
                (id, pelajaran_id, tahun_id, semester_id, nilai_kkm, uh, pas, p5ra, tugas, kehadiran, keaktifan, kekompakan, nilai_akhir) 
                VALUES 
                ($id, $pelajaran_id, $tahun_id, $semester_id, $nilai_kkm, $uh, $pas, $p5ra, $tugas, $kehadiran, $keaktifan, $kekompakan, $nilai_akhir)";
    }

    return $conn->query($sql);
}

// Import artikel data
function importArtikel($conn, $record)
{
    // Check for required fields
    if (empty($record['artikel_judul']) || empty($record['kategori_id'])) {
        return false;
    }

    // Sanitize input
    $artikel_judul = $conn->real_escape_string($record['artikel_judul']);
    $artikel_isi = $conn->real_escape_string($record['artikel_isi']);
    $kategori_id = (int) $record['kategori_id'];
    $artikel_tgl = date('Y-m-d H:i:s'); // Current date time

    // Insert new article
    $sql = "INSERT INTO artikel 
            (artikel_judul, artikel_isi, artikel_tgl, kategori_id) 
            VALUES 
            ('$artikel_judul', '$artikel_isi', '$artikel_tgl', $kategori_id)";

    return $conn->query($sql);
}

// Import galeri data
function importGaleri($conn, $record)
{
    // Check for required fields
    if (empty($record['galeri_nama']) || empty($record['galeri_link'])) {
        return false;
    }

    // Sanitize input
    $galeri_nama = $conn->real_escape_string($record['galeri_nama']);
    $galeri_keterangan = $conn->real_escape_string($record['galeri_keterangan']);
    $galeri_link = $conn->real_escape_string($record['galeri_link']);
    $galeri_tgl = date('Y-m-d H:i:s'); // Current date time

    // Check if gallery name already exists
    $checkSql = "SELECT galeri_id FROM galeri WHERE galeri_nama = '$galeri_nama'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Update existing gallery
        $sql = "UPDATE galeri SET 
                galeri_keterangan = '$galeri_keterangan',
                galeri_link = '$galeri_link',
                galeri_tgl = '$galeri_tgl'
                WHERE galeri_nama = '$galeri_nama'";
    } else {
        // Insert new gallery
        $sql = "INSERT INTO galeri 
                (galeri_nama, galeri_keterangan, galeri_link, galeri_tgl) 
                VALUES 
                ('$galeri_nama', '$galeri_keterangan', '$galeri_link', '$galeri_tgl')";
    }

    return $conn->query($sql);
}

// Import kelas data
function importKelas($conn, $record)
{
    // Check for required fields
    if (empty($record['kelas_nama'])) {
        return false;
    }

    // Sanitize input
    $kelas_nama = $conn->real_escape_string($record['kelas_nama']);

    // Check if class already exists
    $checkSql = "SELECT kelas_id FROM kelas WHERE kelas_nama = '$kelas_nama'";
    $result = $conn->query($checkSql);

    if ($result->num_rows == 0) {
        // Insert new class
        $sql = "INSERT INTO kelas (kelas_nama) VALUES ('$kelas_nama')";
        return $conn->query($sql);
    }

    return true; // Class already exists, no need to insert
}

// Import pelajaran data
function importPelajaran($conn, $record)
{
    // Check for required fields
    if (empty($record['pelajaran_nama'])) {
        return false;
    }

    // Sanitize input
    $pelajaran_nama = $conn->real_escape_string($record['pelajaran_nama']);

    // Check if subject already exists
    $checkSql = "SELECT pelajaran_id FROM pelajaran WHERE pelajaran_nama = '$pelajaran_nama'";
    $result = $conn->query($checkSql);

    if ($result->num_rows == 0) {
        // Insert new subject
        $sql = "INSERT INTO pelajaran (pelajaran_nama) VALUES ('$pelajaran_nama')";
        return $conn->query($sql);
    }

    return true; // Subject already exists, no need to insert
}

// Import sekolah data
function importSekolah($conn, $record)
{
    // Check for required fields
    if (empty($record['sekolah_nama'])) {
        return false;
    }

    // Sanitize input
    $sekolah_nama = $conn->real_escape_string($record['sekolah_nama']);
    $sekolah_alamat = $conn->real_escape_string(isset($record['sekolah_alamat']) ? $record['sekolah_alamat'] : '');
    $sekolah_telp = $conn->real_escape_string(isset($record['sekolah_telp']) ? $record['sekolah_telp'] : '');
    $sekolah_visi = $conn->real_escape_string(isset($record['sekolah_visi']) ? $record['sekolah_visi'] : '');
    $sekolah_misi = $conn->real_escape_string(isset($record['sekolah_misi']) ? $record['sekolah_misi'] : '');

    // Check if school already exists
    $checkSql = "SELECT sekolah_id FROM sekolah WHERE sekolah_nama = '$sekolah_nama'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Update existing school
        $sekolah_id = $result->fetch_assoc()['sekolah_id'];
        $sql = "UPDATE sekolah SET 
                sekolah_alamat = '$sekolah_alamat',
                sekolah_telp = '$sekolah_telp',
                sekolah_visi = '$sekolah_visi', 
                sekolah_misi = '$sekolah_misi'
                WHERE sekolah_id = $sekolah_id";
    } else {
        // Insert new school
        $sql = "INSERT INTO sekolah 
                (sekolah_nama, sekolah_alamat, sekolah_telp, sekolah_visi, sekolah_misi) 
                VALUES 
                ('$sekolah_nama', '$sekolah_alamat', '$sekolah_telp', '$sekolah_visi', '$sekolah_misi')";
    }

    return $conn->query($sql);
}

if (isset($_POST['import'])) {
    $fileName = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sqlInsert = mysqli_query($conn, "INSERT into users (nomor_induk, name, username, password, telp, alamat, status, jenis_kelamin, kelas_id, access)
                values ('" . mysqli_real_escape_string($conn, $column[1]) . "',
                        '" . mysqli_real_escape_string($conn, $column[2]) . "',
                        '" . mysqli_real_escape_string($conn, $column[3]) . "',
                        '" . mysqli_real_escape_string($conn, $column[4]) . "',
                        '" . mysqli_real_escape_string($conn, $column[5]) . "',
                        '" . mysqli_real_escape_string($conn, $column[6]) . "',
                        '" . mysqli_real_escape_string($conn, $column[7]) . "',
                        '" . mysqli_real_escape_string($conn, $column[8]) . "',
                        '" . mysqli_real_escape_string($conn, $column[9]) . "',
                        '" . mysqli_real_escape_string($conn, $column[10]) . "')");

            if (!$sqlInsert) {
                echo "<script type=\"text/javascript\">
                        alert(\"Invalid File:Please Upload CSV File.\");
                        window.location = \"index.php\"
                      </script>";
            }
        }

        if ($sqlInsert) {
            echo "<script type=\"text/javascript\">
                    alert(\"CSV File has been successfully Imported.\");
                    window.location = \"index.php\"
                  </script>";
        }
        fclose($file);
    }
}

include "config/db.php";

// Import Excel for Siswa
if (isset($_POST['import-siswa'])) {
    require_once 'PHPExcel/PHPExcel.php';

    $file = $_FILES['file']['tmp_name'];
    $path = $_FILES['file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    if ($ext == 'xlsx' || $ext == 'xls') {
        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objReader->setLoadSheetsOnly('Siswa');

        $objPHPExcel = $objReader->load($file);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

        for ($i = 2; $i <= $highestRow; $i++) {
            $nis = mysqli_real_escape_string($conn, $sheetData[$i]['A']);
            $username = mysqli_real_escape_string($conn, $sheetData[$i]['B']);
            $password = password_hash($sheetData[$i]['C'], PASSWORD_DEFAULT);
            $name = mysqli_real_escape_string($conn, $sheetData[$i]['D']);
            $telp = mysqli_real_escape_string($conn, $sheetData[$i]['E']);
            $status = mysqli_real_escape_string($conn, $sheetData[$i]['F']);
            $kelas = mysqli_real_escape_string($conn, $sheetData[$i]['G']);
            $jenis_kelamin = mysqli_real_escape_string($conn, $sheetData[$i]['H']);
            $alamat = mysqli_real_escape_string($conn, $sheetData[$i]['I']);
            $access = 'siswa';

            $query = mysqli_query($conn, "INSERT INTO users (nomor_induk, username, password, name, telp, status, kelas_id, jenis_kelamin, alamat, access) 
                                        VALUES ('$nis', '$username', '$password', '$name', '$telp', '$status', '$kelas', '$jenis_kelamin', '$alamat', '$access')");
        }

        if ($query) {
            echo "<script>alert('Data berhasil diimport!'); window.location='?users=siswa';</script>";
        } else {
            echo "<script>alert('Data gagal diimport!'); window.location='?page=import';</script>";
        }
    } else {
        echo "<script>alert('File yang diupload bukan file Excel!'); window.location='?page=import';</script>";
    }
}

// Import Excel for Guru
if (isset($_POST['import-guru'])) {
    require_once 'PHPExcel/PHPExcel.php';

    $file = $_FILES['file']['tmp_name'];
    $path = $_FILES['file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    if ($ext == 'xlsx' || $ext == 'xls') {
        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objReader->setLoadSheetsOnly('Guru');

        $objPHPExcel = $objReader->load($file);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

        for ($i = 2; $i <= $highestRow; $i++) {
            $nip = mysqli_real_escape_string($conn, $sheetData[$i]['A']);
            $username = mysqli_real_escape_string($conn, $sheetData[$i]['B']);
            $password = password_hash($sheetData[$i]['C'], PASSWORD_DEFAULT);
            $name = mysqli_real_escape_string($conn, $sheetData[$i]['D']);
            $telp = mysqli_real_escape_string($conn, $sheetData[$i]['E']);
            $status = mysqli_real_escape_string($conn, $sheetData[$i]['F']);
            $kelas = mysqli_real_escape_string($conn, $sheetData[$i]['G']);
            $jenis_kelamin = mysqli_real_escape_string($conn, $sheetData[$i]['H']);
            $alamat = mysqli_real_escape_string($conn, $sheetData[$i]['I']);
            $access = 'guru';

            $query = mysqli_query($conn, "INSERT INTO users (nomor_induk, username, password, name, telp, status, kelas_id, jenis_kelamin, alamat, access) 
                                        VALUES ('$nip', '$username', '$password', '$name', '$telp', '$status', '$kelas', '$jenis_kelamin', '$alamat', '$access')");
        }

        if ($query) {
            echo "<script>alert('Data berhasil diimport!'); window.location='?users=guru';</script>";
        } else {
            echo "<script>alert('Data gagal diimport!'); window.location='?page=import';</script>";
        }
    } else {
        echo "<script>alert('File yang diupload bukan file Excel!'); window.location='?page=import';</script>";
    }
}

// Import Excel for Nilai
if (isset($_POST['import-nilai'])) {
    require_once 'PHPExcel/PHPExcel.php';

    $file = $_FILES['file']['tmp_name'];
    $path = $_FILES['file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    if ($ext == 'xlsx' || $ext == 'xls') {
        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objReader->setLoadSheetsOnly('Nilai');

        $objPHPExcel = $objReader->load($file);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

        for ($i = 2; $i <= $highestRow; $i++) {
            $id = mysqli_real_escape_string($conn, $sheetData[$i]['A']);
            $pelajaran = mysqli_real_escape_string($conn, $sheetData[$i]['B']);
            $semester = mysqli_real_escape_string($conn, $sheetData[$i]['C']);
            $tahun = mysqli_real_escape_string($conn, $sheetData[$i]['D']);
            $kkm = mysqli_real_escape_string($conn, $sheetData[$i]['E']);
            $uh = mysqli_real_escape_string($conn, $sheetData[$i]['F']);
            $pas = mysqli_real_escape_string($conn, $sheetData[$i]['G']);
            $p5ra = !empty($sheetData[$i]['H']) ? mysqli_real_escape_string($conn, $sheetData[$i]['H']) : NULL;
            $tugas = mysqli_real_escape_string($conn, $sheetData[$i]['I']);
            $kehadiran = mysqli_real_escape_string($conn, $sheetData[$i]['J']);
            $keaktifan = mysqli_real_escape_string($conn, $sheetData[$i]['K']);
            $kekompakan = mysqli_real_escape_string($conn, $sheetData[$i]['L']);

            // Calculate final grade based on whether P5RA exists
            if (!empty($p5ra)) {
                // With P5RA
                $nilai_akhir = ($uh * 0.20) + ($pas * 0.30) + ($p5ra * 0.20) +
                    ($tugas * 0.15) + ($kehadiran * 0.05) +
                    ($keaktifan * 0.05) + ($kekompakan * 0.05);
            } else {
                // Without P5RA
                $nilai_akhir = ($uh * 0.25) + ($pas * 0.35) + ($tugas * 0.20) +
                    ($kehadiran * 0.075) + ($keaktifan * 0.0625) +
                    ($kekompakan * 0.0625);
            }

            $query = mysqli_query($conn, "INSERT INTO nilai (id, pelajaran_id, semester_id, tahun_id, nilai_kkm, uh, pas, p5ra, tugas, kehadiran, keaktifan, kekompakan, nilai_akhir) 
                                        VALUES ('$id', '$pelajaran', '$semester', '$tahun', '$kkm', '$uh', '$pas', " . ($p5ra ? "'$p5ra'" : "NULL") . ", '$tugas', '$kehadiran', '$keaktifan', '$kekompakan', '$nilai_akhir')");
        }

        if ($query) {
            echo "<script>alert('Data berhasil diimport!'); window.location='?nilai=tampil';</script>";
        } else {
            echo "<script>alert('Data gagal diimport!'); window.location='?page=import';</script>";
        }
    } else {
        echo "<script>alert('File yang diupload bukan file Excel!'); window.location='?page=import';</script>";
    }
}
?>

<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Data Import Tool</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Import Type</label>
                        <div class="col-sm-10">
                            <select id="import_type" style="width:100%;" name="import_type" required>
                                <option value="">-- Select Import Type --</option>
                                <option value="users">Users</option>
                                <option value="nilai">Nilai</option>
                                <!-- <option value="artikel">Artikel</option> -->
                                <!-- <option value="galeri">Galeri</option> -->
                                <option value="kelas">Kelas</option>
                                <option value="pelajaran">Pelajaran</option>
                                <!-- <option value="sekolah">Sekolah</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Import File (CSV)</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="import_file" required>
                            <small class="form-text text-muted">Only CSV files are supported.</small>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Import Data</button>
                        </div>
                    </div>
                </form>
                <div class="mt-4">
                    <h2>Template CSV</h2>
                    <p>Download template untuk tiap tipe:</p>
                    <ul>
                        <li><a href="templates/users_template.csv" class="btn btn-link" download>Users Template</a></li>
                        <li><a href="templates/nilai_template.csv" class="btn btn-link" download>Nilai Template</a></li>
                        <li><a href="templates/kelas_template.csv" class="btn btn-link" download>Kelas Template</a></li>
                        <li><a href="templates/pelajaran_template.csv" class="btn btn-link" download>Pelajaran
                                Template</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>