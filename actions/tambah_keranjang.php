<?php
session_start();

// Pastikan session cart ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Pastikan ini adalah request POST dan ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity']; // Ambil kuantitas dari form

    // Validasi kuantitas (minimal 1)
    if ($quantity <= 0) {
        $quantity = 1;
    }

    // --- LOGIKA KERANJANG BARU ---
    // Periksa apakah produk sudah ada di keranjang
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Jika sudah ada, tambahkan kuantitasnya
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Jika belum ada, set kuantitasnya
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    // --- PERUBAHAN UTAMA: REDIRECT ---
    // Alih-alih mengirim JSON, kita kembalikan pengguna ke halaman produk.
    // Kita bisa tambahkan parameter 'status=added' untuk notifikasi nanti.
    header("Location: ../produk.php?status=cart_added");
    exit;

} else {
    // Jika data tidak lengkap, kembalikan ke halaman produk
    header("Location: ../produk.php?error=cart_fail");
    exit;
}
?>