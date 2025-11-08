<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/style.css">
<div class="section" style="max-width: 700px;">
    <div class="card" style="text-align: center; padding: 50px 30px;">
        <div style="font-size: 4rem; color: #10b981; margin-bottom: 20px;">✓</div>
        <h2 style="color: #1e40af; margin-bottom: 20px;">Terima Kasih!</h2>
        <p style="font-size: 1.1rem; margin-bottom: 20px;">Pesanan Anda telah berhasil kami terima.</p>

        <?php
        if (isset($_GET['order_id'])) {
            $order_id = htmlspecialchars($_GET['order_id']);
            echo "<div style='background: #f0f9ff; padding: 20px; border-radius: 10px; margin: 20px 0; border: 2px solid #3b82f6;'>";
            echo "<p style='margin: 0; font-size: 1.1rem;'>Nomor pesanan Anda: <strong style='color: #1e40af; font-size: 1.3rem;'>#" . $order_id . "</strong></p>";
            echo "</div>";
        }
        ?>
        
        <p style="margin-bottom: 30px; color: #666;">Silakan lakukan pembayaran agar pesanan Anda dapat segera kami proses.</p>
        
        <a href="produk.php" class="btn btn-primary" style="font-size: 1.1rem;">← Kembali Berbelanja</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>