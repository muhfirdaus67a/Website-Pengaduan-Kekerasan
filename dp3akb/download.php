<?php
require('fpdf.php');

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : 0;
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : 0;

if ($bulan < 1 || $bulan > 12 || $tahun < 2000) {
    die("Parameter bulan atau tahun tidak valid.");
}

$koneksi = new mysqli("localhost", "root", "", "pkl");
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

$query = "SELECT * FROM pengaduan WHERE MONTH(tanggal_pengaduan) = $bulan AND YEAR(tanggal_pengaduan) = $tahun ORDER BY id_pengaduan ASC";
$result = $koneksi->query($query);
if (!$result) {
    die("Query error: " . $koneksi->error);
}

$pdf = new FPDF('L','mm','A4'); // landscape
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,"Rekap Pengaduan Bulan $bulan Tahun $tahun",0,1,'C');

$pdf->SetFont('Arial','B',10);

// Header tabel
$pdf->Cell(10,10,'No',1,0,'C');
$pdf->Cell(40,10,'Nama',1,0,'C');
$pdf->Cell(50,10,'Alamat',1,0,'C');
$pdf->Cell(65,10,'kategori',1,0,'C');
$pdf->Cell(70,10,'isi_laporan',1,0,'C');
$pdf->Cell(30,10,'Status',1,1,'C');

$pdf->SetFont('Arial','',10);

$no = 1;

// Fungsi untuk memotong teks agar muat di lebar kolom tanpa melebihi width, tanpa wrap
function fitTextInCell($pdf, $text, $maxWidth) {
    $ellipsis = '...';
    $widthEllipsis = $pdf->GetStringWidth($ellipsis);
    
    // Kalau teks sudah muat, langsung return
    if ($pdf->GetStringWidth($text) <= $maxWidth) {
        return $text;
    }
    
    $cutText = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $substr = substr($text, 0, $i + 1);
        $substrWidth = $pdf->GetStringWidth($substr);
        
        if ($substrWidth + $widthEllipsis > $maxWidth) {
            break;
        }
        $cutText = $substr;
    }
    
    return $cutText . $ellipsis;
}

while ($row = $result->fetch_assoc()) {
    // Lebar kolom
    $wNo = 10;
    $wNama = 40;
    $wAlamat = 50;
    $wKategori = 65;
    $wIsi_laporan = 70;

    $wStatus = 30;


    // Potong teks sesuai lebar kolom supaya tidak overflow
    $nama = fitTextInCell($pdf, $row['nama'], $wNama - 2); // dikurangi padding kiri dan kanan 2 mm
    $alamat = fitTextInCell($pdf, $row['alamat'], $wAlamat - 2);
    $kategori = fitTextInCell($pdf, $row['kategori'], $wKategori - 2);
    $isi_laporan = fitTextInCell($pdf, $row['isi_laporan'], $wIsi_laporan - 2);
    $status = fitTextInCell($pdf, $row['status'], $wStatus - 2);

    // Tulis row dengan cell biasa, satu baris, tinggi 10mm
    $pdf->Cell($wNo, 10, $no++, 1, 0, 'C');
    $pdf->Cell($wNama, 10, $nama, 1, 0, 'L');
    $pdf->Cell($wAlamat, 10, $alamat, 1, 0, 'L');
    $pdf->Cell($wKategori, 10, $kategori, 1, 0, 'L');
    $pdf->Cell($wIsi_laporan, 10, $isi_laporan, 1, 0, 'L');
    $pdf->Cell($wStatus, 10, $status, 1, 1, 'L');
}

$pdf->Output('D', "rekap_pengaduan_bulan_{$bulan}_tahun_{$tahun}.pdf");
exit;
