<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM tbl_absensi WHERE id_absensi = $id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: absensi.php?msg=deleted");
    } else {
        header("Location: absensi.php?msg=error");
    }
    exit();
} else {
    header("Location: absensi.php?msg=error");
    exit();
}
?>