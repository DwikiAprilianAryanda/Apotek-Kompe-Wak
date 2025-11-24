<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}

// LOGIKA DETEKSI HALAMAN
$current_page = basename($_SERVER['PHP_SELF']);
$is_home = ($current_page == 'index.php');
$header_class = $is_home ? '' : 'header-solid'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Arshaka</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=2.2">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<header class="<?php echo $header_class; ?>">
    <div class="header-container">
        <a href="/index.php" class="logo-area">
            <img src="/assets/images/logo_apotek.jpg" alt="Logo" class="logo-img" style="height: 50px; width: auto;">
            <span class="logo-text">ARSHAKA</span>
        </a>

        <nav class="nav-menu">
            <a href="/index.php">Beranda</a>
            <a href="/produk.php">Produk</a>
            <a href="/unggah_resep.php">Unggah Resep</a>
            <a href="/index.php#contact">Kontak</a>
        </nav>

        <div class="header-icons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/akun.php" class="icon-link">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span>Akun</span>
                </a>
                <a href="/actions/logout.php" class="icon-link" style="margin-left:10px;">
                    <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-9c-1.103 0-2 .897-2 2v4h2V5h9v14h-9v-4H9v4c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2z"/></svg>
                </a>
            <?php else: ?>
                <a href="/login.php" class="btn" style="background-color: #ffc107; color: #1b3270; padding: 8px 20px;">Masuk / Daftar</a>
            <?php endif; ?>

            <a href="/keranjang.php" class="icon-link">
                <svg viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                <?php if($cart_count > 0): ?>
                    <span class="cart-badge"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</header>

<main style="<?php echo $is_home ? '' : 'padding-top: 100px;'; ?>">