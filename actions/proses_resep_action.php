<?php
session_start();
include '../includes/db_connect.php';

// KEAMANAN: Pastikan Admin/Resepsionis yang login
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'receptionist')) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resep_id'])) {
    
    $resep_id = $_POST['resep_id'];
    $action = $_POST['action'];

    if ($action == 'reject') {
        try {
            $stmt = $conn->prepare("UPDATE prescriptions SET status = 'Rejected' WHERE id = ?");
            $stmt->bind_param("i", $resep_id);
            $stmt->execute();
            $stmt->close();
            
            header("Location: ../admin/verifikasi_resep.php?status=rejected");
            exit;
        } catch (Exception $e) {
            die("Gagal menolak resep. " . $e->getMessage());
        }
    }

    if ($action == 'verify' && isset($_POST['products']) && isset($_POST['user_id'])) {
        
        $user_id = (int)$_POST['user_id']; 
        
        // --- VALIDASI KRITIS USER ID ---
        if ($user_id <= 0) { 
            header("Location: ../admin/proses_resep.php?id=$resep_id&error=db&msg=" . urlencode("ID Pelanggan (User ID) tidak valid. Tidak dapat membuat pesanan."));
            exit;
        }
        // --- AKHIR VALIDASI KRITIS ---
        
        $selected_items = $_POST['products'];
        $total_amount = 0;
        $items_for_db = [];
        $product_ids = [];

        foreach ($selected_items as $item) {
            if (!empty($item['id']) && $item['qty'] > 0) {
                $product_ids[] = $item['id'];
            }
        }
        
        if (empty($product_ids)) {
             header("Location: ../admin/proses_resep.php?id=$resep_id&error=empty");
             exit;
        }
        
        $ids_string = implode(',', array_map('intval', $product_ids));
        $sql = "SELECT id, price, stock_quantity, name FROM products WHERE id IN ($ids_string)";
        $result = $conn->query($sql);
        $product_data = [];
        while ($row = $result->fetch_assoc()) {
            $product_data[$row['id']] = $row;
        }

        $conn->begin_transaction();

        try {
            // 3. Verifikasi Stok dan Hitung Total
            foreach ($selected_items as $item) {
                $p_id = $item['id'];
                $qty = (int)$item['qty'];

                if ($qty <= 0 || !isset($product_data[$p_id])) continue; 

                $data = $product_data[$p_id];
                $price = $data['price'];
                $stock = $data['stock_quantity'];

                if ($qty > $stock) {
                    throw new Exception("Stok tidak mencukupi untuk produk: " . $product_data[$p_id]['name'] . ".");
                }

                $subtotal = $price * $qty;
                $total_amount += $subtotal;
                
                $items_for_db[] = [
                    'product_id' => $p_id,
                    'quantity' => $qty,
                    'price_per_item' => $price
                ];
                
                // Kurangi stok ke dalam transaksi
                $stmt_stock = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $stmt_stock->bind_param("ii", $qty, $p_id);
                if (!$stmt_stock->execute()) {
                    throw new Exception("Gagal mengurangi stok.");
                }
                $stmt_stock->close();
            }
            
            // 4. Masukkan Pesanan ke tabel ORDERS
            $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
            $stmt_order->bind_param("id", $user_id, $total_amount);
            $stmt_order->execute();
            $order_id = $conn->insert_id;
            $stmt_order->close();
            
            // 5. Masukkan Item ke tabel ORDER_ITEMS
            $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_per_item) VALUES (?, ?, ?, ?)");
            foreach ($items_for_db as $item) {
                $stmt_items->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price_per_item']);
                $stmt_items->execute();
            }
            $stmt_items->close();
            
            // 6. UPDATE STATUS RESEP menjadi 'Verified'
            $stmt_resep = $conn->prepare("UPDATE prescriptions SET status = 'Verified' WHERE id = ?");
            $stmt_resep->bind_param("i", $resep_id);
            $stmt_resep->execute();
            $stmt_resep->close();

            // 7. Selesai: COMMIT transaksi
            $conn->commit();

            header("Location: ../admin/verifikasi_resep.php?status=verified");
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            header("Location: ../admin/proses_resep.php?id=$resep_id&error=db&msg=" . urlencode($e->getMessage()));
            exit;
        }

    }

} else {
    header("Location: ../admin/verifikasi_resep.php");
    exit;
}
$conn->close();
?>