<?php
require_once "../config.php";

header('Content-Type: application/json');

$ekskul = $_GET['ekskul'] ?? '';
$tgl = $_GET['tgl'] ?? '';  // Jika perlu cek absensi existing

if (empty($ekskul)) {
    echo json_encode(['data' => []]);
    exit;
}

// Filter siswa hanya yang terassign ke ekskul ini
$sql = "
    SELECT nis, nama, kelas
    FROM tbl_siswa
    WHERE id_ekskul = ?
    ORDER BY nama ASC
";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, 'i', $ekskul);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);
mysqli_stmt_close($stmt);
?>