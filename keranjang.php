<?php
session_start(); 
include 'includes/header.php';
include 'includes/db_connect.php'; 
?>
<link rel="stylesheet" href="assets/css/style.css">
<div class="section">
    <h2>Keranjang Belanja Anda</h2>

    <div class="cart-container">
        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            
            $product_ids = array_keys($_SESSION['cart']);
            $ids_string = implode(',', array_map('intval', $product_ids));
            
            $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($ids_string)";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<div style="overflow-x: auto;">';
                echo '<table style="width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">';
                echo '<thead><tr style="background: linear-gradient(135deg, #1e40af, #3b82f6); color: white;">';
                echo '<th style="padding: 15px;">Gambar</th>';
                echo '<th style="padding: 15px;">Produk</th>';
                echo '<th style="padding: 15px; text-align: right;">Harga Satuan</th>';
                echo '<th style="padding: 15px; text-align: center;">Kuantitas</th>';
                echo '<th style="padding: 15px; text-align: right;">Total Harga</th>';
                echo '<th style="padding: 15px; text-align: center;">Aksi</th>';
                echo '</tr></thead><tbody>';
                
                $total_belanja_keseluruhan = 0;
                
                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['id'];
                    $quantity = $_SESSION['cart'][$product_id];
                    $total_harga_produk = $row['price'] * $quantity;
                    $total_belanja_keseluruhan += $total_harga_produk;

                    echo '<tr style="border-bottom: 1px solid #e5e7eb;">';
                    echo '<td style="padding: 15px;"><img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"></td>';
                    echo '<td style="padding: 15px; font-weight: 600;">' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td style="padding: 15px; text-align: right;">Rp ' . number_format($row['price']) . '</td>';
                    echo '<td style="padding: 15px; text-align: center; font-weight: bold; color: #1e40af;">' . $quantity . '</td>';
                    echo '<td style="padding: 15px; text-align: right; font-weight: bold;">Rp ' . number_format($total_harga_produk) . '</td>';
                    echo '<td style="padding: 15px; text-align: center;">';
                    echo '<form action="actions/hapus_keranjang.php" method="POST" style="margin:0;">';
                    echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                    echo '<button type="submit" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.9rem;">Hapus</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table></div>';

                echo '<div style="background: white; padding: 30px; margin-top: 20px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: right;">';
                echo '<h3 style="color: #1e40af; font-size: 1.8rem;">Total Belanja: <span style="color: #2563eb;">Rp ' . number_format($total_belanja_keseluruhan) . '</span></h3>';
                echo '<a href="/actions/place_order.php" class="btn btn-primary" style="margin-top: 20px; font-size: 1.1rem; padding: 15px 30px;">Proses ke Checkout →</a>';
                echo '</div>';

            } else {
                echo "<div class='card'><p style='text-align:center; padding: 40px;'>Produk di keranjang tidak ditemukan di database.</p></div>";
            }

        } else {
            echo "<div class='card'><p style='text-align:center; padding: 40px; color: #666;'>Keranjang belanja Anda masih kosong. <a href='produk.php' style='color: #1e40af;'>Mulai berbelanja sekarang!</a></p></div>";
        }
        
        $conn->close();
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>