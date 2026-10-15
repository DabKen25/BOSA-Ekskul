<?php
session_start();

// Cek login
if (!isset($_SESSION['ssLogin'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Proteksi: Hanya Tim Kurikulum yang boleh menghapus semua data
if ($_SESSION["jabatan"] !== "Tim Kurikulum") {
    echo "<script>
            alert('Anda tidak memiliki akses untuk menghapus semua data!');
            document.location.href='siswa.php';
          </script>";
    exit();
}

require_once "../config.php";

// Cek apakah ada parameter konfirmasi dari URL
if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    // Gunakan DELETE daripada TRUNCATE jika ada relasi tabel (Foreign Key)
    $query = "DELETE FROM tbl_siswa"; 

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
               alert('Semua data siswa berhasil dihapus.');
               document.location.href='siswa.php';
            </script>";
    } else {
        echo "<script>
               alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
               document.location.href='siswa.php';
            </script>";
    }
} else {
    // Alur konfirmasi JavaScript
    echo "<script>
            if (confirm('PERINGATAN! Apakah Anda yakin ingin MENGHAPUS SEMUA DATA SISWA? Data tidak bisa dikembalikan.')) {
                document.location.href='hapus-semua-siswa.php?confirm=true';
            } else {
                document.location.href='siswa.php';
            }
          </script>";
}
exit();
?>