<?php
session_start();
include 'includes/header.php';
include 'includes/db_connect.php';

// Keamanan: Pastikan user login dan keranjang tidak kosong
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php?error=loginrequired");
    exit;
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: /produk.php?error=emptycart");
    exit;
}

// Ambil data keranjang untuk ditampilkan
$total_belanja_keseluruhan = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', array_map('intval', $product_ids));
    
    $sql = "SELECT id, name, price FROM products WHERE id IN ($ids_string)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $quantity = $_SESSION['cart'][$row['id']];
            $total_belanja_keseluruhan += $row['price'] * $quantity;
        }
    }
}
$conn->close();
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="section">
    <h2>Checkout Pembayaran</h2>
    <p style="text-align: center; margin-bottom: 30px;">Tinjau pesanan Anda dan pilih metode pembayaran.</p>

    <div class="checkout-container">
        
        <div class="order-summary card">
            <h3 style="color: #1e40af; margin-bottom: 20px;">Ringkasan Pesanan</h3>
            <p>Total Belanja Anda:</p>
            <h2 class="total-price">Rp <?php echo number_format($total_belanja_keseluruhan); ?></h2>
            <p style="font-size: 0.9em; color: #666; margin-top: 15px;">Anda akan menyelesaikan pesanan dengan total ini.</p>
        </div>

        <div class="payment-options card">
            <h3 style="color: #1e40af; margin-bottom: 20px;">Pilih Metode Pembayaran</h3>
            
            <form action="actions/place_order.php" method="POST">
                
                <div class="payment-choice">
                    <input type="radio" id="payment_cod" name="payment_method" value="COD" required>
                    <label for="payment_cod">
                        <strong>Bayar di Tempat (COD)</strong>
                        <span>Siapkan uang tunai saat kurir tiba.</span>
                    </label>
                </div>
                
                <div class="payment-choice">
                    <input type="radio" id="payment_qris" name="payment_method" value="QRIS" required>
                    <label for="payment_qris">
                        <strong>QRIS</strong>
                        <span>Scan kode QR untuk pembayaran (GoPay, OVO, Dana, etc).</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px; font-size: 1.1rem; padding: 15px;">
                    Selesaikan Pesanan
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.checkout-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    max-width: 900px;
    margin: 0 auto;
}
.card { /* Menggunakan style .card yang sudah ada */
    padding: 30px;
}
.order-summary .total-price {
    font-size: 2.5rem;
    color: #1D3557;
    margin: 10px 0;
}
.payment-choice {
    border: 2px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.payment-choice:hover,
.payment-choice input:checked + label {
    border-color: #1D3557;
    background-color: #f0f9ff;
}
.payment-choice input[type="radio"] {
    margin-right: 15px;
    transform: scale(1.5);
}
.payment-choice label {
    cursor: pointer;
    width: 100%;
}
.payment-choice label strong {
    font-size: 1.1rem;
    color: #333;
}
.payment-choice label span {
    display: block;
    font-size: 0.9rem;
    color: #666;
    margin-top: 5px;
}
@media (max-width: 768px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>