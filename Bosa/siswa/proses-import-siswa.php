<?php
session_start();

if (!isset($_SESSION["ssLogin"]) || $_SESSION["jabatan"] !== "Tim Kurikulum") {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
require_once "../vendor/autoload.php"; 

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import'])) {
    $ekskul_id = mysqli_real_escape_string($koneksi, $_POST['ekskul_id']);
    $fileName = $_FILES['file_excel']['name'];
    $fileTmpName = $_FILES['file_excel']['tmp_name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowedExtensions = ['xls', 'xlsx'];

    if (empty($ekskul_id)) {
        echo "<script>alert('Pilih ekstrakurikuler terlebih dahulu!'); history.back();</script>";
        exit();
    }

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo "<script>alert('Hanya file .xls atau .xlsx yang diperbolehkan.'); window.location='import-siswa.php';</script>";
        exit();
    }

    try {
        $spreadsheet = IOFactory::load($fileTmpName);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $successCount = 0;
        $errorCount = 0;

        // Loop mulai baris 2 (Baris 1 diasumsikan Header)
        for ($row = 2; $row <= $highestRow; $row++) {
            $nis   = trim($sheet->getCell('A' . $row)->getValue());
            $nama  = trim($sheet->getCell('B' . $row)->getValue());
            $kelas = trim($sheet->getCell('C' . $row)->getValue());

            // Lewati jika NIS atau Nama kosong
            if (empty($nis) || empty($nama)) continue;

            $nis   = mysqli_real_escape_string($koneksi, $nis);
            $nama  = mysqli_real_escape_string($koneksi, $nama);
            $kelas = mysqli_real_escape_string($koneksi, $kelas);

            // Cek apakah siswa sudah ada berdasarkan NIS
            $checkNis = mysqli_query($koneksi, "SELECT nis FROM tbl_siswa WHERE nis = '$nis'");
            
            if (mysqli_num_rows($checkNis) > 0) {
                // Jika sudah ada, Update data dan pindahkan ke ID Ekskul yang baru dipilih
                $query = "UPDATE tbl_siswa SET nama='$nama', kelas='$kelas', id_ekskul='$ekskul_id' WHERE nis='$nis'";
            } else {
                // Jika belum ada, Insert baru
                $query = "INSERT INTO tbl_siswa (nis, nama, kelas, id_ekskul) VALUES('$nis', '$nama', '$kelas', '$ekskul_id')";
            }

            if (mysqli_query($koneksi, $query)) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        echo "<script>
                alert('Proses selesai! " . $successCount . " siswa berhasil diupdate/tambah ke ekskul tersebut.');
                document.location.href = 'siswa.php';
              </script>";

    } catch (Exception $e) {
        echo "<script>
                alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "');
                window.location='import-siswa.php';
              </script>";
    }
} else {
    header("Location: import-siswa.php");
    exit();
}