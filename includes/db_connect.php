<?php
// Konfigurasi untuk localhost Laragon
$db_host = 'localhost';         // Host di localhost biasanya 'localhost'
$db_user = 'root';              // User default Laragon biasanya 'root'
$db_pass = '';                  // Password default Laragon untuk user 'root' biasanya kosong
$db_name = 'db_apotek'; // Gunakan nama database yang sama atau ganti sesuai kebutuhan Anda

// Membuat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database LOKAL gagal: " . $conn->connect_error);
}
?>