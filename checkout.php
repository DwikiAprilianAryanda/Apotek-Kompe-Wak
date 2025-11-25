<?php
session_start();
include 'includes/header.php';
include 'includes/db_connect.php';

// Keamanan: Cek login dan keranjang
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=loginrequired");
    exit;
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: produk.php?error=emptycart");
    exit;
}

// Hitung Total Belanja
$total_belanja = 0;
$total_items = 0;
if (isset($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', array_map('intval', $product_ids));
    
    $sql = "SELECT id, price FROM products WHERE id IN ($ids_string)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['cart'][$row['id']];
        $total_belanja += $row['price'] * $qty;
        $total_items += $qty;
    }
}
?>

<div class="section-wrapper bg-light">
    <div class="container">
        
        <div class="checkout-wrapper">
            <div class="checkout-header">
                <h2>Checkout Pembayaran</h2>
                <p>Selesaikan pesanan Anda dengan memilih metode pembayaran di bawah ini.</p>
            </div>

            <div class="checkout-grid">
                
                <div class="checkout-left">
                    <h3 class="checkout-subtitle">Pilih Metode Pembayaran</h3>
                    
                    <form action="actions/place_order.php" method="POST" id="checkoutForm">
                        
                        <div class="payment-options-list">
                            
                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="pay_cod" value="COD" checked>
                                <label for="pay_cod" class="payment-card">
                                    <div class="pay-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18v-8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8z"></path><path d="M3 10 12 3l9 7"></path><path d="M9 21V9"></path><path d="M15 21V9"></path></svg>
                                    </div>
                                    <div class="pay-info">
                                        <h4>Ambil & Bayar di Apotek (COD)</h4>
                                        <p>Siapkan uang tunai saat Anda tiba di apotek kami.</p>
                                    </div>
                                    <div class="pay-check">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </label>
                            </div>

                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="pay_qris" value="QRIS">
                                <label for="pay_qris" class="payment-card">
                                    <div class="pay-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                    </div>
                                    <div class="pay-info">
                                        <h4>QRIS (Scan Code)</h4>
                                        <p>Bayar instan via GoPay, OVO, Dana, ShopeePay, dll.</p>
                                    </div>
                                    <div class="pay-check">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </label>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="checkout-right">
                    <div class="summary-card">
                        <h3 class="summary-title">Ringkasan Pesanan</h3>
                        
                        <div class="summary-row">
                            <span>Total Item</span>
                            <span><?php echo $total_items; ?> Barang</span>
                        </div>
                        
                        <div class="summary-row total-row">
                            <span>Total Tagihan</span>
                            <span class="total-price">Rp <?php echo number_format($total_belanja); ?></span>
                        </div>

                        <button type="submit" form="checkoutForm" class="btn-finish-checkout">
                            Selesaikan Pesanan
                        </button>
                        
                        <p style="text-align:center; font-size:0.8rem; color:#999; margin-top:15px;">
                            Pastikan pesanan Anda sudah benar sebelum melanjutkan.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>