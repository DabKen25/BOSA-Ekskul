<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Siswa - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Siswa</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item active">Siswa</li>
      </ol>

      <div class="card">
        <div class="card-header">
          <span class="h5 my-2"><i class="fa-solid fa-list"></i> Data Siswa </span>
          
          <?php if ($_SESSION["jabatan"] == "Tim Kurikulum") : ?>
            <a href="<?= $main_url ?>siswa/add-siswa.php" class="btn btn-sm btn-primary float-end">
              <i class="fa-solid fa-plus"></i> Tambah Siswa </a>
            <a href="<?= $main_url ?>siswa/import-siswa.php" class="btn btn-sm btn-success float-end me-2">
              <i class="fa-solid fa-file-excel"></i> Import Siswa </a>
            <a href="<?= $main_url ?>siswa/hapus-semua-siswa.php" class="btn btn-sm btn-danger float-end me-2">
              <i class="fa-solid fa-trash-can"></i> Hapus Semua </a>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <table class="table table-hover" id="datatablesSimple">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col"><center>NIS</center></th>
                <th scope="col"><center>Nama</center></th>
                <th scope="col"><center>Kelas</center></th>
                <th scope="col"><center>Ekstrakurikuler</center></th>  
                <?php if ($_SESSION["jabatan"] == "Tim Kurikulum") : ?>
                  <th scope="col"><center>Operasi</center></th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $querySiswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa");
            while ($data = mysqli_fetch_array($querySiswa)) { ?>
              <tr>
                <th scope="row"><?= $no++ ?></th>
                <td><?= $data['nis']; ?></td>
                <td><?= $data['nama']; ?></td>
                <td><?= $data['kelas']; ?></td>
                <td><?= $data['Ekstrakurikuler']; ?></td>
                
                <?php if ($_SESSION["jabatan"] == "Tim Kurikulum") : ?>
                  <td align="center">
                    <a href="edit-siswa.php?nis=<?= $data['nis'] ?>" class="btn btn-sm btn-warning" title="Update Siswa"><i class="fa-solid fa-pen"></i></a>
                    <a href="hapus-siswa.php?nis=<?= $data['nis'] ?>" class="btn btn-sm btn-danger" title="Hapus Siswa" onclick="return confirm('Apakah anda yakin menghapus data ini?')"><i class="fa-solid fa-trash"></i></a>
                  </td>
                <?php endif; ?>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
<?php require_once "../template/footer.php"; ?>