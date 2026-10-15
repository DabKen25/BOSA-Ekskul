<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
require_once "../vendor/autoload.php"; // Memuat autoload Composer untuk pustaka PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import'])) {
    $fileName = $_FILES['file_excel']['name'];
    $fileTmpName = $_FILES['file_excel']['tmp_name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowedExtensions = ['xls', 'xlsx'];

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        $_SESSION['import_message'] = [
            'type' => 'alert-danger',
            'text' => 'Tipe file tidak diizinkan. Hanya file .xls atau .xlsx yang diperbolehkan.'
        ];
        header("Location: import-ekstrakulikuler.php");
        exit();
    }

    try {
        $spreadsheet = IOFactory::load($fileTmpName);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $successCount = 0;
        $errorCount = 0;

        // Loop mulai dari baris kedua (asumsi baris pertama adalah header)
        for ($row = 2; $row <= $highestRow; $row++) {
            $ekstrakulikuler = mysqli_real_escape_string($koneksi, $sheet->getCellByColumnAndRow(1, $row)->getValue());
            $guru            = mysqli_real_escape_string($koneksi, $sheet->getCellByColumnAndRow(2, $row)->getValue());

            // Validasi sederhana, skip jika data kosong
            if (empty($ekstrakulikuler) || empty($guru)) {
                $errorCount++;
                continue;
            }

            // Cek apakah ekstrakurikuler sudah ada di database
            $cekEkstrakulikuler = mysqli_query($koneksi, "SELECT * FROM tbl_ekstrakulikuler WHERE ekstrakulikuler = '$ekstrakulikuler'");
            if (mysqli_num_rows($cekEkstrakulikuler) > 0) {
                $errorCount++;
                continue; // Lanjut ke baris berikutnya jika ekstrakurikuler sudah ada
            }

            // Masukkan data ke database
            // diasumsikan ID adalah AUTO_INCREMENT
            $insert = mysqli_query($koneksi, "INSERT INTO tbl_ekstrakulikuler (ekstrakulikuler, guru) VALUES('$ekstrakulikuler', '$guru')");

            if ($insert) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        $_SESSION['import_message'] = [
            'type' => 'alert-success',
            'text' => 'Proses impor selesai! ' . $successCount . ' data berhasil diimpor, ' . $errorCount . ' data gagal/diskip.'
        ];
        header("Location: ekstrakulikuler.php"); // Redirect ke halaman utama ekstrakulikuler
        exit();

    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        $_SESSION['import_message'] = [
            'type' => 'alert-danger',
            'text' => 'Error membaca file Excel: ' . $e->getMessage()
        ];
        header("Location: import-ekstrakulikuler.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['import_message'] = [
            'type' => 'alert-danger',
            'text' => 'Terjadi kesalahan: ' . $e->getMessage()
        ];
        header("Location: import-ekstrakulikuler.php");
        exit();
    }
} else {
    header("Location: ekstrakulikuler.php");
    exit();
}
?>
