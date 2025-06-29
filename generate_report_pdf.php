<?php

// Start session to access user data
session_start();

require_once('config/db.php'); // Database connection
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

        // Header background - adjusted for portrait (210mm width)
        $this->SetFillColor(76, 154, 75); // Green
        $this->Rect(0, 0, 210, 30, 'F');
        $this->SetDrawColor(76, 154, 75);
        $this->SetLineWidth(1);
        $this->Line(0, 30, 210, 30);

        // Logo (adjust path as needed)
        if (file_exists('assets/images/logo_logo.png')) {
            $this->Image('assets/images/logo_logo.png', 10, 5, 20);
        }

        // Header text
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 16);
        $this->SetXY(35, 8);
        $this->Cell(0, 8, 'LAPORAN AKADEMIK SISWA', 0, 1, 'L');

        if ($school) {
            $this->SetFont('Arial', 'B', 10);
            $this->SetXY(35, 16);
            $this->Cell(0, 5, strtoupper($school['sekolah_nama']), 0, 1, 'L');
            $this->SetFont('Arial', '', 8);
            $this->SetXY(35, 21);
            $this->Cell(0, 4, $school['sekolah_alamat'] . ' | Telp: ' . $school['sekolah_telp'], 0, 1, 'L');
        }

        // Print date - right aligned
        $this->SetFont('Arial', '', 8);
        $this->SetXY(150, 8);
        $this->Cell(50, 4, 'Dicetak: ' . date('d F Y, H:i'), 0, 1, 'R');

        $this->SetY(35);

        // Student info box - adjusted for portrait width
        $this->SetFillColor(236, 240, 241);
        $this->SetDrawColor(149, 165, 166);
        $this->Rect(10, 35, 190, 30, 'DF');

        $this->SetTextColor(44, 62, 80);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(15, 40);
        $this->Cell(0, 5, 'INFORMASI SISWA', 0, 1, 'L');

        global $student, $semester, $academic_year;
        $this->SetFont('Arial', '', 9);

        // Student info in two columns
        $this->SetXY(15, 48);
        $this->Cell(80, 4, 'NIS : ' . $student['nomor_induk'], 0, 0, 'L');
        $this->Cell(80, 4, 'Kelas : ' . $student['kelas_nama'], 0, 1, 'L');

        $this->SetX(15);
        $this->Cell(80, 4, 'Nama : ' . $student['name'], 0, 0, 'L');
        $this->Cell(80, 4, 'Jenis Kelamin : ' . $student['jenis_kelamin'], 0, 1, 'L');

        $this->SetX(15);
        $this->Cell(80, 4, 'Tahun Ajaran : ' . $academic_year, 0, 0, 'L');
        $this->Cell(80, 4, 'Semester : ' . $semester, 0, 1, 'L');

        $this->SetY(70);

        // Table header - adjusted column widths for portrait
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(52, 73, 94);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(44, 62, 80);
        $this->SetLineWidth(0.3);
        $this->SetX(10);

        // Adjusted column widths to fit portrait (total = 190mm)
        $this->Cell(65, 8, 'Mata Pelajaran', 1, 0, 'C', true);
        $this->Cell(18, 8, 'KKM', 1, 0, 'C', true);
        $this->Cell(18, 8, 'UH', 1, 0, 'C', true);
        $this->Cell(18, 8, 'PAS', 1, 0, 'C', true);
        $this->Cell(18, 8, 'P5RA', 1, 0, 'C', true);
        $this->Cell(18, 8, 'Tugas', 1, 0, 'C', true);
        $this->Cell(18, 8, 'Sikap', 1, 0, 'C', true);
        $this->Cell(17, 8, 'Akhir', 1, 1, 'C', true);

        $this->SetTextColor(0, 0, 0);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-20);
        $this->SetDrawColor(189, 195, 199);
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY(), 200, $this->GetY());

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(127, 140, 141);
        $this->Cell(95, 10, 'Halaman ' . $this->PageNo() . '/{nb} - Sistem Informasi Akademik', 0, 0, 'L');
        $this->Cell(95, 10, 'Generated: ' . date('d/m/Y H:i:s'), 0, 0, 'R');
    }

    // Alternating row colors for grades
    function GradeRow($grade, $fill = false)
    {
        $this->SetX(10);

        if ($fill) {
            $this->SetFillColor(250, 250, 250);
        } else {
            $this->SetFillColor(255, 255, 255);
        }

        $this->SetFont('Arial', '', 8);
        $this->SetDrawColor(189, 195, 199);
        $this->SetLineWidth(0.2);

        // Calculate attitude average
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

        // Adjusted column widths to match header
        $this->Cell(65, 7, $grade['pelajaran_nama'], 1, 0, 'L', true);
        $this->Cell(18, 7, $grade['nilai_kkm'], 1, 0, 'C', true);
        $this->Cell(18, 7, $grade['uh'], 1, 0, 'C', true);
        $this->Cell(18, 7, $grade['pas'], 1, 0, 'C', true);
        $this->Cell(18, 7, $grade['p5ra'], 1, 0, 'C', true);
        $this->Cell(18, 7, $grade['tugas'], 1, 0, 'C', true);
        $this->Cell(18, 7, $attitude_avg, 1, 0, 'C', true);

        // Highlight final grade if high
        if ($grade['nilai_akhir'] >= 90) {
            $this->SetTextColor(39, 174, 96);
            $this->SetFont('Arial', 'B', 8);
        } else {
            $this->SetTextColor(0, 0, 0);
        }

        $this->Cell(17, 7, $grade['nilai_akhir'], 1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 8);
    }
}

