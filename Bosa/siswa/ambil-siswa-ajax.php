<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["ssLogin"])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi tidak valid.', 'data' => []]);
    exit();
}

require_once "../config.php";

$jabatan = $_SESSION["jabatan"];
$ekskulGuru = isset($_SESSION["ekskul"]) ? $_SESSION["ekskul"] : '';

// Jika yang login adalah GURU, filter berdasarkan ekskul yang diampu
if ($jabatan === "Guru") {
    $query = "SELECT nis, nama, kelas FROM tbl_siswa WHERE Ekstrakurikuler = '$ekskulGuru' ORDER BY nama";
} else {
    // Jika Admin/Tim Kurikulum, tampilkan semua siswa
    $query = "SELECT nis, nama, kelas FROM tbl_siswa ORDER BY nama";
}

$result = mysqli_query($koneksi, $query);

$siswa = [];
while ($row = mysqli_fetch_assoc($result)) {
    $siswa[] = [
        'nis' => $row['nis'],
        'nama' => $row['nama'],
        'kelas' => $row['kelas']
    ];
}

if (empty($siswa)) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada siswa ditemukan untuk ekskul ini.', 'data' => []]);
} else {
    echo json_encode(['status' => 'success', 'message' => 'Data siswa berhasil diambil.', 'data' => $siswa]);
}
exit();
?>