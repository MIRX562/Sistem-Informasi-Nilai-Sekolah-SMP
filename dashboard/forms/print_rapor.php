<?php
require_once('../config/db2.php');

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
            <span class="widget-caption">Form Cetak Rapor Siswa</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="GET" action="../core/generate_report_pdf.php"
                    target="_blank">
                    <?php if ($access === 'admin' || $access === 'guru'): ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">Siswa</label>
                            <div class="col-sm-10">
                                <select name="student_id" id="student_id" style="width:100%;" required>
                                    <option value="">-- Pilih Siswa --</option>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?php echo $student['id']; ?>">
                                            <?php echo $student['nomor_induk'] . ' - ' . $student['name'] . ' (' . $student['kelas_nama'] . ')'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">Siswa</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                    value="<?php echo $student_info['name'] . ' (' . $student_info['kelas_nama'] . ')'; ?>"
                                    disabled>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Tahun Ajaran</label>
                        <div class="col-sm-10">
                            <select name="tahun_id" id="tahun_id" style="width:100%;" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <?php foreach ($academic_years as $year): ?>
                                    <option value="<?php echo $year['tahun_id']; ?>">
                                        <?php echo $year['tahun_nama']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Semester</label>
                        <div class="col-sm-10">
                            <select name="semester_id" id="semester_id" style="width:100%;" required>
                                <option value="">-- Pilih Semester --</option>
                                <?php foreach ($semesters as $semester): ?>
                                    <option value="<?php echo $semester['semester_id']; ?>">
                                        <?php echo $semester['semester_nama']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr />
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Cetak Rapor</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>