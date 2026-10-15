<?php
session_start();

// Hanya Tim Kurikulum yang boleh akses import
if (!isset($_SESSION["ssLogin"]) || $_SESSION["jabatan"] !== "Tim Kurikulum") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Import Data Siswa - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Import Siswa</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="siswa.php">Siswa</a></li>
                <li class="breadcrumb-item active">Import Siswa</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-file-import me-1"></i>
                    Form Import Siswa via Excel
                </div>
                <div class="card-body">
                    <form action="proses-import-siswa.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3 col-md-6">
                            <label for="ekskul_id" class="form-label">Tujuan Ekstrakurikuler</label>
                            <select name="ekskul_id" id="ekskul_id" class="form-select" required>
                                <option value="">-- Pilih Ekstrakurikuler --</option>
                                <?php
                                $queryEkskul = mysqli_query($koneksi, "SELECT id, ekstrakulikuler FROM tbl_ekstrakulikuler ORDER BY ekstrakulikuler");
                                while ($row = mysqli_fetch_assoc($queryEkskul)) {
                                    echo "<option value='{$row['id']}'>{$row['ekstrakulikuler']}</option>";
                                }
                                ?>
                            </select>
                            <div class="form-text text-danger">* Semua siswa di file Excel akan dimasukkan ke ekskul ini.</div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="file_excel" class="form-label">File Excel (.xls / .xlsx)</label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xls,.xlsx" required>
                            <div class="form-text">Format kolom: <b>A: NIS, B: Nama, C: Kelas</b> (Baris 1 adalah Header)</div>
                        </div>

                        <button type="submit" name="import" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Mulai Import
                        </button>
                        <a href="siswa.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once "../template/footer.php"; ?>