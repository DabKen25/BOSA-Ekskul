<?php

$koneksi = mysqli_connect("localhost","root","","db_bosa");

// cek koneksi
//if (mysqli_connect_errno()) {
//  echo "Gagal Koneksi";
//} else {
//echo "Berhasil Koneksi";
//}

// url induk
$main_url = "http://localhost/bosa/";

function uploadimage($url){
    $namafile = $_FILES['image']['name'];
    $ukuran   = $_FILES['image']['size'];
    $error    = $_FILES['image']['error'];
    $tmp      = $_FILES['image']['tmp_name'];

    //cek file yg double
    $validExtension = ['jpg', 'jpeg', 'png'];
    $fileExtension = explode('.', $namafile);
    $fileExtension = strtolower(end($fileExtension));
    if (lin_array($fileExtension, $validExtension)) {
        header("location:" . $url . '?msg=not image');
        die;
    }

    //cek ukuran gambar
    if ($ukuran > 1000000) {
        header("location" . $url . '?msg-oversize');
        die;
    }

    // generate nama file gambar
    if ($url == 'profile-sekolah.php') {
        $namafilebaru = rand(0,50) . '-bgLogin.' . $fileExtension;
    } else {
    $namafileterbaru = rand(10, 1000) . '_' . $namafile;
    }



    //upload gambar
    move_uploaded_file($tmp, "../asset/image/" . $namafilebaru);
    return $namafilebaru;
}

?>

