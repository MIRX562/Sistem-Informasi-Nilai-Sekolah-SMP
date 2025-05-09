<?php

// Start session to access user data
session_start();

require_once('config/db2.php'); // Database connection
require_once('fpdf/fpdf.php'); // Ensure you have FPDF library installed

// Check if user is logged in
// if (!isset($_SESSION['username']) || !isset($_SESSION['access'])) {
//     header("Location: login.php");
//     exit();
// }

// Get user access level
$access = $_SESSION['access'];
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Check if parameters are provided
if (!isset($_GET['student_id']) || !isset($_GET['semester_id']) || !isset($_GET['tahun_id'])) {
    die("Missing required parameters");
}

$student_id = $_GET['student_id'];
$semester_id = $_GET['semester_id'];
$tahun_id = $_GET['tahun_id'];

// For security, check if user is allowed to access this student's data
if ($access === 'orang_tua' && $student_id != $user_id) {
    die("You are not authorized to access this report");
}

// Function to get student info
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

// Function to get semester name
function getSemesterName($conn, $semester_id)
{
    $query = "SELECT semester_nama FROM semester WHERE semester_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $semester_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['semester_nama'];
    }
    return null;
}

// Function to get academic year name
function getAcademicYearName($conn, $tahun_id)
{
    $query = "SELECT tahun_nama FROM tahun WHERE tahun_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $tahun_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['tahun_nama'];
    }
    return null;
}

// Function to get student's grades
function getStudentGrades($conn, $student_id, $semester_id, $tahun_id)
{
    $query = "SELECT n.*, p.pelajaran_nama 
              FROM nilai n
              JOIN pelajaran p ON n.pelajaran_id = p.pelajaran_id
              WHERE n.id = ? AND n.semester_id = ? AND n.tahun_id = ?
              ORDER BY p.pelajaran_nama";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $student_id, $semester_id, $tahun_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $grades = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $grades[] = $row;
        }
    }
    return $grades;
}

// Function to get school info
function getSchoolInfo($conn)
{
    $query = "SELECT * FROM sekolah WHERE sekolah_id = 1"; // Assuming ID 1 is the main school
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Get data for the report
$student = getStudentInfo($conn, $student_id);
$semester = getSemesterName($conn, $semester_id);
$academic_year = getAcademicYearName($conn, $tahun_id);
$grades = getStudentGrades($conn, $student_id, $semester_id, $tahun_id);
$school = getSchoolInfo($conn);

// Check if student exists
if (!$student) {
    die("Student not found");
}

// Check if we got valid semester and academic year
if (!$semester || !$academic_year) {
    die("Invalid semester or academic year");
}

// Create PDF using FPDF
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        global $school;

        if ($school) {
            // Add logo if available
            // $this->Image('../../assets/images/logo1.jpg', 10, 6, 30);

            // School header
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, strtoupper($school['sekolah_nama']), 0, 1, 'C');

            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, $school['sekolah_alamat'], 0, 1, 'C');
            $this->Cell(0, 5, 'Telp: ' . $school['sekolah_telp'], 0, 1, 'C');

            // Line
            $this->Line(10, $this->GetY() + 3, 200, $this->GetY() + 3);
        }

        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Initialize PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Report title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'LAPORAN AKADEMIK', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, 'Tahun Ajaran: ' . $academic_year . ' - Semester: ' . $semester, 0, 1, 'C');
$pdf->Ln(10);

// Student information
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 7, 'INFORMASI SISWA', 0, 1, 'L');
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 6, 'NIS:', 0, 0, 'L');
$pdf->Cell(0, 6, $student['nomor_induk'], 0, 1, 'L');

$pdf->Cell(40, 6, 'Nama:', 0, 0, 'L');
$pdf->Cell(0, 6, $student['name'], 0, 1, 'L');

$pdf->Cell(40, 6, 'Kelas:', 0, 0, 'L');
$pdf->Cell(0, 6, $student['kelas_nama'], 0, 1, 'L');

