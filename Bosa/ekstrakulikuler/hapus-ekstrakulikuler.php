<?php

session_start();

// Memastikan hanya user yang terautentikasi yang bisa mengakses halaman ini
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";

// Gunakan TRUNCATE TABLE untuk menghapus semua baris dan mereset auto-increment
// TRUNCATE TABLE lebih cepat daripada DELETE FROM saat menghapus seluruh data
$query = "TRUNCATE TABLE tbl_ekstrakulikuler";
$result = mysqli_query($koneksi, $query);

if ($result) {
    // Jika penghapusan berhasil, redirect ke halaman ekstrakurikuler dengan pesan sukses
    header("Location: ekstrakulikuler.php?msg=all_deleted");
    exit();
} else {
    // Jika ada kesalahan, redirect dengan pesan error
    // Anda bisa menambahkan pesan error yang lebih spesifik jika diperlukan
    header("Location: ekstrakulikuler.php?msg=error_delete");
    exit();
}
?>
