<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php");
  exit();
}

require_once "../config.php"; // Koneksi ke database ($koneksi)

/* -------------------------------------------------------------------- */
/* HEADER UNTUK MEMAKSA BROWSER MENGUNDUH SEBAGAI EXCEL                  */
/* -------------------------------------------------------------------- */
$fileName = "Data_Nilai_Ekstrakurikuler_" . date('Ymd_His') . ".xls";

// Mengatur Header untuk file Excel (.xls)
header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel"); 
header("Pragma: no-cache");
header("Expires: 0");

/* ── Ambil semua data penilaian (TANPA FILTER) ─── */
$sql = "SELECT p.id_penilaian, p.nis, p.nama, p.nilai, p.sb_keterangan, p.b_keterangan, p.c_keterangan, p.k_keterangan, s.kelas, e.ekstrakulikuler
        FROM tbl_penilaian  p
        LEFT JOIN tbl_siswa s ON p.nis = s.nis
        LEFT JOIN tbl_ekstrakulikuler e ON p.no_ekskul = e.id
        ORDER BY p.id_penilaian DESC";
$q   = mysqli_query($koneksi, $sql);

/* -------------------------------------------------------------------- */
/* SIAPKAN BARIS DATA TABEL                                             */
/* -------------------------------------------------------------------- */
$today = date('d-m-Y  H:i');
$tblRows = '';
$no = 1;
while ($row = mysqli_fetch_assoc($q)) {
    // Gabungkan keterangan
    $keterangan = '';
    if ($row['sb_keterangan']) $keterangan .= 'Sangat Baik dalam: ' . $row['sb_keterangan'] . '. ';
    if ($row['b_keterangan'])  $keterangan .= 'Baik dalam: ' . $row['b_keterangan'] . '. ';
    if ($row['c_keterangan'])  $keterangan .= 'Cukup dalam: ' . $row['c_keterangan'] . '. ';
    if ($row['k_keterangan'])  $keterangan .= 'Kurang dalam: ' . $row['k_keterangan'] . '. ';
    
    // Tambahkan tanda kutip (') sebelum NIS
    $tblRows .= '<tr>
                   <td style="text-align:center;">'.$no++.'</td>
                   <td>'.htmlspecialchars($row['ekstrakulikuler']).'</td>
                   <td>\''.$row['nis'].'</td> 
                   <td>'.htmlspecialchars($row['nama']).'</td>
                   <td style="text-align:center;">'.$row['kelas'].'</td>
                   <td style="text-align:center;">'.$row['nilai'].'</td>
                   <td>'.htmlspecialchars($keterangan).'</td>
                 </tr>';
}

/* -------------------------------------------------------------------- */
/* TAMPILAN HTML YANG AKAN DIUNDUH SEBAGAI EXCEL                          */
/* -------------------------------------------------------------------- */
?>
<html>
<head>
  <meta charset="UTF-8">
  <title>Data Nilai Ekstrakurikuler</title>
</head>
<body>
  <center>
    <h2>DATA NILAI EKSTRAKURIKULER</h2>
    <p>SMA BOPKRI 1 YOGYAKARTA</p>
  </center>
  <p>Dicetak: <?= $today ?></p>
  <br>

  <table border="1">
    <thead>
      <tr>
        <th width="4%">No</th>
        <th width="18%">Ekstrakurikuler</th>
        <th width="12%">NIS</th>
        <th width="23%">Nama Siswa</th>
        <th width="7%">Kelas</th>
        <th width="7%">Nilai</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody><?= $tblRows ?></tbody>
  </table>
</body>
</html>