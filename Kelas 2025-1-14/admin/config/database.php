<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Ensure this matches your MySQL setup
$database = 'toko_online';

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>