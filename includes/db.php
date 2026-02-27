<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root'; 
$pass = ''; 
$dbname = 'aplikasi_pemesanan_kue';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>