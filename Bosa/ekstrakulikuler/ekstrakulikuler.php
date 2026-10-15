<?php

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION["ssLogin"])) {
    header("location: ../auth/login.php");
    exit();
}

require_once "../config.php";
$title = "Ekstrakulikuler - SMA Bopkri 1 Yogyakarta";
require_once "../template/header.php";
require_once "../template/navbar.php";
require_once "../template/sidebar.php";

$msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : "";

$alert = '';
if ($msg == 'cancel') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-xmark"></i> Tambah ekskul gagal, nama ekskul sudah ada..
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

if ($msg == 'added') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Tambah ekskul berhasil
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

if ($msg == 'deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data ekstrakurikuler berhasil dihapus
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

if ($msg == 'all_deleted') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Seluruh data ekstrakurikuler berhasil dihapus secara permanen
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

if ($msg == 'error_delete') {
    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-xmark"></i> Gagal menghapus semua data. Database menolak perintah penghapusan massal.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}

if ($msg == 'updated') {
    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check"></i> Data ekstrakurikuler berhasil diperbarui
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Ekstrakurikuler</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item active">Ekstrakurikuler</li>
            </ol>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fa-solid fa-list-ul"></i> Data Ekstrakurikuler
                            <a href="add-ekstrakulikuler.php" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-plus"></i> Tambah Ekstrakurikuler</a>
                            <a href="import-ekstrakulikuler.php" class="btn btn-sm btn-info float-end me-1 text-white"><i class="fa-solid fa-file-excel"></i> Import Data Excel</a>
                            
                            <button type="button" class="btn btn-sm btn-danger float-end me-1" data-bs-toggle="modal" data-bs-target="#mdlHapusSemua">
                                <i class="fa-solid fa-trash-can"></i> Hapus Semua Data
                            </button>
                        </div>
                        <div class="card-body">
                            <?= $alert ?>
                            <div class="table-responsive">
                                <table class="table table-hover" id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Ekstrakurikuler</th>
                                            <th scope="col">Guru</th>
                                            <th scope="col">Operasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $queryEkstrakulikuler = mysqli_query($koneksi, "SELECT * FROM tbl_ekstrakulikuler");
                                        while ($data = mysqli_fetch_array($queryEkstrakulikuler)) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?= $no++ ?></th>
                                                <td><?= htmlspecialchars($data['ekstrakulikuler']) ?></td>
                                                <td><?= htmlspecialchars($data['guru']) ?></td>
                                                <td align="center">
                                                    <a href="edit-ekstrakulikuler.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning mb-1" title="Update"><i class="fa-solid fa-pen"></i></a>
                                                    <button type="button" class="btn btn-sm btn-danger" id="btnHapus" data-id="<?= $data['id'] ?>" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal" id="mdlHapus" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin akan menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="" id="btnMdlHapus" class="btn btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mdlHapusSemua" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Konfirmasi Hapus Semua Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">Anda yakin akan menghapus <strong>seluruh</strong> data ekstrakurikuler?</p>
                    <p>Tindakan ini tidak bisa dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="hapus-semua-ekstrakulikuler.php" class="btn btn-danger">Ya, Hapus Semua</a>
                </div>
            </div>
        </div>
    </div>

<?php
require_once "../template/footer.php";
?>