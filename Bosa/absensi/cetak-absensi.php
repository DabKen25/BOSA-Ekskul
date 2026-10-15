<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit;
}

$jabatan = $_SESSION["jabatan"] ?? '';
if ($jabatan !== "Guru" && $jabatan !== "Tim Kurikulum") {
    echo "<script>alert('Halaman cetak hanya untuk Guru atau Tim Kurikulum'); window.close();</script>";
    exit;
}

require_once "../config.php";
require_once "../vendor/autoload.php";

use Dompdf\Dompdf;

// ── Query sama dengan riwayat di absensi.php ───────────────────────────────
$sql = "
    SELECT a.id_absensi, a.tgl, a.nis, a.nama, s.kelas,
           e.ekstrakulikuler AS nama_ekskul,
           a.status, a.keterangan
    FROM tbl_absensi a
    LEFT JOIN tbl_siswa s ON a.nis = s.nis
    LEFT JOIN tbl_ekstrakulikuler e ON a.id_ekskul = e.id
    ORDER BY a.tgl DESC, a.id_absensi DESC
";

$q = mysqli_query($koneksi, $sql);

/* ── Menyiapkan baris tabel ──────────────────────────────────────────────── */
$tblRows = '';
$no = 1;
$today = date('d-m-Y H:i');

while ($row = mysqli_fetch_assoc($q)) {
    $status = $row['status'];
    switch ($status) {
        case 'H': $status = 'Hadir'; break;
        case 'I': $status = 'Izin';  break;
        case 'S': $status = 'Sakit'; break;
        case 'A': $status = 'Alpa';  break;
        default:  $status = '-';
    }

    $tblRows .= '<tr>
        <td style="text-align:center;">' . $no++ . '</td>
        <td>' . htmlspecialchars($row['tgl']) . '</td>
        <td>' . htmlspecialchars($row['nis']) . '</td>
        <td>' . htmlspecialchars($row['nama']) . '</td>
        <td style="text-align:center;">' . htmlspecialchars($row['kelas']) . '</td>
        <td>' . htmlspecialchars($row['nama_ekskul']) . '</td>
        <td style="text-align:center;">' . $status . '</td>
        <td>' . htmlspecialchars($row['keterangan'] ?? '-') . '</td>
    </tr>';
}

/* ── HTML untuk PDF ──────────────────────────────────────────────────────── */
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Presensi Siswa</title>
  <style>
    body   { font-family: sans-serif; font-size: 11px; }
    h2, p  { margin: 0; }
    table  { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #444; padding: 5px; }
    th     { background: #f2f2f2; text-align: center; }
    .right { text-align: right; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">RIWAYAT PRESENSI SISWA EKSTRAKURIKULER</h2>
  <p style="text-align:center;">SMA BOPKRI 1 YOGYAKARTA</p>
  <p class="right">Dicetak: ' . $today . '</p>
  <p class="right">Oleh: ' . htmlspecialchars($_SESSION["nama"] ?? 'Administrator') . ' (' . htmlspecialchars($jabatan) . ')</p>

  <table>
    <thead>
      <tr>
        <th width="4%">No</th>
        <th width="12%">Tanggal</th>
        <th width="10%">NIS</th>
        <th width="22%">Nama Siswa</th>
        <th width="8%">Kelas</th>
        <th width="18%">Ekstrakurikuler</th>
        <th width="8%">Status</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>' . $tblRows . '</tbody>
  </table>

  <br><br>
  <table style="width:100%; border:0;">
    <tr>
      <td style="border:0; width:70%"></td>
      <td style="border:0;">
        <p style="text-align:center;">Yogyakarta, ' . date('d-m-Y') . '</p>
        <p style="text-align:center;">Petugas / Tim Kurikulum</p>
        <br><br><br><br>
        <p style="text-align:center;">( ........................................ )</p>
      </td>
    </tr>
  </table>
</body>
</html>
';

/* ── Generate PDF ────────────────────────────────────────────────────────── */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Riwayat_Presensi_Siswa.pdf', ['Attachment' => false]); // false = buka di browser, true = langsung download

exit();
?>