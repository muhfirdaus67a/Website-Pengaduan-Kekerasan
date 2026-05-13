<?php

use Google\Service\AIPlatformNotebooks\Location;
$koneksi = new mysqli("localhost", "root", "", "pkl");

if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

// jumlah laporan
$sql = "SELECT status, COUNT(*) AS jumlah FROM pengaduan GROUP BY status";
$result = $koneksi->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
  $data[$row['status']] = $row['jumlah'];
}

// Ambil 3 laporan terbaru yang statusnya masih "Laporan Diproses"
$queryLaporanBaru = "SELECT nama, kategori FROM pengaduan 
                     WHERE status = 'Laporan Diproses' 
                     ORDER BY tanggal_pengaduan DESC 
                     LIMIT 3";
$resultLaporanBaru = $koneksi->query($queryLaporanBaru);


$queryLaporanTerbaru = "SELECT nama, kategori FROM pengaduan ORDER BY tanggal_pengaduan DESC LIMIT 3";
$resultLaporanTerbaru = $koneksi->query($queryLaporanTerbaru);

//rekap
$jumlah_pengaduan = null;
$bulan_terpilih = $_POST["bulan"] ?? null;
$tahun_terpilih = $_POST["tahun"] ?? date("Y");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bulan"], $_POST["tahun"])) {
    $bulan = (int) $_POST["bulan"];
    $tahun = (int) $_POST["tahun"];

    if ($bulan >= 1 && $bulan <= 12 && $tahun >= 2000) {
        $query = "SELECT COUNT(*) as total FROM pengaduan WHERE MONTH(tanggal_pengaduan) = $bulan AND YEAR(tanggal_pengaduan) = $tahun";
        $result = $koneksi->query($query);
        $data = $result->fetch_assoc();
        $jumlah_pengaduan = $data["total"];
    } else {
        $jumlah_pengaduan = null; // atau bisa tampilkan pesan error
    }
}




// Update status dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pengaduan'], $_POST['status'])) {
  $id = $_POST['id_pengaduan'];
  $status = $_POST['status'];

  $stmt = $koneksi->prepare("UPDATE pengaduan SET status = ? WHERE id_pengaduan = ?");
  $stmt->bind_param("si", $status, $id);
  $stmt->execute();
  $stmt->close();

  header("Location: dashboard_admin.php");
  exit();
}

// Tambah Berita 
if (isset($_POST['tambah_berita'])) {
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $penulis = $_POST['penulis'];
    $isi = $_POST['isi'];

    $folder = "image_berita/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $targetPath = $folder . basename($imageName);
    $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $allowed)) {
        if ($_FILES['image']['size'] <= 5 * 1024 * 1024) {
            if (move_uploaded_file($tmpName, $targetPath)) {
                $query = "INSERT INTO berita (judul, tanggal, penulis, isi, gambar) VALUES ('$judul', '$tanggal', '$penulis', '$isi', '$imageName')";
                $result = mysqli_query($koneksi, $query);
                if ($result) {
                    echo "<script>alert('Berita berhasil ditambahkan!');</script>";
                } else {
                    echo "<script>alert('Gagal menyimpan ke database.');</script>";
                }
            } else {
                echo "<script>alert('Gagal mengunggah gambar.');</script>";
            }
        } else {
            echo "<script>alert('Ukuran gambar maksimal 5MB');</script>";
        }
    } else {
        echo "<script>alert('Format file tidak didukung!');</script>";
    }
}

//ambil 3 data terbaru 
$query = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3";
$result = mysqli_query($koneksi, $query);


// Fungsi ambil data
function ambilPengaduan($koneksi, $status_filter) {
  $stmt = $koneksi->prepare("SELECT * FROM pengaduan WHERE status = ? ORDER BY id_pengaduan DESC");
  $stmt->bind_param("s", $status_filter);
  $stmt->execute();
  return $stmt->get_result();
}

$diproses = ambilPengaduan($koneksi, "Laporan Diproses");
$diterima = ambilPengaduan($koneksi, "Laporan Diterima");
$ditolak = ambilPengaduan($koneksi, "Laporan Ditolak");
$selesai = ambilPengaduan($koneksi, "Laporan Selesai");

//ulasan
// Gunakan variabel unik untuk query ulasan
$queryUlasan = "SELECT * FROM ulasan";
$resultUlasan = $koneksi->query($queryUlasan);

