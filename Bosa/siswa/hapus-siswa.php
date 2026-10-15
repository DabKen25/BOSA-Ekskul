<?php

session_start();

if (!isset($_SESSION['ssLogin'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";

$id = $_GET['nis'];


mysqli_query($koneksi, "DELETE FROM tbl_siswa WHERE nis = '$id'");

echo "<script>
       alert('Data siswa berhasil dihapus..');
       document.location.href='siswa.php';
    </script>";
return;
?>