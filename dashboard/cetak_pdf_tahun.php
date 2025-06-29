<?php
session_start();
require_once('../config/db.php');
require_once('../fpdf/fpdf.php');

if (!isset($_GET['tahun_id'])) {
    die('Parameter tahun_id diperlukan.');
}
$tahun_id = $_GET['tahun_id'];

// Get tahun ajaran name
$tahun_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tahun_nama FROM tahun WHERE tahun_id='$tahun_id'"));
$tahun_nama = $tahun_row ? $tahun_row['tahun_nama'] : $tahun_id;

// List all classes in this year (with students)
$kelas_result = mysqli_query($conn, "SELECT DISTINCT k.kelas_nama FROM users u JOIN kelas k ON u.kelas_id = k.kelas_id WHERE u.kelas_id IS NOT NULL");
$kelas = [];
while ($k = mysqli_fetch_assoc($kelas_result))
    $kelas[] = $k['kelas_nama'];

// List all subjects taught in this year
$pelajaran_result = mysqli_query($conn, "SELECT DISTINCT p.pelajaran_nama FROM guru_mengajar gm JOIN pelajaran p ON gm.pelajaran_id = p.pelajaran_id WHERE gm.tahun_id = '$tahun_id'");
$pelajaran = [];
while ($p = mysqli_fetch_assoc($pelajaran_result))
    $pelajaran[] = $p['pelajaran_nama'];

// List all teachers teaching in this year, with subject and class
$guru_result = mysqli_query($conn, "SELECT u.name AS guru_name, p.pelajaran_nama, k.kelas_nama FROM guru_mengajar gm JOIN users u ON gm.guru_id = u.id JOIN pelajaran p ON gm.pelajaran_id = p.pelajaran_id JOIN kelas k ON gm.kelas_id = k.kelas_id WHERE gm.tahun_id = '$tahun_id' ORDER BY u.name, p.pelajaran_nama, k.kelas_nama");

// List all students enrolled in this year (by having nilai in this tahun)
$siswa_result = mysqli_query($conn, "SELECT DISTINCT u.name, u.nomor_induk, k.kelas_nama FROM nilai n JOIN users u ON n.id = u.id LEFT JOIN kelas k ON u.kelas_id = k.kelas_id WHERE n.tahun_id = '$tahun_id' AND u.access = 'orang_tua' ORDER BY u.name");

// List all grades recorded in this year
$nilai_result = mysqli_query($conn, "SELECT u.name AS siswa, k.kelas_nama, p.pelajaran_nama, n.semester_id, n.nilai_akhir FROM nilai n JOIN users u ON n.id = u.id JOIN pelajaran p ON n.pelajaran_id = p.pelajaran_id LEFT JOIN kelas k ON u.kelas_id = k.kelas_id WHERE n.tahun_id = '$tahun_id' ORDER BY u.name, p.pelajaran_nama");

// Get school info
$school = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sekolah WHERE sekolah_id = 1"));

class PDF extends FPDF
{
    function Header()
    {
        global $tahun_nama, $school;
        // Header background
        $this->SetFillColor(76, 154, 75);
        $this->Rect(0, 0, 297, 35, 'F');
        $this->SetDrawColor(76, 154, 75);
        $this->SetLineWidth(2);
        $this->Line(0, 35, 297, 35);
        // Logo (adjust path if needed)
        if ($school && file_exists('../assets/images/logo_logo.png')) {
            $this->Image('../assets/images/logo_logo.png', 10, 5, 25);
        }
        // School name and info
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 18);
        $this->Text(45, 15, 'REKAP TAHUN AJARAN');
        if ($school) {
            $this->SetFont('Arial', 'B', 12);
            $this->Text(45, 22, strtoupper($school['sekolah_nama']));
            $this->SetFont('Arial', '', 9);
            $this->Text(45, 28, $school['sekolah_alamat'] . ' | Telp: ' . $school['sekolah_telp']);
        }
        $this->SetFont('Arial', '', 9);
        $this->Text(220, 15, 'Dicetak: ' . date('d F Y, H:i'));
        $this->SetY(40); // Move content start below header
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, 'Tahun Ajaran: ' . $tahun_nama, 0, 1, 'C', false);
        $this->Ln(2);
    }
    function SectionTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(236, 240, 241);
        $this->Cell(0, 8, $title, 0, 1, 'L', true);
        $this->Ln(2);
    }
    function ListSection($title, $items)
    {
        $this->SectionTitle($title);
        $this->SetFont('Arial', '', 10);
        foreach ($items as $item) {
            $this->Cell(0, 6, '- ' . $item, 0, 1);
        }
        $this->Ln(2);
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

// Kelas
$pdf->ListSection('Kelas yang Aktif', $kelas);
// Pelajaran
$pdf->ListSection('Mata Pelajaran yang Diajarkan', $pelajaran);

// Guru Table
$pdf->SectionTitle('Guru, Mata Pelajaran, dan Kelas');
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(52, 73, 94);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(60, 8, 'Nama Guru', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Mata Pelajaran', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Kelas', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$fill = false;
while ($g = mysqli_fetch_assoc($guru_result)) {
    $pdf->SetFillColor($fill ? 250 : 255, 250, 250);
    $pdf->Cell(60, 7, $g['guru_name'], 1, 0, 'L', true);
    $pdf->Cell(60, 7, $g['pelajaran_nama'], 1, 0, 'L', true);
    $pdf->Cell(40, 7, $g['kelas_nama'], 1, 1, 'L', true);
    $fill = !$fill;
}
$pdf->Ln(2);

// Siswa Table
$pdf->SectionTitle('Daftar Siswa yang Memiliki Nilai Tahun Ini');
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(52, 73, 94);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(40, 8, 'NIS', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Nama Siswa', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Kelas', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$fill = false;
while ($s = mysqli_fetch_assoc($siswa_result)) {
    $pdf->SetFillColor($fill ? 250 : 255, 250, 250);
    $pdf->Cell(40, 7, $s['nomor_induk'], 1, 0, 'L', true);
    $pdf->Cell(60, 7, $s['name'], 1, 0, 'L', true);
    $pdf->Cell(40, 7, $s['kelas_nama'], 1, 1, 'L', true);
    $fill = !$fill;
}
$pdf->Ln(2);

// Nilai Table
$pdf->SectionTitle('Rekap Nilai yang Tercatat Tahun Ini');
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(52, 73, 94);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(60, 8, 'Nama Siswa', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Kelas', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Mata Pelajaran', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Semester', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Nilai Akhir', 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$fill = false;
while ($n = mysqli_fetch_assoc($nilai_result)) {
    $pdf->SetFillColor($fill ? 250 : 255, 250, 250);
    $pdf->Cell(60, 7, $n['siswa'], 1, 0, 'L', true);
    $pdf->Cell(40, 7, $n['kelas_nama'], 1, 0, 'L', true);
    $pdf->Cell(60, 7, $n['pelajaran_nama'], 1, 0, 'L', true);
    $pdf->Cell(25, 7, $n['semester_id'], 1, 0, 'C', true);
    $pdf->Cell(25, 7, $n['nilai_akhir'], 1, 1, 'C', true);
    $fill = !$fill;
}

$pdf->Output('I', 'Rekap_Tahun_Ajaran_' . $tahun_nama . '.pdf');
exit();