// Jika query gagal
if (!$resultUlasan) {
    die("Query ulasan gagal: " . $koneksi->error);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            transition: all 0.3s;
        }
        .dashboard-content {
            transition: all 0.3s;
        }
        .report-card {
            transition: transform 0.2s;
        }
        .report-card:hover {
            transform: translateY(-5px);
        }
        .star-rating .star {
            color: #e2e8f0;
            cursor: pointer;
        }
        .star-rating .star.active {
            color: #fbbf24;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 fixed h-full overflow-y-auto">
            <div class="p-4 flex items-center space-x-2 border-b border-blue-700">
                <img src="image\logoputih.png" alt="" class="rounded">
            </div>
            <nav class="p-4">
                <div class="mb-8">
                    <h2 class="text-xs uppercase text-blue-200 mb-4">Menu Utama</h2>
                    <ul>
                        <li class="mb-2">
                            <button id="dashboardBtn" class="w-full text-left py-2 px-3 bg-blue-700 rounded flex items-center">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                Dashboard
                            </button>
                        </li>
                        <li class="mb-2">
                            <button id="reportsBtn" class="w-full text-left py-2 px-3 hover:bg-blue-700 rounded flex items-center">
                                <i class="fas fa-file-alt mr-3"></i>
                                Laporan
                            </button>
                              
                              
                        </li>
                        <li class="mb-2">
                            <button id="newsBtn" class="w-full text-left py-2 px-3 hover:bg-blue-700 rounded flex items-center">
                                <i class="fas fa-newspaper mr-3"></i>
                                Berita
                            </button>
                        </li>
                        <li class="mb-2">
                            <button id="recapBtn" class="w-full text-left py-2 px-3 hover:bg-blue-700 rounded flex items-center">
                                <i class="fas fa-chart-bar mr-3"></i>
                                Rekap
                            </button>
                        </li>
                        <li class="mb-2">
                            <button id="reviewsBtn" class="w-full text-left py-2 px-3 hover:bg-blue-700 rounded flex items-center">
                                <i class="fas fa-star mr-3"></i>
                                Ulasan
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700">
                    <button id="logoutBtn" class="w-full text-left py-2 px-3 hover:bg-blue-700 rounded flex items-center">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content ml-64 flex-1">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center p-4">
                    <h1 class="text-2xl font-bold text-gray-800" id="pageTitle">Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                          <i class="fa-solid fa-user-tie" class="rounded-full"></i>    
                            <span class="font-medium">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content Area -->
            <main class="p-6">
                <!-- Dashboard Overview Section -->
                <section id="dashboardSection" class="block">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500">Total Laporan</p>
                                <h3 class="text-2xl font-bold"><?= array_sum($data) ?></h3>
                            </div>
                            <div class="bg-blue-100 p-4 rounded-full flex items-center justify-center w-14 h-14">
                                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                            </div>
                        </div>

                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500">Laporan Masuk</p>
                                    <h3 class="text-2xl font-bold"><?= $data['Laporan Masuk'] ?? 0 ?></h3>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <i class="fas fa-inbox text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500">Laporan Diterima</p>
                                    <h3 class="text-2xl font-bold"><?= $data['Laporan Diterima'] ?? 0 ?></h3>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500">Laporan Ditolak</p>
                                    <h3 class="text-2xl font-bold"><?= $data['Laporan Ditolak'] ?? 0 ?></h3>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <i class="fa-solid fa-x text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500">Laporan Selesai</p>
                                    <h3 class="text-2xl font-bold"><?= $data['Laporan Selesai'] ?? 0 ?></h3>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <i class="fas fa-flag-checkered text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Laporan Terbaru</h2>
                            </div>
                            <div class="space-y-4">
                                <?php if ($resultLaporanTerbaru && $resultLaporanTerbaru->num_rows > 0): ?>
                                  <?php while ($row = $resultLaporanTerbaru->fetch_assoc()): ?>
                                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded">
                                        <div class="bg-gray-100 p-2 rounded-full">
                                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium"><?= htmlspecialchars($row['nama']) ?></h4>
                                            <p class="text-sm text-gray-500"><?= htmlspecialchars($row['kategori']) ?></p>
                                        </div>
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Baru</span>
                                    </div>
                                  <?php endwhile; ?>
                                <?php else: ?>
                                  <p class="text-sm text-gray-400">Belum ada laporan baru.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Berita Terbaru</h2>
                                
                            </div>
                            <div class="space-y-4">
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                  <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded">
                                      <img src="image_berita/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar ilustrasi berita" class="rounded-md w-20 h-15 object-cover">
                                      <div class="flex-1">
                                        <h4 class="font-medium">
                                            <?= htmlspecialchars(substr($row['judul'], 0, 30)) . ''; ?>
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                        </p>
                                    </div>

                                  </div>
                              <?php endwhile; ?>
                        
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Reports Section -->
                <section id="reportsSection" class="hidden">
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="border-b border-gray-200">
                            <div class="flex">
                                <button id="incomingReportsTab" class="px-5 py-4 font-medium border-b-2 border-blue-600 text-blue-600">Laporan Masuk</button>
                                <button id="acceptedReportsTab" class="px-5 py-4 font-medium text-gray-500">Laporan Diterima</button>
                                <button id="rejectedReportsTab" class="px-5 py-4 font-medium text-gray-500">Laporan Ditolak</button>
                                <button id="completedReportsTab" class="px-5 py-4 font-medium text-gray-500">Laporan Selesai</button>
                                <button id="allReportsTab" class="px-5 py-4 font-medium text-gray-500">Semua Laporan</button>
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Incoming Reports Content -->
                          <div id="incomingReportsContent" class="space-y-4"> 
                          <?php
                            $query = "SELECT * FROM pengaduan WHERE status='Laporan Masuk'";  
                            $result = mysqli_query($koneksi, $query);

                            if($result && mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                $buktiPath = str_replace('\\', '/', $row['bukti']);
                          ?>
                            <div class="report-card bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                              <div class="flex justify-between items-start flex-wrap gap-3">
                                <div class="flex items-start space-x-4">
                          
                                  <div>
                                    <h3 class="text-lg font-bold">
                                    <?= htmlspecialchars($row['no_tiket']) ?>
                                  </h3>
                                    <p class="text-gray-500 text-xs mt-1">
                                      <i class="far fa-calendar-alt mr-1"></i> <?= htmlspecialchars($row['tanggal_pengaduan']) ?>
                                    </p>
                                    <p class="text-sm mt-2 break-words whitespace-normal-pre-line max"><?= nl2br(htmlspecialchars($row['isi_laporan'])) ?></p>
                                    
                                    <!-- Gambar hanya ditampilkan jika ada -->
                                    <?php if (!empty($row['bukti'])) : ?>
                                      <div class="mt-2">
                                        <img src="<?= htmlspecialchars($buktiPath) ?>" alt="Foto laporan" class="rounded-md w-full max-w-xs">
                                      </div>
                                    <?php endif; ?>

                                    <!-- Tombol tetap muncul untuk semua laporan -->
                                    <div class="mt-4 flex space-x-2">
                                      <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan'] ?>">
                                        <input type="hidden" name="status" value="Laporan Diterima">
                                        <button type="submit" class="approve-btn px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Terima</button>
                                      </form>
                                      <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan'] ?>">
                                        <input type="hidden" name="status" value="Laporan Ditolak">
                                        <button type="submit" class="reject-btn px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Tolak</button>
                                      </form>
                                      <button type="button"
                                        class="detail-btn px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 text-sm"
                                        data-no_tiket="<?= htmlspecialchars($row['no_tiket']) ?>"
                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                        data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                                        data-nohp="<?= htmlspecialchars($row['no_hp']) ?>"
                                        data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
                                        data-isi="<?= htmlspecialchars($row['isi_laporan']) ?>"
                                        data-bukti="<?= htmlspecialchars($row['bukti']) ?>">
                                        Detail
                                      </button>

                                    </div>

                                  </div>
                                </div>

                                <!-- Tombol tetap muncul untuk semua laporan -->
                                
                              </div>
                            </div>
                          <?php
                              } // end while
                            } else {
                              echo "<p class='text-gray-500'>Tidak ada laporan masuk.</p>";
                            }
                          ?>
                        </div>





                            <!-- Accepted Reports Content -->
                            <div id="acceptedReportsContent" class="hidden space-y-4">
                                <?php
                            $query = "SELECT * FROM pengaduan WHERE status='Laporan Diterima'";  
                            $result = mysqli_query($koneksi, $query);

                            if($result && mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                $buktiPath = str_replace('\\', '/', $row['bukti']);
                          ?>
                            <div class="report-card bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                              <div class="flex justify-between items-start flex-wrap gap-3">
                                <div class="flex items-start space-x-4">
                          
                                  <div>
                                    <h3 class="text-lg font-bold">
                                    <?= htmlspecialchars($row['no_tiket']) ?>
                                  </h3>
                                    <p class="text-gray-500 text-xs mt-1">
                                      <i class="far fa-calendar-alt mr-1"></i> <?= htmlspecialchars($row['tanggal_pengaduan']) ?>
                                    </p>
                                    <p class="text-sm mt-2 break-words whitespace-normal-pre-line max"><?= nl2br(htmlspecialchars($row['isi_laporan'])) ?></p>
                                    
                                    <!-- Gambar hanya ditampilkan jika ada -->
                                    <?php if (!empty($row['bukti'])) : ?>
                                      <div class="mt-2">
                                        <img src="<?= htmlspecialchars($buktiPath) ?>" alt="Foto laporan" class="rounded-md w-full max-w-xs">
                                      </div>
                                    <?php endif; ?>

                                    <!-- Tombol tetap muncul untuk semua laporan -->
                                    <div class="mt-4 flex space-x-2">
                                      <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan'] ?>">
                                        <input type="hidden" name="status" value="Laporan Selesai">
                                        <button type="submit" class="approve-btn px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Selesai</button>
                                      </form>

                                      <button type="button"
                                        class="detail-btn px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 text-sm"
                                        data-no_tiket="<?= htmlspecialchars($row['no_tiket']) ?>"
                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                        data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                                        data-nohp="<?= htmlspecialchars($row['no_hp']) ?>"
                                        data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
                                        data-isi="<?= htmlspecialchars($row['isi_laporan']) ?>"
                                        data-bukti="<?= htmlspecialchars($row['bukti']) ?>">
                                        Detail
                                      </button>

                                    </div>

                                  </div>
                                </div>

                                <!-- Tombol tetap muncul untuk semua laporan -->
                                
                              </div>
                            </div>
                          <?php
                              } // end while
                            } else {
                              echo "<p class='text-gray-500'>Tidak ada laporan masuk.</p>";
                            }
                          ?>
                            </div>

                            <!-- Rejected Reports Content -->
                            <div id="rejectedReportsContent" class="hidden space-y-4">
                                <?php
                            $query = "SELECT * FROM pengaduan WHERE status='Laporan Ditolak'";  
                            $result = mysqli_query($koneksi, $query);

                            if($result && mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                $buktiPath = str_replace('\\', '/', $row['bukti']);
                          ?>
                            <div class="report-card bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                              <div class="flex justify-between items-start flex-wrap gap-3">
                                <div class="flex items-start space-x-4">
                          
                                  <div>
                                    <h3 class="text-lg font-bold">
                                    <?= htmlspecialchars($row['no_tiket']) ?>
                                  </h3>
                                    <p class="text-gray-500 text-xs mt-1">
                                      <i class="far fa-calendar-alt mr-1"></i> <?= htmlspecialchars($row['tanggal_pengaduan']) ?>
                                    </p>
                                    <p class="text-sm mt-2 break-words whitespace-normal-pre-line max"><?= nl2br(htmlspecialchars($row['isi_laporan'])) ?></p>
                                    
                                    <!-- Gambar hanya ditampilkan jika ada -->
                                    <?php if (!empty($row['bukti'])) : ?>
                                      <div class="mt-2">
                                        <img src="<?= htmlspecialchars($buktiPath) ?>" alt="Foto laporan" class="rounded-md w-full max-w-xs">
                                      </div>
                                    <?php endif; ?>

                                    <!-- Tombol tetap muncul untuk semua laporan -->
                                    <div class="mt-4 flex space-x-2">
                                      <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan'] ?>">
                                        <input type="hidden" name="status" value="Laporan Diterima">
                                        <button type="submit" class="approve-btn px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Terima</button>
                                      </form>
                                      
                                      <button type="button"
                                        class="detail-btn px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 text-sm"
                                        data-no_tiket="<?= htmlspecialchars($row['no_tiket']) ?>"
                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                        data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                                        data-nohp="<?= htmlspecialchars($row['no_hp']) ?>"
                                        data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
                                        data-isi="<?= htmlspecialchars($row['isi_laporan']) ?>"
                                        data-bukti="<?= htmlspecialchars($row['bukti']) ?>">
                                        Detail
                                      </button>

                                    </div>

                                  </div>
                                </div>

                                <!-- Tombol tetap muncul untuk semua laporan -->
                                
                              </div>
                            </div>
                          <?php
                              } // end while
                            } else {
                              echo "<p class='text-gray-500'>Tidak ada laporan masuk.</p>";
                            }
                          ?>>
                            </div>

                            <!-- Completed Reports Content -->
                            <div id="completedReportsContent" class="hidden space-y-4">
                                <?php
                            $query = "SELECT * FROM pengaduan WHERE status='Laporan Selesai'";  
                            $result = mysqli_query($koneksi, $query);

                            if($result && mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                $buktiPath = str_replace('\\', '/', $row['bukti']);
                          ?>
                            <div class="report-card bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                              <div class="flex justify-between items-start flex-wrap gap-3">
                                <div class="flex items-start space-x-4">
                          
                                  <div>
                                    <h3 class="text-lg font-bold">
                                    <?= htmlspecialchars($row['no_tiket']) ?>
                                  </h3>
                                    <p class="text-gray-500 text-xs mt-1">
                                      <i class="far fa-calendar-alt mr-1"></i> <?= htmlspecialchars($row['tanggal_pengaduan']) ?>
                                    </p>
                                    <div class="text-sm mt-2 break-words whitespace-pre-line max-w-xl">
                                      <?= nl2br(htmlspecialchars($row['isi_laporan'])) ?>
                                    </div>

                                    
                                    <!-- Gambar hanya ditampilkan jika ada -->
                                    <?php if (!empty($row['bukti'])) : ?>
                                      <div class="mt-2">
                                        <img src="<?= htmlspecialchars($buktiPath) ?>" alt="Foto laporan" class="rounded-md w-full max-w-xs">
                                      </div>
                                    <?php endif; ?>

                                    <!-- Tombol tetap muncul untuk semua laporan -->
                                    <div class="mt-4 flex space-x-2">
                                      <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan'] ?>">
                                        <input type="hidden" name="status" value="Laporan Diterima">
                                        <button type="submit" class="approve-btn px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Terima</button>
                                      </form>
                                      <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan'] ?>">
                                        <input type="hidden" name="status" value="Laporan Ditolak">
                                        <button type="submit" class="reject-btn px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Tolak</button>
                                      </form>
                                      <button type="button"
                                        class="detail-btn px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 text-sm"
                                        data-no_tiket="<?= htmlspecialchars($row['no_tiket']) ?>"
                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                        data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                                        data-nohp="<?= htmlspecialchars($row['no_hp']) ?>"
                                        data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
                                        data-isi="<?= htmlspecialchars($row['isi_laporan']) ?>"
                                        data-bukti="<?= htmlspecialchars($row['bukti']) ?>">
                                        Detail
                                      </button>

                                    </div>

                                  </div>
                                </div>

                                <!-- Tombol tetap muncul untuk semua laporan -->
                                
                              </div>
                            </div>
                          <?php
                              } // end while
                            } else {
                              echo "<p class='text-gray-500'>Tidak ada laporan masuk.</p>";
                            }
                          ?>
                            </div>
                            
                                <!--Semua Laporan-->
                            <div id="allReportsContent" class="hidden space-y-4">
                            <div class="overflow-x-auto">
                              <table class="min-w-full divide-y divide-gray-200">
                                  <thead class="bg-gray-50">
                                      <tr>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Tiket</th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasus</th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Kejadian</th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isi Laporan</th>
                                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                      </tr>
                                  </thead>
                                  <tbody class="bg-white divide-y divide-gray-200">
                                  <?php
                                  $query = "SELECT * FROM pengaduan";  
                                  $result = mysqli_query($koneksi, $query);

                                  if ($result && mysqli_num_rows($result) > 0) {
                                      while ($row = mysqli_fetch_assoc($result)) {
                                          echo "<tr>";
                                          echo "<td class='px-6 py-4 text-sm text-gray-500'>{$row['no_tiket']}</td>";
                                          echo "<td class='px-6 py-4 text-sm text-gray-500'>{$row['nama']}</td>";
                                          echo "<td class='px-6 py-4 max-w-[300px] text-sm text-gray-500 break-words whitespace-normal'>{$row['alamat']}</td>"; // ✅ WRAP aktif
                                          echo "<td class='px-6 py-4 text-sm text-gray-500 whitespace-nowrap'>{$row['kategori']}</td>";
                                          echo "<td class='px-6 py-4 text-sm text-gray-500 whitespace-nowerap'>{$row['tempat_kejadian']}</td>";
                                          echo "<td class='px-6 py-4 max-w-[300px] text-sm text-gray-500 break-words whitespace-normal'>{$row['isi_laporan']}</td>"; // ✅ WRAP aktif
                                          echo "<td class='px-6 py-4'>";
                                          echo "<span class='px-2 py-1 text-xs whitespace-nowrap rounded-full bg-purple-100 text-purple-800'>{$row['status']}</span>";
                                          echo "</td>";
                                          echo "</tr>";
                                      }
                                  } else {
                                      echo "<tr><td colspan='7' class='px-6 py-4 text-center text-gray-500'>Tidak ada data ditemukan.</td></tr>";
                                  }
                                  ?>
                                  </tbody>

                              </table>
                            </div>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- Add News Section -->
                <section id="newsSection" class="hidden">
                  <div class="bg-white rounded-lg shadow overflow-hidden">
                      <div class="px-6 py-4 border-b border-gray-200">
                          <h2 class="text-xl font-bold">Tambah Berita Baru</h2>
                      </div>
                      <div class="p-6">
                          <form method="POST" action="" enctype="multipart/form-data">
                              <div class="mb-4">
                                  <label class="block text-sm font-medium text-gray-700 mb-1">Judul Berita</label>
                                  <input type="text" name="judul" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Masukkan judul berita" required>
                              </div>
                              <div class="mb-4">
                                  <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berita</label>
                                  <input type="date" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                              </div>
                              <div class="mb-4">
                                  <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                                  <input type="text" name="penulis" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Nama penulis" required>
                              </div>
                              <div class="mb-4">
                                  <label class="block text-sm font-medium text-gray-700 mb-1">Isi Berita</label>
                                  <textarea name="isi" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Tulis isi berita di sini" required></textarea>
                              </div>

                              <!-- Upload Gambar -->
                              <div class="mb-6">
                                  <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Berita</label>
                                  <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                      <div class="space-y-1 text-center">
                                          <div class="flex items-center justify-center text-sm text-gray-600">
                                              <label for="newsImage" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                  <span>Unggah file</span>
                                                  <input id="newsImage" name="image" type="file" accept="image/*" class="sr-only" onchange="previewImage(event)" required>
                                              </label>
                                              <p class="pl-2">atau drag and drop</p>
                                          </div>
                                          <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 5MB</p>
                                      </div>
                                  </div>

                                  <div id="previewContainer" class="mt-4 hidden">
                                      <p class="text-sm font-medium text-gray-700 mb-2">Pratinjau Gambar:</p>
                                      <img id="imagePreview" src="#" alt="Preview" class="max-w-xs rounded-md shadow-sm">
                                  </div>
                              </div>

                              <div class="flex justify-end">
                                  <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                      Batal
                                  </button>
                                  <button type="submit" name="tambah_berita" class="ml-3 py-2 px-4 border border-transparent rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                      Simpan Berita
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
              </section>

                <!-- Recap Section -->
                <section id="recapSection" class="hidden">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-bold">Rekapitulasi Laporan</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                  <!-- Dropdown Bulan -->
                                  <div class="mb-4 md:mb-0">
                                      <label for="monthSelect" class="block text-sm font-medium text-gray-700 mb-1">Pilih Bulan</label>
                                      <select id="monthSelect" name="bulan" class="w-full md:w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                          <option value="">Semua Bulan</option>
                                          <?php
                                              $nama_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                                                          "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                                              $bulan_terpilih = isset($_GET['bulan']) ? intval($_GET['bulan']) : ''; // ambil bulan dari query string

                                              for ($i = 1; $i <= 12; $i++) {
                                                  $selected = ($bulan_terpilih == $i) ? "selected" : "";
                                                  echo "<option value='$i' $selected>{$nama_bulan[$i - 1]}</option>";
                                              }
                                          ?>
                                      </select>
                                  </div>

                                  <!-- Dropdown Tahun -->
                                  <div class="mb-4 md:mb-0">
                                      <label for="yearSelect" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tahun</label>
                                      <select id="yearSelect" name="tahun" class="w-full md:w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                          <option value="">Pilih Tahun</option>
                                          <option value="2025">2025</option>
                                          <option value="2026">2026</option>
                                          <option value="2027">2027</option>
                                          <!-- Tambahkan lebih banyak tahun jika diperlukan -->
                                      </select>
                                  </div>

                                  <!-- Tombol Unduh PDF -->
                                  <button id="downloadPdfBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                      <i class="fas fa-file-pdf mr-2"></i> Unduh PDF
                                  </button>
                              </div>

                                

                            </div>
                            
                            

                        </div>
                    </div>
                </section>

                <!-- Reviews Section -->
                <section id="reviewsSection" class="hidden">
                    <div class="bg-white rounded-lg shadow overflow-hidden">

                        <div class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">Rating</th>
                    <th class="border px-4 py-2">Ulasan</th>
                    <th class="border px-4 py-2">Tanggal Ulasan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultUlasan->num_rows > 0): ?>
                  <?php while ($row = $resultUlasan->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="border px-4 py-2"><?= htmlspecialchars($row['rating']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($row['ulasan']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($row['tanggal_ulasan']) ?></td>
                    </tr>
                 <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-red-500 py-4">Tidak ada data ulasan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
                </div>
                            </div>
                      
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

<!--modal-detail-->
<!-- MODAL DETAIL -->
<div id="detailModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center">
  <div class="bg-white rounded-lg p-6 w-full max-w-lg relative">
    <button class="close absolute top-2 right-2 text-gray-600 hover:text-black">&times;</button>
    <h2 class="text-lg font-semibold mb-4">Detail Laporan</h2>
    <div class="space-y-2 text-sm max-h-[70vh] overflow-y-auto">
      <p><strong>No Tiket:</strong> <span id="detailTiket"></span></p>
      <p><strong>Nama:</strong> <span id="detailNama"></span></p>
      <p><strong>Alamat:</strong> <span id="detailAlamat"></span></p>
      <p><strong>No HP:</strong> <span id="detailNoHp"></span></p>
      <p><strong>Kasus:</strong> <span id="detailKategori"></span></p>
      <p><strong>Isi Laporan:</strong></p>
      <p class="bg-gray-100 p-2 rounded"><span id="detailIsi"></span></p>
      <div>
        <strong>Bukti:</strong><br>
        <img id="detailBukti" src="" alt="Bukti Laporan" class="rounded max-w-full mt-2">
      </div>
    </div>
  </div>
</div>


   <script>
document.addEventListener('DOMContentLoaded', function () {
    // Submenu Laporan
    const btn = document.getElementById('reportsBtn');
    const submenu = document.getElementById('reportsSubmenu');

    if (btn && submenu) {
        btn.addEventListener('click', function () {
            submenu.classList.toggle('hidden');
        });
    }

    // Navigation Logic
    const sections = {
        dashboardBtn: 'dashboardSection',
        reportsBtn: 'reportsSection',
        newsBtn: 'newsSection',
        recapBtn: 'recapSection',
        reviewsBtn: 'reviewsSection'
    };

    function showSection(sectionId) {
        document.querySelectorAll('main section').forEach(section => {
            section.classList.add('hidden');
        });
        document.getElementById(sectionId).classList.remove('hidden');
        document.getElementById('pageTitle').textContent =
            document.querySelector(`[id="${Object.keys(sections).find(key => sections[key] === sectionId)}"]`).textContent.trim();
    }

    Object.entries(sections).forEach(([btnId, sectionId]) => {
        const button = document.getElementById(btnId);
        if (button) {
            button.addEventListener('click', () => {
                showSection(sectionId);
                document.querySelectorAll('nav button').forEach(btn => {
                    btn.classList.remove('bg-blue-700');
                    btn.classList.add('hover:bg-blue-700');
                });
                button.classList.add('bg-blue-700');
                button.classList.remove('hover:bg-blue-700');
            });
        }
    });

    showSection('dashboardSection');
    document.getElementById('dashboardBtn')?.classList.add('bg-blue-700');
    document.getElementById('dashboardBtn')?.classList.remove('hover:bg-blue-700');

    // Tabs Laporan
    const reportTabs = {
        incomingReportsTab: 'incomingReportsContent',
        acceptedReportsTab: 'acceptedReportsContent',
        rejectedReportsTab: 'rejectedReportsContent',
        completedReportsTab: 'completedReportsContent',
        allReportsTab: 'allReportsContent'
    };

    function showReportContent(tabId) {
        document.querySelectorAll('#reportsSection > div > div > div[id$="Content"]').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById(reportTabs[tabId])?.classList.remove('hidden');

        document.querySelectorAll('#reportsSection > div > div > button').forEach(tab => {
            tab.classList.remove('border-blue-600', 'text-blue-600');
            tab.classList.add('text-gray-500');
        });
        document.getElementById(tabId)?.classList.add('border-blue-600', 'text-blue-600');
        document.getElementById(tabId)?.classList.remove('text-gray-500');
    }

    Object.keys(reportTabs).forEach(tabId => {
        document.getElementById(tabId)?.addEventListener('click', () => {
            showReportContent(tabId);
        });
    });

    document.getElementById('incomingReportsTab')?.classList.add('border-blue-600', 'text-blue-600');
    document.getElementById('incomingReportsTab')?.classList.remove('text-gray-500');
    showReportContent('incomingReportsTab');

    // Konfirmasi Tombol Aksi
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('approve-btn') || e.target.classList.contains('reject-btn')) {
            if (!confirm('Apakah Anda yakin dengan tindakan ini?')) {
                e.preventDefault();
            }
        }

        if (e.target.classList.contains('complete-btn')) {
            const reportCard = e.target.closest('.report-card');
            if (confirm('Apakah Anda yakin ingin menandai laporan ini sebagai selesai?')) {
                reportCard.style.display = 'none';
                alert('Laporan telah ditandai sebagai selesai');
            }
        }
    });

    // Gambar Berita
    const newsImageInput = document.getElementById('newsImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('previewContainer');

    newsImageInput?.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    // Logout
    document.getElementById('logoutBtn')?.addEventListener('click', function () {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            alert('Anda telah logout');
            window.location.href = 'login.html';
        }
    });

    // Download PDF
    document.getElementById('downloadPdfBtn')?.addEventListener('click', function () {
        const month = document.getElementById('monthSelect').value;
        const year = document.getElementById('yearSelect').value;
        if (!month || !year) {
            alert('Harap pilih bulan dan tahun terlebih dahulu!');
            return;
        }
        window.location.href = `download.php?bulan=${month}&tahun=${year}`;
    });

    // Rating bintang
    document.querySelectorAll('.star-rating .star').forEach(star => {
        star.addEventListener('mouseover', function () {
            const rating = parseInt(this.getAttribute('data-rating'));
            const stars = this.parentElement.querySelectorAll('.star');
            stars.forEach((s, index) => {
                if (index < rating) s.classList.add('active');
                else s.classList.remove('active');
            });
        });
    });

    // Preview gambar tambahan
    window.previewImage = function (event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('previewContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});

//Modal Detail
  const modal = document.getElementById("detailModal");
  const closeBtn = document.querySelector(".close");

  document.querySelectorAll(".detail-btn").forEach(button => {
    button.addEventListener("click", function () {
      // Ambil data dari atribut tombol
      const tiket = this.dataset.no_tiket;
      const nama = this.dataset.nama;
      const alamat = this.dataset.alamat;
      const noHp = this.dataset.nohp;
      const kategori = this.dataset.kategori;
      const isi = this.dataset.isi;
      const bukti = this.dataset.bukti;

      // Isi ke modal
      document.getElementById("detailTiket").textContent = tiket || "-";
      document.getElementById("detailNama").textContent = nama || "-";
      document.getElementById("detailAlamat").textContent = alamat || "-";
      document.getElementById("detailNoHp").textContent = noHp || "-";
      document.getElementById("detailKategori").textContent = kategori || "-";
      document.getElementById("detailIsi").textContent = isi || "-";
      document.getElementById("detailBukti").src = bukti ? bukti.replaceAll('\\', '/') : "";

      // Tampilkan modal
      modal.classList.remove("hidden");
      modal.classList.add("flex");
    });
  });

  // Tutup modal saat klik tombol close (x)
  closeBtn.onclick = function () {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
  };

  // Tutup modal saat klik di luar modal box
  window.onclick = function (event) {
    if (event.target === modal) {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
    }
  };

</script>



</body>
</html>
