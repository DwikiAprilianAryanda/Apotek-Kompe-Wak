<?php
session_start();
include '../includes/db_connect.php';

// Keamanan
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'receptionist')) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Tangani Aksi TOLAK (Reject)
    if (isset($_POST['action']) && $_POST['action'] == 'reject') {
        $resep_id = $_POST['prescription_id'];
        $stmt = $conn->prepare("UPDATE prescriptions SET status = 'Rejected' WHERE id = ?");
        $stmt->bind_param("i", $resep_id);
        $stmt->execute();
        $stmt->close();
        header("Location: ../admin/verifikasi_resep.php?status=rejected");
        exit;
    }

    // 2. Tangani Aksi PROSES (Simpan Pesanan)
    if (isset($_POST['prescription_id']) && isset($_POST['medicines']) && isset($_POST['user_id'])) {
        
        $resep_id = $_POST['prescription_id'];
        $user_id = $_POST['user_id'];
        $medicines = $_POST['medicines']; // Array obat dari form
        
        if (empty($medicines)) {
            header("Location: ../admin/proses_resep.php?id=$resep_id&error=empty");
            exit;
        }

        // Hitung Total & Siapkan Data
        $total_amount = 0;
        foreach ($medicines as $item) {
            $qty = (int)$item['quantity'];
            $price = (float)$item['price'];
            $total_amount += ($qty * $price);
        }

        $conn->begin_transaction();

        try {
            // A. Buat Order Baru
            $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
            $stmt_order->bind_param("id", $user_id, $total_amount);
            $stmt_order->execute();
            $order_id = $conn->insert_id;
            $stmt_order->close();

            // B. Masukkan Item Order & Kurangi Stok
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_per_item) VALUES (?, ?, ?, ?)");
            $stmt_stock = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");

            foreach ($medicines as $item) {
                $p_id = $item['id'];
                $qty = $item['quantity'];
                $price = $item['price'];

                // Insert Item
                $stmt_item->bind_param("iiid", $order_id, $p_id, $qty, $price);
                $stmt_item->execute();

                // Kurangi Stok
                $stmt_stock->bind_param("ii", $qty, $p_id);
                $stmt_stock->execute();
            }
            $stmt_item->close();
            $stmt_stock->close();

            // C. Update Status Resep jadi 'Verified'
            $stmt_resep = $conn->prepare("UPDATE prescriptions SET status = 'Verified' WHERE id = ?");
            $stmt_resep->bind_param("i", $resep_id);
            $stmt_resep->execute();
            $stmt_resep->close();

            $conn->commit();
            
            // Berhasil
            header("Location: ../admin/verifikasi_resep.php?status=success");
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            die("Terjadi kesalahan: " . $e->getMessage());
        }
    }
}

// Jika akses langsung tanpa POST
header("Location: ../admin/verifikasi_resep.php");
exit;
?>