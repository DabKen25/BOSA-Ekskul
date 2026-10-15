<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Guru - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$msg = isset($_GET['msg']) ? $_GET['msg'] : "";
$alert = '';

// Logika Alert
if ($msg == 'all_deleted') {
  $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-check"></i> Semua data guru telah dikosongkan.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

if ($msg == 'delete_failed') {
  $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="fa-solid fa-circle-xmark"></i> Gagal menghapus data. Database menolak perintah penghapusan massal.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Guru</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item active">Guru</li>
      </ol>

      <?php if (!empty($msg)) echo $alert; ?>

      <div class="card">
        <div class="card-header">
          <span class="h5 my-2"><i class="fa-solid fa-list"></i> Data Guru </span>
          <a href="<?= $main_url ?>guru/add-guru.php" class="btn btn-sm btn-primary float-end ms-2">
            <i class="fa-solid fa-plus"></i> Tambah Guru </a>
          <a href="<?= $main_url ?>guru/import-guru.php" class="btn btn-sm btn-success float-end me-2">
            <i class="fa-solid fa-file-excel"></i> Import Data Excel </a>
          
          <a href="hapus-semua-guru.php?confirm=true" class="btn btn-sm btn-danger float-end me-2" onclick="return confirm('Apakah Anda yakin ingin MENGHAPUS SEMUA DATA GURU?')">
            <i class="fa-solid fa-trash-can"></i> Hapus Semua Data </a>
        </div>
        <div class="card-body">
          <table class="table table-hover" id="datatablesSimple">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col"><center>ID Guru</center></th>
                <th scope="col"><center>Nama Guru</center></th>
                <th scope="col"><center>Ekstrakulikuler</center></th>
                <th scope="col"><center>Operasi</center></th>
              </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru");
            while ($data = mysqli_fetch_array($queryGuru)) { ?>
              <tr>
                <th scope="row"><?= $no++ ?></th>
                <td><?= $data['id']; ?></td>
                <td><?= $data['nama']; ?></td>
                <td><?= $data['ekstrakulikuler']; ?></td>
                <td align="center">
                  <a href="edit-guru.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
                  <a href="hapus-guru.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')"><i class="fa-solid fa-trash"></i></a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
<?php require_once "../template/footer.php"; ?>