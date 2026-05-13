<?php
$koneksi = new mysqli("localhost", "root", "", "pkl");

if ($koneksi->connect_error) {
    die(json_encode([
        'sudah_ulas' => true,
        'error' => 'Koneksi ke database gagal: ' . $koneksi->connect_error
    ]));
}

// Pastikan parameter id_pengaduan dikirim
if (isset($_GET['id_pengaduan'])) {
    $id_pengaduan = intval($_GET['id_pengaduan']);

    // Query untuk cek apakah sudah ada ulasan untuk id_pengaduan ini
    $query = "SELECT COUNT(*) as total FROM ulasan WHERE id_pengaduan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pengaduan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = $result->fetch_assoc();
        $sudahUlas = $data['total'] > 0;

        echo json_encode(['sudah_ulas' => $sudahUlas]);
    } else {
        echo json_encode([
            'sudah_ulas' => true,
            'error' => 'Gagal mengambil data ulasan.'
        ]);
    }
} else {
    echo json_encode([
        'sudah_ulas' => true,
        'error' => 'Parameter id_pengaduan tidak ditemukan.'
    ]);
}
?>
