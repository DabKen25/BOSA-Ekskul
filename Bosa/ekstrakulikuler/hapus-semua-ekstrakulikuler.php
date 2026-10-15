<?php

session_start();

// Memastikan hanya user yang terautentikasi yang bisa mengakses halaman ini
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

// REVISI: Menggunakan DELETE FROM alih-alih TRUNCATE untuk menghindari error relasi database
$query = "DELETE FROM tbl_ekstrakulikuler";
$result = mysqli_query($koneksi, $query);

if ($result) {
    // Jika penghapusan berhasil, redirect ke halaman ekstrakurikuler dengan pesan sukses
    header("Location: ekstrakulikuler.php?msg=all_deleted");
    exit();
} else {
    // Jika ada kesalahan, redirect dengan pesan error
    header("Location: ekstrakulikuler.php?msg=error_delete");
    exit();
}
?>