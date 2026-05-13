<?php
$koneksi = new mysqli("localhost", "root", "", "pkl");

if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_GET['no_tiket'])) {
  $no_tiket = $koneksi->real_escape_string($_GET['no_tiket']);
  $sql = "SELECT * FROM pengaduan WHERE no_tiket = '$no_tiket'";
  $result = $koneksi->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
      *{
        font-family: 'Poppins';
      }
      .pengaduan-container {
        display: flex;
        gap: 30px;
        max-width: 800px;
        border-radius: 10px;
      }

      .info-kiri, .info-kanan {
        flex: 1;
      }

      .info-kanan {
        margin-top: 0;
        text-align: right;
      }
      .info-kanan img {
        max-width: 200px;
        height: auto;
        border-radius: 8px;
        border: 1px solid #ccc;
      }

      .baris-info {
        display: flex;
        margin-bottom: 10px;
        font-size: 16px;
      }

      .label {
        width: 130px;
      }

      .colon {
        width: 10px;
        text-align: center;
      }

      .nilai {
        flex: 1;
      }

      h2 {
        text-align: left;
        color: #1d4ed8;
        font-family: 'Baloo 2';
        font-weight: bold;
      }
    </style>

    <h2>Hasil Pengecekan</h2>
    <div class="pengaduan-container">
      <div class="info-kiri">
        <div class="baris-info">
          <div class="label">Nama</div>
          <div class="colon">:</div>
          <div class="nilai"><?= htmlspecialchars($row['nama']) ?></div>
        </div>
        <div class="baris-info">
          <div class="label">No Handphone</div>
          <div class="colon">:</div>
          <div class="nilai"><?= htmlspecialchars($row['no_hp']) ?></div>
        </div>
        <div class="baris-info">
          <div class="label">Status</div>
          <div class="colon">:</div>
          <div class="nilai"><?= htmlspecialchars($row['status']) ?></div>
        </div>
      </div>
      <div class="info-kanan">
        <img src="<?= htmlspecialchars($row['bukti']) ?>" alt="Bukti">
      </div>
    </div>
<?php
  } else {
    echo "<p>Tidak ditemukan laporan dengan No Tiket tersebut.</p>";
  }
} else {
  echo "<p>Masukkan No Tiket pengaduan untuk melihat status laporan.</p>";
}

$koneksi->close();
?>
