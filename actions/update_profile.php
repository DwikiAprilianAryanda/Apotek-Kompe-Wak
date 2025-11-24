<?php
session_start();
include '../includes/db_connect.php';
if (!isset($_SESSION['user_id'])) { die("Akses ditolak."); }

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $ktp = $_POST['no_ktp'];
    $height = (int)$_POST['height'];
    $weight = (int)$_POST['weight'];
    $phone = $_POST['phone_number'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, no_ktp = ?, height = ?, weight = ?, phone_number = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssiisss", $name, $ktp, $height, $weight, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        header("Location: /akun.php?status=updated");
    } else {
        echo "Gagal update: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>