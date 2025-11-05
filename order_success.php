<?php include 'includes/header.php'; ?>

<h1 class="page-title">Terima Kasih!</h1>
<p>Pesanan Anda telah berhasil kami terima.</p>

<?php
if (isset($_GET['order_id'])) {
    $order_id = htmlspecialchars($_GET['order_id']);
    echo "<p>Nomor pesanan Anda adalah: <strong>" . $order_id . "</strong></p>";
}
?>
<p>Silakan lakukan pembayaran agar pesanan Anda dapat segera kami proses.</p>
<br>
<a href="produk.php" class="button-checkout">Kembali Berbelanja</a>

<?php include 'includes/footer.php'; ?>