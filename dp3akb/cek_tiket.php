<?php
// tambahkan fungsi di cek_tiket.php
function formatTanggalIndoLengkap($tanggalWaktu) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $tanggal = explode(' ', $tanggalWaktu)[0];
    $jam = explode(' ', $tanggalWaktu)[1] ?? '';

    $pecah = explode('-', $tanggal);
    return intval($pecah[2]) . ' ' . $bulan[intval($pecah[1])] . ' ' . $pecah[0] . ' ' . $jam;
}


$koneksi = new mysqli("localhost", "root", "", "pkl");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_POST['no_tiket'])) {
    $no_tiket = $koneksi->real_escape_string($_POST['no_tiket']);
    
    $stmt = $koneksi->prepare("SELECT id_pengaduan, no_tiket, status, tanggal_pengaduan, kategori FROM pengaduan WHERE no_tiket = ?");
    $stmt->bind_param("s", $no_tiket);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => [
                'id_pengaduan' => $data['id_pengaduan'],
                'no_tiket' => $data['no_tiket'],
                'status' => $data['status'],
                'tanggal_pengaduan' => formatTanggalIndoLengkap($data['tanggal_pengaduan']),// pastikan ini nama kolom yang benar
                'kategori' => $data['kategori']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tiket tidak ditemukan'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Nomor tiket tidak dikirim'
    ]);
}
?>
