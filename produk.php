<link rel="stylesheet" href="assets/css/style.css">
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'includes/header.php';
include 'includes/db_connect.php';
?>

<link rel="stylesheet" href="assets/css/style.css">
<div class="section">
    <h2>Katalog Produk Kami</h2>
    <p style="text-align: center; margin-bottom: 40px;">Temukan obat dan vitamin yang Anda butuhkan.</p>

    <div class="grid">
        <?php
            $sql = "SELECT id, name, price, image_url FROM products WHERE stock_quantity > 0";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    
                    if (!empty($row['image_url'])) {
                        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    } else {
                        echo "<img src='assets/images/placeholder.jpg' alt='Gambar tidak tersedia'>";
                    }

                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p style='font-size: 1.3rem; font-weight: bold; color: #1e40af;'>Rp " . number_format($row['price']) . "</p>";
                    echo "<button class='btn btn-primary' data-product-id='" . $row['id'] . "'>Tambah ke Keranjang</button>";
                    echo "</div>";
                }
            } else {
                echo "<div style='grid-column: 1/-1;'><div class='card'><p style='text-align:center; padding: 40px;'>Tidak ada produk yang tersedia saat ini.</p></div></div>";
            }

            $conn->close();
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
