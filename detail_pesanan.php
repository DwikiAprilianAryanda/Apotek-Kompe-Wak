<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

// Ambil Data Order Utama
$sql_order = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("ii", $order_id, $user_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    echo "<div class='section-wrapper'><div class='container'><p style='text-align:center; color:#dc2626;'>Pesanan tidak ditemukan atau Anda tidak memiliki akses.</p></div></div>";
    exit;
}

// Ambil Item Barang
$sql_items = "SELECT products.name, products.image_url, order_items.quantity, order_items.price_per_item 
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              WHERE order_items.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();

include 'includes/header.php'; 
?>

<div class="section-wrapper bg-light">
    <div class="container">
        
        <div class="detail-wrapper">
            
            <div class="detail-header">
                <div class="header-left">
                    <h2 class="detail-title">Detail Pesanan <span class="highlight-id">#<?php echo $order['id']; ?></span></h2>
                    <p class="detail-date">Dipesan pada <?php echo date("d F Y, H:i", strtotime($order['order_date'])); ?></p>
                </div>
                <div class="header-right">
                    <a href="riwayat_pesanan.php" class="btn-back-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="detail-content-grid">
                
                <div class="detail-sidebar">
                    
                    <div class="detail-card status-card-wrapper">
                        <h4 class="card-label">Status Pesanan</h4>
                        <?php 
                            $status = $order['status'];
                            $statusClass = 'status-pending';
                            $icon = '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>'; // Jam
                            
                            if ($status == 'Completed') {
                                $statusClass = 'status-completed';
                                $icon = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>'; // Ceklis
                            } elseif ($status == 'Cancelled') {
                                $statusClass = 'status-cancelled';
                                $icon = '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>'; // X
                            } elseif ($status == 'Shipped') {
                                $statusClass = 'status-shipped';
                                $icon = '<rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle>'; // Truk
                            }
                        ?>
                        <div class="status-box <?php echo $statusClass; ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $icon; ?></svg>
                            <span><?php echo htmlspecialchars($status); ?></span>
                        </div>

                        <?php if (!empty($order['shipping_code'])): ?>
                            <div class="resi-box">
                                <span>No. Resi:</span>
                                <strong><?php echo htmlspecialchars($order['shipping_code']); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="detail-card payment-summary">
                        <h4 class="card-label">Rincian Pembayaran</h4>
                        <div class="summary-row">
                            <span>Metode</span>
                            <strong><?php echo htmlspecialchars($order['payment_method'] ?? 'Manual'); ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Status Bayar</span>
                            <span class="badge-payment"><?php echo htmlspecialchars($order['payment_status'] ?? 'Unpaid'); ?></span>
                        </div>
                        <div class="divider-dashed"></div>
                        <div class="summary-total">
                            <span>Total Belanja</span>
                            <span class="amount">Rp <?php echo number_format($order['total_amount']); ?></span>
                        </div>
                    </div>

                </div>

                <div class="detail-main">
                    <div class="detail-card">
                        <h4 class="card-label mb-20">Daftar Barang</h4>
                        
                        <div class="item-list-container">
                            <?php while ($item = $items->fetch_assoc()): ?>
                            <div class="order-item-row">
                                <div class="item-image">
                                    <img src="assets/images/<?php echo htmlspecialchars($item['image_url']); ?>" alt="Produk">
                                </div>
                                <div class="item-details">
                                    <h5 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <p class="item-price-single">
                                        <?php echo $item['quantity']; ?> barang x Rp <?php echo number_format($item['price_per_item']); ?>
                                    </p>
                                </div>
                                <div class="item-total-price">
                                    Rp <?php echo number_format($item['price_per_item'] * $item['quantity']); ?>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>

                    </div>
                    
                    <div class="help-section">
                        <p>Butuh bantuan pesanan ini?</p>
                        <a href="https://wa.me/6282188392309" target="_blank" class="link-help">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            Hubungi CS via WhatsApp
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>