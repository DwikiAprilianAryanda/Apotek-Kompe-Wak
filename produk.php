<?php
// Tampilkan error (bagus untuk debugging)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sertakan file header dan koneksi database
include 'includes/header.php';
include 'includes/db_connect.php';

$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'cart_added') {
    $message = '<div class="alert-success">Produk berhasil ditambahkan ke keranjang!</div>';
}
if (isset($_GET['error']) && $_GET['error'] == 'cart_fail') {
    $message = '<div class="alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin: 20px 0; border: 1px solid #f5c6cb;">Gagal menambahkan produk.</div>';
}

?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="section">
    <h2>Katalog Produk Kami</h2>
    <p style="text-align: center; margin-bottom: 40px;">Temukan obat dan vitamin yang Anda butuhkan.</p>

    <div class="grid">
        <?php
            // Query Anda sudah bagus, kita tambahkan 'stock_quantity'
            $sql = "SELECT id, name, price, image_url, stock_quantity FROM products WHERE stock_quantity > 0";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    
                    // Path gambar (sudah benar dari DB Anda)
                    if (!empty($row['image_url'])) {
                        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    } else {
                        // Gambar placeholder jika tidak ada
                        echo "<img src='assets/images/placeholder.jpg' alt='Gambar tidak tersedia'>";
                    }

                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p style='font-size: 1.3rem; font-weight: bold; color: #1D3557; margin-bottom: 15px;'>Rp " . number_format($row['price']) . "</p>";

                    // --- PERUBAHAN UTAMA: FORM KERANJANG ---
                    // Mengganti <button> statis Anda dengan <form> yang fungsional
                    echo "<form action='actions/tambah_keranjang.php' method='POST' class='cart-form'>";
                        
                        // Kirim ID produk secara tersembunyi
                        echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                        
                        // Input untuk Kuantitas (Qty)
                        echo "<div class='quantity-selector'>";
                        echo "<label for='quantity_" . $row['id'] . "'>Qty:</label>";
                        // Batasi jumlah maks berdasarkan stok
                        echo "<input type='number' id='quantity_" . $row['id'] . "' name='quantity' value='1' min='1' max='" . $row['stock_quantity'] . "' style='width: 60px; padding: 5px; margin-right: 10px; border-radius: 8px; border: 1px solid #ddd;'>";
                        echo "</div>";

                        // Tombol Submit
                        echo "<button type='submit' class='btn btn-primary'>Tambah ke Keranjang</button>";
                    
                    echo "</form>";
                    // --- AKHIR FORM KERANJANG ---
                    
                    echo "</div>"; // Akhir .card
                }
            } else {
                echo "<div style='grid-column: 1/-1;'><div class='card'><p style='text-align:center; padding: 40px;'>Tidak ada produk yang tersedia saat ini.</p></div></div>";
            }

            $conn->close();
        ?>
    </div>
</div>

<style>
.cart-form {
    padding: 0 20px 20px 20px;
    margin-top: auto; /* Mendorong form ke bagian bawah card */
}
.quantity-selector {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}
.quantity-selector label {
    margin-right: 10px;
    font-weight: 600;
    color: #333;
}
.cart-form .btn-primary {
    width: 100%; /* Membuat tombol memenuhi lebar card */
}
</style>
    
<?php include 'includes/footer.php'; ?>