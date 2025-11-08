<?php
session_start();

// Pastikan ini adalah request POST dan ada product_id
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    
    $product_id = $_POST['product_id'];

    // Periksa apakah keranjang ada dan apakah produk ada di keranjang
    if (isset($_SESSION['cart']) && array_key_exists($product_id, $_SESSION['cart'])) {
        
        // Hapus produk dari array session keranjang
        unset($_SESSION['cart'][$product_id]);
    }
}

// Setelah selesai (baik berhasil atau gagal),
// kembalikan pengguna ke halaman keranjang
header("Location: ../keranjang.php");
exit;
?>