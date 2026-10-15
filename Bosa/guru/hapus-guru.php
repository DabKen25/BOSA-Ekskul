<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";

// Cek apakah parameter id tersedia
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Jalankan query hapus
    $query = mysqli_query($koneksi, "DELETE FROM tbl_guru WHERE id = '$id'");

    if ($query) {
        $_SESSION['success'] = "Data guru berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus data guru.";
    }
} else {
    $_SESSION['error'] = "ID tidak ditemukan.";
}

// Redirect kembali ke halaman guru
header("Location: guru.php");
exit();
