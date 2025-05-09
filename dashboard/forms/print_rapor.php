<?php

// Start output buffering to prevent premature output

// Check if user is logged in
// if (!isset($_SESSION['username']) || !isset($_SESSION['access'])) {
//     header("Location: login.php");
//     exit();
// }

// Get user access level
$access = $_SESSION['access'];
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Function to get all students (for admin/guru)
function getAllStudents($conn)
{
    $query = "SELECT u.id, u.nomor_induk, u.name, k.kelas_nama 
              FROM users u 
              LEFT JOIN kelas k ON u.kelas_id = k.kelas_id 
              WHERE u.access = 'orang_tua' 
              ORDER BY k.kelas_nama, u.name";
    $result = mysqli_query($conn, $query);

    $students = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    }
    return $students;
}

// Function to get all semesters
function getAllSemesters($conn)
{
    $query = "SELECT semester_id, semester_nama FROM semester ORDER BY semester_nama";
    $result = mysqli_query($conn, $query);

    $semesters = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $semesters[] = $row;
        }
    }
    return $semesters;
}

// Function to get all academic years
function getAllAcademicYears($conn)
{
    $query = "SELECT tahun_id, tahun_nama FROM tahun ORDER BY tahun_nama DESC";
    $result = mysqli_query($conn, $query);

    $years = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $years[] = $row;
        }
    }
    return $years;
}

// Function to get student info by ID
function getStudentInfo($conn, $student_id)
{
    $query = "SELECT u.id, u.nomor_induk, u.name, u.jenis_kelamin, u.alamat, 
              k.kelas_nama, k.kelas_id
              FROM users u 
              LEFT JOIN kelas k ON u.kelas_id = k.kelas_id 
              WHERE u.id = ? AND u.access = 'orang_tua'";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// For parent/student, get their child's info
$student_info = null;
if ($access === 'orang_tua') {
    $student_info = getStudentInfo($conn, $user_id);
}

// Get available semesters and academic years
$semesters = getAllSemesters($conn);
$academic_years = getAllAcademicYears($conn);

// If admin or guru is logged in, get all students
$students = ($access === 'admin' || $access === 'guru') ? getAllStudents($conn) : null;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester_id = isset($_POST['semester_id']) ? $_POST['semester_id'] : null;
    $tahun_id = isset($_POST['tahun_id']) ? $_POST['tahun_id'] : null;

    // For admin/guru, get selected student ID from form
    if ($access === 'admin' || $access === 'guru') {
        $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;
    } else {
        // For parent/student, use their own ID
        $student_id = $user_id;
    }

    if ($student_id && $semester_id && $tahun_id) {
        // Redirect to report generation script with parameters
        echo "<script>window.location.href = 'generate_report_pdf.php?student_id=" . $student_id . "&semester_id=" . $semester_id . "&tahun_id=" . $tahun_id . "';</script>";
        exit();
    }
}

?>

