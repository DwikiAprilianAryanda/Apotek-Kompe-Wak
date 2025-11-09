<?php
// --- DIAGNOSA: AKTIFKAN ERROR REPORTING ---
ini_set('display_errors', 1);
error_reporting(E_ALL);
// --- AKHIR DIAGNOSA ---

session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan pengguna login
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login dulu.");
}

// 1. Definisikan variabel dasar
$user_id = $_SESSION['user_id'];
$catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : '';
$upload_dir = '../uploads/resep/'; 

// 2. Periksa apakah file ada dan tidak ada error
if (isset($_FILES['resep_file']) && $_FILES['resep_file']['error'] == 0) {
    
    $file = $_FILES['resep_file'];
    $original_name = basename($file['name']);
    
    // 3. Validasi Keamanan File (Dilewati untuk debugging, tapi biarkan kode ada)
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg']; // Tambahkan jpg
    $max_size = 5 * 1024 * 1024; // 5 MB

    if (!in_array($file['type'], $allowed_types) || $file['size'] > $max_size) {
        header("Location: ../unggah_resep.php?status=uploadfail"); 
        exit;
    }

    // 4. Buat nama file yang unik
    $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid('resep_' . $user_id . '_') . '.' . $file_extension;
    $target_path = $upload_dir . $new_file_name;
    
    // --- TES DIAGNOSA TAMBAHAN: Cek Path dan Izin ---
    if (!is_dir($upload_dir)) {
        die("Fatal Error: Folder upload TIDAK DITEMUKAN. Pastikan folder 'uploads/resep' ada di root.");
    }
    // --- AKHIR TES DIAGNOSA TAMBAHAN ---

    // 5. Pindahkan file dari temp ke folder permanen
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        
        // 6. Simpan informasi ke database
        try {
            $stmt = $conn->prepare("INSERT INTO prescriptions (user_id, file_name, original_name, catatan) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $new_file_name, $original_name, $catatan);
            
            if ($stmt->execute()) {
                // Berhasil!
                header("Location: ../unggah_resep.php?status=uploaded");
                exit;
            } else {
                // Gagal simpan ke DB
                die("SQL Error: " . $conn->error);
            }
            $stmt->close();

        } catch (Exception $e) {
            die("Database Exception: " . $e->getMessage());
        }

    } else {
        // Gagal memindahkan file (kemungkinan besar masalah izin)
        die("Fatal Error: GAGAL MEMINDAHKAN FILE. Periksa izin folder 'uploads/resep'.");
    }

} else {
    // Tidak ada file atau error upload (misalnya file size melebihi batas PHP)
    header("Location: ../unggah_resep.php?status=uploadfail");
    exit;
}

$conn->close();
?>