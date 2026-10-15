<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

$jabatan = $_SESSION["jabatan"] ?? '';
if ($jabatan !== "Guru") {
    echo "<script>
        alert('Hanya Guru yang dapat menghapus semua data absensi.');
        window.location.href = 'absensi.php';
    </script>";
    exit();
}

require_once "../config.php";

// Proses hapus semua data absensi
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $query = "TRUNCATE TABLE tbl_absensi";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
            alert('Semua data absensi berhasil dihapus.');
            window.location.href = 'absensi.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
            window.location.href = 'absensi.php';
        </script>";
    }
    exit();
}

// Jika tidak ada konfirmasi, redirect kembali (keamanan tambahan)
header("Location: absensi.php");
exit();
?>