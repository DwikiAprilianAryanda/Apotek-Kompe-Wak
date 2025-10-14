<?php include 'includes/header.php'; ?>
<?php include 'includes/db_connect.php'; // Panggil file koneksi ?>

<h1 class="page-title">Katalog Produk Kami</h1>
<p>Temukan obat dan vitamin yang Anda butuhkan.</p>

<div class="product-grid">
    <?php
        // 1. Buat query untuk mengambil semua data produk
        $sql = "SELECT id, name, price, image_url FROM products WHERE stock_quantity > 0";
        $result = $conn->query($sql);

        // 2. Periksa apakah ada produk yang ditemukan
        if ($result->num_rows > 0) {
            // 3. Looping untuk menampilkan setiap produk
            while($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                
                // Tampilkan gambar jika ada, jika tidak, tampilkan placeholder
                if (!empty($row['image_url'])) {
                    echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                } else {
                    echo "<img src='assets/images/placeholder.jpg' alt='Gambar tidak tersedia'>";
                }

                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p>Rp " . number_format($row['price']) . "</p>";
                echo "<button data-product-id='" . $row['id'] . "'>Tambah ke Keranjang</button>";
                echo "</div>";
            }
        } else {
            echo "<p>Tidak ada produk yang tersedia saat ini.</p>";
        }

        // Tutup koneksi
        $conn->close();
    ?>
</div>

<?php include 'includes/footer.php'; ?>