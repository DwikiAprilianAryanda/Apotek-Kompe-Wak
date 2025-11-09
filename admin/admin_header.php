<?php
session_start();
// Tampilkan error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// INI YANG PALING PENTING: MEMUAT KONEKSI DB
include '../includes/db_connect.php'; 

// KEAMANAN: Halaman ini hanya boleh diakses oleh Admin atau Resepsionis
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Ambil nama dan peran pengguna
$admin_name = htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna');
$user_role = $_SESSION['user_role'] ?? 'customer';

// Jika BUKAN admin atau receptionist, tendang ke halaman customer
if ($user_role != 'admin' && $user_role != 'receptionist') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin - Apotek</title>
    
<   <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<div class="admin-wrapper">
    <div class="admin-sidebar">
        <h2>Admin Apotek</h2>
        <div class="admin-welcome">
            Selamat datang,<br><strong><?php echo $admin_name; ?></strong>
            <small>(<?php echo ucfirst($user_role); ?>)</small>
        </div>
        
        <nav class="admin-nav">
            
            <a href="index.php">Manajemen Pesanan</a>
            
            <?php if ($user_role == 'admin' || $user_role == 'receptionist'): ?>
            <a href="verifikasi_resep.php" style="background: #ffc107; color: #333; font-weight: bold;">Verifikasi Resep</a>
            <?php endif; ?>

            <hr style="border-top: 1px solid #3c5061; margin: 15px 0;">
            
            <?php if ($user_role == 'admin'): ?>
            <a href="produk.php">Manajemen Produk</a>
            <a href="laporan_penjualan.php">Laporan Penjualan</a>
            <?php endif; ?>
            
            <hr style="border-top: 1px solid #3c5061; margin: 15px 0;">
            
            <a href="../index.php" target="_blank">Lihat Toko</a>
            <a href="../actions/logout.php" class="logout">Logout</a>
        </nav>
    </div>
    
    <div class="admin-content">