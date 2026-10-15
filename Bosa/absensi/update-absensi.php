<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php");
  exit();
}

require_once "../config.php";

if (isset($_POST['update'])) {
  $id_absensi  = trim($_POST['id_absensi']  ?? '');
  $tgl         = trim($_POST['tgl']         ?? '');
  $nis         = trim($_POST['nis']         ?? '');
  $nama        = trim($_POST['nama']        ?? '');
  $status      = trim($_POST['status']      ?? '');
  $keterangan  = trim($_POST['keterangan']  ?? '');
  $ekskul      = trim($_POST['ekskul']      ?? '');

  if (empty($id_absensi) || empty($tgl) || empty($nis) || empty($nama) || empty($status)) {
    header("Location: absensi.php?msg=error");
    exit();
  }

  /*
   * Cek duplikat (tgl, nis) selain baris yang sedang diedit.
   */
  $cek_stmt = mysqli_prepare($koneksi, "SELECT 1 FROM tbl_absensi WHERE tgl=? AND nis=? AND id_absensi <> ?");
  mysqli_stmt_bind_param($cek_stmt, "ssi", $tgl, $nis, $id_absensi);
  mysqli_stmt_execute($cek_stmt);
  mysqli_stmt_store_result($cek_stmt);
  if (mysqli_stmt_num_rows($cek_stmt) > 0) {
    header("Location: absensi.php?msg=duplikat");
    mysqli_stmt_close($cek_stmt);
    exit();
  }
  mysqli_stmt_close($cek_stmt);

  /*
   * Update data absensi.
   */
  $stmt = mysqli_prepare($koneksi, "UPDATE tbl_absensi SET tgl=?, nis=?, nama=?, id_ekskul=?, status=?, keterangan=? WHERE id_absensi=?");
  mysqli_stmt_bind_param($stmt, "ssssssi", $tgl, $nis, $nama, $ekskul, $status, $keterangan, $id_absensi);
  $update = mysqli_stmt_execute($stmt);

  if ($update) {
    header("Location: absensi.php?msg=updated");
  } else {
    header("Location: absensi.php?msg=error");
  }
  mysqli_stmt_close($stmt);
  exit();
} else {
  header("Location: absensi.php");
  exit();
}
?>