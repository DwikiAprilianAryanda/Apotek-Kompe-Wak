<?php
// Pengaturan untuk koneksi database LOKAL (Laragon)
$db_host = 'localhost';     // atau 127.0.0.1
$db_user = 'root';          // User default Laragon
$db_pass = '';              // Password default Laragon kosong
$db_name = 'db_apotek';     // Nama database lokal yang baru Anda buat

// Membuat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database LOKAL gagal: " . $conn->connect_error);
}
?>