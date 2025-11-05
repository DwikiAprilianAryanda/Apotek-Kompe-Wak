<?php
session_start(); 
include 'includes/header.php';
include 'includes/db_connect.php'; 
?>

<h1 class="page-title">Keranjang Belanja Anda</h1>

<div class="cart-container">
    <?php
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        
        // Ambil SEMUA product_id dari keys array session
        $product_ids = array_keys($_SESSION['cart']);
        
        $ids_string = implode(',', array_map('intval', $product_ids));
        
        $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($ids_string)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>Gambar</th><th>Produk</th><th>Harga Satuan</th><th>Kuantitas</th><th>Total Harga</th><th>Aksi</th></tr>';
            
            $total_belanja_keseluruhan = 0;
            
            while ($row = $result->fetch_assoc()) {
                // Ambil kuantitas dari session
                $product_id = $row['id'];
                $quantity = $_SESSION['cart'][$product_id];
                $total_harga_produk = $row['price'] * $quantity;
                
                // Tambahkan ke total belanja keseluruhan
                $total_belanja_keseluruhan += $total_harga_produk;

                echo '<tr>';
                echo '<td><img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" width="50"></td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>Rp ' . number_format($row['price']) . '</td>';
                echo '<td>' . $quantity . '</td>'; // Tampilkan kuantitas
                echo '<td>Rp ' . number_format($total_harga_produk) . '</td>'; // Tampilkan total harga per produk
                
                // Form untuk tombol Hapus
                echo '<td>';
                echo '<form action="actions/hapus_keranjang.php" method="POST" style="margin:0;">';
                echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                echo '<button type="submit" class="button-hapus">Hapus</button>';
                echo '</form>';
                echo '</td>';
                
                echo '</tr>';
            }
            echo '</table>';

            echo '<h3>Total Belanja Keseluruhan: Rp ' . number_format($total_belanja_keseluruhan) . '</h3>';
            echo '<div class_="checkout-button-container">';
            echo '<a href="/actions/place_order.php" class="button-checkout">Proses ke Checkout</a>';
            echo '</div>';

        } else {
            echo "<p>Produk di keranjang tidak ditemukan di database.</p>";
        }

    } else {
        echo "<p>Keranjang belanja Anda masih kosong.</p>";
    }
    
    $conn->close();
    ?>
</div>

<?php include 'includes/footer.php'; ?>