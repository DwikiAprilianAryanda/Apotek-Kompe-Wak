<?php
session_start();
// Naik satu folder untuk akses db_connect
include '../includes/db_connect.php';

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password_input = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($email) || empty($password_input)) {
    header("Location: login.php?error=empty");
    exit;
}

// Ambil data user berdasarkan email
$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    // Verifikasi Password
    if (password_verify($password_input, $user['password'])) {
        
        // --- VALIDASI KHUSUS ADMIN ---
        // Jika user yang login BUKAN admin/resepsionis, tolak!
        if ($user['role'] !== 'admin' && $user['role'] !== 'receptionist') {
            header("Location: login.php?error=access");
            exit;
        }

        // Set Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        
        // Redirect ke Dashboard Admin
        header("Location: index.php");
        exit;

    } else {
        header("Location: login.php?error=wrong");
        exit;
    }
} else {
    header("Location: login.php?error=wrong");
    exit;
}

$stmt->close();
$conn->close();
?>