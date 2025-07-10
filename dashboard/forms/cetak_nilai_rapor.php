<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once '../config/db.php';

$access = $_SESSION['access'];
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Helper functions (reuse if needed)
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
// Get all students for admin
function getAllStudentsForAdmin($conn)
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
// Get students for guru (only from kelas and pelajaran handled by guru)
function getStudentsForGuru($conn, $guru_id)
{
    $query = "SELECT DISTINCT u.id, u.nomor_induk, u.name, k.kelas_nama 
              FROM guru_mengajar gm
              INNER JOIN kelas k ON gm.kelas_id = k.kelas_id
              INNER JOIN users u ON u.kelas_id = gm.kelas_id AND u.access = 'orang_tua'
              WHERE gm.guru_id = '" . intval($guru_id) . "' 
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester_id = isset($_POST['semester']) ? $_POST['semester'] : '';
    $tahun_id = isset($_POST['tahun']) ? $_POST['tahun'] : '';
    if ($access === 'orang_tua') {
        $student_id = $user_id;
    } else {
        $student_id = isset($_POST['siswa']) ? $_POST['siswa'] : '';
    }
    if ($student_id && $semester_id && $tahun_id) {
        // Redirect to PDF generator with GET params (open in same tab)
        $pdf_url = '../../generate_report_pdf.php?student_id=' . urlencode($student_id) . '&semester_id=' . urlencode($semester_id) . '&tahun_id=' . urlencode($tahun_id);
        header('Location: ' . $pdf_url);
        exit();
    }
}

$semesters = getAllSemesters($conn);
$years = getAllAcademicYears($conn);
if ($access === 'admin') {
    $students = getAllStudentsForAdmin($conn);
} elseif ($access === 'guru') {
    $students = getStudentsForGuru($conn, $user_id);
} else {
    $students = [];
}
?>
<div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="widget">
        <div class="widget-header bordered-bottom bordered-lightred">
            <span class="widget-caption">Print Rapor Form</span>
        </div>
        <div class="widget-body">
            <div id="horizontal-form">
                <form class="form-horizontal" role="form" method="GET" action="../../generate_report_pdf.php">
                    <?php if ($access !== 'orang_tua') { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-right">Siswa</label>
                            <div class="col-sm-10">
                                <select id="e1" style="width:100%;" name="student_id" required>
                                    <option value="">Pilih Siswa</option>
                                    <?php foreach ($students as $data) { ?>
                                        <option value="<?php echo $data['id']; ?>">
                                            <?php echo $data['name'] . ' - ' . $data['kelas_nama']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <?php } ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Semester</label>
                        <div class="col-sm-10">
                            <select id="e2" style="width:100%;" name="semester_id" required>
                                <option value="">Pilih Semester</option>
                                <?php foreach ($semesters as $s) { ?>
                                    <option value="<?php echo $s['semester_id']; ?>"><?php echo $s['semester_nama']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right">Tahun Ajaran</label>
                        <div class="col-sm-10">
                            <select id="e3" style="width:100%;" name="tahun_id" required>
                                <option value="">Pilih Tahun</option>
                                <?php foreach ($years as $y) { ?>
                                    <option value="<?php echo $y['tahun_id']; ?>"><?php echo $y['tahun_nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success"><i class="fa fa-print"></i> Cetak PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>