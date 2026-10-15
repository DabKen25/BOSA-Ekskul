<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
require_once "../vendor/autoload.php"; // Menggunakan autoload Composer

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

if (isset($_POST['import'])) {
    $fileName = $_FILES['excelFile']['name'];
    $fileTmpName = $_FILES['excelFile']['tmp_name'];
    $fileError = $_FILES['excelFile']['error'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('xls', 'xlsx');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            $spreadsheet = IOFactory::load($fileTmpName);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            if ($highestRow <= 1) { // Mengecek jika hanya ada header atau file kosong
                header("location: import-guru.php?msg=error_empty");
                exit();
            }

            $successCount = 0;
            $errorCount = 0;

            // Loop dimulai dari baris ke-2 karena baris pertama adalah header
            for ($row = 2; $row <= $highestRow; $row++) {
                $id = htmlspecialchars($sheet->getCellByColumnAndRow(1, $row)->getValue());
                $nama = htmlspecialchars($sheet->getCellByColumnAndRow(2, $row)->getValue());
                $ekstrakulikuler = htmlspecialchars($sheet->getCellByColumnAndRow(3, $row)->getValue());

                // Validasi data (opsional, tapi sangat disarankan)
                if (empty($id) || empty($nama) || empty($ekstrakulikuler)) {
                    $errorCount++;
                    continue; // Lewati baris ini jika ada data yang kosong
                }

                // Cek apakah ID guru sudah ada di database
                $cekId = mysqli_query($koneksi, "SELECT id FROM tbl_guru WHERE id = '$id'");
                if (mysqli_num_rows($cekId) > 0) {
                    $errorCount++;
                    continue; // Lewati jika ID sudah ada
                }

                // Insert data ke database
                $query = "INSERT INTO tbl_guru (id, nama, ekstrakulikuler) VALUES ('$id', '$nama', '$ekstrakulikuler')";
                if (mysqli_query($koneksi, $query)) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }

            if ($successCount > 0) {
                header("location: import-guru.php?msg=success");
            } else {
                header("location: import-guru.php?msg=error_insert");
            }
            exit();

        } else {
            header("location: import-guru.php?msg=error_upload");
            exit();
        }
    } else {
        header("location: import-guru.php?msg=error_file");
        exit();
    }
}
?>