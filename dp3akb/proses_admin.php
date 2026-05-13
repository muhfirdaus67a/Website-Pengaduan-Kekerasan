<?php
$koneksi = new mysqli("localhost", "root", "", "pkl");

if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status_update'])) {
    $id_pengaduan = $_POST['id_pengaduan'];
    $status = $_POST['status'];

    $sql_update = "UPDATE pengaduan SET status='$status' WHERE id_pengaduan='$id_pengaduan'";
    if ($koneksi->query($sql_update) === TRUE) {
        echo "<p>Status pengaduan berhasil diperbarui.</p>";
    } else {
        echo "<p>Gagal memperbarui status: " . $koneksi->error . "</p>";
    }
}

$sql = "SELECT id_pengaduan, nama, isi_laporan, status, bukti FROM pengaduan ORDER BY id_pengaduan DESC";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Status Pengaduan</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #f3f4f6;
    }

    h1 {
      color: #2563eb;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #2563eb;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .status {
      padding: 6px 10px;
      border-radius: 5px;
      font-weight: bold;
    }

    .Diterima { background-color: #e0f2fe; color: #0284c7; }
    .Diproses { background-color: #fef9c3; color: #ca8a04; }
    .Ditolak  { background-color: #fee2e2; color: #dc2626; }
    .Selesai  { background-color: #d1fae5; color: #059669; }

    img {
      width: 80px;
      height: auto;
      border-radius: 4px;
    }

    .form-group select {
      padding: 10px;
      border-radius: 4px;
      font-size: 16px;
      width: 150px;
    }
  </style>
</head>
<body>

  <h1>Status Pengaduan</h1>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Isi Laporan</th>
        <th>Status</th>
        <th>Bukti</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $statusClass = '';
          if ($row['status'] == 'Laporan Diproses') $statusClass = 'Diproses';
          elseif ($row['status'] == 'Laporan Diterima') $statusClass = 'Diterima';
          elseif ($row['status'] == 'Laporan Ditolak') $statusClass = 'Ditolak';
          elseif ($row['status'] == 'Laporan Selesai') $statusClass = 'Selesai';

          echo "<tr>
                  <td>{$row['id_pengaduan']}</td>
                  <td>{$row['nama']}</td>
                  <td>{$row['isi_laporan']}</td>
                  <td><span class='status $statusClass'>{$row['status']}</span></td>
                  <td><img src='{$row['bukti']}' alt='Bukti'></td>
                  <td>
                    <form method='POST'>
                      <input type='hidden' name='id_pengaduan' value='{$row['id_pengaduan']}'>
                      <div class='form-group'>
                        <select name='status'>
                          <option value='Laporan Diproses' " . ($row['status'] == 'Laporan Diproses' ? 'selected' : '') . ">Laporan Diproses</option>
                          <option value='Laporan Diterima' " . ($row['status'] == 'Laporan Diterima' ? 'selected' : '') . ">Laporan Diterima</option>
                          <option value='Laporan Ditolak' " . ($row['status'] == 'Laporan Ditolak' ? 'selected' : '') . ">Laporan Ditolak</option>
                          <option value='Laporan Selesai' " . ($row['status'] == 'Laporan Selesai' ? 'selected' : '') . ">Laporan Selesai</option>
                        </select>
                      </div>
                      <button type='submit' name='status_update' class='submit-btn'>Update Status</button>
                    </form>
                  </td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='6'>Belum ada data pengaduan.</td></tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>
