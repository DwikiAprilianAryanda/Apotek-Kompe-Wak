<?php
session_start();
include '../includes/db_connect.php'; 

// --- PERUBAHAN DI SINI ---
// Jika belum login, lempar ke login admin (BUKAN login.php user biasa)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

$user_role = $_SESSION['user_role'] ?? 'customer';

// Keamanan ganda: Jika login tapi bukan admin, tendang ke login admin
if ($user_role != 'admin' && $user_role != 'receptionist') {
    // Hapus sesi user biasa agar tidak konflik
    session_unset();
    session_destroy();
    header("Location: login.php?error=access");
    exit;
}
// --- BATAS PERUBAHAN ---

$admin_name = htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna');
$current_page = basename($_SERVER['PHP_SELF']);

function is_active($page, $current_page) {
    return ($page == $current_page) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin - Apotek</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
            <a href="index.php" class="<?php echo is_active('index.php', $current_page); ?>">Dasbor Admin</a>
            <a href="manajemen_pesanan.php" class="<?php echo is_active('manajemen_pesanan.php', $current_page); ?>">Manajemen Pesanan</a>
            
            <?php if ($user_role == 'admin' || $user_role == 'receptionist'): ?>
            <a href="verifikasi_resep.php" class="<?php echo is_active('verifikasi_resep.php', $current_page); ?>">Verifikasi Resep</a>
            <?php endif; ?>

            <hr style="border-top: 1px solid #3c5061; margin: 15px 0;">
            
            <?php if ($user_role == 'admin'): ?>
            <a href="produk.php" class="<?php echo is_active('produk.php', $current_page); ?>">Manajemen Produk</a>
            <a href="laporan_penjualan.php" class="<?php echo is_active('laporan_penjualan.php', $current_page); ?>">Laporan Penjualan</a>
            <?php endif; ?>
            
            <hr style="border-top: 1px solid #3c5061; margin: 15px 0;">
            
            <a href="../index.php" target="_blank">Lihat Toko</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>
    </div>
    
    <div class="admin-content">