<?php 
include 'includes/header.php'; 
include 'includes/db_connect.php'; 

$total_amount = 0;
$order_id_display = '';
$payment_method = isset($_GET['method']) ? $_GET['method'] : '';

// Ambil data pesanan dari database
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $order_id_display = htmlspecialchars($order_id);

    $stmt = $conn->prepare("SELECT total_amount FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $total_amount = $order['total_amount']; 
    }
    $stmt->close();
}
$conn->close();
?>

<div class="section-wrapper bg-light">
    <div class="container">
        
        <div class="success-wrapper">
            <div class="success-header">
                <div class="success-icon-circle">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h2>Pesanan Berhasil Dibuat!</h2>
                <p>Terima kasih telah berbelanja di Apotek Arshaka.</p>
            </div>

            <div class="payment-detail-card">
                
                <div class="order-info-bar">
                    <span>Order ID: <strong>#<?php echo $order_id_display; ?></strong></span>
                    <span><?php echo date("d M Y, H:i"); ?></span>
                </div>

                <div class="payment-amount-box">
                    <p>Total Pembayaran</p>
                    <h1 class="amount">Rp <?php echo number_format($total_amount); ?></h1>
                </div>

                <?php if ($payment_method == 'QRIS'): ?>
                    <div class="qris-container">
                        <div class="qris-label">
                            <img src="assets/images/qris_placeholder.png" alt="Logo QRIS" style="height: 30px; width: auto; object-fit: contain; margin-bottom: 10px;">
                            <p>Scan kode di bawah ini</p>
                        </div>
                        
                        <div class="qr-frame">
                            <img src="assets/images/qris_placeholder.png" alt="QR Code Pembayaran" class="qr-image">
                        </div>

                        <div class="qris-instructions">
                            <p>1. Buka aplikasi e-wallet (Gopay, OVO, Dana, dll) atau Mobile Banking.</p>
                            <p>2. Scan kode QR di atas.</p>
                            <p>3. Periksa detail nama merchant <strong>Apotek Arshaka</strong>.</p>
                            <p>4. Selesaikan pembayaran.</p>
                        </div>
                    </div>

                <?php elseif ($payment_method == 'COD'): ?>
                    <div class="cod-container">
                        <div class="cod-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#1b3270" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18v-8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8z"></path><path d="M3 10 12 3l9 7"></path><path d="M9 21V9"></path><path d="M15 21V9"></path></svg>
                        </div>
                        <h3>Ambil di Apotek</h3>
                        <p>Silakan datang ke apotek kami dan tunjukkan Order ID <strong>#<?php echo $order_id_display; ?></strong> kepada kasir.</p>
                        <div class="alert-info-cod">
                            Siapkan uang pas sebesar <strong>Rp <?php echo number_format($total_amount); ?></strong> untuk mempercepat proses.
                        </div>
                    </div>
                
                <?php else: ?>
                    <p style="text-align:center; padding: 20px;">Metode pembayaran tidak dikenal.</p>
                <?php endif; ?>

                <div class="action-buttons">
                    <a href="riwayat_pesanan.php" class="btn-check-order">Cek Status Pesanan</a>
                    <a href="produk.php" class="btn-back-home">Kembali Berbelanja</a>
                </div>

            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>