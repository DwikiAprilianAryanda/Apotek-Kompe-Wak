<?php
session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan admin yang login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Akses ditolak.");
}

// Pastikan ini adalah request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Ambil data dari form
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    
    // 2. Logika Upload Gambar
    $image_name = 'default.jpg'; // Gambar default jika tidak ada yg di-upload
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/'; // Pastikan folder ini ada dan bisa ditulis (writable)
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        
        // Buat nama file unik untuk mencegah penimpaan file
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid('prod_') . '.' . $file_extension;
        $target_file = $upload_dir . $image_name;
        
        // Validasi tipe file
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            // Pindahkan file dari temp ke folder permanen
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Gagal upload, kembali ke form dengan pesan error
                header("Location: ../admin/tambah_produk.php?error=uploadfail");
                exit;
            }
        } else {
            // Tipe file tidak diizinkan
            header("Location: ../admin/tambah_produk.php?error=uploadfail");
            exit;
        }
    }

    // 3. Simpan data ke Database
    try {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?)");
        // 'ssdss' = string, string, decimal, integer, string
        $stmt->bind_param("ssdis", $name, $description, $price, $stock_quantity, $image_name);
        
        if ($stmt->execute()) {
            // Berhasil! Redirect ke halaman produk dengan pesan sukses
            header("Location: ../admin/produk.php?status=added");
            exit;
        } else {
            // Gagal eksekusi query
            header("Location: ../admin/tambah_produk.php?error=dbfail");
            exit;
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        // Error database lainnya
        header("Location: ../admin/tambah_produk.php?error=dbfail&msg=" . $e->getMessage());
        exit;
    }

    $conn->close();

} else {
    // Jika bukan request POST, tendang ke halaman produk
    header("Location: ../admin/produk.php");
    exit;
}
?>