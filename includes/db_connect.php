<?php
<<<<<<< HEAD
// Konfigurasi untuk localhost Laragon
$db_host = 'localhost';         // Host di localhost biasanya 'localhost'
$db_user = 'root';              // User default Laragon biasanya 'root'
$db_pass = '';                  // Password default Laragon untuk user 'root' biasanya kosong
$db_name = 'db_apotek'; // Gunakan nama database yang sama atau ganti sesuai kebutuhan Anda
=======

$db_host = 'localhost';     // Biasanya 'localhost'
$db_user = 'root';          // User default XAMPP
$db_pass = '';              // Password default XAMPP kosong
$db_name = 'db_apotek';     // Nama database yang Anda buat
>>>>>>> c0e517ac900364d2c58f85dbe68da7272d355faf

// Membuat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database LOKAL gagal: " . $conn->connect_error);
}
?>