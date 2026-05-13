/*
 Navicat Premium Data Transfer

 Source Server         : dp3a
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : pkl

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 08/07/2025 08:57:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for berita
-- ----------------------------
DROP TABLE IF EXISTS `berita`;
CREATE TABLE `berita`  (
  `id_berita` int(11) NOT NULL AUTO_INCREMENT,
  `penulis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_berita`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of berita
-- ----------------------------
INSERT INTO `berita` VALUES (29, 'DP3AKB', 'Strategi Implementasi dan Launching Program Berani II di Jawa Timur ', '2025-07-06', 'u.jpg', 'Bidang Perlindungan Anak Dinas Pemberdayaan Perempuan Perlindungan Anak dan Keluarga Berencana DPPPAKB Kabupaten Jember melaksanakan giat menghadiri undangan LPA Jatim dalam kegiatan Lokarya Penyamaan Persepsi, Strategi Implementasi dan Launching Program Berani II di Jawa Timur pada hari Selasa-Kamis tanggal 23-25 April 2024 di Hotel Aria Centra Surabaya.\r\n\r\nKegiatan ini diikuti oleh :\r\n\r\n1. Bappeda Provinsi Jawa Timur\r\n\r\n2. DP3AK Provinsi Jawa Timur\r\n\r\n3. Akademisi\r\n\r\n4. Tim LPA Jatim\r\n\r\n5. Bappeda Kabupaten Jember dan Malang\r\n\r\n6. DP3AKB Kabupaten Jember dan Malang\r\n\r\n7. Dinas Pendidikan Kabupaten Jember dan Malang\r\n\r\n8. DPMD Kabupaten Jember dan Malang\r\n\r\n9. Dinas Kesehatan Kabupaten Jember dan Malang\r\n10. Pengadilan Agama Jember dan Malang\r\n\r\n11. Kemenag Jember dan Malang\r\n\r\n12. TP PKK Kabupaten Jember dan Malang\r\n\r\n13. Puspaga\r\n\r\n14. Dinas Sosial Kabupaten Jember dan Malang\r\n\r\n15. Forum Anak Jember Dan Forum Anak Malang\r\n\r\n16. Forum Anak Jawa Timur\r\n\r\nKegiatan ini diawali dengan Sambutan oleh Pimpinan LPA Jatim bahwa tujuan kegiatan sela');
INSERT INTO `berita` VALUES (30, 'DP3AKB', 'Berani Laporkan Kekerasan terhadap Perempuan dan Anak, Pemerintah Ajak Masyarakat Peduli!', '2025-05-22', 'download.jpg', 'Lampung, [Tanggal Hari Ini] – Pemerintah Provinsi Lampung mengajak seluruh masyarakat untuk berani melaporkan tindakan kekerasan terhadap perempuan dan anak melalui layanan pengaduan resmi yang telah disediakan. Melalui kampanye \"Berani Laporkan Kekerasan Perempuan dan Anak\", masyarakat diingatkan bahwa keberanian untuk bersuara adalah langkah awal untuk menghentikan rantai kekerasan.\r\n\r\nDalam poster kampanye yang disebarkan secara luas, terlihat ilustrasi perempuan dari berbagai latar belakang memegang papan bertuliskan \"Setop Kekerasan pada Perempuan dan Anak!\". Pesan ini ditujukan untuk memberikan dukungan moral dan informasi kepada korban maupun saksi untuk tidak ragu melapor.\r\n\r\nPemerintah menyediakan layanan pengaduan melalui Lapor Via #SAPA 129 atau WhatsApp ke nomor 0811 1129 129. Layanan ini terbuka untuk siapa saja yang mengetahui atau mengalami kekerasan, baik fisik, verbal, maupun psikologis.\r\n\r\n“Setiap suara sangat berarti. Laporkan kekerasan yang terjadi agar korban mendapatkan perlindungan dan pelaku mendapatkan sanksi yang setimpal,” ujar salah satu perwakilan Dinas Pemberdayaan Perempuan dan Perlindungan Anak.\r\n\r\nMasyarakat diimbau untuk lebih peka dan tidak diam ketika melihat atau mengetahui adanya kekerasan. Keberanian dalam melapor menjadi bentuk kepedulian dan langkah nyata dalam menciptakan lingkungan yang aman bagi perempuan dan anak.\r\n\r\nUntuk informasi lebih lanjut, masyarakat dapat mengakses layanan melalui website resmi: www.sapa129.go.id atau mengikuti akun media sosial resmi milik pemerintah provinsi.');
INSERT INTO `berita` VALUES (31, 'DP3AKB', 'Stop Kekerasan pada Anak: Lindungi Masa Depan Generasi Bangsa', '2025-06-27', 'Berita_241311111120_hari-anti-kekerasan-pada-perempuan-dan-anak.jpeg', 'Kekerasan terhadap anak masih menjadi permasalahan serius yang harus segera dihentikan. Melalui kampanye “Stop Kekerasan pada Anak”, Pemerintah Provinsi Lampung mengajak seluruh lapisan masyarakat untuk lebih peduli dan bertindak aktif dalam melindungi anak-anak dari segala bentuk kekerasan.\r\n\r\nAnak adalah aset bangsa yang berhak tumbuh dan berkembang dalam lingkungan yang aman, nyaman, dan penuh kasih sayang. Namun, kenyataannya, masih banyak anak yang menjadi korban kekerasan, baik secara fisik, verbal, emosional, maupun seksual. Hal ini tentu akan berdampak buruk terhadap perkembangan mental dan masa depan mereka.\r\n\r\nSebagai upaya pencegahan dan penanganan, masyarakat yang mengetahui atau mengalami kekerasan terhadap anak didorong untuk segera melapor. Pemerintah menyediakan layanan pengaduan melalui SAPA 129 atau WhatsApp ke nomor 0811 1129 129, yang dapat diakses kapan saja oleh korban, saksi, maupun pihak keluarga.\r\n\r\n“Kekerasan terhadap anak bukan hanya tanggung jawab pemerintah, tetapi juga seluruh masyarakat. Mari kita hentikan bersama, jangan biarkan anak-anak tumbuh dalam ketakutan,” tegas perwakilan dari Dinas Pemberdayaan Perempuan dan Perlindungan Anak.\r\n\r\nSelain itu, edukasi kepada orang tua, guru, dan lingkungan sekitar sangat penting dilakukan secara berkelanjutan agar pola pengasuhan yang sehat dan positif bisa diterapkan dalam kehidupan sehari-hari.\r\n\r\nDengan melaporkan setiap tindakan kekerasan, kita telah menjadi bagian dari solusi untuk menciptakan lingkungan yang aman dan layak bagi anak-anak. Jangan diam, saatnya bertindak! Stop Kekerasan pada Anak, Mulai dari Kita!');

-- ----------------------------
-- Table structure for pengaduan
-- ----------------------------
DROP TABLE IF EXISTS `pengaduan`;
CREATE TABLE `pengaduan`  (
  `id_pengaduan` int(11) NOT NULL AUTO_INCREMENT,
  `no_tiket` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('Kekerasan Fisik','Kekerasan Psikis','Kekerasan Seksual','Kekerasan Ekonomi','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_kejadian` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `isi_laporan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bukti` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Laporan Masuk','Laporan Diterima','Laporan Ditolak','Laporan Selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pengaduan` datetime(0) NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pengaduan`) USING BTREE,
  INDEX `nama`(`nama`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengaduan
-- ----------------------------
INSERT INTO `pengaduan` VALUES (1, 'TKT175182172660', 'Andi Munawar', 'Jl. Bedadung Rt.1 RW.2 Kec. Panti Kabupaten Jember', '081765433122', 'Kekerasan Fisik', 'rumah', 'Pada hari Senin, tanggal 1 Juli 2025 sekitar pukul 20.30 WIB, saya mengalami kekerasan fisik yang dilakukan oleh suami saya di rumah kami yang beralamat di Jl. Melati No. 12, Kecamatan Sukajadi. Kejadian berawal dari pertengkaran karena masalah ekonomi. Saat itu, pelaku tiba-tiba memukul saya di bagian lengan dan menarik rambut saya sambil membentak dengan kata-kata kasar. Saya berusaha melindungi diri, namun pelaku semakin emosi dan menendang saya hingga saya terjatuh.\r\n\r\nAkibat kejadian tersebut, saya mengalami memar di lengan kiri dan luka lecet di bagian siku. Saya sudah memeriksakan diri ke puskesmas dan mendapatkan surat keterangan visum. Ini bukan pertama kalinya saya mengalami kekerasan, namun saya baru berani melaporkan karena merasa keselamatan saya dan anak-anak terancam.\r\n\r\nSaya berharap pihak berwenang dapat segera menindaklanjuti laporan ini demi keamanan dan perlindungan kami.', 'uploads/Berita_241311111120_hari-anti-kekerasan-pada-perempuan-dan-anak.jpeg', 'Laporan Masuk', '2025-07-07 00:08:46');
INSERT INTO `pengaduan` VALUES (2, 'TKT175182316819', 'Rudi Siswanto', '-', '081949601135', 'Kekerasan Ekonomi', '-', 'Saya ingin melaporkan bahwa saya telah mengalami kekerasan ekonomi yang dilakukan oleh suami saya sendiri. Sejak awal tahun 2024 hingga sekarang, suami saya melarang saya untuk bekerja dan tidak memberikan saya uang belanja yang cukup untuk kebutuhan sehari-hari, termasuk untuk makan, membeli perlengkapan anak, dan biaya transportasi.\r\n\r\nSeluruh penghasilan dari pekerjaan suami tidak pernah saya ketahui, dan setiap kali saya meminta uang, saya dimarahi dan dianggap tidak tahu diri. Bahkan, untuk membeli susu dan perlengkapan sekolah anak pun saya harus meminjam dari tetangga.\r\n\r\nBeberapa kali saya mencoba berdiskusi baik-baik, namun suami saya justru mengancam akan meninggalkan saya jika saya terus menuntut uang atau meminta izin bekerja. Saya merasa dikendalikan secara finansial dan tidak punya kebebasan memenuhi kebutuhan dasar keluarga.\r\n\r\nSaya berharap laporan ini bisa ditindaklanjuti, karena kondisi ini membuat saya dan anak-anak hidup dalam tekanan dan ketidakpastian setiap hari. Saya ingin mendapatkan perlindungan dan solusi agar bisa hidup mandiri dan layak kembali.', 'uploads/523.jpg', 'Laporan Masuk', '2025-07-07 00:32:48');
INSERT INTO `pengaduan` VALUES (3, 'TKT175182331466', 'A M', 'Jl. Tegal Waringin Desa Silo Kec. Silo Kabupaten Jember', '081267877666', 'Kekerasan Seksual', 'rumah paman', '', 'uploads/', 'Laporan Ditolak', '2025-07-07 00:35:14');
INSERT INTO `pengaduan` VALUES (4, 'TKT175182344459', 'Siti Aminah', 'Jl. Kalimatan Blok B Kec. Sumbersari Kab. Jember', '0812765897644', 'Kekerasan Psikis', 'rumah sendiri', 'Saya ingin melaporkan bahwa saya telah mengalami kekerasan psikis yang dilakukan oleh suami saya selama kurang lebih satu tahun terakhir. Kekerasan ini terjadi dalam bentuk kata-kata kasar, hinaan, dan ancaman hampir setiap hari. Suami saya sering merendahkan saya dengan sebutan bodoh, tidak berguna, dan menyalahkan saya atas segala masalah rumah tangga, bahkan untuk hal-hal yang tidak saya lakukan.\r\n\r\nSelain itu, suami saya juga sering membandingkan saya dengan perempuan lain, membuat saya merasa sangat rendah diri dan kehilangan kepercayaan diri. Ia juga kerap mengancam akan meninggalkan saya dan anak-anak jika saya membantah atau mencoba melawan ucapannya.\r\n\r\nSaya merasa sangat tertekan secara emosional, sulit tidur, mudah cemas, dan mulai kehilangan semangat untuk menjalani kehidupan sehari-hari. Saya sudah mencoba bicara baik-baik, namun responsnya justru semakin memperburuk keadaan.\r\n\r\nSaya membuat laporan ini dengan harapan agar saya bisa mendapatkan perlindungan, pendampingan psikologis, dan tindakan hukum terhadap kekerasan psikis yang saya alami. Saya tidak ingin anak-anak saya tumbuh dalam lingkungan penuh ketakutan dan tekanan mental.', 'uploads/', 'Laporan Diterima', '2025-07-07 00:37:24');
INSERT INTO `pengaduan` VALUES (5, 'TKT175182415368', 'Maimunah', 'Jl. Jember Kec. Sumbersari Kab. Jember', '087867443214', 'Kekerasan Fisik', 'rumah', 'Saya melaporkan bahwa saya telah mengalami kekerasan fisik yang dilakukan oleh suami saya pada hari Sabtu, 5 Juli 2025 sekitar pukul 22.00 WIB di rumah kami yang beralamat di Jl. Mawar No. 45, Kecamatan Sukamaju.\r\n\r\nKejadian bermula saat saya menanyakan pengeluaran rumah tangga yang tidak jelas. Suami saya tiba-tiba marah dan memukul lengan kiri saya menggunakan tangan kosong sebanyak dua kali. Selain itu, ia juga menendang kaki saya hingga saya terjatuh. Akibat tindakan tersebut, saya mengalami memar di lengan dan paha, yang hingga saat ini masih terasa nyeri.\r\n\r\nSaya telah memeriksakan diri ke puskesmas terdekat dan mendapatkan surat keterangan visum yang menunjukkan adanya bekas kekerasan fisik. Saya juga memiliki dokumentasi foto memar sebagai bukti tambahan.\r\n\r\nKekerasan ini bukan pertama kali terjadi, namun kali ini saya memberanikan diri untuk melapor karena kondisi saya semakin tertekan dan khawatir akan keselamatan diri dan anak-anak.\r\n\r\nSaya memohon agar laporan ini dapat segera ditindaklanjuti demi perlindungan saya dan keluarga, serta agar pelaku mendapatkan sanksi yang sesuai.', 'uploads/images.jpg', 'Laporan Selesai', '2025-07-07 00:49:13');

-- ----------------------------
-- Table structure for ulasan
-- ----------------------------
DROP TABLE IF EXISTS `ulasan`;
CREATE TABLE `ulasan`  (
  `id_ulasan` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengaduan` int(11) NULL DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `ulasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `tanggal_ulasan` timestamp(0) NOT NULL DEFAULT current_timestamp(),
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_ulasan`) USING BTREE,
  INDEX `fk_id`(`id_pengaduan`) USING BTREE,
  INDEX `fk_pengaduan`(`nama`) USING BTREE,
  CONSTRAINT `fk_id` FOREIGN KEY (`id_pengaduan`) REFERENCES `pengaduan` (`id_pengaduan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pengaduan` FOREIGN KEY (`nama`) REFERENCES `pengaduan` (`nama`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ulasan
-- ----------------------------
INSERT INTO `ulasan` VALUES (15, 5, 5, 'respon satset', '2025-07-07 01:06:32', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (0, 'alexanderzamil93@gmail.com', 'alexander', 'c81e728d9d4c2f636f067f89cc14862c');
INSERT INTO `users` VALUES (0, 'ridwanahmd46@gmail.com', 'ridwan', '1');
INSERT INTO `users` VALUES (1, 'ridwanahmd46@gmail.com', 'ridwan', '1');
INSERT INTO `users` VALUES (0, 'satu@gmail.com', '2110651032', 'c4ca4238a0b923820dcc509a6f75849b');

SET FOREIGN_KEY_CHECKS = 1;
