<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion bg-white shadow-sm" id="sidenavAccordion">

        <style>
            /* =============================================
               SIDEBAR & LAYOUT FIX - konsisten di semua halaman
            ============================================= */
            #layoutSidenav_nav {
                position: fixed;
                top: 56px;           /* tinggi navbar */
                left: 0;
                width: 220px;
                height: calc(100vh - 56px);
                z-index: 1030;
                background: blue;
                overflow-y: auto;
                transition: width 0.3s ease;
            }

            .sb-sidenav {
                width: 220px;
                padding-top: 10px;
            }

            .sb-sidenav .nav-link {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
                display: flex;
                align-items: center;
            }

            .sb-nav-link-icon {
                width: 24px;
                text-align: center;
                margin-right: 10px;
                color: #0d6efd;
            }

            .sb-sidenav-menu-heading {
                font-size: 0.7rem;
                text-transform: uppercase;
                font-weight: 600;
                color: #6c757d;
                padding: 0.75rem 1rem 0.25rem;
            }

            .sb-sidenav-footer {
                font-size: 0.75rem;
                padding: 0.75rem;
                background: #f8f9fa;
                position: absolute;
                bottom: 0;
                width: 100%;
            }

            /* Konten utama bergeser sesuai sidebar */
            #layoutSidenav_content {
                margin-left: 220px;
                padding-top: 56px;
                min-height: 100vh;
                transition: margin-left 0.3s ease;
            }

            /* Mobile / collapse */
            @media (max-width: 992px) {
                #layoutSidenav_nav {
                    width: 0;
                    overflow: hidden;
                }
                #layoutSidenav_content {
                    margin-left: 0 !important;
                }
            }

            /* Card hover (opsional - boleh dipindah ke file lain nanti) */
            .card-custom {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-radius: 12px;
            }
            .card-custom:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
            }
        </style>

        <div class="sb-sidenav-menu">
            <div class="nav">

                <div class="sb-sidenav-menu-heading">Home</div>
                <a class="nav-link" href="<?= $main_url ?>index.php">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Dashboard
                </a>

                <div class="sb-sidenav-menu-heading">Data</div>
                <a class="nav-link" href="<?= $main_url ?>siswa/siswa.php">
                    <div class="sb-nav-link-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    Siswa
                </a>
                <a class="nav-link" href="<?= $main_url ?>guru/guru.php">
                    <div class="sb-nav-link-icon">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    Guru
                </a>
                <a class="nav-link" href="<?= $main_url ?>ekstrakulikuler/ekstrakulikuler.php">
                    <div class="sb-nav-link-icon">
                        <i class="fa-solid fa-person-running"></i>
                    </div>
                    Ekskul
                </a>

                <div class="sb-sidenav-menu-heading">Kegiatan</div>
                <a class="nav-link" href="<?= $main_url ?>absensi/absensi.php">
                    <div class="sb-nav-link-icon">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    Presensi
                </a>
                <a class="nav-link" href="<?= $main_url ?>penilaian/penilaian.php">
                    <div class="sb-nav-link-icon">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    Penilaian
                </a>

                <?php if ($_SESSION["jabatan"] == "Tim Kurikulum") : ?>
                    <div class="sb-sidenav-menu-heading">Manajemen User</div>
                    <a class="nav-link" href="<?= $main_url ?>user/add-user.php">
                        <div class="sb-nav-link-icon">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        User
                    </a>
                <?php endif; ?>

            </div>
        </div>

        <div class="sb-sidenav-footer text-center">
            <div class="small text-muted">Login sebagai</div>
            <span class="badge bg-primary"><?= $_SESSION["jabatan"] ?></span>
        </div>

    </nav>
</div>