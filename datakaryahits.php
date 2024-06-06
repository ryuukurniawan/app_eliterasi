<?php
include 'koneksi.php';

$sql = "SELECT buku.*, pengguna.Username FROM buku INNER JOIN pengguna ON buku.User_ID = pengguna.User_ID WHERE keterangan = 'hits'";
$result = $conn->query($sql);
$books = $result->fetch_all(MYSQLI_ASSOC);

// Convert to JSON
$json_books = json_encode($books);
echo $json_books
?>