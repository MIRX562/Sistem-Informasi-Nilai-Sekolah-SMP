<?php
    session_start();
    require_once('config/db.php');
    require_once('fpdf/fpdf.php');

    if (!isset($_GET['pelajaran']) || !isset($_GET['kelas']) || !isset($_GET['semester']) || !isset($_GET['tahun'])) {
        echo "<script>alert('Parameter tidak lengkep!'); window.history.back();</script>";
        exit;
    }

    $pelajaran = $_GET['pelajaran'];
    $kelas = $_GET['kelas'];
    $semester = $_GET['semester'];
    $tahun = $_GET['tahun'];

    $check_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM nilai 
                            INNER JOIN users ON nilai.id=users.id 
                            WHERE nilai.pelajaran_id='$pelajaran' AND users.kelas_id='$kelas' 
                            AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun'");

    $check_result = mysqli_fetch_array($check_query);

    if ($check_result['count'] == 0) {
        echo "<script>alert('Tidak ada data nilai yang ditemukan!'); window.history.back();</script>";
        exit;
    }

    $info_query = mysqli_query($conn, "SELECT kelas.kelas_nama, pelajaran.pelajaran_nama, semester.semester_nama, tahun.tahun_nama 
                            FROM kelas, pelajaran, semester, tahun
                            WHERE kelas.kelas_id='$kelas' AND pelajaran.pelajaran_id='$pelajaran' 
                            AND semester.semester_id='$semester' AND tahun.tahun_id='$tahun'");

    $info = mysqli_fetch_array($info_query);


    class PDF extends FPDF
    {
        private $info_data;
        
        function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $info = null)
        {
            parent::__construct($orientation, $unit, $size);
            $this->info_data = $info;
        }
        
        // Header halaman
        function Header()
        {
            // Background header dengan gradient effect
            $this->SetFillColor(76, 154, 75); // Warna biru modern
            $this->Rect(0, 0, 297, 35, 'F');
            
            // Garis dekoratif atas
            $this->SetDrawColor(76, 154, 75);
            $this->SetLineWidth(2);
            $this->Line(0, 35, 297, 35);
            
            $this->Image('C:\\xampp\\htdocs\\erapor_pondok\\assets\\images\\logo_logo.png', 10, 5, 25);

            
            // Judul Header
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', 'B', 18);
            $this->Text(45, 15, 'LAPORAN NILAI SISWA');
            
            $this->SetFont('Arial', 'B', 12);
            $this->Text(45, 22, 'PONDOK PESANTREN DARUSSALAM AUR DURI SUMANI');
            
            $this->SetFont('Arial', '', 9);
            $this->Text(45, 28, 'Jln. Lintas Sumatra Km.9 Kab. Solok |  Telp: (0755) 325554 | Email: pesantrendarussalam19@gmail.com ');
            
            
            // Info tanggal di kanan atas
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', '', 9);
            $tanggal = 'Dicetak: ' . date('d F Y, H:i');
            $this->Text(220, 15, $tanggal);
            
            // Reset posisi Y setelah header
            $this->SetY(45);
            
            // Box informasi mata pelajaran dengan background
            $this->SetFillColor(236, 240, 241);
            $this->SetDrawColor(149, 165, 166);
            $this->Rect(15, 45, 267, 25, 'DF');
            
            // Informasi mata pelajaran dalam box
            $this->SetTextColor(44, 62, 80);
            $this->SetFont('Arial', 'B', 11);
            $this->Text(20, 53, 'INFORMASI PELAJARAN');
            
            $this->SetFont('Arial', '', 10);
            $this->Text(20, 60, 'Mata Pelajaran : ' . $this->info_data['pelajaran_nama']);
            $this->Text(20, 66, 'Kelas                  : ' . $this->info_data['kelas_nama']);
            
            $this->Text(150, 60, 'Semester        : ' . $this->info_data['semester_nama']);
            $this->Text(150, 66, 'Tahun Ajaran : ' . $this->info_data['tahun_nama']);
            
            // Posisi untuk tabel
            $this->SetY(75);
            $this->SetX(15); 
            
            // Header tabel dengan desain modern
            $this->SetFont('Arial', 'B', 8);
            $this->SetFillColor(52, 73, 94); 
            $this->SetTextColor(255, 255, 255);
            $this->SetDrawColor(44, 62, 80);
            $this->SetLineWidth(0.5);
            
            // Header dengan lebar yang disesuaikan untuk landscape
            $this->Cell(8, 10, 'No', 1, 0, 'C', true);
            $this->Cell(54, 10, 'Nama Siswa', 1, 0, 'C', true);
            $this->Cell(20, 10, 'KKM', 1, 0, 'C', true);
            $this->Cell(20, 10, 'UH', 1, 0, 'C', true);
            $this->Cell(20, 10, 'PAS', 1, 0, 'C', true);
            $this->Cell(20, 10, 'P5RA', 1, 0, 'C', true);
            $this->Cell(20, 10, 'Tugas', 1, 0, 'C', true);
            $this->Cell(20, 10, 'Kehadiran', 1, 0, 'C', true);
            $this->Cell(20, 10, 'Keaktifan', 1, 0, 'C', true);
            $this->Cell(20, 10, 'Kekompakan', 1, 0, 'C', true);
            $this->Cell(20, 10, 'Nilai Akhir', 1, 0, 'C', true);
            $this->Cell(25, 10, 'Keterangan', 1, 1, 'C', true);
            
            // Reset warna text untuk isi tabel
            $this->SetTextColor(0, 0, 0);
        }
        
        // Footer halaman
        function Footer()
        {
            // Garis pembatas footer
            $this->SetY(-25);
            $this->SetDrawColor(189, 195, 199);
            $this->SetLineWidth(0.5);
            $this->Line(15, $this->GetY(), 282, $this->GetY());
            
            // Background footer
            $this->SetY(-20);
            $this->SetFillColor(248, 249, 250);
            $this->Rect(0, $this->GetY(), 297, 20, 'F');
            
            // Info halaman di kiri
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->SetTextColor(127, 140, 141);
            $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' - Sistem Informasi Akademik Pondok Pesantren Darussalam Aur Duri Sumani', 0, 0, 'L');
            
            // Timestamp di kanan
            $this->Cell(0, 10, 'Generated: ' . date('d/m/Y H:i:s'), 0, 0, 'R');
        }
        
        // Function untuk alternating row colors
        function Row($data, $no, $fill = false)
        {
            $this->SetX(15);
            // Set warna background untuk baris bergantian
            if ($fill) {
                $this->SetFillColor(250, 250, 250);
            } else {
                $this->SetFillColor(255, 255, 255);
            }
            
            $this->SetFont('Arial', '', 8);
            $this->SetDrawColor(189, 195, 199);
            $this->SetLineWidth(0.3);
            
            // Data cells
            $this->Cell(8, 8, $no, 1, 0, 'C', true);
            $this->Cell(54, 8, substr($data['name'], 0, 25), 1, 0, 'L', true);
            $this->Cell(20, 8, $data['nilai_kkm'], 1, 0, 'C', true);
            $this->Cell(20, 8, $data['uh'], 1, 0, 'C', true);
            $this->Cell(20, 8, $data['pas'], 1, 0, 'C', true);
            $this->Cell(20, 8, $data['p5ra'], 1, 0, 'C', true);
            $this->Cell(20, 8, $data['tugas'], 1, 0, 'C', true);
            $this->Cell(20, 8, $data['kehadiran'], 1, 0, 'C', true);
            $this->Cell(20, 8, $data['keaktifan'], 1, 0, 'C', true);
            $this->Cell(20, 8, $data['kekompakan'], 1, 0, 'C', true);
            
            // Nilai akhir dengan highlight jika tinggi
            $nilai_akhir = $data['nilai_akhir'];
            if ($nilai_akhir >= 90) {
                $this->SetTextColor(39, 174, 96); 
                $this->SetFont('Arial', 'B', 8);
            } else {
                $this->SetTextColor(0, 0, 0);
            }
            $this->Cell(20, 8, $nilai_akhir, 1, 0, 'C', true);
            
            // Keterangan dengan warna dan background
            $keterangan = $data['keterangan'];
            if ($keterangan == "Tuntas") {
                $this->SetTextColor(255, 255, 255);
                $this->SetFillColor(46, 204, 113); 
            } else {
                $this->SetTextColor(255, 255, 255);
                $this->SetFillColor(231, 76, 60); 
            }
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(25, 8, $keterangan, 1, 1, 'C', true);
            
            // Reset colors
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', '', 8);
        }
    }

    $pdf = new PDF('L', 'mm', 'A4', $info);
    $pdf->AddPage();

    $nilai_query = mysqli_query($conn, "SELECT nilai.nilai_id, nilai.nilai_kkm, users.name, 
                                nilai.uh, nilai.pas, nilai.p5ra, nilai.tugas, 
                                nilai.kehadiran, nilai.keaktifan, nilai.kekompakan
                                FROM nilai 
                                INNER JOIN users ON nilai.id=users.id 
                                INNER JOIN kelas ON users.kelas_id=kelas.kelas_id 
                                WHERE nilai.pelajaran_id='$pelajaran' AND kelas.kelas_id='$kelas' 
                                AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun'
                                ORDER BY users.name ASC") or die(mysqli_error($conn));

    $no = 1;
    while ($data = mysqli_fetch_array($nilai_query)) {
        $uh_value = floatval($data['uh']);
        $pas_value = floatval($data['pas']);
        $p5ra_value = floatval($data['p5ra']);
        $tugas_value = floatval($data['tugas']);
        $kehadiran_value = floatval($data['kehadiran']);
        $keaktifan_value = floatval($data['keaktifan']);
        $kekompakan_value = floatval($data['kekompakan']);

        if (!empty($data['p5ra']) && $p5ra_value > 0) {
            $nilai_akhir = ($uh_value * 0.20) + ($pas_value * 0.30) + ($p5ra_value * 0.20) +
                ($tugas_value * 0.15) + ($kehadiran_value * 0.05) +
                ($keaktifan_value * 0.05) + ($kekompakan_value * 0.05);
        } else {
            $nilai_akhir = ($uh_value * 0.25) + ($pas_value * 0.35) + ($tugas_value * 0.20) +
                ($kehadiran_value * 0.075) + ($keaktifan_value * 0.0625) +
                ($kekompakan_value * 0.0625);
        }

        $nilai_akhir = round($nilai_akhir, 2);
        
        $keterangan = ($data['nilai_kkm'] <= $nilai_akhir) ? "Tuntas" : "Tidak Tuntas";
        
        if ($pdf->GetY() > 180) {
            $pdf->AddPage();
        }
        
        $row_data = array_merge($data, [
            'nilai_akhir' => $nilai_akhir,
            'keterangan' => $keterangan
        ]);
        
        $pdf->Row($row_data, $no, ($no % 2 == 0));
        
        $no++;
    }

    $pdf->Ln(10);

    // Box statistik
    $pdf->SetFillColor(236, 240, 241);
    $pdf->SetDrawColor(149, 165, 166);
    $pdf->Rect(15, $pdf->GetY(), 267, 40, 'DF');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->Cell(0, 8, 'STATISTIK PENILAIAN', 0, 1, 'C');

    // Hitung statistik
    $stats_query = mysqli_query($conn, "SELECT 
        COUNT(*) as total_siswa,
        AVG(nilai_akhir) as rata_rata,
        MIN(nilai_akhir) as nilai_min,
        MAX(nilai_akhir) as nilai_max,
        SUM(CASE WHEN nilai_akhir >= nilai_kkm THEN 1 ELSE 0 END) as tuntas,
        SUM(CASE WHEN nilai_akhir < nilai_kkm THEN 1 ELSE 0 END) as tidak_tuntas
        FROM nilai 
        INNER JOIN users ON nilai.id=users.id 
        WHERE nilai.pelajaran_id='$pelajaran' AND users.kelas_id='$kelas' 
        AND nilai.semester_id='$semester' AND nilai.tahun_id='$tahun'");

    $stats = mysqli_fetch_array($stats_query);
    $persentase_tuntas = ($stats['total_siswa'] > 0) ? round(($stats['tuntas'] / $stats['total_siswa']) * 100, 2) : 0;

    // Statistik dalam 3 kolom
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(52, 73, 94);

    // Kolom 1
    $pdf->SetXY(25, $pdf->GetY() + 5);
    $pdf->Cell(80, 6, 'Total Siswa: ' . $stats['total_siswa'] . ' siswa', 0, 1);
    $pdf->SetX(25);
    $pdf->Cell(80, 6, 'Nilai Rata-rata: ' . round($stats['rata_rata'], 2), 0, 1);
    $pdf->SetX(25);
    $pdf->Cell(80, 6, 'Nilai Tertinggi: ' . $stats['nilai_max'], 0, 0);

    // Kolom 2
    $pdf->SetXY(115, $pdf->GetY() - 12);
    $pdf->Cell(80, 6, 'Siswa Tuntas: ' . $stats['tuntas'] . ' siswa', 0, 1);
    $pdf->SetX(115);
    $pdf->Cell(80, 6, 'Siswa Tidak Tuntas: ' . $stats['tidak_tuntas'] . ' siswa', 0, 1);
    $pdf->SetX(115);
    $pdf->Cell(80, 6, 'Nilai Terendah: ' . $stats['nilai_min'], 0, 0);

    // Kolom 3 - Persentase dengan highlight
    $pdf->SetXY(205, $pdf->GetY() - 12);
    $pdf->SetFont('Arial', 'B', 12);
    if ($persentase_tuntas >= 75) {
        $pdf->SetTextColor(39, 174, 96); // Hijau
    } else {
        $pdf->SetTextColor(231, 76, 60); // Merah
    }
    $pdf->Cell(80, 8, 'Persentase Ketuntasan', 0, 1, 'C');
    $pdf->SetX(205);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(80, 8, $persentase_tuntas . '%', 0, 0, 'C');

    // ttd
    $pdf->Ln(25);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->SetFont('Arial', '', 10);

    // Box ttd
    $pdf->SetFillColor(248, 249, 250);
    $pdf->SetDrawColor(189, 195, 199);
    $pdf->Rect(200, $pdf->GetY(), 80, 30, 'DF');

    $pdf->SetXY(205, $pdf->GetY() + 5);
    $pdf->Cell(70, 6, 'Sumani, ' . date('d F Y'), 0, 1, 'C');
    $pdf->SetX(205);
    $pdf->Cell(70, 6, 'Guru Mata Pelajaran', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetX(205);
    $pdf->Cell(70, 6, '(_____________________)', 0, 1, 'C');

    // Output PDF
    $filename = 'Laporan_Nilai_' . str_replace(' ', '_', $info['pelajaran_nama']) . '_' . 
            str_replace(' ', '_', $info['kelas_nama']) . '_' . date('Y-m-d') . '.pdf';
    $pdf->Output('D', $filename);
?>