<?php 
// 1. AMBIL KODE DARI PERBAIKAN BUG "Rp 0"
include 'includes/header.php'; 
include 'includes/db_connect.php'; 

$total_amount = 0; // Nilai default
$order_id_display = ''; // Nilai default

// Ambil data pesanan dari database
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $order_id_display = htmlspecialchars($order_id);

    // Siapkan query untuk mengambil total_amount
    $stmt = $conn->prepare("SELECT total_amount FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $total_amount = $order['total_amount']; // Simpan total harga
    }
    $stmt->close();
}
$conn->close();
?>

<div class="section">
    <div class="success-card card"> 
        <div class="success-icon">✓</div> 
        <h2 class="success-title">Terima Kasih!</h2> 
        <p class="success-message">Pesanan Anda telah berhasil kami terima.</p>

        <?php
        if (!empty($order_id_display)) {
            // Kontainer kotak biru
            echo "<div class='order-id-container'>";
            echo "<p class='order-id-label'>Nomor pesanan Anda:</p>";
            echo "<p class='order-id-number'>#" . $order_id_display . "</p>";
            echo "</div>";
        }
        ?>

        <div class="payment-instructions">
            <?php if (isset($_GET['method']) && $_GET['method'] == 'COD'): ?>
                <h3 class="payment-instructions-title">Instruksi Bayar di Tempat (COD)</h3>
                <p class="success-note">Mohon siapkan uang tunai pas sejumlah <strong>Rp <?php echo number_format($total_amount); ?></strong> untuk dibayarkan kepada kurir saat pesanan tiba.</p>
            
            <?php elseif (isset($_GET['method']) && $_GET['method'] == 'QRIS'): ?>
                <h3 class="payment-instructions-title">Instruksi Pembayaran QRIS</h3>
                <p class="success-note">Silakan lakukan pembayaran dengan memindai kode QRIS di bawah ini.</p>
                <img src="assets/images/qris_placeholder.png" alt="Scan QRIS untuk Pembayaran" style="width: 250px; height: 250px; margin-top: 15px; border: 1px solid #ddd;">
                <p class="success-note" style="font-weight: bold; color: #1D3557; margin-top: 5px;">Total Pembayaran: Rp <?php echo number_format($total_amount); ?></p>
            
            <?php else: ?>
                <p class="success-note">Pesanan Anda akan segera kami proses.</p>
            <?php endif; ?>
        </div>

        <a href="produk.php" class="btn btn-primary">← Kembali Berbelanja</a> 
    </div>
</div>

<style>
/* CSS dari file order_success.php Anda */
.section {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0; /* Pastikan padding section ada */
}

.success-card {
    text-align: center;
    max-width: 500px; 
    margin: 20px auto; 
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 5px; /* DIKURANGI: Jarak antar elemen */
    padding: 20px;
}

.success-icon {
    font-size: 2.5rem; color: #10b981; margin: 0;
}

.success-title {
    color: #1e40af; margin: 0; font-size: 1.5rem;
}

.success-message,
.success-note {
    margin: 0; font-size: 1rem;
}

.success-note {
    color: #666; 
}

/* === INI PERBAIKANNYA === */
.order-id-container {
    background: #f0f9ff;
    padding: 10px 20px; /* DIKURANGI */
    border-radius: 8px;
    margin: 10px 0; /* DIKURANGI */
    border: 2px solid #3b82f6;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1px;
    max-width: 280px; /* DITAMBAHKAN */
    width: 100%;
}
/* === AKHIR PERBAIKAN === */

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

/* Style responsif dari file Anda */
@media (max-width: 768px) {
    .section {
        padding: 10px 0; 
    }
    .success-card {
        min-height: auto; 
        max-width: 100%; 
        padding: 20px 15px;
        margin: 10px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>