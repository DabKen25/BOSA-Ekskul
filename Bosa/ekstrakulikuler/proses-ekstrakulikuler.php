<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
    $id                = htmlspecialchars($_POST['id']);
    $ekstrakulikuler   = htmlspecialchars($_POST["ekstrakulikuler"]);
    $guru              = $_POST["guru"];

    $cekEkstrakulikuler = mysqli_query($koneksi, "SELECT * FROM tbl_ekstrakulikuler WHERE ekstrakulikuler = '$ekstrakulikuler'");
    if (mysqli_num_rows($cekEkstrakulikuler) > 0) {
        header('location:add-ekstrakulikuler.php?msg=cancel');
        return;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_ekstrakulikuler VALUES (null, '$ekstrakulikuler', '$guru')");

    header("location: ekstrakulikuler.php?msg=added");
    return;
}

if (isset($_POST['update'])) {
    $id                = $_POST['id'];
    $ekstrakulikuler   = htmlspecialchars($_POST["ekstrakulikuler"]);
    $guru              = $_POST["guru"];

    $queryEkstrakulikuler = mysqli_query($koneksi, "SELECT * FROM tbl_ekstrakulikuler WHERE id = $id");
    $data = mysqli_fetch_array($queryEkstrakulikuler);
    $curEkstrakulikuler = $data['ekstrakulikuler'];

    $cekEkstrakulikuler = mysqli_query($koneksi, "SELECT * FROM tbl_ekstrakulikuler WHERE ekstrakulikuler = '$ekstrakulikuler'");

    if ($ekstrakulikuler !== $curEkstrakulikuler) {
        if (mysqli_num_rows($cekEkstrakulikuler) > 0) {
            header("location:ekstrakulikuler.php?msg=cancelupdated");
            return;
        }
    }

    mysqli_query($koneksi, "UPDATE tbl_ekstrakulikuler SET ekstrakulikuler = '$ekstrakulikuler', guru = '$guru' WHERE id = $id");

    header("location:ekstrakulikuler.php?msg=updated");
    return;
}
