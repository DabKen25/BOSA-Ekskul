<?php
session_start();
if (!isset($_SESSION["ssLogin"])) {
  header("Location: ../auth/login.php");
  exit();
}

require_once "../config.php";          // koneksi $koneksi
require_once "../vendor/autoload.php"; // load Dompdf

use Dompdf\Dompdf;

/* ──────────────────────────────────────────────────────────────────── */
/* ── PENGEMBALIAN: Ambil username dari session untuk placeholder ───── */
/* ──────────────────────────────────────────────────────────────────── */
// Asumsi 'ssLogin' berisi username (seperti yang ada di kode awal Anda)
$username_login = $_SESSION['ssLogin']; 

// Query untuk mengambil nama lengkap dari tbl_user
$qUser = mysqli_query($koneksi, 
           "SELECT nama FROM tbl_user 
            WHERE username = '$username_login'");

$guruPendamping = 'Guru Pendamping'; // Default value
if (mysqli_num_rows($qUser) > 0) {
    $dataUser = mysqli_fetch_assoc($qUser);
    $guruPendamping = htmlspecialchars($dataUser['nama']);
}
/* ──────────────────────────────────────────────────────────────────── */

/* ── Ambil semua data penilaian (TANPA FILTER) ─────────────────────── */
$sql = "SELECT p.id_penilaian, p.nis, p.nama, p.nilai, p.sb_keterangan, p.b_keterangan, p.c_keterangan, p.k_keterangan, s.kelas, e.ekstrakulikuler
        FROM tbl_penilaian  p
        LEFT JOIN tbl_siswa s ON p.nis = s.nis
        LEFT JOIN tbl_ekstrakulikuler e ON p.no_ekskul = e.id
        ORDER BY p.id_penilaian DESC";
$q   = mysqli_query($koneksi, $sql);

/* ── Siapkan tampilan HTML tabel ───────────────────────────────────── */
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
    
  $tblRows .= '<tr>
                 <td style="text-align:center;">'.$no++.'</td>
                 <td>'.htmlspecialchars($row['ekstrakulikuler']).'</td>
                 <td>'.$row['nis'].'</td>
                 <td>'.htmlspecialchars($row['nama']).'</td>
                 <td style="text-align:center;">'.$row['kelas'].'</td>
                 <td style="text-align:center;">'.$row['nilai'].'</td>
                 <td>'.htmlspecialchars($keterangan).'</td>
               </tr>';
}

/* ──────────────────────────────────────────────────────────────────── */
/* ── TAMPILAN HTML UNTUK PDF ───────────────────────────────────────── */
/* ──────────────────────────────────────────────────────────────────── */

$html = '
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Nilai Ekstrakurikuler</title>
  <style>
    body   { font-family: sans-serif; font-size: 11px; }
    h2, p  { margin: 0; }
    table  { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #444; padding: 4px; }
    th     { background: #f2f2f2; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
  </style>
</head>
<body>
  <div class="header-container">
    <h2 class="text-center">DATA NILAI EKSTRAKURIKULER</h2>
    <p class="text-center">SMA BOPKRI 1 YOGYAKARTA</p>
  </div>
  
  <p class="text-right">Dicetak: '.$today.'</p>

  <table>
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
    <tbody>'.$tblRows.'</tbody>
  </table>

  <br><br>
  <table style="width:100%; border:0;">
    <tr>
      <td style="border:0; width:70%"></td>
      <td style="border:0;">
        <p style="text-align:center;">Yogyakarta, '.date('d F Y').'</p>
        <p style="text-align:center;">Guru Pendamping Ekstrakurikuler</p>
        <br><br><br><br>
        <p style="text-align:center;"><b>'.$guruPendamping.'</b></p>
      </td>
    </tr>
  </table>
</body>
</html>
';

/* ──────────────────────────────────────────────────────────────────── */
/* ── PROSES GENERASI PDF DENGAN DOMPDF ─────────────────────────────── */
/* ──────────────────────────────────────────────────────────────────── */

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Data_Nilai_Ekstrakurikuler_'.date('Ymd').'.pdf', array("Attachment" => false));

exit(0);