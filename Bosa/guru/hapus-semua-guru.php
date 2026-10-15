<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
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

// Validasi: Pastikan ada parameter confirm untuk mencegah penghapusan tidak disengaja
if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    // Menggunakan DELETE agar tidak error jika ada relasi Foreign Key
    $query = "DELETE FROM tbl_guru"; 

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success_message'] = "Semua data guru berhasil dihapus secara keseluruhan.";
        header("location: guru.php?msg=all_deleted");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data guru: " . mysqli_error($koneksi);
        header("location: guru.php?msg=delete_failed");
        exit();
    }
} else {
    // Jika diakses tanpa confirm=true, arahkan kembali
    header("location: guru.php?msg=confirm_required");
    exit();
}
?>