<?php

session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Tambah Guru - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$id = $_GET['id'];

$queryGuru = mysqli_query($koneksi, "SELECT * FROM tbl_guru WHERE id = '$id'");
$data = mysqli_fetch_array($queryGuru);

?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Update Guru</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="../guru.php">Guru</a></li>
        <li class="breadcrumb-item active">Update Guru</li>
      </ol>
      <form action="proses-guru.php" method="POST" enctype="multipart/form-data">
        <div class="card">
          <div class="card-header">
            <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Update Guru </span>
            <button type="submit" name="update" class="btn btn-primary float-end ms-2">
              <i class="fa-solid fa-floppy-disk"></i> Update </button>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-8">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">

                <div class="mb-3 row">
                  <label for="id" class="col-sm-3 col-form-label">Id</label>
                  <div class="col-sm-9">
                    <input type="text" name="id" pattern="[0-9]{10,}" title="Minimal 10 digit angka" class="form-control border-0 border-bottom ps-2" value="<?= $data['id'] ?>" required>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                  <div class="col-sm-9">
                    <input type="text" name="nama" id="nama" class="form-control border-0 border-bottom ps-2" value = "<?= $data['nama'] ?>" required>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="ekstrakulikuler" class="col-sm-3 col-form-label">Ekstrakurikuler</label>
                  <div class="col-sm-9">
                    <select name="ekstrakulikuler" id="ekstrakulikuler" class="form-select border-0 border-bottom" required>
                      <option selected disabled>--Pilih ekstrakurikuler--</option>
                      <option value="Pramuka">Pramuka</option>
                      <option value="Jurnalistik">Jurnalistik</option>
                      <option value="Cheers Leader">Cheers Leader</option>
                      <option value="Modern Dance">Modern Dance</option>
                      <option value="Fotografi">Fotografi</option>
                      <option value="Karate">Karate</option>
                      <option value="Basket Putra">Basket Putra</option>
                      <option value="Basket Putri">Basket Putri</option>
                      <option value="Futsal Putra">Futsal Putra</option>
                      <option value="Bulu Tangkis">Bulu Tangkis</option>
                      <option value="Teater">Teater</option>
                      <option value="Vocal Group / Paduan Suara">Vocal Group / Paduan Suara</option>
                      <option value="Band / Musik (Ensamble Gesek)">Band / Musik (Ensamble Gesek)</option>
                      <option value="Pecinta Alam dan PMR">Pecinta Alam dan PMR</option>
                      <option value="Pemrograman (TIK)">Pemrograman (TIK)</option>
                      <option value="Tari / Kerawitan">Tari / Kerawitan</option>
                      <option value="KIR (Karya Ilmiah Remaja)">KIR (Karya Ilmiah Remaja)</option>
                      <option value="Sinematografi">Sinematografi</option>
                      <option value="PAB">PAB</option>
                      <option value="Hoki">Hoki</option>
                      <option value="Hapkido">Hapkido</option>
                      <option value="Tonti">Tonti</option>
                    </select>
                  </div>
                </div>

              </div> <!-- end col-lg-8 -->
              <div class="col-4 text-center px-5">
                <!-- Bisa digunakan untuk preview foto atau informasi tambahan -->
              </div>
            </div> <!-- end row -->
          </div> <!-- end card-body -->
        </div> <!-- end card -->
      </form>
    </div> <!-- end container-fluid -->
  </main>

<?php require_once "../template/footer.php"; ?>
