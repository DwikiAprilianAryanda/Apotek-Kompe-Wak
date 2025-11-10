<?php include 'includes/header.php'; ?>

<div class="section">
    <div class="success-card card">
        <div class="success-icon">✓</div>
        <h2 class="success-title">Terima Kasih!</h2>
        <p class="success-message">Pesanan Anda telah berhasil kami terima.</p>

        <?php
        if (isset($_GET['order_id'])) {
            $order_id = htmlspecialchars($_GET['order_id']);
            echo "<div class='order-id-container'>";
            echo "<p class='order-id-label'>Nomor pesanan Anda:</p>";
            echo "<p class='order-id-number'>#" . $order_id . "</p>";
            echo "</div>";
        }
        ?>

        <div class="payment-instructions">
            <?php if (isset($_GET['method']) && $_GET['method'] == 'COD'): ?>
                <h3 class="payment-instructions-title">Instruksi Bayar di Tempat (COD)</h3>
                <p class="success-note">Pesanan Anda akan segera kami siapkan. Mohon siapkan uang tunai pas sejumlah <strong>Rp <?php echo number_format($total_belanja_keseluruhan ?? 0); ?></strong> untuk dibayarkan kepada kurir saat pesanan tiba.</p>
            
            <?php elseif (isset($_GET['method']) && $_GET['method'] == 'QRIS'): ?>
                <h3 class="payment-instructions-title">Instruksi Pembayaran QRIS</h3>
                <p class="success-note">Silakan lakukan pembayaran dengan memindai kode QRIS di bawah ini. Pesanan akan kami proses setelah pembayaran terkonfirmasi.</p>
                
                <img src="assets/images/qris_placeholder.png" alt="Scan QRIS untuk Pembayaran" style="width: 250px; height: 250px; margin-top: 15px; border: 1px solid #ddd;">
                <p class="success-note" style="font-weight: bold; color: #1D3557;">Total Pembayaran: Rp <?php echo number_format($total_belanja_keseluruhan ?? 0); ?></p>
            
            <?php else: ?>
                <p class="success-note">Pesanan Anda akan segera kami proses.</p>
            <?php endif; ?>
        </div>
        <a href="produk.php" class="btn btn-primary">← Kembali Berbelanja</a>
    </div>
</div>

<style>
.section {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
}
.success-card {
    text-align: center;
    max-width: 500px;
    margin: 20px auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
    padding: 30px;
}
.success-icon {
    font-size: 2.5rem; color: #10b981; margin: 0;
}
.success-title {
    color: #1e40af; margin: 0; font-size: 1.5rem;
}
.success-message, .success-note {
    margin: 0; font-size: 1rem;
}
.success-note {
    color: #666;
}
.order-id-container {
    background: #f0f9ff;
    padding: 10px 15px;
    border-radius: 8px;
    margin: 10px 0;
    border: 2px solid #3b82f6;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1px;
}
.order-id-label {
    margin: 0; font-size: 0.9rem; font-weight: 600; color: #333;
}
.order-id-number {
    margin: 0; font-size: 1.1rem; font-weight: bold; color: #1e40af;
}
.btn-primary {
    margin-top: 10px;
    background: linear-gradient(135deg, #1b3270, #457B9D);
    color: white; border: none; border-radius: 8px; font-weight: 600;
    cursor: pointer; transition: all 0.3s ease; text-decoration: none;
    display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 10px 20px; /* Pastikan padding ada */
}
.btn-primary:hover {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* === STYLE BARU UNTUK INSTRUKSI PEMBAYARAN === */
.payment-instructions {
    width: 100%;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.payment-instructions-title {
    color: #1D3557;
    margin-bottom: 10px;
}
/* === AKHIR STYLE BARU === */
</style>

<?php include 'includes/footer.php'; ?>