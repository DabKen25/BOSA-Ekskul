<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

require_once "../config.php";

if(isset($_POST['simpan'])) {
    $curPass = trim(htmlspecialchars($_POST['curPass']));
    $newPass = trim(htmlspecialchars($_POST['newPass']));
    $confPass = trim(htmlspecialchars($_POST['confPass']));

    // Asumsi session key yang benar adalah 'ssUser'
    $userName = $_SESSION['ssUser'];

    $queryUser = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '$userName'");
    $data = mysqli_fetch_array($queryUser);

    // Cek apakah password baru dan konfirmasi password sama
    if ($newPass !== $confPass) {
        header("location:password.php?msg=err1");
        exit;
    }

    // Cek apakah password lama yang dimasukkan benar
    if (!password_verify($curPass, $data['password'])) {
        header("location:password.php?msg=err2");
        exit;
    } else {
        // Hash password baru dan update ke database
        $pass = password_hash($newPass, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($koneksi, "UPDATE tbl_user SET password = ? WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $pass, $userName);
        $update = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($update) {
            header("location:password.php?msg=updated");
        } else {
            header("location:password.php?msg=error");
        }
        exit;
    }
}
?>
