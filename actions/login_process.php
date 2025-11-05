<?php
session_start();
include '../includes/db_connect.php';

$email = $_POST['email'];
$password_input = $_POST['password'];

// UBAH QUERY INI: Tambahkan 'role'
$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password_input, $user['password'])) {
        // Password cocok! Buat sesi login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role']; // <-- TAMBAHKAN BARIS INI
        
        // Arahkan admin ke dasbor, pelanggan ke index
        if ($user['role'] == 'admin') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    } else {
        echo "Password salah. <a href='../login.php'>Coba lagi</a>.";
    }
} else {
    echo "Email tidak terdaftar. <a href='../login.php'>Coba lagi</a>.";
}

$stmt->close();
$conn->close();
?>