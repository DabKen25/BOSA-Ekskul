<?php
session_start();

if (!isset($_SESSION["ssLogin"])) {
    header("Location: auth/login.php");
    exit;
}

require_once "config.php";

$title = "Dashboard - SMA Bopkri 1 Yogyakarta";
require_once "template/header.php";
require_once "template/navbar.php";
require_once "template/sidebar.php";

/* ── Statistik ─────────────────────────────────────────── */
$jmlSiswa  = mysqli_num_rows(mysqli_query($koneksi, "SELECT nis FROM tbl_siswa"));
$jmlGuru   = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM tbl_guru"));
$jmlEkskul = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM tbl_ekstrakulikuler"));
$jmlNilai  = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_penilaian FROM tbl_penilaian"));
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 px-md-5">

            <!-- HEADER + GREETING -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4 mb-5">
                <div>
                    <h1 class="fw-bold text-dark mb-1" style="font-size: 2.1rem; letter-spacing: -0.5px;">
                        Dashboard
                    </h1>
                    <p class="text-muted fs-5 mb-0">
                        Selamat datang kembali, 
                        <span class="fw-semibold text-primary"><?= htmlspecialchars($_SESSION["nama"]) ?></span>
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge bg-white text-dark shadow-sm px-3 py-2 fs-6 border border-light">
                        <i class="fa-solid fa-calendar-day me-2 text-primary"></i>
                        <?= date('l, d F Y') ?>
                    </span>
                </div>
            </div>

            <!-- STATISTIK CARDS - versi lebih menarik -->
            <div class="row g-4 mb-5">
                <!-- Card Siswa -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card card-custom shadow-lg border-0 h-100 overflow-hidden position-relative">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-gradient-primary text-white fs-4">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <span class="badge bg-light text-primary fw-semibold px-3 py-2">Siswa</span>
                            </div>
                            <h2 class="fw-bolder text-dark mb-1"><?= number_format($jmlSiswa) ?></h2>
                            <p class="text-muted small mb-0">Total siswa ekskul aktif</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Guru -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card card-custom shadow-lg border-0 h-100 overflow-hidden position-relative">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-gradient-success text-white fs-4">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <span class="badge bg-light text-success fw-semibold px-3 py-2">Guru</span>
                            </div>
                            <h2 class="fw-bolder text-dark mb-1"><?= number_format($jmlGuru) ?></h2>
                            <p class="text-muted small mb-0">Guru Pendamping Ekstrakurikuler</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Ekskul -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card card-custom shadow-lg border-0 h-100 overflow-hidden position-relative">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-gradient-warning text-white fs-4">
                                    <i class="fa-solid fa-person-running"></i>
                                </div>
                                <span class="badge bg-light text-warning fw-semibold px-3 py-2">Ekskul</span>
                            </div>
                            <h2 class="fw-bolder text-dark mb-1"><?= number_format($jmlEkskul) ?></h2>
                            <p class="text-muted small mb-0">Ekstrakurikuler Aktif</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Penilaian -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card card-custom shadow-lg border-0 h-100 overflow-hidden position-relative">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="icon-shape bg-gradient-danger text-white fs-4">
                                    <i class="fa-solid fa-user-pen"></i>
                                </div>
                                <span class="badge bg-light text-danger fw-semibold px-3 py-2">Penilaian</span>
                            </div>
                            <h2 class="fw-bolder text-dark mb-1"><?= number_format($jmlNilai) ?></h2>
                            <p class="text-muted small mb-0">Data penilaian terinput</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-danger" style="width: 96%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- CSS tambahan hanya untuk dashboard ini (bisa dipindah ke file terpisah nanti) -->
<style>
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --warning: #f6c23e;
        --danger: #e74a3b;
    }

    .card-custom {
        border-radius: 16px;
        transition: all 0.25s ease;
        background: linear-gradient(145deg, #ffffff, #f8f9fc);
    }
    .card-custom:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0,0,0,0.08) !important;
    }

    .icon-shape {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .bg-gradient-primary  { background: linear-gradient(135deg, #4e73df, #224abe); }
    .bg-gradient-success  { background: linear-gradient(135deg, #1cc88a, #13855c); }
    .bg-gradient-warning  { background: linear-gradient(135deg, #f6c23e, #dda20a); }
    .bg-gradient-danger   { background: linear-gradient(135deg, #e74a3b, #be2617); }

    .progress-bar {
        transition: width 1.2s ease;
    }

    @media (max-width: 991.98px) {
        #layoutSidenav_content {
            margin-left: 0 !important;
        }
    }
</style>

<?php require_once "template/footer.php"; ?>