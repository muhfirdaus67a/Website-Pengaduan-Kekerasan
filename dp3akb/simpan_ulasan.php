<?php
$koneksi = new mysqli("localhost", "root", "", "pkl");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengaduan = intval($_POST['id_pengaduan']);
    $rating = intval($_POST['rating']);
    $ulasan = $koneksi->real_escape_string($_POST['ulasan']);

    $query = "INSERT INTO ulasan (id_pengaduan, rating, ulasan) VALUES ($id_pengaduan, $rating, '$ulasan')";
    
    if ($koneksi->query($query)) {
        echo "<script> window.location.href='pengaduan.php#cek-tiket';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan ulasan'); history.back();</script>";
    }
}
?>
