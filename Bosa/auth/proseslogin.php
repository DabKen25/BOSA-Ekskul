<?php
session_start();
require_once "../config.php";

// Tentukan password default yang akan digunakan oleh semua guru pendamping
$default_password = 'bosa1YK'; // Ganti dengan password yang Anda inginkan

// Jika tombol login ditekan
if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $result = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username = '$username'");

    // Cek username
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        //verifikasi password, ubah menjadi seperti ini:
if ($password === $default_password) {
    $_SESSION["ssLogin"] = true;
    $_SESSION["ssUser"]  = $username;
    $_SESSION["nama"]    = $row['nama'];    // Simpan nama asli
    $_SESSION["jabatan"] = $row['jabatan']; // Simpan jabatan (Guru / Kurikulum)
    
    header("Location: ../index.php");
    exit;
}
        
        else {
            echo "<script>
                alert('Password salah..');
                document.location.href = 'login.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Username tidak terdaftar..');
            document.location.href = 'login.php';
        </script>";
    }
}
?>