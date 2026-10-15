<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php");
  exit();
}

/* ── Tentukan hak akses berdasarkan jabatan ────────────────── */
$jabatan = $_SESSION["jabatan"] ?? '';

$isGuru         = $jabatan === "Guru";
$isTimKurikulum = $jabatan === "Tim Kurikulum";
$bolehCetak     = $isGuru || $isTimKurikulum;     // boleh cetak PDF & Excel
$bolehEdit      = $isGuru;                        // boleh simpan, edit, hapus, input data

require_once "../config.php";

$title = "Data Nilai Ekstrakurikuler - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

/* ── ALERT pesan ─────────────────────────────────────────────────────── */
$msg   = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
$alert = '';
switch ($msg) {
  case 'added':
    $alert = '<div class="alert alert-success alert-dismissible fade show" id="flash" role="alert">
                <i class="fa-solid fa-circle-check"></i> Penilaian berhasil ditambahkan
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    break;
  case 'deleted':
    $alert = '<div class="alert alert-success alert-dismissible fade show" id="flash" role="alert">
                <i class="fa-solid fa-circle-check"></i> Data berhasil dihapus
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    break;
  case 'truncate':
    $alert = '<div class="alert alert-success alert-dismissible fade show" id="flash" role="alert">
                <i class="fa-solid fa-trash-can"></i> Seluruh data penilaian telah dikosongkan
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    break;
}

