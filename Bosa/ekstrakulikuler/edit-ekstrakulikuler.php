<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Ekstrakulikuler - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];

$queryEkstrakulikuler = mysqli_query($koneksi, "SELECT * FROM tbl_ekstrakulikuler WHERE id = $id");
$dataEdit = mysqli_fetch_array($queryEkstrakulikuler);

?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Update Ekstrakurikuler</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../ekstrakulikuler.php">Back</a></li>
      </ol>
      <div class="row">
        <div class="col-4">
          <div class="card">
            <div class="card-header">
              <i class="fa-solid fa-pen"></i> Edit Ekstrakurikuler
            </div>
            <div class="card-body">
              <form action="proses-ekstrakulikuler.php" method="POST">
                <input type="hidden" name="id" value="<?= $dataEdit['id'] ?>">
                <div class="mb-3">
                  <label for="ekstrakulikuler" class="form-label ps-1">Ekstrakurikuler</label>
                  <input type="text" class="form-control" id="ekstrakulikuler" name="ekstrakulikuler" placeholder="Ekstrakurikuler" value="<?= $dataEdit['ekstrakulikuler'] ?>" required>
                </div>

                <div class="mb-3">
                  <label for="guru" class="form-label ps-1">Guru</label>
                  <select name="guru" id="guru" class="form-select" required>
                    <?php
                    $queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru");
                    while ($dataGuru = mysqli_fetch_array($queryGuru)) {
                        if ($dataEdit['guru'] == $dataGuru['nama']) {
                    ?>
                      <option value="<?= $dataGuru['nama'] ?>" selected><?= $dataGuru['nama'] ?></option>
                    <?php } else { ?>
                      <option value="<?= $dataGuru['nama'] ?>"><?= $dataGuru['nama'] ?></option>
                    <?php
                        }
                    }
                    ?>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary" name="update"><i class="fa-solid fa-pen"></i> Update</button>
                <a href="ekstrakulikuler.php" class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Cancel</a>
              </form>
            </div>
          </div>
        </div>

        <div class="col-8">
          <div class="card">
            <div class="card-header"><i class="fa-solid fa-list"></i> Data Ekstrakurikuler</div>
            <div class="card-body">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col"><center>Id</center></th>
                    <th scope="col"><center>Ekstrakurikuler</center></th>
                    <th scope="col"><center>Guru</center></th>
                    <th scope="col"><center>Operasi</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $queryAll = mysqli_query($koneksi, "SELECT * FROM tbl_ekstrakulikuler");
                  while ($data = mysqli_fetch_array($queryAll)) {
                    if ($data['id'] == $id) {
                  ?>
                    <tr>
                      <th scope="row"><?= $no ?></th>
                      <td><center><?= $data['id'] ?></center></td>
                      <td><center><?= htmlspecialchars($data['ekstrakulikuler']) ?></center></td>
                      <td><center><?= htmlspecialchars($data['guru']) ?></center></td>
                      <td align="center">
                        <button type="button" class="btn btn-sm btn-warning rounded-0 col-10">Updating..</button>
                      </td>
                    </tr>
                  <?php
                    }
                    $no++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

<?php 
require_once "../template/footer.php";
?>
