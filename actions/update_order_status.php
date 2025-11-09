<?php
session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan admin yang login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Akses ditolak.");
}

// 1. Ambil semua data dari form (Tugas 4)
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    // TITIK INTEGRASI TUGAS 4 (Nomor Resi)
    // Ambil nomor resi dari form. Jika tidak ada, isi null.
    // Pastikan Anda sudah menambahkan input 'shipping_code' di form admin/view_order.php
    $shipping_code = !empty($_POST['shipping_code']) ? $_POST['shipping_code'] : null; 

    // --- LOGIKA INTEGRASI TUGAS 1 & 3 (Inventaris) ---
    // Cek DULU status lama. Kita perlu tahu apakah statusnya *baru* diubah jadi 'Shipped'
    $stmt_check = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt_check->bind_param("i", $order_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows == 0) {
        die("Order tidak ditemukan.");
    }
    
    $old_status = $result_check->fetch_assoc()['status'];
    $stmt_check->close();

    // Tentukan apakah kita perlu mengurangi stok.
    // HANYA JIKA status BARU adalah 'Shipped' DAN status LAMA BUKAN 'Shipped'.
    // Ini mencegah stok berkurang 2x jika admin menekan tombol update 2x.
    $reduce_stock = ($new_status == 'Shipped' && $old_status != 'Shipped');
    // --- AKHIR LOGIKA INTEGRASI ---

    
    // Gunakan Database Transaction untuk keamanan data
    $conn->begin_transaction();

    try {
        // PERTAMA: Update status dan nomor resi (TUGAS 4)
        $stmt_update_order = $conn->prepare("UPDATE orders SET status = ?, shipping_code = ? WHERE id = ?");
        // 'ssi' = string (status), string (shipping_code), integer (id)
        $stmt_update_order->bind_param("ssi", $new_status, $shipping_code, $order_id);
        
        if (!$stmt_update_order->execute()) {
            throw new Exception("Gagal mengupdate status pesanan.");
        }
        $stmt_update_order->close();

        // KEDUA: Jika $reduce_stock adalah true, jalankan logika inventaris (TUGAS 3)
        if ($reduce_stock) {
            // Ambil SEMUA item dari pesanan ini
            $stmt_items = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
            $stmt_items->bind_param("i", $order_id);
            $stmt_items->execute();
            $items = $stmt_items->get_result();
            
            if ($items->num_rows > 0) {
                // Siapkan query untuk update stok (akan dipakai berulang kali)
                $stmt_update_stock = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                
                while ($item = $items->fetch_assoc()) {
                    // Kurangi stok untuk setiap item
                    $stmt_update_stock->bind_param("ii", $item['quantity'], $item['product_id']);
                    if (!$stmt_update_stock->execute()) {
                        // Jika gagal update stok, lempar error agar transaksi dibatalkan
                        throw new Exception("Gagal mengupdate stok untuk produk ID: " . $item['product_id']);
                    }
                }
                $stmt_update_stock->close();
            }
            $stmt_items->close();
        }

        // Jika semua langkah di atas berhasil, simpan perubahan
        $conn->commit();
        
        // Kembalikan ke halaman detail dengan pesan sukses
        header("Location: /admin/view_order.php?id=" . $order_id . "&status=updated");

    } catch (Exception $e) {
        // Jika ada SATU saja yang gagal, batalkan SEMUA perubahan
        $conn->rollback();
        echo "Terjadi kesalahan. Transaksi dibatalkan: " . $e->getMessage();
        echo "<br><a href='/admin/view_order.php?id=" . $order_id . "'>Kembali ke pesanan</a>";
    }

    $conn->close();

} else {
    // Jika data tidak lengkap, kembalikan ke dasbor admin
    header("Location: /admin/index.php");
}
exit;
?>