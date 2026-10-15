<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION["ssLogin"])) {
  echo json_encode(['status' => 'error', 'message' => 'Sesi tidak valid.', 'total_processed' => 0, 'total_success' => 0]);
  exit();
}

require_once "../config.php";

/* ── Mengambil dan membersihkan data dari AJAX ────────────────── */
$tgl        = trim($_POST['tgl']    ?? '');
$ekskul     = trim($_POST['ekskul'] ?? '');
$dataJson   = $_POST['data']        ?? '[]';

$dataArray = json_decode($dataJson, true);

/* ── Validasi dasar ───────────────────────────────────────────── */
if (empty($tgl) || empty($ekskul) || !is_array($dataArray) || count($dataArray) === 0) {
  echo json_encode(['status' => 'error', 'message' => 'Data input tidak valid (tanggal, ekskul, atau array absensi kosong).', 'total_processed' => 0, 'total_success' => 0]);
  exit();
}

$total_processed = count($dataArray);
$total_success   = 0;
$error_messages  = [];

/* ── Mempersiapkan statement database (Prepared Statement) ────── */
$cek_stmt    = mysqli_prepare($koneksi, "SELECT id_absensi FROM tbl_absensi WHERE tgl=? AND nis=?");
$insert_stmt = mysqli_prepare($koneksi, "INSERT INTO tbl_absensi (tgl, nis, nama, status, keterangan, id_ekskul) VALUES (?, ?, ?, ?, ?, ?)");
$update_stmt = mysqli_prepare($koneksi, "UPDATE tbl_absensi SET status=?, keterangan=?, nama=? WHERE id_absensi=?");

foreach ($dataArray as $data) {
    $nis        = trim($data['nis']        ?? '');
    $nama       = trim($data['nama']       ?? '');
    $status     = trim($data['status']     ?? 'H'); // Default 'H' jika kosong
    $keterangan = trim($data['keterangan'] ?? '');

    // Validasi data per siswa
    if (empty($nis) || empty($nama) || empty($status)) {
        $error_messages[] = "Data siswa NIS $nis tidak lengkap.";
        continue; // Lanjut ke data siswa berikutnya
    }
    
    /* ── Cek apakah data sudah ada ───────────────────────────────── */
    mysqli_stmt_bind_param($cek_stmt, "ss", $tgl, $nis);
    mysqli_stmt_execute($cek_stmt);
    mysqli_stmt_store_result($cek_stmt);
    $exists = mysqli_stmt_num_rows($cek_stmt) > 0;
    $id_absensi = null;
    if ($exists) {
        mysqli_stmt_bind_result($cek_stmt, $id_absensi);
        mysqli_stmt_fetch($cek_stmt);
    }
    
    $ekskul_int = intval($ekskul);
    
    if ($exists) {
        // UPDATE jika sudah ada
        mysqli_stmt_bind_param($update_stmt, "sssi", $status, $keterangan, $nama, $id_absensi);
        if (mysqli_stmt_execute($update_stmt)) {
            $total_success++;
        } else {
            $error_messages[] = "Gagal UPDATE NIS $nis ($nama): " . mysqli_error($koneksi);
        }
    } else {
        // INSERT jika belum ada
        mysqli_stmt_bind_param($insert_stmt, "sssssi", $tgl, $nis, $nama, $status, $keterangan, $ekskul_int);
        if (mysqli_stmt_execute($insert_stmt)) {
            $total_success++;
        } else {
            $error_messages[] = "Gagal INSERT NIS $nis ($nama): " . mysqli_error($koneksi);
        }
    }
}

/* ── Menutup statement ────────────────────────────────────────── */
mysqli_stmt_close($cek_stmt);
mysqli_stmt_close($insert_stmt);
mysqli_stmt_close($update_stmt);

/* ── Hasil Akhir ──────────────────────────────────────────────── */
if ($total_success === $total_processed) {
    // Semua data berhasil
    echo json_encode(['status' => 'success', 'message' => 'Semua data absensi berhasil disimpan/diperbarui.', 'total_processed' => $total_processed, 'total_success' => $total_success]);
} elseif ($total_success > 0) {
    // Ada yang berhasil, ada yang gagal
    $error_msg_str = implode('<br>', $error_messages);
    echo json_encode(['status' => 'partial_success', 'message' => $error_msg_str, 'total_processed' => $total_processed, 'total_success' => $total_success]);
} else {
    // Semua data gagal
    $error_msg_str = implode('<br>', $error_messages);
    echo json_encode(['status' => 'error', 'message' => 'Semua data gagal disimpan/diperbarui.<br>' . $error_msg_str, 'total_processed' => $total_processed, 'total_success' => $total_success]);
}
?>