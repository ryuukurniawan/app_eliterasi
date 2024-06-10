<?php
include 'koneksi.php';

// Mengambil data dari form
$username = $_POST['username'];
$nama = $_POST['nama'];
$sandi = $_POST['sandi'];
$email = $_POST['email'];
$data = mysqli_query($conn,"INSERT INTO pengguna set Username='$username',Nama='$nama',Sandi='$sandi',Email='$email'");

if ($data){
    echo json_encode([
        'pesan'=>'Suskses'
    ]);
}else{
    echo json_encode([
        'pesan'=>'Gagal'
    ]);
}
?>
