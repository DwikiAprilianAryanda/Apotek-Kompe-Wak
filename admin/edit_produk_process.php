<?php
session_start();
include '../includes/db_connect.php'; // Path ini masih benar (keluar satu level ke root)

// Keamanan: Pastikan admin yang login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Akses ditolak.");
}

// Pastikan ini adalah request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Ambil semua data dari form
    $product_id = $_POST['product_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $old_image = $_POST['old_image'];
    
    $image_name = $old_image; 

    // 2. Logika Upload Gambar BARU (jika ada)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
        $upload_dir = '../assets/images/'; // Path ini masih benar
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid('prod_') . '.' . $file_extension;
        $target_file = $upload_dir . $image_name;
        
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                if ($old_image != 'default.jpg' && file_exists($upload_dir . $old_image)) {
                    unlink($upload_dir . $old_image);
                }
            } else {
                // PERUBAHAN DI SINI: Path redirect
                header("Location: edit_produk.php?id=" . $product_id . "&error=uploadfail");
                exit;
            }
        } else {
            // PERUBAHAN DI SINI: Path redirect
            header("Location: edit_produk.php?id=" . $product_id . "&error=uploadfail");
            exit;
        }
    }

    // 3. Update data di Database
    try {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("ssdisi", $name, $description, $price, $stock_quantity, $image_name, $product_id);
        
        if ($stmt->execute()) {
            // PERUBAHAN DI SINI: Path redirect
            header("Location: produk.php?status=updated");
            exit;
        } else {
            // PERUBAHAN DI SINI: Path redirect
            header("Location: edit_produk.php?id=" . $product_id . "&error=dbfail");
            exit;
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        // PERUBAHAN DI SINI: Path redirect
        header("Location: edit_produk.php?id=" . $product_id . "&error=dbfail&msg=" . $e->getMessage());
        exit;
    }

    $conn->close();

} else {
    // PERUBAHAN DI SINI: Path redirect
    header("Location: produk.php");
    exit;
}
?>