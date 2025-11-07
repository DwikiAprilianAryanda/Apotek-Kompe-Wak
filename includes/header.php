<?php session_start(); // Pindahkan session_start() ke paling atas header ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Arshaka</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=1.1">
</head>
<body>

<header>
    <nav>
        <a href="index.php" class="logo">Apotek Arshaka</a>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="produk.php">Produk</a></li>
            <li><a href="keranjang.php">Keranjang</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="akun.php">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a></li>
                <li><a href="actions/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
            
        </ul>
    </nav>
</header>

<main>