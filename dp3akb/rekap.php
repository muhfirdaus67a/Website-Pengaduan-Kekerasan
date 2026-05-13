<?php
$jumlah_pengaduan = null;
$bulan_terpilih = $_POST["bulan"] ?? null;
$tahun_terpilih = $_POST["tahun"] ?? date("Y");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bulan"], $_POST["tahun"])) {
    $bulan = $_POST["bulan"];
    $tahun = $_POST["tahun"];

    // Koneksi ke database Anda
    $koneksi = new mysqli("localhost", "root", "", "pkl");

    $query = "SELECT COUNT(*) as total FROM pengaduan WHERE MONTH(tanggal_pengaduan) = $bulan AND YEAR(tanggal_pengaduan) = $tahun";
    $result = $koneksi->query($query);
    $data = $result->fetch_assoc();
    $jumlah_pengaduan = $data["total"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rekap Pengaduan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            padding: 30px;
        }

        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 600px;
            text-align: center;
            border: 5px solid #3A56D0;
        }

        h2 {
            color: #3A56D0;
            margin-bottom: 20px;
        }

        .bulan-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .bulan-grid button {
            padding: 10px;
            background-color: #3A56D0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .bulan-grid button.selected {
            background-color: #ccc;
            color: #333;
        }

        .tahun-section {
            margin: 20px 0;
        }

        .submit-btn {
            background: #3A56D0;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
        }

        .total-box {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #3A56D0;
        }

        .download-btn {
            margin-top: 20px;
            background: #3A56D0;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Rekap Pengaduan</h2>

    <form method="POST">
        <div class="bulan-grid">
            <?php
            $nama_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                           "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            for ($i = 1; $i <= 12; $i++) {
                $isSelected = ($bulan_terpilih == $i) ? "selected" : "";
                echo "<button type='button' class='bulan-btn $isSelected' data-bulan='$i'>{$nama_bulan[$i - 1]}</button>";
            }
            ?>
        </div>

        <!-- Input bulan tersembunyi yang akan dikirim -->
        <input type="hidden" name="bulan" id="inputBulan" value="<?= $bulan_terpilih ?>">


        <div class="tahun-section">
            <label for="tahun">TAHUN</label><br>
            <input type="number" name="tahun" id="inputTahun" value="<?= $tahun_terpilih ?>" 
                style="padding: 10px; border-radius: 20px; border: none; background: #e0e0e0; text-align: center;">
        </div>

        <button type="submit" class="submit-btn">Submit</button>
    </form>

    <?php if ($jumlah_pengaduan !== null): ?>
        <div class="total-box">
            <img src="icon.png" style="width:24px; vertical-align: middle;"> <?= $jumlah_pengaduan ?>
        </div>
        <a href="download.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="download-btn">⬇ Download PDF</a>
    <?php endif; ?>
</div>

<script>
    const bulanButtons = document.querySelectorAll('.bulan-btn');
    const inputBulan = document.getElementById('inputBulan');

    bulanButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Hapus class selected dari semua tombol
            bulanButtons.forEach(btn => btn.classList.remove('selected'));

            // Tambahkan class selected pada tombol yang diklik
            button.classList.add('selected');

            // Set nilai bulan pada input tersembunyi
            inputBulan.value = button.getAttribute('data-bulan');
        });
    });
</script>
</body>
</html>
