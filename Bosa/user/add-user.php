<?php

session_start();

// 1. Cek apakah user sudah login
if (!isset($_SESSION["ssLogin"])) {
    header("location:../auth/login.php");
    exit;
}

// 2. Proteksi Role: Hanya "Tim Kurikulum" yang boleh menambah user
// Kita sesuaikan dengan value yang ada di database kamu
if ($_SESSION["jabatan"] !== "Tim Kurikulum") {
    echo "<script>
        alert('Akses Ditolak! Halaman ini hanya untuk Tim Kurikulum.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

require_once "../config.php";

$title = "Tambah User - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';

if ($msg == 'cancel') {
    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-xmark"></i> Tambah user gagal, username sudah ada..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah user berhasil..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tambah User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="user.php">User</a></li>
                <li class="breadcrumb-item active">Tambah User</li>
            </ol>

            <form action="proses-user.php" method="POST">
                <div class="card">
                    <div class="card-header">
                        <span class="h5 my-2"><i class="fa-solid fa-plus me-1"></i> Tambah User</span>
                        <button type="submit" name="simpan" class="btn btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <?php echo $alert; ?>
                                <div class="mb-3 row">
                                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control border-0 border-bottom" id="username" name="username" maxlength="20" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <input type="text" class="form-control border-0 border-bottom" id="nama" name="nama" maxlength="20" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                                    <label for="" class="col-sm-1 col-form-label">:</label>
                                    <div class="col-sm-9" style="margin-left: -50px;">
                                        <select name="jabatan" id="jabatan" class="form-select border-0 border-bottom" required>
                                            <option value="" selected>--Pilih Jabatan--</option>
                                            <option value="Tim Kurikulum">Tim Kurikulum</option>
                                            <option value="Guru">Guru Pendamping</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

<?php
require_once "../template/footer.php";
?>