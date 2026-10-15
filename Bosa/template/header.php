<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Presensi dan Penilaian Ekstrakurikuler SMA BOPKRI 1 Yogyakarta" />
    <meta name="author" content="Kenaz Manuel Chris Natalino" />
    <title><?= htmlspecialchars($title ?? 'Sistem Ekskul') ?></title>

    <!-- Favicon & Logo -->
    <link rel="shortcut icon" href="<?= $main_url ?>asset/image/logobosa.png" type="image/x-icon">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="<?= $main_url ?>asset/sb-admin/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome & jQuery -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
            crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">

    <!-- Navbar / Header -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-primary shadow-sm">
        <a class="navbar-brand ps-3 d-flex align-items-center" href="<?= $main_url ?>dashboard.php">
            <!-- LOGO SEKOLAH (TANPA DATABASE) -->
            <img src="<?= $main_url ?>asset/image/logobosa.png" 
                 alt="Logo SMA BOPKRI 1 Yogyakarta" 
                 class="me-2" 
                 style="height: 40px; border-radius: 8px;">
            <div>
                <strong class="d-block text-white" style="font-size: 1rem; line-height: 1.2;">
                    SMA BOPKRI 1
                </strong>
                <small class="text-white-50" style="font-size: 0.75rem;">Yogyakarta</small>
            </div>
        </a>

        <!-- Navbar Toggler (Mobile) -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars text-white"></i>
        </button>

        <!-- User Info -->
        <div class="d-none d-md-inline-block ms-auto me-3 text-white">
            <i class="fas fa-user me-1"></i>
            <?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?>
            <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($_SESSION['jabatan'] ?? '') ?></span>
        </div>

        <!-- Logout -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?= $main_url ?>auth/ganti-password.php">Ganti Password</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item text-danger" href="<?= $main_url ?>auth/logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>