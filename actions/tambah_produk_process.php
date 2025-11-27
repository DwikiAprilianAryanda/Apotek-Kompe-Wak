<?php
session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Ambil data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']); // Kolom Baru
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    
    // 2. Upload Gambar
    $image_name = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/';
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = uniqid('prod_') . '.' . $ext;
        
        if (in_array($_FILES['image']['type'], $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name)) {
                $image_name = $new_name;
            } else {
                header("Location: ../admin/tambah_produk.php?error=uploadfail"); exit;
            }
        } else {
            header("Location: ../admin/tambah_produk.php?error=uploadfail"); exit;
        }
    }

    // 3. Simpan ke Database (UPDATE SQL DI SINI)
    try {
        // Menambahkan 'category' ke query
        $stmt = $conn->prepare("INSERT INTO products (name, description, category, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        // sssdis -> string, string, string, decimal, integer, string
        $stmt->bind_param("sssdis", $name, $description, $category, $price, $stock_quantity, $image_name);
        
        if ($stmt->execute()) {
            header("Location: ../admin/produk.php?status=added");
            exit;
        } else {
            header("Location: ../admin/tambah_produk.php?error=dbfail");
            exit;
        }
        $stmt->close();
        
    } catch (Exception $e) {
        header("Location: ../admin/tambah_produk.php?error=dbfail&msg=" . $e->getMessage());
        exit;
    }
    $conn->close();
} else {
    header("Location: ../admin/produk.php");
    exit;
}
?>