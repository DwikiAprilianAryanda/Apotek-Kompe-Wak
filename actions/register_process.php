<?php
include '../includes/db_connect.php'; // Hubungkan ke database

// Ambil data dari form dan BERSIHKAN
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']); 

// ROLE default untuk registrasi
$role = 'customer'; 

// VALIDASI SEDERHANA
if (empty($name) || empty($email) || empty($password)) {
    die("Error: Semua field harus diisi.");
}

// KEAMANAN: HASH PASSWORD!
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Siapkan query untuk mencegah SQL Injection
// Perubahan: Tambahkan kolom 'role'
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
// 'ssss' berarti empat variabel berikutnya adalah string
$stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

if ($stmt->execute()) {
    // Jika berhasil, arahkan ke halaman sukses
    header("Location: ../register_success.php");
    exit;
} else {
    // Jika gagal (misal: email sudah terdaftar)
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>