/* ── Query Dropdown Berdasarkan Hak Akses ─────────────────── */
if ($isGuru && isset($_SESSION['id_ekskul'])) {
    $id_ekskul = $_SESSION['id_ekskul'];
    // Guru hanya bisa melihat & menilai ekskul miliknya sendiri
    $qEkskul = mysqli_query($koneksi, "
        SELECT id, ekstrakulikuler 
        FROM tbl_ekstrakulikuler 
        WHERE id = '$id_ekskul'
    ");
} else {
    // Tim Kurikulum atau Admin bisa melihat semua ekskul
    $qEkskul = mysqli_query($koneksi, "
        SELECT id, ekstrakulikuler 
        FROM tbl_ekstrakulikuler 
        ORDER BY ekstrakulikuler
    ");
}

/* ── Query Data Penilaian ────────────────────────────── */
$whereEkskul = '';
if ($isGuru && isset($_SESSION['id_ekskul'])) {
    $whereEkskul = " WHERE p.no_ekskul = '$id_ekskul'";
}
$queryPenilaian = mysqli_query($koneksi, "SELECT p.*, s.kelas, e.ekstrakulikuler, s.nama, p.nis
                                         FROM tbl_penilaian p
                                         LEFT JOIN tbl_siswa s ON p.nis = s.nis
                                         LEFT JOIN tbl_ekstrakulikuler e ON p.no_ekskul = e.id
                                         $whereEkskul
                                         ORDER BY p.id_penilaian DESC");
?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Penilaian Ekstrakurikuler</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item active">Penilaian</li>
      </ol>

      <?= $alert ?>

      <div class="card mb-4">
        <div class="card-header">
          <div class="row">
            <div class="col-md-4">
                <span class="h5 my-2"><i class="fa-solid fa-list"></i> Data Penilaian</span>
            </div>
            <div class="col-md-8 text-end">
                <?php if ($bolehEdit): ?>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mdlTambah">
                    <i class="fa-solid fa-plus"></i> Tambah Penilaian
                </button>
                <?php endif; ?>
                <?php if ($bolehCetak): ?>
                <a href="cetak-penilaian.php" target="_blank" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-print"></i> PDF
                </a>
                <a href="cetak-penilaian-excel.php" target="_blank" class="btn btn-info btn-sm text-white">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </a>
                <?php endif; ?>
                <?php if ($bolehEdit): ?>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#mdlHapusSemua">
                    <i class="fa-solid fa-dumpster-fire"></i> Hapus Semua Data
                </button>
                <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="datatablesSimple" class="table table-striped table-hover border">
            <thead>
              <tr>
                <th>No</th>
                <th>Ekskul</th>
                <th>Siswa</th>
                <th>Nilai</th>
                <th>Keterangan</th>
                <?php if ($bolehEdit): ?>
                <th>Aksi</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              while ($data = mysqli_fetch_assoc($queryPenilaian)) {
                $ket = array_filter([$data['sb_keterangan'], $data['b_keterangan'], $data['c_keterangan'], $data['k_keterangan']]);
              ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><span class="badge bg-secondary"><?= htmlspecialchars($data['ekstrakulikuler']) ?></span></td>
                  <td><strong><?= htmlspecialchars($data['nama']) ?></strong><br><small><?= $data['nis'] ?></small></td>
                  <td class="text-center"><strong><?= $data['nilai'] ?></strong></td>
                  <td><small><?= htmlspecialchars(implode('. ', $ket)) ?></small></td>
                  <?php if ($bolehEdit): ?>
                  <td>
                    <button type="button" class="btn btn-sm btn-danger" id="btnHapus" data-id="<?= $data['id_penilaian'] ?>">
                      <i class="fa-solid fa-trash"></i>
                    </button>
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

  <?php if ($bolehEdit): ?>
  <div class="modal fade" id="mdlTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form action="proses-penilaian.php" method="POST">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Input Penilaian Kolektif</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="fw-bold">Pilih Ekstrakurikuler</label>
                    <select name="no_ekskul" id="pilih_ekskul" class="form-select" required>
                        <?php if (!$isGuru): ?>
                        <option value="">-- Pilih Ekskul --</option>
                        <?php endif; ?>
                        <?php mysqli_data_seek($qEkskul, 0); while ($row = mysqli_fetch_assoc($qEkskul)) : ?>
                          <option value="<?= $row['id'] ?>"><?= $row['ekstrakulikuler'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-sm">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th width="5%">Pilih</th>
                            <th width="30%">Nama Siswa</th>
                            <th width="15%">Nilai</th>
                            <th>Keterangan (Dalam Hal Apa)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $qSiswa = mysqli_query($koneksi, "SELECT nis, nama FROM tbl_siswa ORDER BY nama");
                        $idx = 0;
                        while($s = mysqli_fetch_assoc($qSiswa)) : ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="pilih_siswa[]" value="<?= $idx ?>" class="form-check-input check-siswa">
                                <input type="hidden" name="nis[<?= $idx ?>]" value="<?= $s['nis'] ?>">
                                <input type="hidden" name="nama[<?= $idx ?>]" value="<?= $s['nama'] ?>">
                            </td>
                            <td><small><strong><?= $s['nama'] ?></strong><br><?= $s['nis'] ?></small></td>
                            <td>
                                <select name="nilai[<?= $idx ?>]" class="form-select form-select-sm select-nilai-row" data-index="<?= $idx ?>">
                                    <option value="">- Nilai -</option>
                                    <option value="SB">SB</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="K">K</option>
                                </select>
                            </td>
                            <td>
                                <textarea name="ket[<?= $idx ?>]" class="form-control form-control-sm text-keterangan" id="ket_<?= $idx ?>" rows="1" disabled placeholder="Keterangan..."></textarea>
                            </td>
                        </tr>
                        <?php $idx++; endwhile; ?>
                    </tbody>
                </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="simpan" class="btn btn-primary">Simpan Data Dipilih</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($bolehEdit): ?>
  <div class="modal fade" id="mdlHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">Yakin ingin menghapus data ini?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <a href="#" id="btnMdlHapus" class="btn btn-danger">Hapus</a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="mdlHapusSemua" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation"></i> PERINGATAN KERAS</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-center">Anda akan menghapus <strong>SELURUH</strong> data penilaian ekstrakurikuler. Tindakan ini tidak dapat dibatalkan!</p>
          <p class="text-center fw-bold">Apakah Anda benar-benar yakin?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <a href="hapus-penilaian.php" class="btn btn-danger fw-bold">YA, HAPUS SEMUA DATA</a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      setTimeout(() => $('#flash').fadeOut('slow'), 3000);

      // Logika Aktivasi Textarea berdasarkan Nilai
      $(document).on('change', '.select-nilai-row', function() {
          const idx = $(this).data('index');
          const nilai = $(this).val();
          const textarea = $('#ket_' + idx);
          const checkbox = $('.check-siswa[value="' + idx + '"]');

          if(nilai !== "") {
              textarea.prop('disabled', false).attr('placeholder', 'Isi keterangan ' + nilai + '...').focus();
              checkbox.prop('checked', true);
          } else {
              textarea.prop('disabled', true).val('').attr('placeholder', 'Keterangan...');
              checkbox.prop('checked', false);
          }
      });

      // Modal Hapus Satu Data
      $(document).on('click', '#btnHapus', function () {
        const id = $(this).data('id');
        $('#mdlHapus').modal('show');
        $('#btnMdlHapus').attr('href', 'hapus-penilaian.php?id=' + id);
      });
    });
  </script>

<?php require_once "../template/footer.php"; ?>