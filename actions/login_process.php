<?php
session_start();
include '../includes/db_connect.php';

// Ambil data dari form dan BERSIHKAN
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password_input = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validasi input kosong
if (empty($email) || empty($password_input)) {
     header("Location: ../login.php?error=empty");
     exit;
}

$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    // Verifikasi password
    if (password_verify($password_input, $user['password'])) {
        // Password cocok! Buat sesi login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role']; 
        
        // --- LOGIKA REDIRECT YAKIN BENAR ---
        if ($user['role'] == 'admin' || $user['role'] == 'receptionist') {
            header("Location: ../admin/index.php"); // Resepsionis & Admin ke Dasbor
        } else {
            header("Location: ../index.php"); // Pelanggan ke Index
        }
        exit;
    } else {
        // Password salah
        header("Location: ../login.php?error=wrongpass");
        exit;
    }
} else {
    // Email tidak terdaftar
    header("Location: ../login.php?error=nouser");
    exit;
}

$stmt->close();
$conn->close();
?>