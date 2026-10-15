<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Import Ekstrakurikuler - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Import Data Ekstrakurikuler dari Excel</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="../ekstrakulikuler.php">Ekstrakurikuler</a></li>
        <li class="breadcrumb-item active">Import Ekstrakurikuler</li>
      </ol>

      <?php
      // Menampilkan pesan sukses/error dari proses-import-ekstrakulikuler.php
      if (isset($_SESSION['import_message'])) {
          echo '<div class="alert ' . $_SESSION['import_message']['type'] . ' alert-dismissible fade show" role="alert">
                  <i class="fa-solid fa-circle-' . ($_SESSION['import_message']['type'] == 'alert-success' ? 'check' : 'xmark') . '"></i> ' . $_SESSION['import_message']['text'] . '
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
          unset($_SESSION['import_message']); // Hapus pesan setelah ditampilkan
      }
      ?>

      <div class="card">
        <div class="card-header">
          <span class="h5 my-2"><i class="fa-solid fa-file-excel"></i> Import Ekstrakurikuler dari Excel </span>
        </div>
        <div class="card-body">
          <form action="proses-import-ekstrakulikuler.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="file_excel" class="form-label">Pilih File Excel (.xlsx atau .xls)</label>
              <input class="form-control" type="file" id="file_excel" name="file_excel" accept=".xlsx, .xls" required>
              <div class="form-text">
                Pastikan file Excel memiliki kolom dengan nama "Ekstrakurikuler" dan "Guru" (sesuai urutan di tabel).
                Baris pertama akan dianggap sebagai header.
              </div>
            </div>
            <button type="submit" name="import" class="btn btn-primary">
              <i class="fa-solid fa-file-import"></i> Import Data
            </button>
            <a href="<?= $main_url ?>ekstrakulikuler.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
          </form>
        </div>
      </div>
    </div>
  </main>

<?php
require_once "../template/footer.php";
?>
