<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Update Siswa - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$nis = $_GET['nis'];

$siswa = mysqli_query($koneksi, "SELECT * FROM tbl_siswa WHERE nis = '$nis'");
$data = mysqli_fetch_array($siswa);
?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Update Siswa</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="../siswa.php">Siswa</a></li>
        <li class="breadcrumb-item active">Update Siswa</li>
      </ol>

      <form action="proses-siswa.php" method="POST" enctype="multipart/form-data">
        <div class="card">
          <div class="card-header">
            <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Update Siswa </span>
            <button type="submit" name="update" class="btn btn-primary float-end">
              <i class="fa-solid fa-floppy-disk"></i> Update
            </button>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-8">

                <div class="mb-3 row">
                  <label for="nis" class="col-sm-2 col-form-label">NIS</label>
                  <div class="col-sm-10">
                    <input type="text" name="nis" class="form-control" id="nis" value="<?= $nis ?>" readonly>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                  <div class="col-sm-10">
                    <input type="text" name="nama" required class="form-control" id="nama" value="<?= $data['nama'] ?>">
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                  <div class="col-sm-10">
                    <select name="kelas" id="kelas" class="form-select" required>
                      <?php
                      $kelas = ["X E1", "X E2", "X E3", "X E4", "X E5", "X E6", "X E7", "X E8", "XI F1", "XI F2", "XI F3", "XI F4", "XI F5", "XI F6", "XI F7", "XI F8", "XI F9", "XII", "XII 1", "XII 2", "XII 3", "XII 4"];
                      foreach ($kelas as $kls) {
                        if ($data['kelas'] == $kls) {
                          echo "<option value=\"$kls\" selected>$kls</option>";
                        } else {
                          echo "<option value=\"$kls\">$kls</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
                  <div class="col-sm-10">
                    <select name="jurusan" id="jurusan" class="form-select" required>
                      <?php
                      $jurusan = ["IPA", "IPS", "Bahasa"];
                      foreach ($jurusan as $jrs) {
                        if ($data['jurusan'] == $jrs) {
                          echo "<option value=\"$jrs\" selected>$jrs</option>";
                        } else {
                          echo "<option value=\"$jrs\">$jrs</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>

              </div>
              <div class="col-md-4"></div>
            </div>
          </div>
        </div>
      </form>

    </div>
  </main>

<?php require_once "../template/footer.php"; ?>
