<?php
session_start();

/* ── Cek login ───────────────────────────────────────────── */
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
$title = "Data Presensi Siswa - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

/* ── Query Dropdown Berdasarkan Hak Akses ─────────────────── */
if ($isGuru && isset($_SESSION['id_ekskul'])) {
    $id_ekskul = $_SESSION['id_ekskul'];
    // Guru hanya bisa melihat & mengabsen ekskul miliknya sendiri
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

/* ── Query Data Tabel Riwayat ────────────────────────────── */
$qDataAbsensi = mysqli_query($koneksi, "
  SELECT a.id_absensi, a.tgl, a.nis, a.nama, s.kelas,
         e.ekstrakulikuler AS nama_ekskul,
         a.status, a.keterangan
  FROM tbl_absensi a
  LEFT JOIN tbl_siswa s ON a.nis = s.nis
  LEFT JOIN tbl_ekstrakulikuler e ON a.id_ekskul = e.id
  ORDER BY a.tgl DESC, a.id_absensi DESC
");

/* ── Query Rekapitulasi ────────────────────────────────────── */
$qRekap = mysqli_query($koneksi, "
    SELECT 
        e.ekstrakulikuler,
        COUNT(CASE WHEN a.status = 'H' THEN 1 END) as hadir,
        COUNT(CASE WHEN a.status = 'I' THEN 1 END) as izin,
        COUNT(CASE WHEN a.status = 'S' THEN 1 END) as sakit,
        COUNT(CASE WHEN a.status = 'A' THEN 1 END) as alfa,
        COUNT(a.id_absensi) as total
    FROM tbl_ekstrakulikuler e
    LEFT JOIN tbl_absensi a ON e.id = a.id_ekskul
    GROUP BY e.id
    ORDER BY e.ekstrakulikuler ASC
");
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Data Presensi Siswa</h1>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="h5 my-2"><i class="fa-solid fa-list"></i> Presensi & Riwayat</span>

                    <?php if ($bolehCetak): ?>
                    <div>
                        <a href="cetak-absensi.php" target="_blank" class="btn btn-sm btn-danger me-2">
                            <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                        </a>
                        <a href="cetak-absensi-excel.php" target="_blank" class="btn btn-sm btn-success me-2">
                            <i class="fa-solid fa-file-excel"></i> Export Excel
                        </a>

                        <?php if ($bolehEdit): ?>
                        <a href="hapus-semua-absensi.php?confirm=yes" 
                           class="btn btn-sm btn-warning"
                           onclick="return confirm('ANDA AKAN MENGHAPUS SELURUH DATA PRESENSI SISWA!\n\nTindakan ini TIDAK DAPAT DIBATALKAN.\n\nYakin ingin melanjutkan?');">
                            <i class="fa-solid fa-trash-can"></i> Hapus Semua Data
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <form id="formAbsensi">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Ekstrakurikuler</label>
                                <select id="ekskul" class="form-select" required>
                                    <?php if (!$isGuru): ?>
                                        <option value="">-- Pilih Ekstrakurikuler --</option>
                                    <?php endif; ?>
                                    
                                    <?php while ($e = mysqli_fetch_assoc($qEkskul)): ?>
                                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['ekstrakulikuler']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" id="tgl" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="col-md-5 d-flex align-items-end">
                                <button type="button" id="btnProses" class="btn btn-primary me-2">
                                    <i class="fa-solid fa-search"></i> Cari Siswa
                                </button>

                                <?php if ($bolehEdit): ?>
                                <button type="button" id="btnSimpanAbsensi" class="btn btn-success" disabled>
                                    <i class="fa-solid fa-database"></i> Simpan (<span id="countAbsen">0</span>)
                                </button>
                                <?php else: ?>
                                <div class="alert alert-info mb-0 ms-2 py-1 px-2">
                                    <small><i class="fa-solid fa-circle-info"></i> <strong>Mode Lihat:</strong> Tim Kurikulum</small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>

                    <div id="listSiswa" class="table-responsive"></div>

                    <hr>

                    <h5 class="mt-4"><i class="fa-solid fa-chart-pie"></i> Rekapitulasi Kehadiran Per Ekskul</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped">
                            <thead class="table-secondary text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Ekstrakurikuler</th>
                                    <th width="10%">Hadir</th>
                                    <th width="10%">Izin</th>
                                    <th width="10%">Sakit</th>
                                    <th width="10%">Alfa</th>
                                    <th width="15%">Total Partisipasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $noR = 1; while ($r = mysqli_fetch_assoc($qRekap)): ?>
                                <tr>
                                    <td class="text-center"><?= $noR++ ?></td>
                                    <td><strong><?= htmlspecialchars($r['ekstrakulikuler']) ?></strong></td>
                                    <td class="text-center text-success fw-bold"><?= $r['hadir'] ?></td>
                                    <td class="text-center text-primary"><?= $r['izin'] ?></td>
                                    <td class="text-center text-warning"><?= $r['sakit'] ?></td>
                                    <td class="text-center text-danger"><?= $r['alfa'] ?></td>
                                    <td class="text-center bg-light"><?= $r['total'] ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <h5>Data Riwayat Presensi</h5>
                    <table class="table table-bordered table-striped" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Ekstrakurikuler</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($d = mysqli_fetch_assoc($qDataAbsensi)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d F Y', strtotime($d['tgl'])) ?></td>
                                <td><?= $d['nis'] ?></td>
                                <td><?= htmlspecialchars($d['nama']) ?></td>
                                <td><?= $d['kelas'] ?></td>
                                <td><?= htmlspecialchars($d['nama_ekskul']) ?></td>
                                <td class="text-center">
                                    <?php 
                                        $badge = "secondary";
                                        if($d['status'] == "H") $badge = "success";
                                        if($d['status'] == "I") $badge = "primary";
                                        if($d['status'] == "S") $badge = "warning text-dark";
                                        if($d['status'] == "A") $badge = "danger";
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= $d['status'] ?></span>
                                </td>
                                <td><?= htmlspecialchars($d['keterangan']) ?></td>
                                <td>
                                    <?php if ($bolehEdit): ?>
                                    <a href="edit-absensi.php?id=<?= $d['id_absensi'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="hapus-absensi.php?id=<?= $d['id_absensi'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data presensi ini?')">Hapus</a>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
const bolehEdit = <?= $bolehEdit ? 'true' : 'false' ?>;
let tempAbsensiData = {};

$('#btnProses').on('click', function () {
    const ekskul = $('#ekskul').val();
    const tgl = $('#tgl').val();
    const disabled = bolehEdit ? '' : 'disabled';

    if (!ekskul) {
        alert('Pilih ekstrakurikuler terlebih dahulu');
        return;
    }

    $.getJSON('cari-siswa-ekskul.php', { ekskul, tgl }, function (res) {
        let html = `
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>`;

        tempAbsensiData = {};

        res.data.forEach(data => {
            html += `
            <tr>
                <td>${data.nis}</td>
                <td>${data.nama}</td>
                <td>${data.kelas}</td>
                <td align="center">
                    <input type="radio" name="status_${data.nis}" value="H" class="status-radio" data-nis="${data.nis}" checked ${disabled}> H
                    <input type="radio" name="status_${data.nis}" value="I" class="status-radio" data-nis="${data.nis}" ${disabled}> I
                    <input type="radio" name="status_${data.nis}" value="S" class="status-radio" data-nis="${data.nis}" ${disabled}> S
                    <input type="radio" name="status_${data.nis}" value="A" class="status-radio" data-nis="${data.nis}" ${disabled}> A
                </td>
                <td>
                    <textarea class="form-control keterangan" data-nis="${data.nis}" placeholder="Catatan..." rows="1" ${disabled}></textarea>
                </td>
            </tr>`;

            tempAbsensiData[data.nis] = {
                nis: data.nis,
                nama: data.nama,
                status: 'H',
                keterangan: ''
            };
        });

        html += `</tbody></table>`;
        $('#listSiswa').html(html);
        $('#countAbsen').text(Object.keys(tempAbsensiData).length);

        if (bolehEdit) {
            $('#btnSimpanAbsensi').prop('disabled', false);
        }
    });
});

$(document).on('change', '.status-radio', function () {
    if (!bolehEdit) return;
    const nis = $(this).data('nis');
    tempAbsensiData[nis].status = $(this).val();
});

$(document).on('keyup', '.keterangan', function () {
    if (!bolehEdit) return;
    const nis = $(this).data('nis');
    tempAbsensiData[nis].keterangan = $(this).val();
});

if (bolehEdit) {
    $('#btnSimpanAbsensi').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...');
        
        $.post('simpan-absensi-ajax.php', {
            tgl: $('#tgl').val(),
            ekskul: $('#ekskul').val(),
            data: JSON.stringify(Object.values(tempAbsensiData))
        }, function () {
            location.reload();
        }).fail(function() {
            alert('Gagal menyimpan data. Silakan coba lagi.');
            $btn.prop('disabled', false).html('<i class="fa-solid fa-database"></i> Simpan (<span id="countAbsen">' + Object.keys(tempAbsensiData).length + '</span>)');
        });
    });
}
</script>

<?php require_once "../template/footer.php"; ?>