<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Print Rapor Form</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="POST" target="_blank"
                    action="../core/generate_report_pdf.php">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Siswa</label>
                        <div class="col-sm-10">
                            <select id="e1" style="width:100%;" name="siswa" required>
                                <?php
                                $siswa = mysqli_query($conn, "SELECT * FROM users WHERE access='siswa' ORDER BY name ASC");
                                while ($data = mysqli_fetch_array($siswa)) {
                                    ?>
                                    <option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Semester</label>
                        <div class="col-sm-10">
                            <select id="e2" style="width:100%;" name="semester" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Tahun Ajaran</label>
                        <div class="col-sm-10">
                            <select id="e3" style="width:100%;" name="tahun" required>
                                <?php
                                $tahun = mysqli_query($conn, "SELECT * FROM tahun");
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
                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" name="print">Print</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include "../config/db.php";

// Get student information
if (isset($_GET['siswa'])) {
    $siswa_id = mysqli_real_escape_string($conn, $_GET['siswa']);
    $semester = mysqli_real_escape_string($conn, $_GET['semester']);
    $tahun = mysqli_real_escape_string($conn, $_GET['tahun']);

    // Get student details
    $siswa_query = mysqli_query($conn, "SELECT u.*, k.kelas_nama 
                                      FROM users u 
                                      LEFT JOIN kelas k ON u.kelas_id = k.kelas_id 
                                      WHERE u.id = '$siswa_id'");
    $siswa = mysqli_fetch_assoc($siswa_query);

    // Get school information
    $sekolah_query = mysqli_query($conn, "SELECT * FROM sekolah LIMIT 1");
    $sekolah = mysqli_fetch_assoc($sekolah_query);

    // Get all grades
    $nilai_query = mysqli_query($conn, "SELECT n.*, p.pelajaran_nama, u.name as guru_name 
                                      FROM nilai n 
                                      LEFT JOIN pelajaran p ON n.pelajaran_id = p.pelajaran_id 
                                      LEFT JOIN users u ON p.guru_id = u.id 
                                      WHERE n.id = '$siswa_id' 
                                      AND n.semester_id = '$semester' 
                                      AND n.tahun_id = '$tahun'
                                      ORDER BY p.pelajaran_nama ASC");

    // Calculate averages
    $total_nilai = 0;
    $total_pelajaran = mysqli_num_rows($nilai_query);
    $nilai_data = array();

    while ($nilai = mysqli_fetch_assoc($nilai_query)) {
        $nilai_data[] = $nilai;
        $total_nilai += $nilai['nilai_akhir'];
    }

    $rata_rata = $total_pelajaran > 0 ? round($total_nilai / $total_pelajaran, 2) : 0;

    // Get semester and year information
    $semester_query = mysqli_query($conn, "SELECT semester_nama FROM semester WHERE semester_id = '$semester'");
    $semester_data = mysqli_fetch_assoc($semester_query);

    $tahun_query = mysqli_query($conn, "SELECT tahun_nama FROM tahun WHERE tahun_id = '$tahun'");
    $tahun_data = mysqli_fetch_assoc($tahun_query);
    ?>

    <!-- HTML template for report card -->
    <div class="report-card">
        <div class="header">
            <h2><?php echo $sekolah['sekolah_nama']; ?></h2>
            <p><?php echo $sekolah['sekolah_alamat']; ?></p>
            <p>Telp: <?php echo $sekolah['sekolah_telp']; ?></p>
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td>Nama Siswa</td>
                    <td>: <?php echo $siswa['name']; ?></td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>: <?php echo $siswa['nomor_induk']; ?></td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>: <?php echo $siswa['kelas_nama']; ?></td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>: <?php echo $semester_data['semester_nama']; ?></td>
                </tr>
                <tr>
                    <td>Tahun Ajaran</td>
                    <td>: <?php echo $tahun_data['tahun_nama']; ?></td>
                </tr>
            </table>
        </div>

        <div class="grades">
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>KKM</th>
                        <th>Nilai Akhir</th>
                        <th>Guru Pengajar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($nilai_data as $nilai): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $nilai['pelajaran_nama']; ?></td>
                            <td><?php echo $nilai['nilai_kkm']; ?></td>
                            <td><?php echo $nilai['nilai_akhir']; ?></td>
                            <td><?php echo $nilai['guru_name']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>Rata-rata</strong></td>
                        <td colspan="2"><strong><?php echo $rata_rata; ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="signatures">
            <table width="100%">
                <tr>
                    <td width="33%">
                        Orang Tua/Wali,<br><br><br>
                        ________________
                    </td>
                    <td width="33%">
                        Wali Kelas,<br><br><br>
                        ________________
                    </td>
                    <td width="33%">
                        Kepala Sekolah,<br><br><br>
                        ________________
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <?php
} else {
    echo "Data siswa tidak ditemukan.";
}
?>