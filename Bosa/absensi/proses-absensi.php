<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php");
  exit();
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
  /*
   * Mengambil dan membersihkan data dari form.
   */
  $tgl        = trim($_POST['tgl']        ?? '');
  $nis        = trim($_POST['nis']        ?? '');
  $nama       = trim($_POST['nama']       ?? '');
  $status     = trim($_POST['status']     ?? '');
  $keterangan = trim($_POST['keterangan'] ?? '');
  $ekskul     = trim($_POST['ekskul']     ?? '');

  /*
   * Validasi dasar.
   */
  if (empty($tgl) || empty($nis) || empty($nama) || empty($status)) {
    header("Location: absensi.php?msg=error");
    exit();
  }

  /*
   * Mencegah duplikat data (tanggal dan NIS harus unik).
   * Menggunakan prepared statement untuk mencegah SQL injection.
   */
  $cek_stmt = mysqli_prepare($koneksi, "SELECT 1 FROM tbl_absensi WHERE tgl=? AND nis=?");
  mysqli_stmt_bind_param($cek_stmt, "ss", $tgl, $nis);
  mysqli_stmt_execute($cek_stmt);
  mysqli_stmt_store_result($cek_stmt);
  if (mysqli_stmt_num_rows($cek_stmt) > 0) {
    header("Location: absensi.php?msg=duplikat");
    mysqli_stmt_close($cek_stmt);
    exit();
  }
  mysqli_stmt_close($cek_stmt);

  /*
   * Memasukkan data baru ke database.
   * Menggunakan prepared statement untuk keamanan.
   */
  $stmt = mysqli_prepare($koneksi, "INSERT INTO tbl_absensi (tgl, nis, nama, id_ekskul, status, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "ssssss", $tgl, $nis, $nama, $ekskul, $status, $keterangan);
  $insert = mysqli_stmt_execute($stmt);

  if ($insert) {
    header("Location: absensi.php?msg=added");
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