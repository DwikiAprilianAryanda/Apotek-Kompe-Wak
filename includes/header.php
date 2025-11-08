<?php session_start(); // Pindahkan session_start() ke paling atas header ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Arshaka</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header>
        <div class="header-top">
            <div class="logo-judul">
                <img src="foto/logo.png" alt="Apotek Arshaka" class="logo-kanan">
                <h1>Apotek Arshaka</h1>
            </div>
            <div class="header-form">
                <div class="input-wrapper">
                    <input type="text" placeholder="Cari produk atau wisata..." value="<?php echo $searchTerm; ?>">
                    <button type="submit">&#x1F50D;</button>
                </div>
            </div>
        </div>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="produk.php">Produk</a>
            <a href="keranjang.php">Keranjang</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </nav>
    </header>

<main>