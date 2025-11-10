<?php

$db_host = 'sql200.infinityfree.com';     // Biasanya 'localhost'
$db_user = 'if0_40167434';          // User default XAMPP
$db_pass = 'vb0J2kvY9yo';              // Password default XAMPP kosong
$db_name = 'if0_40167434_db_apotek';     // Nama database yang Anda buat

// Membuat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database LOKAL gagal: " . $conn->connect_error);
}
?>