<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $username = trim($_POST['username']);
    $nama     = trim($_POST['nama']);
    $jabatan  = trim($_POST['jabatan']); // Mengambil dari dropdown (Tim Kurikulum/Guru)

    $password = '1234'; // Password default
    $pass     = password_hash($password, PASSWORD_DEFAULT);

    // Cek duplikasi username
    $stmt = mysqli_prepare($koneksi, "SELECT 1 FROM tbl_user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        header("location:add-user.php?msg=cancel");
        exit;
    }
    mysqli_stmt_close($stmt);

    // Insert user baru dengan JABATAN yang sesuai
    $stmt = mysqli_prepare($koneksi, "INSERT INTO tbl_user (username, password, nama, jabatan) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $username, $pass, $nama, $jabatan);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location:add-user.php?msg=added");
    exit;
}