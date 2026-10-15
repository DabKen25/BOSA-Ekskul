<?php
session_start();

/* Cek login */
if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php");
  exit();
}

require_once "../config.php";

$title = "Edit Penilaian - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

/* Ambil id dari query string */
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: penilaian.php?msg=error");
  exit();
}

$id = intval($_GET['id']);

/* Ambil data penilaian berdasarkan id (TANPA FILTER) */
$query = mysqli_query($koneksi, "SELECT * FROM tbl_penilaian WHERE id_penilaian = $id");
if (mysqli_num_rows($query) == 0) {
  // Jika data tidak ditemukan, kembali ke halaman penilaian
  header("Location: penilaian.php?msg=error");
  exit();
}

$data = mysqli_fetch_assoc($query);

/* Ambil daftar siswa untuk dropdown */
$qSiswa = mysqli_query($koneksi, "SELECT nis, nama FROM tbl_siswa ORDER BY nama");

/* Ambil daftar ekstrakurikuler untuk dropdown */
$qEkskul = mysqli_query($koneksi, "SELECT id, ekstrakulikuler FROM tbl_ekstrakulikuler ORDER BY ekstrakulikuler");
?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Edit Penilaian</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="penilaian.php">Penilaian</a></li>
        <li class="breadcrumb-item active">Edit Penilaian</li>
      </ol>

      <div class="card">
        <div class="card-header">
          <span class="h5 my-2"><i class="fa-solid fa-pen-to-square"></i> Edit Data Penilaian</span>
        </div>
        <div class="card-body">
          <form action="update-penilaian.php" method="POST">
            <input type="hidden" name="id_penilaian" value="<?= $data['id_penilaian'] ?>">

            <div class="mb-3">
              <label for="no_ekskul" class="form-label">Ekstrakurikuler</label>
              <select name="no_ekskul" id="no_ekskul" class="form-select" required>
                <option value="" selected>--Pilih Ekskul--</option>
                <?php
                while ($row = mysqli_fetch_assoc($qEkskul)) {
                  $selected = ($row['id'] == $data['no_ekskul']) ? 'selected' : '';
                  echo "<option value=\"{$row['id']}\" {$selected}>{$row['ekstrakulikuler']}</option>";
                }
                ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="nis" class="form-label">NIS - Nama Siswa</label>
              <select name="nis" id="nis" class="form-select" required>
                <?php
                while ($row = mysqli_fetch_assoc($qSiswa)) {
                  $selected = ($row['nis'] == $data['nis']) ? 'selected' : '';
                  echo "<option value=\"{$row['nis']}\" {$selected}>{$row['nis']} - {$row['nama']}</option>";
                }
                ?>
              </select>
            </div>
            
            <div class="mb-3">
              <label for="nama_otomatis" class="form-label">Nama Siswa</label>
              <input type="text" class="form-control" id="nama_otomatis" value="<?= htmlspecialchars($data['nama']) ?>" readonly disabled>
              <input type="hidden" id="nama_hidden" name="nama" value="<?= htmlspecialchars($data['nama']) ?>"> </div>

            <div class="mb-3">
              <label for="nilai" class="form-label">Nilai</label>
              <select name="nilai" id="nilai" class="form-select" required>
                <option value="SB" <?= ($data['nilai'] == 'SB') ? 'selected' : '' ?>>SB (Sangat Baik)</option>
                <option value="B" <?= ($data['nilai'] == 'B') ? 'selected' : '' ?>>B (Baik)</option>
                <option value="C" <?= ($data['nilai'] == 'C') ? 'selected' : '' ?>>C (Cukup)</option>
                <option value="K" <?= ($data['nilai'] == 'K') ? 'selected' : '' ?>>K (Kurang)</option>
              </select>
            </div>

            <hr>
            <h6>Detail Keterangan (Opsional)</h6>
            <div class="mb-3">
              <label for="sb_keterangan" class="form-label">Sangat Baik dalam...</label>
              <textarea name="sb_keterangan" id="sb_keterangan" class="form-control" rows="2"><?= htmlspecialchars($data['sb_keterangan']) ?></textarea>
              <div class="form-text">Isi ini jika Nilai = SB (Sangat Baik)</div>
            </div>
            <div class="mb-3">
              <label for="b_keterangan" class="form-label">Baik dalam...</label>
              <textarea name="b_keterangan" id="b_keterangan" class="form-control" rows="2"><?= htmlspecialchars($data['b_keterangan']) ?></textarea>
              <div class="form-text">Isi ini jika Nilai = B (Baik)</div>
            </div>
            <div class="mb-3">
              <label for="c_keterangan" class="form-label">Cukup dalam...</label>
              <textarea name="c_keterangan" id="c_keterangan" class="form-control" rows="2"><?= htmlspecialchars($data['c_keterangan']) ?></textarea>
              <div class="form-text">Isi ini jika Nilai = C (Cukup)</div>
            </div>
            <div class="mb-3">
              <label for="k_keterangan" class="form-label">Kurang dalam...</label>
              <textarea name="k_keterangan" id="k_keterangan" class="form-control" rows="2"><?= htmlspecialchars($data['k_keterangan']) ?></textarea>
              <div class="form-text">Isi ini jika Nilai = K (Kurang)</div>
            </div>

            <button type="submit" class="btn btn-success" name="update">
              <i class="fa-solid fa-floppy-disk"></i> Update
            </button>
            <a href="penilaian.php" class="btn btn-secondary">
              <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Auto-fill nama saat NIS dipilih
    $(function() {
      $('#nis').on('change', function () {
        const selectedText = $(this).find('option:selected').text();
        const parts = selectedText.split(' - ');
        
        if (parts.length > 1) {
            const nama = parts.slice(1).join(' - '); 
            $('#nama_otomatis').val(nama);
            $('#nama_hidden').val(nama); // Isi hidden input
        } else {
            $('#nama_otomatis').val('');
            $('#nama_hidden').val('');
        }
      });
    });
  </script>

<?php
require_once "../template/footer.php";
?>