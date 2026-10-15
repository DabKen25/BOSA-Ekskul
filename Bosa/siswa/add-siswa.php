<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Tambah Siswa - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$queryNis = mysqli_query($koneksi, "SELECT max(nis) as maxnis FROM tbl_siswa");
$data = mysqli_fetch_array($queryNis);
$maxnis = $data ["maxnis"];

$noUrut = (int) substr($maxnis, 3, 3);
$noUrut++;
$maxnis = "NIS" . sprintf("%03s", $noUrut);

?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Tambah Siswa</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="../siswa.php">Siswa</a></li>
        <li class="breadcrumb-item active">Tambah Siswa</li>
      </ol>
      <form action="proses-siswa.php" method="POST" enctype="multipart/form-data">

      <div class="card">
        <div class="card-header">
          <span class="h5 my-2"><i class="fa-solid fa-square-plus"></i> Tambah Siswa </span>
          <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
          <button type="reset" name="reset" class="btn btn-danger float-end me-1"><i class="fa-solid fa-xmark"></i> Reset</button>
      
        </div>
        <div class="card-body">
          <form action="proses-tambah.php" method="post">
            <div class="row">
              <div class="col-8">
                <div class="mb-3 row">
                  <label for="nis" class="col-sm-2 col-form-label">NIS</label>
                  <label for="nis" class="col-sm-1 col-form-label">:</label>
                  <div class="col-sm-9" style="margin-left: -50px;">
                    <input type="text" name="nis" class="form-control border-bottom ps-2" id="nis" value=" <?=$maxnis ?> ">
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                  <label for="nama" class="col-sm-1 col-form-label">:</label>
                  <div class="col-sm-9" style="margin-left: -50px;">
                    <input type="text" name="nama" required class="form-control border-bottom ps-2" id="nama">
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                  <label for="kelas" class="col-sm-1 col-form-label">:</label>
                  <div class="col-sm-9" style="margin-left: -50px;">
                    <select name="kelas" id="kelas" class="form-select border-0 border-bottom" required>
                      <option selected disabled>--Pilih Kelas--</option>
                      <option value="X E1">X E1</option>
                      <option value="X E2">X E2</option>
                      <option value="X E3">X E3</option>
                      <option value="X E4">X E4</option>
                      <option value="X E5">X E5</option>
                      <option value="X E6">X E6</option>
                      <option value="X E7">X E7</option>
                      <option value="X E8">X E8</option>
                      <option value="XI F1">XI F1</option>
                      <option value="XI F2">XI F2</option>
                      <option value="XI F3">XI F3</option>
                      <option value="XI F4">XI F4</option>
                      <option value="XI F5">XI F5</option>
                      <option value="XI F6">XI F6</option>
                      <option value="XI F7">XI F7</option>
                      <option value="XIF8">XI F8</option>
                      <option value="XI F9">XI F9</option>
                      <option value="XII">XII</option>
                      <option value="XII 1">XII 1</option>
                      <option value="XII 2">XII 2</option>
                      <option value="XII 3">XII 3</option>
                      <option value="XII 4">XII 4</option>
                    </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
                  <label for="jurusan" class="col-sm-1 col-form-label">:</label>
                  <div class="col-sm-9" style="margin-left: -50px;">
                    <select name="jurusan" id="jurusan" class="form-select border-0 border-bottom" required>
                      <option selected disabled>--Pilih Jurusan--</option>
                      <option value="IPA">IPA</option>
                      <option value="IPS">IPS</option>
                      <option value="Bahasa">Bahasa</option>
                    </select>
                  </div>
                </div>

            <div class="col-4"></div>
                  </div>
                </div>

              </div>
            </div>
          </form>
        </div>
      </div>
      </form>
    </div>
  </main>

<?php 

require_once "../template/footer.php"; 

?>
