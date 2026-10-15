<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["ssLogin"])) {
  echo json_encode(['status' => 'error', 'message' => 'Sesi tidak valid.', 'total_processed' => 0, 'total_success' => 0]);
  exit();
}

require_once "../config.php";

$tgl        = trim($_POST['tgl']    ?? '');
$ekskul     = trim($_POST['ekskul'] ?? '');
$dataJson   = $_POST['data']        ?? '[]';

$dataArray = json_decode($dataJson, true);

if (empty($tgl) || empty($ekskul) || !is_array($dataArray) || count($dataArray) === 0) {
  echo json_encode(['status' => 'error', 'message' => 'Data input tidak valid (tanggal, ekskul, atau array absensi kosong).', 'total_processed' => 0, 'total_success' => 0]);
  exit();
}

$total_processed = count($dataArray);
$total_success   = 0;
$error_messages  = [];

$insert_stmt = mysqli_prepare($koneksi, "INSERT INTO tbl_absensi (tgl, nis, nama, status, keterangan, id_ekskul) VALUES (?, ?, ?, ?, ?, ?)");
$cek_stmt    = mysqli_prepare($koneksi, "SELECT 1 FROM tbl_absensi WHERE tgl=? AND nis=?");

foreach ($dataArray as $data) {
    $nis        = trim($data['nis']        ?? '');
    $nama       = trim($data['nama']       ?? '');
    $status     = trim($data['status']     ?? '');
    $keterangan = trim($data['keterangan'] ?? '');

    if (empty($nis) || empty($nama) || empty($status)) {
        $error_messages[] = "Data siswa NIS $nis tidak lengkap.";
        continue;
    }

    mysqli_stmt_bind_param($cek_stmt, "ss", $tgl, $nis);
    mysqli_stmt_execute($cek_stmt);
    mysqli_stmt_store_result($cek_stmt);

    if (mysqli_stmt_num_rows($cek_stmt) > 0) {
      $error_messages[] = "Absensi untuk **NIS $nis ($nama)** pada tanggal $tgl sudah ada (Duplikat).";
      continue;
    }

    $ekskul_int = intval($ekskul);
    mysqli_stmt_bind_param($insert_stmt, "sssssi", $tgl, $nis, $nama, $status, $keterangan, $ekskul_int);

    if (mysqli_stmt_execute($insert_stmt)) {
        $total_success++;
    } else {
        $error_messages[] = "Gagal INSERT **NIS $nis ($nama)**: " . mysqli_error($koneksi);
    }
}

mysqli_stmt_close($cek_stmt);
mysqli_stmt_close($insert_stmt);

if ($total_success === $total_processed) {
    echo json_encode(['status' => 'success', 'message' => 'Semua data absensi berhasil disimpan.', 'total_processed' => $total_processed, 'total_success' => $total_success]);
} elseif ($total_success > 0) {
    $error_msg_str = implode('<br>', $error_messages);
    echo json_encode(['status' => 'partial_success', 'message' => $error_msg_str, 'total_processed' => $total_processed, 'total_success' => $total_success]);
} else {
    $error_msg_str = implode('<br>', $error_messages);
    echo json_encode(['status' => 'error', 'message' => 'Semua data gagal disimpan.<br>' . $error_msg_str, 'total_processed' => $total_processed, 'total_success' => $total_success]);
}
?>