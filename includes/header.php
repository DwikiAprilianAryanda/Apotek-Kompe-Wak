<?php 
// Pengecekan Aman: Pastikan sesi dimulai HANYA jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inisialisasi variabel pencarian untuk memperbaiki error
$searchTerm = ''; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Arshaka</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <header>
        <div class="header-top">
            <div class="logo-judul">
                <img src="/assets/images/apotek.jpg" alt="Apotek Arshaka" class="logo-kanan">
                <h1>Apotek Arshaka</h1>
            </div>
            
            <div class="header-form">
                <form action="produk.php" method="GET" class="input-wrapper">
                    <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit">&#x1F50D;</button>
                </form>
            </div>
        </div>
        
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="produk.php">Produk</a>
            <a href="keranjang.php">Keranjang</a>
            
            <?php if (isset($_SESSION['user_id'])): // Jika pengguna SUDAH LOGIN ?>
                
                <?php 
                // Ambil role pengguna
                $role = $_SESSION['user_role'] ?? 'customer';
                
                // Tampilkan "Unggah Resep" HANYA jika role-nya customer
                if ($role == 'customer'): 
                ?>
                    <a href="unggah_resep.php" style="color: #ffc107; font-weight: bold;">Unggah Resep</a>
                <?php endif; ?>

                <a href="akun.php">Akun Saya</a>
                
                <?php 
                // Tampilkan "Dasbor Admin" HANYA jika role-nya admin atau receptionist
                if ($role == 'admin' || $role == 'receptionist'): 
                ?>
                    <a href="admin/index.php" style="color: #ffc107; font-weight: bold;">Dasbor Admin</a>
                <?php endif; ?>
                
                <a href="actions/logout.php">Logout</a>

            <?php else: // Jika pengguna BELUM LOGIN ?>

                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                
            <?php endif; ?>
        </nav>
    </header>

<main>