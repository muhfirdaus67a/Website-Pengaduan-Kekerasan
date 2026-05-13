<?php
$koneksi = new mysqli("localhost", "root", "", "pkl");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM berita WHERE id_berita = $id";
$result = mysqli_query($koneksi, $query);
$berita = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Berita</title>
    <link rel="stylesheet" href="detail_berita.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($berita['judul']); ?></h1>
    <div class="riwayat">
        <p>
            <?php echo htmlspecialchars($berita['tanggal']); ?> 
            | <?php echo htmlspecialchars($berita['penulis']); ?>
        </p>
    </div>
    <img src="image_berita/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="gambar berita" width="300">
    
    <p class="isi"><?php echo nl2br(htmlspecialchars($berita['isi'])); ?></p>
</body>
</html>
