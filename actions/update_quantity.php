<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {

    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Validasi kuantitas (minimal 1, maksimal stok)
    if ($quantity < 1) {
        $quantity = 1;
    }
    // (Opsional) Validasi maksimal sesuai stok dari database

    // Periksa apakah produk ada di keranjang
    if (isset($_SESSION['cart']) && array_key_exists($product_id, $_SESSION['cart'])) {
        // Update kuantitasnya
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Kembali ke halaman keranjang
header("Location: ../keranjang.php");
exit;
?>