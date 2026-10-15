<?php
session_start();
if (!isset($_SESSION["ssLogin"])) { header("location: ../auth/login.php"); exit(); }
require_once "../config.php";

if (isset($_POST['simpan'])) {
    $nis     = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas   = $_POST["kelas"];
    $ekskul  = $_POST["ekskul"]; // Ubah dari jurusan ke ekskul

    // Gunakan nama kolom eksplisit agar aman
    $query = "INSERT INTO tbl_siswa (nis, nama, kelas, Ekstrakurikuler) VALUES ('$nis', '$nama', '$kelas', '$ekskul')";
    mysqli_query($koneksi, $query);

    echo "<script>alert('Data siswa berhasil disimpan'); document.location.href = 'siswa.php';</script>";
    return;

} else if (isset($_POST['update'])) {
    $nis     = $_POST['nis'];
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas   = $_POST['kelas'];
    $ekskul  = $_POST['ekskul'];

    mysqli_query($koneksi, "UPDATE tbl_siswa SET nama = '$nama', kelas = '$kelas', Ekstrakurikuler = '$ekskul' WHERE nis = '$nis'");

    echo "<script>alert('Data siswa berhasil diupdate'); document.location.href = 'siswa.php';</script>";
    return;
}
?>