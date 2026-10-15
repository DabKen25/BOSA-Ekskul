<?php
session_start();

/* ── Cek login ───────────────────────────────────────────── */
if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

/* ── Cek hak akses cetak ─────────────────────────────────── */
$isGuru = isset($_SESSION["jabatan"]) && $_SESSION["jabatan"] === "Guru";
$isTimKurikulum = isset($_SESSION["jabatan"]) && $_SESSION["jabatan"] === "Tim Kurikulum";
$bolehCetak = $isGuru || $isTimKurikulum;

require_once "../config.php";
$title = "Rekapitulasi Absensi Siswa - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

/* ── Query untuk mengambil tanggal unik ──────────────────────── */
$qTgl = mysqli_query($koneksi, "SELECT DISTINCT tgl FROM tbl_absensi ORDER BY tgl ASC");
$tglPertemuan = [];
while ($tglRow = mysqli_fetch_assoc($qTgl)) {
    $tglPertemuan[] = $tglRow['tgl'];
}

/* ── Query untuk data absensi detail ──────────────────────────── */
$sql = "SELECT 
            a.tgl, a.nis, a.nama, a.status, 
            s.kelas, 
            e.ekstrakulikuler AS nama_ekskul
        FROM 
            tbl_absensi a
        LEFT JOIN 
            tbl_siswa s ON a.nis = s.nis
        LEFT JOIN 
            tbl_ekstrakulikuler e ON a.id_ekskul = e.id
        ORDER BY 
            a.nis, a.tgl";

$q = mysqli_query($koneksi, $sql);

// Build array data per siswa
$rekapData = [];
while ($data = mysqli_fetch_assoc($q)) {
    $nis = $data['nis'];
    if (!isset($rekapData[$nis])) {
        $rekapData[$nis] = [
            'nama' => $data['nama'],
            'kelas' => $data['kelas'],
            'nama_ekskul' => $data['nama_ekskul'],
            'absensi' => [],
            'total_hadir' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_alpa' => 0
        ];
    }
    $rekapData[$nis]['absensi'][$data['tgl']] = $data['status'];
    
    // Hitung total
    switch ($data['status']) {
        case 'H': $rekapData[$nis]['total_hadir']++; break;
        case 'I': $rekapData[$nis]['total_izin']++; break;
        case 'S': $rekapData[$nis]['total_sakit']++; break;
        case 'A': $rekapData[$nis]['total_alpa']++; break;
    }
}
?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <h1 class="mt-4">Rekapitulasi Absensi Siswa</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
        <li class="breadcrumb-item active">Rekapitulasi Absensi</li>
      </ol>

      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fa-solid fa-table me-1"></i> Data Rekap Absensi per Siswa dan Tanggal
              
              <?php if ($bolehCetak): ?>
              <div class="float-end">
                <a href="cetak-absensi.php" target="_blank" class="btn btn-sm btn-danger">
                  <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
                <a href="cetak-absensi-excel.php" target="_blank" class="btn btn-sm btn-success ms-2">
                  <i class="fa-solid fa-file-excel"></i> Excel
                </a>
              </div>
              <?php endif; ?>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped" id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>NIS</th>
                      <th>Nama Siswa</th>
                      <th>Kelas</th>
                      <th>Ekstrakurikuler</th>
                      <?php foreach ($tglPertemuan as $tgl): ?>
                        <th><?= date('d/m/Y', strtotime($tgl)) ?></th>
                      <?php endforeach; ?>
                      <th>Total Hadir</th>
                      <th>Total Izin</th>
                      <th>Total Sakit</th>
                      <th>Total Alpa</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($rekapData as $nis => $siswaData) { 
                    ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($nis) ?></td>
                        <td><?= htmlspecialchars($siswaData['nama']) ?></td>
                        <td><?= htmlspecialchars($siswaData['kelas']) ?></td>
                        <td><?= htmlspecialchars($siswaData['nama_ekskul']) ?></td>
                        <?php foreach ($tglPertemuan as $tgl): 
                          $status = $siswaData['absensi'][$tgl] ?? '-';
                          $badgeClass = '';
                          switch ($status) {
                              case 'H': $badgeClass = 'bg-success'; break;
                              case 'I': $badgeClass = 'bg-warning text-dark'; break;
                              case 'S': $badgeClass = 'bg-info text-dark'; break;
                              case 'A': $badgeClass = 'bg-danger'; break;
                              default: $badgeClass = 'bg-secondary'; $status = '-';
                          }
                        ?>
                          <td class="text-center"><span class="badge <?= $badgeClass ?>"><?= $status ?></span></td>
                        <?php endforeach; ?>
                        <td class="text-center"><span class="badge bg-success"><?= $siswaData['total_hadir'] ?></span></td>
                        <td class="text-center"><span class="badge bg-warning text-dark"><?= $siswaData['total_izin'] ?></span></td>
                        <td class="text-center"><span class="badge bg-info text-dark"><?= $siswaData['total_sakit'] ?></span></td>
                        <td class="text-center"><span class="badge bg-danger"><?= $siswaData['total_alpa'] ?></span></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>

              <?php if (empty($tglPertemuan)): ?>
              <div class="alert alert-info mt-3">
                <i class="fa-solid fa-info-circle"></i> Belum ada data presensi yang tercatat.
              </div>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>

      <?php if (!empty($tglPertemuan)): ?>
      <div class="row mt-4">
        <div class="col-12">
          <div class="alert alert-secondary">
            <i class="fa-solid fa-calendar-days"></i> Data di atas direkap berdasarkan total dari 
            <strong><?= count($tglPertemuan) ?></strong> tanggal pertemuan unik:
            <br>
            <?= implode(", ", array_map(function($t) { return date('d/m/Y', strtotime($t)); }, $tglPertemuan)); ?>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </main>
</div>

<?php require_once "../template/footer.php"; ?>