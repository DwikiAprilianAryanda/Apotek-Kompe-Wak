<?php
session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Ambil data teks
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $height = isset($_POST['height']) ? (int)$_POST['height'] : 0;
    $weight = isset($_POST['weight']) ? (int)$_POST['weight'] : 0;
    $phone = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';

    // Validasi Nama
    if (empty($name)) {
        die("Nama tidak boleh kosong. <a href='../akun.php'>Kembali</a>");
    }

    // 2. LOGIKA UPLOAD GAMBAR
    $profile_pic_sql = ""; // Variabel untuk menyimpan query tambahan
    $params = [];          // Array parameter bind
    $types = "";           // String tipe data bind

    // Siapkan folder upload
    $upload_dir = '../uploads/profiles/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Buat folder jika belum ada
    }

    // Cek apakah ada file yang diupload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file = $_FILES['profile_pic'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        
        // Validasi tipe file
        if (in_array($file['type'], $allowed_types)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            // Buat nama file unik: profile_USERID_TIMESTAMP.jpg
            $new_filename = "profile_" . $user_id . "_" . time() . "." . $ext;
            $target_path = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // Jika sukses upload, tambahkan ke query SQL
                $profile_pic_sql = ", profile_pic = ?";
            }
        }
    }

    // 3. SUSUN QUERY SQL (DINAMIS)
    // Jika ada gambar baru, update kolom profile_pic juga. Jika tidak, biarkan.
    $sql = "UPDATE users SET name = ?, gender = ?, height = ?, weight = ?, phone_number = ?, address = ?" . $profile_pic_sql . " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameter dasar
        if (!empty($profile_pic_sql)) {
            // Urutan: name(s), gender(s), height(i), weight(i), phone(s), address(s), profile_pic(s), id(i)
            $stmt->bind_param("ssiisssi", $name, $gender, $height, $weight, $phone, $address, $new_filename, $user_id);
        } else {
            // Urutan: name(s), gender(s), height(i), weight(i), phone(s), address(s), id(i)
            $stmt->bind_param("ssiissi", $name, $gender, $height, $weight, $phone, $address, $user_id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name; // Update nama di session
            header("Location: ../akun.php?status=updated");
            exit;
        } else {
            echo "Gagal menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error Database: " . $conn->error;
    }
}

$conn->close();
?>