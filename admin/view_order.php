<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// 1. Ambil Order ID dari URL
if (!isset($_GET['id'])) {
    header("Location: /admin/index.php");
    exit;
}
$order_id = $_GET['id'];

// 2. Ambil Info Pesanan Utama (dan data pelanggan)
$sql_order = "SELECT orders.*, users.name, users.email, users.address, users.phone_number 
              FROM orders 
              JOIN users ON orders.user_id = users.id 
              WHERE orders.id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    echo "Pesanan tidak ditemukan.";
    // Pastikan footer di-load sebelum exit
    include 'admin_footer.php';
    exit;
}

// 3. Ambil Item Produk dalam Pesanan tersebut
$sql_items = "SELECT products.name, products.image_url, order_items.quantity, order_items.price_per_item 
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              WHERE order_items.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<h1 class="page-title">Detail Pesanan #<?php echo $order['id']; ?></h1>

<div style="display: flex; gap: 30px;">

    <div style="flex: 2;">
        <div class="admin-table-container" style="margin-bottom: 30px;">
            <h3 style="margin-top: 0;">Item yang Dipesan</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>Rp <?php echo number_format($item['price_per_item']); ?></td>
                        <td>Rp <?php echo number_format($item['price_per_item'] * $item['quantity']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-form-container">
            <h3>Informasi Pelanggan</h3>
            <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
            <p><strong>No. Telepon:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>
            <p><strong>Alamat:</strong><br><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
        </div>
    </div>
    
    <div style="flex: 1;">
        <div class="admin-form-container">
            <h3>Update Pesanan</h3>
            <p><strong>Total Belanja:</strong><br><span style="font-size: 1.5rem; font-weight: bold; color: #1D3557;">Rp <?php echo number_format($order['total_amount']); ?></span></p>
            <p style="margin-top: 20px;"><strong>Status Saat Ini:</strong><br>
                <span style="font-weight: bold; font-size: 1.2rem; color: #1D3557;"><?php echo htmlspecialchars($order['status']); ?></span>
            </p>
            
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ddd;">
            
            <form action="../actions/update_order_status.php" method="POST">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                
                <label for="status">Ubah Status:</label>
                <select name="status" id="status" style="width: 100%; padding: 10px; border-radius: 8px; border: 2px solid #ddd; margin-bottom: 15px;">
                    <option value="Pending" <?php if($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Processing" <?php if($order['status'] == 'Processing') echo 'selected'; ?>>Processing (Diproses)</option>
                    <option value="Shipped" <?php if($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped (Dikirim)</option>
                    <option value="Completed" <?php if($order['status'] == 'Completed') echo 'selected'; ?>>Completed (Selesai)</option>
                    <option value="Cancelled" <?php if($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled (Dibatalkan)</option>
                </select>
                
                <label for="shipping_code">Nomor Resi Pengiriman:</label>
                <input type="text" name="shipping_code" id="shipping_code" value="<?php echo htmlspecialchars($order['shipping_code'] ?? ''); ?>">
                
                <button type="submit" class="btn-primary" style="width: 100%;">Update Status</button>
            </form>
        </div>
    </div>
</div>

<?php
$stmt_order->close();
$stmt_items->close();
$conn->close();
include 'admin_footer.php'; 
?>