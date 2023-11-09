<?php
$koneksi = mysqli_connect("localhost", "root", "", "pm2.5_bariri_202309");
if (mysqli_connect_error()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}

?>