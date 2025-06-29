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
    $semester_id = isset($_POST['semester']) ? $_POST['semester'] : null;
    $tahun_id = isset($_POST['tahun']) ? $_POST['tahun'] : null;

    // For admin/guru, get selected student ID from form
    if ($access === 'admin' || $access === 'guru') {
        $student_id = isset($_POST['siswa']) ? $_POST['siswa'] : null;
    } else {
        // For parent/student, use their own ID
        $student_id = $user_id;
    }

    if ($student_id && $semester_id && $tahun_id) {
        // Build relative path to generate_report_pdf.php
        $pdf_url = dirname($_SERVER['PHP_SELF']) . '/../generate_report_pdf.php';
        $pdf_url = str_replace('\\', '/', $pdf_url); // Normalize slashes for Windows
        $pdf_url = preg_replace('#/+#','/',$pdf_url); // Remove duplicate slashes
        echo "<script>window.open('{$pdf_url}?student_id={$student_id}&semester_id={$semester_id}&tahun_id={$tahun_id}', '_blank');</script>";
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
                <form class="form-horizontal" role="form" method="POST" target="_blank" action="">
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Siswa</label>
                        <div class="col-sm-10">
                            <select id="e1" style="width:100%;" name="siswa" required>
                                <?php
                                $siswa = mysqli_query($conn, "SELECT * FROM users WHERE access='orang_tua' ORDER BY name ASC");
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