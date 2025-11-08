<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

$sql_order = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("ii", $order_id, $user_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    echo "<div class='section'><p style='text-align:center; color:#dc2626;'>Pesanan tidak ditemukan atau Anda tidak memiliki akses.</p></div>";
    exit;
}

$sql_items = "SELECT products.name, order_items.quantity, order_items.price_per_item 
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              WHERE order_items.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">
<div class="section">
    <h2>Detail Pesanan #<?php echo $order['id']; ?></h2>
    
    <div class="card" style="max-width: 900px; margin: 30px auto;">
        <div style="padding: 30px;">
            <h3 style="color: #1e40af; margin-bottom: 20px;">Informasi Pesanan</h3>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                <p style="margin-bottom: 10px;"><strong>Status:</strong> <span style="color: #1e40af; font-weight: bold;"><?php echo htmlspecialchars($order['status']); ?></span></p>
                <p style="margin-bottom: 10px;"><strong>Total Belanja:</strong> Rp <?php echo number_format($order['total_amount']); ?></p>
                <p style="margin-bottom: 0;"><strong>Tanggal Pesan:</strong> <?php echo $order['order_date']; ?></p>
            </div>
            
            <h3 style="color: #1e40af; margin-bottom: 20px;">Item yang Dipesan</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #1e40af, #3b82f6); color: white;">
                            <th style="padding: 15px; text-align: left; border-radius: 8px 0 0 0;">Produk</th>
                            <th style="padding: 15px; text-align: center;">Kuantitas</th>
                            <th style="padding: 15px; text-align: right;">Harga Satuan</th>
                            <th style="padding: 15px; text-align: right; border-radius: 0 8px 0 0;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $items->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px;"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td style="padding: 15px; text-align: center;"><?php echo $item['quantity']; ?></td>
                            <td style="padding: 15px; text-align: right;">Rp <?php echo number_format($item['price_per_item']); ?></td>
                            <td style="padding: 15px; text-align: right; font-weight: bold;">Rp <?php echo number_format($item['price_per_item'] * $item['quantity']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="riwayat_pesanan.php" class="btn btn-secondary">← Kembali ke Riwayat Pesanan</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>