<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Akses ditolak.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $product_id = $_POST['product_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']); 
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $old_image = $_POST['old_image'];
    
    $image_name = $old_image; 

    // Logika Upload Gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
        $upload_dir = '../assets/images/';
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = uniqid('prod_') . '.' . $ext;
        
        if (in_array($_FILES['image']['type'], $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name)) {
                $image_name = $new_name;
                if ($old_image != 'default.jpg' && file_exists($upload_dir . $old_image)) {
                    unlink($upload_dir . $old_image);
                }
            } else {
                header("Location: ../admin/edit_produk.php?id=" . $product_id . "&error=uploadfail"); exit;
            }
        } else {
            header("Location: ../admin/edit_produk.php?id=" . $product_id . "&error=uploadfail"); exit;
        }
    }

    // Update Database
    try {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, category = ?, price = ?, stock_quantity = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("sssdisi", $name, $description, $category, $price, $stock_quantity, $image_name, $product_id);
        
        if ($stmt->execute()) {
            // PERBAIKAN PENTING DI SINI: ../admin/produk.php
            header("Location: ../admin/produk.php?status=updated");
            exit;
        } else {
            header("Location: ../admin/edit_produk.php?id=" . $product_id . "&error=dbfail");
            exit;
        }
        $stmt->close();
        
    } catch (Exception $e) {
        header("Location: ../admin/edit_produk.php?id=" . $product_id . "&error=dbfail&msg=" . $e->getMessage());
        exit;
    }
    $conn->close();
} else {
    header("Location: ../admin/produk.php");
    exit;
}
?>