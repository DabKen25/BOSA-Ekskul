<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Import Guru - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$msg = "";
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

$alert = '';
if ($msg == 'success') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data guru berhasil diimpor!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif ($msg == 'error_upload') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Gagal mengunggah file. Pastikan format file benar (xls, xlsx).
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif ($msg == 'error_file') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Hanya file Excel (xls, xlsx) yang diizinkan!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif ($msg == 'error_insert') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Terjadi kesalahan saat menyimpan data ke database. Beberapa data mungkin gagal diimpor.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif ($msg == 'error_empty') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-triangle-exclamation"></i> File Excel kosong atau tidak memiliki data yang valid.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Import Guru</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="../guru.php">Guru</a></li>
        <li class="breadcrumb-item active">Import Guru</li>
      </ol>
      <?php echo $alert; ?>

      <div class="card">
        <div class="card-header">
          <span class="h5 my-2"><i class="fa-solid fa-file-excel"></i> Import Data Guru dari Excel </span>
        </div>
        <div class="card-body">
          <form action="proses-import-guru.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="excelFile" class="form-label">Pilih File Excel (.xls, .xlsx)</label>
              <input class="form-control" type="file" id="excelFile" name="excelFile" accept=".xls, .xlsx" required>
            </div>
            <button type="submit" name="import" class="btn btn-primary">
              <i class="fa-solid fa-upload"></i> Import
            </button>
            <a href="contoh_template_guru.xlsx" class="btn btn-secondary" download>
                <i class="fa-solid fa-download"></i> Unduh Contoh Template
            </a>
          </form>
        </div>
      </div>
    </div>
  </main>

<?php require_once "../template/footer.php"; ?>