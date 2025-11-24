<?php
include '../includes/db_connect.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$ktp = trim($_POST['no_ktp']);
$height = (int)$_POST['height'];
$weight = (int)$_POST['weight'];

$role = 'customer'; 

if (empty($name) || empty($email) || empty($password) || empty($ktp)) {
    die("Error: Data wajib (Nama, Email, Password, KTP) harus diisi.");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update Query
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, no_ktp, height, weight) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssii", $name, $email, $hashed_password, $role, $ktp, $height, $weight);

if ($stmt->execute()) {
    header("Location: ../register_success.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>