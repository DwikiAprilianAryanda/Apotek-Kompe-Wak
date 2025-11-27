<?php
session_start();
include '../includes/db_connect.php'; 

// Cek Login Admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

$user_role = $_SESSION['user_role'] ?? 'customer';

// Keamanan: Hanya Admin/Resepsionis
if ($user_role != 'admin' && $user_role != 'receptionist') {
    session_unset();
    session_destroy();
    header("Location: login.php?error=access");
    exit;
}

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
    <title>Admin Panel - Apotek Arshaka</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <h2>Admin Panel</h2>
        </div>

        <div class="admin-profile">
            <div class="avatar-circle">
                <?php echo strtoupper(substr($admin_name, 0, 1)); ?>
            </div>
            <div class="profile-info">
                <span class="name"><?php echo $admin_name; ?></span>
                <span class="role"><?php echo ucfirst($user_role); ?></span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            
            <a href="index.php" class="nav-item <?php echo is_active('index.php', $current_page); ?>">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></span>
                <span class="label">Dasbor Admin</span>
            </a>

            <div class="nav-divider"></div>

            <div class="nav-group-title">OPERASIONAL</div>
            
            <a href="manajemen_pesanan.php" class="nav-item <?php echo is_active('manajemen_pesanan.php', $current_page); ?>">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg></span>
                <span class="label">Manajemen Pesanan</span>
            </a>

            <?php if ($user_role == 'admin'): ?>
            <a href="produk.php" class="nav-item <?php echo is_active('produk.php', $current_page); ?>">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg></span>
                <span class="label">Manajemen Produk</span>
            </a>
            <?php endif; ?>

            <div class="nav-divider"></div>

            <?php if ($user_role == 'admin' || $user_role == 'receptionist'): ?>
            <a href="verifikasi_resep.php" class="nav-item <?php echo is_active('verifikasi_resep.php', $current_page); ?>">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></span>
                <span class="label">Verifikasi Resep</span>
            </a>
            <?php endif; ?>

            <div class="nav-divider"></div>

            <?php if ($user_role == 'admin'): ?>
            <a href="laporan_penjualan.php" class="nav-item <?php echo is_active('laporan_penjualan.php', $current_page); ?>">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span>
                <span class="label">Laporan Penjualan</span>
            </a>
            <?php endif; ?>

            <div class="nav-divider"></div>

            <div class="nav-group-title">PENGATURAN</div>

            <?php if ($user_role == 'admin'): ?>
            <a href="pengaturan_website.php" class="nav-item <?php echo is_active('pengaturan_website.php', $current_page); ?>">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1Z"/></svg></span>
                <span class="label">Pengaturan Web</span>
            </a>
            <?php endif; ?>

            <div class="nav-divider"></div>

            <a href="../index.php" target="_blank" class="nav-item">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></span>
                <span class="label">Lihat Toko</span>
            </a>

        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="nav-item logout">
                <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></span>
                <span class="label">Logout</span>
            </a>
        </div>
    </aside>
    
    <div class="admin-content">