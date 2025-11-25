<?php
include '../includes/db_connect.php';

// 1. Ambil data dari form
$name = trim($_POST['name']);
$gender = trim($_POST['gender']);
$height = (int)$_POST['height'];
$weight = (int)$_POST['weight'];
$medical_history = trim($_POST['medical_history']); // Bisa kosong
$email = trim($_POST['email']);
$password = trim($_POST['password']);

$role = 'customer'; 

// 2. Validasi Data Wajib
if (empty($name) || empty($email) || empty($password) || empty($gender)) {
    die("Error: Data wajib (Nama, Jenis Kelamin, Email, Password) harus diisi. <a href='../register.php'>Kembali</a>");
}

// 3. Hash Password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 4. Query Insert (Update kolom sesuai DB baru)
// Pastikan urutan kolom di INSERT INTO sama dengan jumlah tanda tanya (?)
$sql = "INSERT INTO users (name, gender, height, weight, medical_history, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    // s = string, i = integer
    // Urutan: name(s), gender(s), height(i), weight(i), medical_history(s), email(s), password(s), role(s)
    $stmt->bind_param("ssiissss", $name, $gender, $height, $weight, $medical_history, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        header("Location: ../register_success.php");
        exit;
    } else {
        echo "Error Eksekusi: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error Prepare Statement: " . $conn->error;
}

$conn->close();
?>