<?php
// Pengecekan Aman: Pastikan sesi dimulai HANYA jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inisialisasi variabel pencarian untuk memperbaiki error
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Arshaka</title>
    <!-- Perbaiki kesalahan penulisan di baris ini -->
    <link rel="stylesheet" href="/assets/css/style.css?v=1.2">
</head>
<body>

    <header>
        <div class="header-container">
            <div class="logo-judul">
                <img src="assets/images/logo_apotek.jpg" alt="Apotek Arshaka" class="logo-kanan">
                <h1>Apotek Arshaka</h1>
            </div>

            <!-- Hamburger Menu (akan muncul di mobile) -->
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Navigasi Utama -->
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="produk.php">Produk</a>
                <a href="keranjang.php">Keranjang</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php
                    $role = $_SESSION['user_role'] ?? 'customer';
                    if ($role == 'customer'):
                    ?>
                        <a href="unggah_resep.php" class="nav-link-special">Unggah Resep</a>
                    <?php endif; ?>

                    <a href="akun.php">Akun Saya</a>

                    <?php
                    if ($role == 'admin' || $role == 'receptionist'):
                    ?>
                        <a href="admin/index.php" class="nav-link-special">Dasbor Admin</a>
                    <?php endif; ?>

                    <a href="actions/logout.php">Logout</a>

                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>

        <?php if (basename($_SERVER['PHP_SELF']) == 'produk.php'): ?>
        <div class="header-form">
            <form action="produk.php" method="GET" class="input-wrapper">
                <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">&#x1F50D;</button>
            </form>
        </div>
        <?php endif; ?>
    </header>

<main>