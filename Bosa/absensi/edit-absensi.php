<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php");
  exit();
}

require_once "../config.php";
$title = "Edit Absensi - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

/*
 * Mengambil id dari URL dan memastikan id tersebut ada.
 */
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: absensi.php?msg=error");
  exit();
}
$id = intval($_GET['id']);

/*
 * Mengambil data absensi berdasarkan id.
 */
$q = mysqli_query($koneksi, "SELECT a.* FROM tbl_absensi a WHERE a.id_absensi=$id");
if (mysqli_num_rows($q) == 0) {
  header("Location: absensi.php?msg=error");
  exit();
}
$data = mysqli_fetch_assoc($q);

/*
 * Mengambil daftar ekstrakurikuler untuk dropdown.
 */
$qEkskul = mysqli_query($koneksi, "SELECT id, ekstrakulikuler FROM tbl_ekstrakulikuler ORDER BY ekstrakulikuler");

/*
 * Mengambil daftar siswa untuk dropdown.
 */
$qSiswa = mysqli_query($koneksi, "SELECT nis, nama FROM tbl_siswa ORDER BY nama");
?>
<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Edit Absensi</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="absensi.php">Data Absensi</a></li>
        <li class="breadcrumb-item active">Edit Absensi</li>
      </ol>
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-header">
              <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit Absensi</span>
            </div>
            <div class="card-body">
              <form action="update-absensi.php" method="POST">
                <input type="hidden" name="id_absensi" value="<?= $data['id_absensi'] ?>">
                <div class="mb-3">
                  <label for="tgl" class="form-label">Tanggal</label>
                  <input type="date" name="tgl" id="tgl" class="form-control" value="<?= htmlspecialchars($data['tgl']) ?>" required>
                </div>
                <div class="mb-3">
                  <label for="nis" class="form-label">NIS</label>
                  <select name="nis" id="nis" class="form-control" required>
                    <option value="">-- Pilih NIS --</option>
                    <?php while ($dataSiswa = mysqli_fetch_assoc($qSiswa)) { ?>
                      <option value="<?= $dataSiswa['nis'] ?>" <?= ($data['nis'] == $dataSiswa['nis']) ? 'selected' : '' ?>><?= htmlspecialchars($dataSiswa['nis'] . ' - ' . $dataSiswa['nama']) ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="nama" class="form-label">Nama Siswa</label>
                  <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
                </div>
                <div class="mb-3">
                  <label for="ekskul" class="form-label">Ekstrakurikuler</label>
                  <select name="ekskul" id="ekskul" class="form-control" required>
                    <option value="">-- Pilih Ekstrakurikuler --</option>
                    <?php while ($dataEkskul = mysqli_fetch_assoc($qEkskul)) { ?>
                      <option value="<?= $dataEkskul['id'] ?>" <?= ($data['id_ekskul'] == $dataEkskul['id']) ? 'selected' : '' ?>><?= htmlspecialchars($dataEkskul['ekstrakulikuler']) ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="hadir" value="H" <?= ($data['status'] == 'H') ? 'checked' : '' ?> required>
                    <label class="form-check-label" for="hadir">Hadir</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="izin" value="I" <?= ($data['status'] == 'I') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="izin">Izin</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="sakit" value="S" <?= ($data['status'] == 'S') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="sakit">Sakit</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="alpa" value="A" <?= ($data['status'] == 'A') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="alpa">Alpa</label>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="keterangan" class="form-label">Keterangan</label>
                  <textarea name="keterangan" id="keterangan" class="form-control" rows="2"><?= htmlspecialchars($data['keterangan']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-success" name="update">
                  <i class="fa-solid fa-floppy-disk"></i> Update
                </button>
                <a href="absensi.php" class="btn btn-secondary">
                  <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
<?php
require_once "../template/footer.php";
?>