// Initialize PDF
$pdf = new PDF('P', 'mm', 'A4'); // Portrait orientation
$pdf->AliasNbPages();
$pdf->AddPage();

// Grades table rows
$total_nilai = 0;
$total_mapel = count($grades);

if ($total_mapel > 0) {
    $rowNo = 0;
    foreach ($grades as $grade) {
        $pdf->GradeRow($grade, ($rowNo % 2 == 0));
        $total_nilai += floatval($grade['nilai_akhir']);
        $rowNo++;
    }

    // Total and average rows
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetFillColor(236, 240, 241);
    $pdf->SetX(10);
    $pdf->Cell(173, 7, 'TOTAL NILAI', 1, 0, 'R', true);
    $pdf->Cell(17, 7, $total_nilai, 1, 1, 'C', true);

    $pdf->SetX(10);
    $pdf->Cell(173, 7, 'RATA-RATA', 1, 0, 'R', true);
    $pdf->Cell(17, 7, $total_mapel > 0 ? round($total_nilai / $total_mapel, 2) : 0, 1, 1, 'C', true);

} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->SetX(10);
    $pdf->Cell(180, 8, 'Tidak ada nilai untuk semester ini', 1, 1, 'C');
}

// Legend for components
$pdf->Ln(5);
$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 6, 'Keterangan Komponen Penilaian:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->SetX(10);
$pdf->Cell(0, 4, 'UH = Ulangan Harian, PAS = Penilaian Akhir Semester, P5RA = Projek Penguatan Profil Pelajar Pancasila', 0, 1, 'L');
$pdf->SetX(10);
$pdf->Cell(0, 4, 'Sikap = Rata-rata dari Kehadiran, Keaktifan, dan Kekompakan', 0, 1, 'L');

// Signature section
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$current_date = date('d F Y');

// Two column signature layout
$pdf->SetX(10);
$pdf->Cell(95, 6, 'Mengetahui,', 0, 0, 'C');
$pdf->Cell(95, 6, ($school ? $school['sekolah_nama'] : 'Sekolah') . ', ' . $current_date, 0, 1, 'C');

$pdf->SetX(10);
$pdf->Cell(95, 6, 'Orang Tua/Wali', 0, 0, 'C');
$pdf->Cell(95, 6, 'Wali Kelas', 0, 1, 'C');

$pdf->Ln(20);
$pdf->SetX(10);
$pdf->Cell(95, 6, '(_____________________)', 0, 0, 'C');
$pdf->Cell(95, 6, '(_____________________)', 0, 1, 'C');

// Output PDF
$filename = 'Laporan_Akademik_' . $student['nomor_induk'] . '_' . str_replace(' ', '_', $semester) . '_' . str_replace('/', '-', $academic_year) . '.pdf';
$pdf->Output($filename, 'D');
exit();
?>