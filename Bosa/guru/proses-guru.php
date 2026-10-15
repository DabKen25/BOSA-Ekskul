<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";

if (isset($_POST['simpan'])) {
    $id                = htmlspecialchars($_POST['id']);
    $nama              = htmlspecialchars($_POST['nama']);
    $ekstrakulikuler   = $_POST["ekstrakulikuler"];

    $cekId = mysqli_query($koneksi, "SELECT id FROM tbl_guru WHERE id = '$id'");
    if (mysqli_num_rows($cekId) > 0) {
        header('location:add-guru.php?msg=cancel');
        return;
    }

    mysqli_query($koneksi, "INSERT INTO tbl_guru VALUES('$id', '$nama', '$ekstrakulikuler')");

    header("location:add-guru.php?msg=added");
    return;
}

if (isset($_POST['update'])) {
    
    $id                = $_POST['id'];
    $nama              = htmlspecialchars($_POST['nama']);
    $ekstrakulikuler   = $_POST["ekstrakulikuler"];

    $sqlGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru WHERE id = $id");
    $data = mysqli_fetch_array($sqlGuru);
    $curId = $data['id'];

    $neId = mysqli_query($koneksi, "SELECT * FROM tbl_guru WHERE id = $id");

    if ($id !== $curId) {
        if (mysqli_num_rows($newId) > 0) {
            header("location:guru.php?msg=cancel");
            return;
        }
    }

    mysqli_query($koneksi, "UPDATE tbl_guru SET
    
                           id = '$id',
                           nama = '$nama',
                           ekstrakulikuler = '$ekstrakulikuler' 
                           WHERE id =$id
                           ");

header("location:guru.php?msg=updated");
return;

}

?>
