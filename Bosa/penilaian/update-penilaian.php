<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php"); exit();
}
require_once "../config.php";

if (isset($_POST['update'])) {
  $id_penilaian = trim($_POST['id_penilaian'] ?? '');
  $no_ekskul    = trim($_POST['no_ekskul'] ?? '');
  $nis          = trim($_POST['nis'] ?? '');
  $nama         = trim($_POST['nama'] ?? '');
  $nilai        = trim($_POST['nilai'] ?? '');
  
  // Ambil 4 keterangan baru
  $sb_keterangan = trim($_POST['sb_keterangan'] ?? '');
  $b_keterangan  = trim($_POST['b_keterangan'] ?? '');
  $c_keterangan  = trim($_POST['c_keterangan'] ?? '');
  $k_keterangan  = trim($_POST['k_keterangan'] ?? '');

  if ($id_penilaian==''||$no_ekskul==''||$nis==''||$nama==''||$nilai=='') {
    header("Location: penilaian.php?msg=error"); exit();
  }

  /* Siapkan statement UPDATE dengan 8 kolom baru */
  $stmt = mysqli_prepare($koneksi,
          "UPDATE tbl_penilaian
           SET no_ekskul=?, nis=?, nama=?, nilai=?, 
           sb_keterangan=?, b_keterangan=?, c_keterangan=?, k_keterangan=?
           WHERE id_penilaian = ?");
           
  // Binding parameter
  mysqli_stmt_bind_param($stmt, 'isssssssi', $no_ekskul, $nis, $nama, $nilai, 
                         $sb_keterangan, $b_keterangan, $c_keterangan, $k_keterangan, $id_penilaian);
                         
  $update = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  if ($update) {
    header("Location: penilaian.php?msg=updated");
  } else {
    header("Location: penilaian.php?msg=error");
  }
  exit();
}