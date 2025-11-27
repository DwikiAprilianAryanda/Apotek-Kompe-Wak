<?php
session_start();
include '../includes/db_connect.php';

// Cek Admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin')) {
    die("Akses ditolak.");
}

// 1. UPDATE TEKS
if (isset($_POST['settings'])) {
    foreach ($_POST['settings'] as $key => $value) {
        $clean_value = $conn->real_escape_string($value);
        // Kita gunakan ON DUPLICATE KEY UPDATE agar bisa insert jika belum ada
        $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$clean_value') 
                ON DUPLICATE KEY UPDATE setting_value = '$clean_value'";
        $conn->query($sql);
    }
}

// 2. UPDATE GAMBAR
$upload_dir = '../assets/images/';
$allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

// Daftar key file yang mungkin diupload
$file_keys = ['home_bg_image', 'home_about_image', 'service_icon_1', 'service_icon_2', 'service_icon_3'];

foreach ($file_keys as $key) {
    if (isset($_FILES[$key]) && $_FILES[$key]['error'] == 0) {
        $file = $_FILES[$key];
        
        if (in_array($file['type'], $allowed_types)) {
            // Buat nama file unik
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_name = $key . '_' . time() . '.' . $ext;
            $target = $upload_dir . $new_name;
            
            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Simpan path relatif ke database (assets/images/namafile.jpg)
                $db_path = 'assets/images/' . $new_name;
                
                $sql = "UPDATE site_settings SET setting_value = '$db_path' WHERE setting_key = '$key'";
                $conn->query($sql);
            }
        }
    }
}

header("Location: ../admin/pengaturan_website.php?status=updated");
exit;
?>