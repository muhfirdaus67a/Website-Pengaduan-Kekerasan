<?php
$koneksi = new mysqli("localhost", "root", "", "pkl");

if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}
    $query = "SELECT * FROM berita ORDER BY id_berita DESC LIMIT 3";
    $result = mysqli_query($koneksi, $query);

    // Berita untuk list 3 terbaru
    $queryTerbaru = "SELECT * FROM berita ORDER BY id_berita DESC LIMIT 3";
    $resultTerbaru = mysqli_query($koneksi, $queryTerbaru);

    // Untuk keperluan detail (opsional, jika memang butuh pakai id dari URL)
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        $queryDetail = "SELECT * FROM berita WHERE id_berita = $id";
        $resultDetail = mysqli_query($koneksi, $queryDetail);
        $berita = mysqli_fetch_assoc($resultDetail);
}

function formatTanggalIndoLengkap($tanggalWaktu) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $parts = explode(' ', $tanggalWaktu);
    $tanggal = $parts[0];
    $jam = isset($parts[1]) ? $parts[1] : ''; // jika tidak ada waktu, biarkan kosong

    $pecah = explode('-', $tanggal);
    $tanggalFormatted = intval($pecah[2]) . ' ' . $bulan[intval($pecah[1])] . ' ' . $pecah[0];

    return $jam ? $tanggalFormatted . ' ' . $jam : $tanggalFormatted;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Pengaduan Kekerasan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .nav-link.active {
            border-bottom: 3px solid black;
            font-weight: 600;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="fixed top-0 left-0 w-full z-50 bg-white shadow-md">

        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="image\hitam.jpg" alt="Logo Sistem Laporan Kekerasan dengan simbol perisai berwarna biru" class="h-12 w-26">
                <h1 class="text-2xl font-bold text-dark">Sistem Laporan Kekerasan</h1>
            </div>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="#dashboard" class="nav-link active py-2 px-1 text-dark  transition">Dashboard</a></li>
                    <li><a href="#pendaftaran" class="nav-link py-2 px-1 text-dark transition">Pendaftaran</a></li>
                    <li><a href="#cek-tiket" class="nav-link py-2 px-1 text-dark transition">Cek Tiket</a></li>
                    <li><a href="#about" class="nav-link py-2 px-1 text-dark transition">Tentang Kami</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 mt-20">
        <!-- Dashboard Section -->
        <section id="dashboard" class="mb-16 scroll-mt-32">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang di Sistem Laporan Kekerasan</h2>
                    
                </div>
                <p class="text-gray-600 mb-6">
                    Sistem ini membantu Anda melaporkan dan melacak kasus kekerasan secara online. 
                    Silahkan gunakan menu di atas untuk navigasi website kami.
                </p>
                
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <!-- Panduan Box -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800">Panduan Pendaftaran</h3>
                        <ol class="list-decimal list-inside text-gray-600 space-y-2">
                            <li>Klik menu Pendaftaran</li>
                            <li>Isi formulir dengan lengkap</li>
                            <li>Upload bukti pendukung</li>
                            <li>Submit formulir</li>
                            <li>Simpan nomor tiket Anda</li>
                        </ol>
                        <a href="#pendaftaran" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Mulai Pendaftaran</a>
                    </div>
                    
                    <!-- Panduan Cek Tiket -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800">Panduan Cek Tiket</h3>
                        <ol class="list-decimal list-inside text-gray-600 space-y-2">
                            <li>Klik menu Cek Tiket</li>
                            <li>Masukkan nomor tiket Anda</li>
                            <li>Klik tombol Lacak</li>
                            <li>Lihat status laporan Anda</li>
                        </ol>
                        <a href="#cek-tiket" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Cek Tiket</a>
                    </div>
                    
                    <!-- Kontak Darurat -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800">Kontak Darurat</h3>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-center"><span class="font-medium">Polisi:</span> 110</li>
                            <li class="flex items-center"><span class="font-medium">RS:</span> 118/119</li>
                            <li class="flex items-center"><span class="font-medium">Hotline KDRT:</span> 021-500535</li>
                            <li class="flex items-center"><span class="font-medium">Komnas Perempuan:</span> 021-3903963</li>
                        </ul>
                        <button class="mt-4 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Situasi Darurat</button>
                    </div>
                </div>
                
                <!-- Berita Terbaru -->
                <div>
    <h3 class="text-xl font-semibold mb-4 text-gray-800">Berita Terkini</h3>
    <div class="grid md:grid-cols-3 gap-6">
        <?php while ($row = mysqli_fetch_assoc($resultTerbaru)) : ?>
            <?php
                // Ambil kalimat pertama dari isi berita
                        $words = explode(' ', strip_tags($row['isi']));
        $isi_pendek = implode(' ', array_slice($words, 0, 15));
                
            ?>
            <div class="news-card bg-white rounded-lg shadow-md overflow-hidden transition duration-300">
                <img src="image_berita/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Demonstrasi masyarakat menentang kekerasan dengan spanduk besar di jalanan" class="w-full h-48 object-cover">
                <div class="p-4">
                    <span class="text-xs text-blue-600 font-medium">
                        <?= formatTanggalIndoLengkap($row['tanggal']); ?>
                    </span>
                    <h4 class="text-lg font-semibold mt-2 mb-2 text-gray-800"><?php echo htmlspecialchars($row['judul']); ?></h4>
                    <p class="text-gray-600 text-sm">
                        <?= htmlspecialchars($isi_pendek); ?>...
                    </p>
                    <a href="detail_berita.php?id=<?php echo $row['id_berita']; ?>" class="text-blue-600 text-sm font-medium mt-3 inline-block hover:underline">Baca Selengkapnya</a>
                </div>
            </div>
        <?php endwhile; ?>       
    </div>
</div>

            </div>
        </section>

        <!-- Pendaftaran Section -->
        <section id="pendaftaran" class="mb-16 scroll-mt-32">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Formulir Pendaftaran Laporan</h2>
                
                <form action="proses_pengaduan.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-gray-700 font-medium mb/2">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="w-full form-input px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label for="alamat" class="block text-gray-700 font-medium mb-2">Alamat</label>
                            <input type="text" id="alamat" name="alamat" class="w-full form-input px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="no_hp" class="block text-gray-700 font-medium mb-2">Nomor HP</label>
                            <input type="tel" id="no_hp" name="no_hp" required class="w-full form-input px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label for="kategori" class="block text-gray-700 font-medium mb-2">Jenis Kekerasan</label>
                            <select id="kategori" name="kategori" class="w-full form-input px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition">
                                <option value="">Pilih Jenis Kekerasan</option>
                                <option value="Kekerasan Fisik">Kekerasan Fisik</option>
                                <option value="Kekerasan Psikis">Kekerasan Psikis</option>
                                <option value="Kekerasan Seksual">Kekerasan Seksual</option>
                                <option value="Kekerasan Ekonomi">Kekerasan Ekonomi</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="tempat_kejadian" class="block text-gray-700 font-medium mb-2">Lokasi Kejadian</label>
                        <textarea id="tempat_kejadian" name="tempat_kejadian" rows="3" class="w-full form-input px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition"></textarea>
                    </div>
                    
                    <div>
                        <label for="laporan" class="block text-gray-700 font-medium mb-2">Deskripsi Kejadian</label>
                        <textarea id="laporan" name="laporan" rows="5" class="w-full form-input px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Upload Bukti Pendukung</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mt-2 text-gray-600">Seret dan lepas file di sini atau klik untuk memilih</p>
                        <input type="file" id="bukti" name="bukti" class="hidden" onchange="tampilkanNamaFile(event)">
                        <button type="button" onclick="document.getElementById('bukti').click()" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                            Pilih File
                        </button>
                        <p id="file-name" class="mt-2 text-sm text-gray-500"></p>
                    </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="persetujuan" name="persetujuan" required class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="persetujuan" class="ml-2 text-gray-700">Saya menyatakan bahwa informasi yang saya berikan adalah benar</label>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition font-medium">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Cek Tiket Section -->
        <section id="cek-tiket" class="mb-16 scroll-mt-32">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Cek Status Tiket</h2>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <form id="ticketForm" class="space-y-6">
                            <div>
                                <label for="no_tiket" class="block text-gray-700 font-medium mb-2">Nomor Tiket</label>
                                <input type="text" id="no_tiket" name="no_tiket" required placeholder="Masukkan nomor tiket Anda" class="w-full form-input px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 transition">
                            </div>
                                                       
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-md hover:bg-blue-700 transition font-medium">Lacak Tiket</button>
                        </form>
                    </div>
                    
                    <div id="ticketResult" class="hidden">
                        <div class="border border-gray-200 rounded-lg p-6 shadow-sm">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Hasil Pelacakan Tiket</h3>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-gray-600">Nomor Tiket:</span>
                                    <span id="hasil_no_tiket" class="font-medium ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Status:</span>
                                    <span id="status" class="px-3 py-1 rounded-full text-xs font-medium ml-2 bg-yellow-100 text-yellow-800">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Tanggal Dibuat:</span>
                                    <span id="tanggal_pengaduan" class="font-medium ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Kasus:</span>
                                    <span id="hasil_kategori" class="font-medium ml-2">-</span>
                                </div>
                                
                                <div id="form-ulasan" class="hidden mt-6 border p-4 rounded bg-white shadow">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Beri Ulasan</h3>
                                    <form action="simpan_ulasan.php" method="POST">
                                        <input type="hidden" id="id_pengaduan_ulasan" name="id_pengaduan">
                                        
                                        <label for="rating" class="block mb-2 text-sm font-medium">Rating:</label>
                                        <select name="rating" id="rating" class="form-input mb-4">
                                        <option value="5">★★★★★</option>
                                        <option value="4">★★★★</option>
                                        <option value="3">★★★</option>
                                        <option value="2">★★</option>
                                        <option value="1">★</option>
                                        </select>

                                        <label for="ulasan" class="block mb-2 text-sm font-medium">Ulasan:</label>
                                        <textarea name="ulasan" id="ulasan" rows="3" class="form-input w-full mb-4"></textarea>

                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Ulasan</button>
                                    </form>
                                    </div>


                            </div>
                        </div>
                    </div>
                    
                    <div id="ticketPlaceholder" class="flex items-center justify-center bg-gray-50 rounded-lg h-full">
                        <div class="text-center p-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-700 mt-4">Masukkan Nomor Tiket</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="mb-16 scroll-mt-32">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Tentang Kami</h2>
                
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <img src="image\kantor.jpg"  alt="Tim profesional kami sedang bekerja di kantor yang modern dengan peralatan komputer lengkap" class="w-full rounded-lg">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Visi dan Misi</h3>
                        <p class="text-gray-600 mb-4">
                            Sistem Laporan Kekerasan ini dibangun dengan tujuan memberikan solusi bagi masyarakat untuk melaporkan berbagai bentuk kekerasan secara mudah, aman, dan terjamin kerahasiaannya.
                        </p>
                        <p class="text-gray-600 mb-4">
                            Kami berkomitmen untuk memberikan respon cepat dan penanganan profesional terhadap setiap laporan yang masuk melalui sistem ini.
                        </p>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r">
                            <p class="text-blue-700 font-medium">"Setiap korban kekerasan berhak mendapatkan perlindungan dan keadilan."</p>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Ulasan</h3>
                <?php
                $queryUlasan = "SELECT rating, ulasan, tanggal_ulasan FROM ulasan ORDER BY tanggal_ulasan DESC LIMIT 3";
                $resultUlasan = mysqli_query($koneksi, $queryUlasan);
                  ?>


                <div class="grid md:grid-cols-3 gap-6">
                    <?php while ($row = mysqli_fetch_assoc($resultUlasan)) : ?>
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm text-center">
        
             <i class="fa fa-user-circle text-5xl text-gray-500 " class="w-24 h-24 rounded-full mx-auto mb-4" aria-hidden="true"></i>
        <h4 class="text-lg font-semibold text-gray-800">
            <?= str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']); ?>
        </h4>
        <p class="text-blue-600 text-sm mb-2">
            <?= date('d M Y', strtotime($row['tanggal_ulasan'])); ?>
        </p>
        <p class="text-gray-600 text-sm">
            <?= htmlspecialchars($row['ulasan']); ?>
        </p>
    </div>
<?php endwhile; ?>
                </div>
                
                <!-- ME US -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Kontak</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-800 mb-2">Dinas Pemberdaayaan Perempuan dan Perlinduangan Anak Kabupaten Jember</h4>
                            <p class="text-gray-600">Jl. Jawa No.51 Tegal Boto Lor Sumbersari Kec. Sumbersari Kabupaten Jember Jawa Timur 68121</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 mb-2">Hubungi Kami</h4>
                            <ul class="text-gray-600 space-y-1">
                                <li><span class="font-medium">Telepon:</span> (0331) 422103</li>
                                <li><span class="font-medium">Email:</span> dpppakb@jemberkab.go.id</li>
                                <li><span class="font-medium">Jam Operasional:</span> Senin-Jumat, 08:00-16:00</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-4">
                <div class="mx-auto">
                    <h3 class="text-lg font-semibold mb-4">Sistem Laporan Kekerasan</h3>
                    <p class="text-gray-400 text-sm">Platform resmi untuk melaporkan dan melacak kasus kekerasan dengan mudah dan aman.</p>
                </div>
                <div class="mx-auto text-center">
                    <h3 class="text-lg font-semibold mb-4">Menu</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#dashboard" class="hover:text-white transition">Dashboard</a></li>
                        <li><a href="#pendaftaran" class="hover:text-white transition">Pendaftaran</a></li>
                        <li><a href="#cek-tiket" class="hover:text-white transition">Cek Tiket</a></li>
                        <li><a href="#about" class="hover:text-white transition">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="mx-auto">
                <h3 class="text-lg font-semibold mb-4 text-center">Sosial Media</h3>
                    <div class="flex space-x-4">
                        
                        <!-- Instagram -->
                        <a href="#" class="bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:scale-110 transition">
                        <i class="fab fa-instagram text-xl"></i>
                        </a>

                        <!-- YouTube -->
                        <a href="#" class="bg-red-600 hover:bg-red-700 text-white w-10 h-10 rounded-full flex items-center justify-center hover:scale-110 transition">
                        <i class="fab fa-youtube text-xl"></i>
                        </a>

                        <!-- TikTok -->
                        <a href="#" class="bg-black hover:bg-gray-800 text-white w-10 h-10 rounded-full flex items-center justify-center hover:scale-110 transition">
                        <i class="fab fa-tiktok text-xl"></i>
                        </a>
                        
                    </div>
                    </div>

            </div>

            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>© 2025 Sistem Laporan Kekerasan.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Navigation active state
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
           
            
            // Form submission for ticket check
            const ticketForm = document.getElementById('ticketForm');
            ticketForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const ticketNumber = document.getElementById('no_tiket').value;

                if (!ticketNumber) {
                    alert('Silakan masukkan nomor tiket');
                    return;
                }

                fetch('cek_tiket.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'no_tiket=' + encodeURIComponent(ticketNumber)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;
                        document.getElementById('hasil_no_tiket').textContent = data.no_tiket;
                        document.getElementById('status').textContent = data.status || 'Belum Ditangani';
                        document.getElementById('tanggal_pengaduan').textContent = data.tanggal_pengaduan;
                        document.getElementById('hasil_kategori').textContent = data.kategori;
                        if (data.status === "Laporan Selesai") {
                            // Periksa via AJAX apakah sudah pernah beri ulasan
                            fetch('cek_ulasan.php?id_pengaduan=' + encodeURIComponent(data.id_pengaduan))
                                .then(response => response.json())
                                .then(res => {
                                    if (!res.sudah_ulas) {
                                        document.getElementById('form-ulasan').classList.remove('hidden');
                                        document.getElementById('id_pengaduan_ulasan').value = data.id_pengaduan;
                                        
                                    }
                                });
                        }

                        document.getElementById('ticketPlaceholder').classList.add('hidden');
                        document.getElementById('ticketResult').classList.remove('hidden');
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mencari tiket.');
                });
            });

            
            // Print ticket button
            document.getElementById('printTicket').addEventListener('click', function() {
                alert('Fitur cetak akan membuka dokumen tiket dalam format PDF untuk dicetak.');
            });
            
            // Ask support button
            document.getElementById('askSupport').addEventListener('click', function() {
                alert('Tim dukungan kami akan menghubungi Anda segera. Mohon pastikan email dan nomor telepon Anda aktif.');
            });
        });

        function tampilkanNamaFile(event) {
        const input = event.target;
        const fileNameDisplay = document.getElementById('file-name');

        // Ambil elemen-elemen yang ingin disembunyikan
        const svgIcon = input.closest('div').querySelector('svg');
        const uploadText = input.closest('div').querySelector('p');

        if (input.files.length > 0) {
            fileNameDisplay.textContent = `File dipilih: ${input.files[0].name}`;

            // Sembunyikan ikon dan teks
            if (svgIcon) svgIcon.style.display = 'none';
            if (uploadText) uploadText.style.display = 'none';
        } else {
            fileNameDisplay.textContent = '';

            // Tampilkan lagi jika file dibatalkan
            if (svgIcon) svgIcon.style.display = 'block';
            if (uploadText) uploadText.style.display = 'block';
        }
    }
        //submite file lapor
            
    </script>
</body>
</html>

