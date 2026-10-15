<?php
session_start();
require_once "../config.php";

// Cek login
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

// Perintah untuk mengosongkan tabel
$queryTruncate = mysqli_query($koneksi, "TRUNCATE TABLE tbl_penilaian");

if ($queryTruncate) {
    header("Location: penilaian.php?msg=truncate");
} else {
    header("Location: penilaian.php?msg=error");
}
exit();
?>