<?php
session_start();
include '../includes/db_connect.php'; // Path ini masih benar

// Keamanan: Pastikan admin yang login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Akses ditolak.");
}

// 1. Validasi ID Produk
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID Produk tidak valid.");
}
$product_id = $_GET['id'];

// (Opsional) Hapus file gambar dari server
try {
    $stmt_img = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt_img->bind_param("i", $product_id);
    $stmt_img->execute();
    $result_img = $stmt_img->get_result();
    
    if ($result_img->num_rows > 0) {
        $image_name = $result_img->fetch_assoc()['image_url'];
        $upload_dir = '../assets/images/'; // Path ini masih benar
        
        if ($image_name != 'default.jpg' && file_exists($upload_dir . $image_name)) {
            unlink($upload_dir . $image_name);
        }
    }
    $stmt_img->close();

} catch (Exception $e) {
    // Abaikan jika gagal
}


// 2. Hapus data produk dari Database
try {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        // --- PERUBAHAN DI SINI ---
        // Redirect kembali ke produk.php (di folder yang sama)
        header("Location: produk.php?status=deleted");
        exit;
    } else {
        die("Gagal menghapus produk: " . $conn->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    // --- PERUBAHAN DI SINI ---
    // Tampilkan pesan error dengan link kembali yang benar
    die("Error: Produk ini tidak bisa dihapus karena sudah ada di pesanan. <a href='produk.php'>Kembali</a>");
}

$conn->close();
?>