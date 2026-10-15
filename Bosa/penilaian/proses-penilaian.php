<?php
session_start();
require_once "../config.php";

if (isset($_POST['simpan'])) {
    $no_ekskul = mysqli_real_escape_string($koneksi, $_POST['no_ekskul']);
    
    // Cek apakah ada siswa yang dipilih/diisi nilainya
    if (isset($_POST['pilih_siswa'])) {
        $pilih_siswa = $_POST['pilih_siswa']; // Ini berisi index baris yang dicentang

        foreach ($pilih_siswa as $idx) {
            $nis    = mysqli_real_escape_string($koneksi, $_POST['nis'][$idx]);
            $nama   = mysqli_real_escape_string($koneksi, $_POST['nama'][$idx]);
            $nilai  = mysqli_real_escape_string($koneksi, $_POST['nilai'][$idx]);
            
            // Mengambil keterangan sesuai nilai yang dipilih
            $sb_ket = ($nilai == 'SB') ? mysqli_real_escape_string($koneksi, $_POST['ket'][$idx]) : '';
            $b_ket  = ($nilai == 'B')  ? mysqli_real_escape_string($koneksi, $_POST['ket'][$idx]) : '';
            $c_ket  = ($nilai == 'C')  ? mysqli_real_escape_string($koneksi, $_POST['ket'][$idx]) : '';
            $k_ket  = ($nilai == 'K')  ? mysqli_real_escape_string($koneksi, $_POST['ket'][$idx]) : '';

            // Jalankan query insert untuk setiap siswa yang dipilih
            if (!empty($nilai)) {
                mysqli_query($koneksi, "INSERT INTO tbl_penilaian 
                (no_ekskul, nis, nama, nilai, sb_keterangan, b_keterangan, c_keterangan, k_keterangan) 
                VALUES 
                ('$no_ekskul', '$nis', '$nama', '$nilai', '$sb_ket', '$b_ket', '$c_ket', '$k_ket')");
            }
        }
        header("Location: penilaian.php?msg=added");
        exit();
    } else {
        // Jika tidak ada siswa yang dipilih
        header("Location: penilaian.php?msg=error");
        exit();
    }
}
?>