$pdf->Cell(40, 6, 'Jenis Kelamin:', 0, 0, 'L');
$pdf->Cell(0, 6, $student['jenis_kelamin'], 0, 1, 'L');

$pdf->Cell(40, 6, 'Alamat:', 0, 0, 'L');
$pdf->Cell(0, 6, $student['alamat'], 0, 1, 'L');
$pdf->Ln(5);

// Grades table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 7, 'HASIL AKADEMIK', 0, 1, 'L');
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// Table header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(60, 7, 'Mata Pelajaran', 1, 0, 'C');
$pdf->Cell(15, 7, 'KKM', 1, 0, 'C');
$pdf->Cell(15, 7, 'UH', 1, 0, 'C');  // Daily Test
$pdf->Cell(15, 7, 'PAS', 1, 0, 'C'); // Final Exam
$pdf->Cell(15, 7, 'P5RA', 1, 0, 'C'); // P5RA
$pdf->Cell(20, 7, 'Tugas', 1, 0, 'C'); // Assignments
$pdf->Cell(20, 7, 'Sikap', 1, 0, 'C'); // Average of kehadiran, keaktifan, kekompakan
$pdf->Cell(20, 7, 'Akhir', 1, 1, 'C'); // Final Grade

// Check if grades exist
if (count($grades) > 0) {
    $pdf->SetFont('Arial', '', 10);

    foreach ($grades as $grade) {
        // Calculate attitude average from kehadiran, keaktifan, kekompakan
        $attitude = 0;
        $count = 0;

        if (!is_null($grade['kehadiran'])) {
            $attitude += $grade['kehadiran'];
            $count++;
        }

        if (!is_null($grade['keaktifan'])) {
            $attitude += $grade['keaktifan'];
            $count++;
        }

        if (!is_null($grade['kekompakan'])) {
            $attitude += $grade['kekompakan'];
            $count++;
        }

        $attitude_avg = $count > 0 ? round($attitude / $count, 1) : 0;

        $pdf->Cell(60, 7, $grade['pelajaran_nama'], 1, 0, 'L');
        $pdf->Cell(15, 7, $grade['nilai_kkm'], 1, 0, 'C');
        $pdf->Cell(15, 7, $grade['uh'], 1, 0, 'C');
        $pdf->Cell(15, 7, $grade['pas'], 1, 0, 'C');
        $pdf->Cell(15, 7, $grade['p5ra'], 1, 0, 'C');
        $pdf->Cell(20, 7, $grade['tugas'], 1, 0, 'C');
        $pdf->Cell(20, 7, $attitude_avg, 1, 0, 'C');
        $pdf->Cell(20, 7, $grade['nilai_akhir'], 1, 1, 'C');
    }
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(180, 7, 'Tidak ada nilai untuk semester ini', 1, 1, 'C');
}

// Legend for Attitude
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'Komponen Sikap:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 5, 'Kehadiran: Kehadiran', 0, 1, 'L');
$pdf->Cell(40, 5, 'Keaktifan: Aktivitas/Partisipasi', 0, 1, 'L');
$pdf->Cell(40, 5, 'Kekompakan: Kerjasama', 0, 1, 'L');

// Signature section
$pdf->Ln(15);
$pdf->SetFont('Arial', '', 11);

$current_date = date('d F Y');
$pdf->Cell(90, 7, 'Orang Tua/Wali', 0, 0, 'C');
$pdf->Cell(90, 7, $school['sekolah_nama'] . ', ' . $current_date, 0, 1, 'C');
$pdf->Cell(90, 7, '', 0, 0, 'C');
$pdf->Cell(90, 7, 'Wali Kelas', 0, 1, 'C');

$pdf->Ln(15); // Space for signature

$pdf->Cell(90, 7, '___________________', 0, 0, 'C');
$pdf->Cell(90, 7, '___________________', 0, 1, 'C');

// Output PDF

$pdf->Output('Laporan_Akademik_' . $student['nomor_induk'] . '_' . $semester . '_' . $academic_year . '.pdf', 'D');
exit();
?>