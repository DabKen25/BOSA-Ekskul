<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";

$jabatan = $_SESSION["jabatan"] ?? '';
if ($jabatan !== "Guru" && $jabatan !== "Tim Kurikulum") {
    echo "<script>alert('Anda tidak memiliki akses untuk mencetak laporan ini.'); window.close();</script>";
    exit();
}

/* ── Query sama dengan riwayat di absensi.php ─────────────────────────────── */
$queryAbsensi = mysqli_query($koneksi, "
    SELECT a.tgl, a.nis, a.nama, s.kelas, e.ekstrakulikuler, a.status, a.keterangan
    FROM tbl_absensi a
    LEFT JOIN tbl_siswa s ON a.nis = s.nis
    LEFT JOIN tbl_ekstrakulikuler e ON a.id_ekskul = e.id
    ORDER BY a.tgl DESC, a.id_absensi DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Presensi Siswa - SMA Bopkri 1 Yogyakarta</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; text-transform: uppercase; }
        .header p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { border: 1px solid #000; padding: 6px 8px; }
        table th { background-color: #e6e6e6; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .footer-ttd { margin-top: 40px; float: right; width: 280px; text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>SMA BOPKRI 1 YOGYAKARTA</h2>
        <p>Jl. Wardhani No.2, Kotabaru, Kec. Gondokusuman, Kota Yogyakarta</p>
        <p><strong>RIWAYAT PRESENSI SISWA EKSTRAKURIKULER</strong></p>
    </div>

    <p><strong>Dicetak oleh:</strong> <?= htmlspecialchars($_SESSION["nama"] ?? 'Administrator') ?> (<?= htmlspecialchars($jabatan) ?>)</p>
    <p><strong>Tanggal cetak:</strong> <?= date('d/m/Y H:i:s') ?></p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">Tanggal</th>
                <th width="10%">NIS</th>
                <th width="24%">Nama Siswa</th>
                <th width="8%">Kelas</th>
                <th>Ekstrakurikuler</th>
                <th width="8%">Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($queryAbsensi) > 0) :
                while ($row = mysqli_fetch_assoc($queryAbsensi)) : 
                    $waktu = date('d-m-Y', strtotime($row['tgl']));
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= $waktu ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nis']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['kelas']) ?></td>
                <td><?= htmlspecialchars($row['ekstrakulikuler']) ?></td>
                <td class="text-center"><strong><?= htmlspecialchars($row['status']) ?></strong></td>
                <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">Tidak ada data presensi yang tercatat.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Yogyakarta, <?= date('d F Y') ?></p>
        <p style="margin: 60px 0 10px;">Yang mencetak,</p>
        <p><strong>( <?= htmlspecialchars($_SESSION["nama"] ?? '........................................') ?> )</strong></p>
        <p><?= htmlspecialchars($jabatan) ?></p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align:center;">
        <button onclick="window.print()">Cetak Ulang</button>
        &nbsp;&nbsp;
        <button onclick="window.close()">Tutup</button>
    </div>

</body>
</html>