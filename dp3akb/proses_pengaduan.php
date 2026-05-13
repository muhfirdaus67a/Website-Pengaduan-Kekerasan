<?php
$koneksi = new mysqli("localhost", "root", "", "pkl");

if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$no_hp = $_POST['no_hp'];
$kategori = $_POST['kategori'];
$tempat_kejadian = $_POST['tempat_kejadian'];
$isi_laporan = $_POST['laporan'];
$status = "Laporan Masuk";

// Proses upload foto
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["bukti"]["name"]);
move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file);

$no_tiket = 'TKT' . time() . rand(10, 99);

$sql = "INSERT INTO pengaduan (no_tiket, nama, alamat, no_hp, kategori, tempat_kejadian, isi_laporan, bukti, status)
        VALUES ('$no_tiket', '$nama', '$alamat', '$no_hp', '$kategori', '$tempat_kejadian', '$isi_laporan', '$target_file', '$status')";

if ($koneksi->query($sql) === TRUE) {
  echo "<h2 style='text-align:center; color:green;'>Laporan berhasil dikirim!</h2>";
  echo "<p style='text-align:center;'>Nomor Tiket Anda: <strong>$no_tiket</strong></p>";
  echo "<p style='text-align:center;'>Silakan simpan nomor tiket ini untuk pengecekan pengaduan.</p>";
  echo "<div style='text-align:center; margin-top:20px;'><a href='pengaduan.php' style='color:blue;'>Kembali ke Halaman Utama</a></div>";
} else {
  echo "Error: " . $sql . "<br>" . $koneksi->error;
}

$koneksi->close();
?>
