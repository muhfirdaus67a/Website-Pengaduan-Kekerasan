<?php
// Contoh data jumlah laporan
$laporanMasuk = 120;
$laporanDiterima = 80;
$laporanDitolak = 30;
$laporanSelesai = 50;
$totalLaporan = $laporanMasuk + $laporanDiterima + $laporanDitolak + $laporanSelesai;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <h1>Dashboard Admin - Statistik Laporan</h1>

    <!-- Diagram Lingkaran -->
    <canvas id="myPieChart"></canvas>

    <script>
        var ctx = document.getElementById('myPieChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Laporan Masuk', 'Laporan Diterima', 'Laporan Ditolak', 'Laporan Selesai'],
                datasets: [{
                    data: [<?php echo $laporanMasuk; ?>, <?php echo $laporanDiterima; ?>, <?php echo $laporanDitolak; ?>, <?php echo $laporanSelesai; ?>],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>

    <div>
        <h3>Total Laporan: <?php echo $totalLaporan; ?></h3>
        <p>Jumlah laporan: <?php echo $laporanMasuk; ?> Masuk, <?php echo $laporanDiterima; ?> Diterima, <?php echo $laporanDitolak; ?> Ditolak, <?php echo $laporanSelesai; ?> Selesai</p>
    </div>

</body>
</html>
