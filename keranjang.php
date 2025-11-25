<?php
session_start();
include 'includes/header.php';
include 'includes/db_connect.php';

// Hitung total item unik
$total_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// --- LOGIKA SORTIR / FILTER ---
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : '';
$order_clause = "";

// Default: Sesuai urutan ID database (atau bisa diubah)
switch ($sort_option) {
    case 'price_asc':
        $order_clause = " ORDER BY price ASC"; // Harga Terendah
        break;
    case 'price_desc':
        $order_clause = " ORDER BY price DESC"; // Harga Tertinggi
        break;
    case 'latest':
        $order_clause = " ORDER BY id DESC"; // Waktu (Produk Terbaru)
        break;
    default:
        $order_clause = ""; // Default
        break;
}
?>

<form id="updateQtyForm" action="actions/update_quantity.php" method="POST" style="display:none;">
    <input type="hidden" name="product_id" id="hidden_product_id">
    <input type="hidden" name="quantity" id="hidden_quantity">
</form>

<div class="cart-wrapper">
    
    <div class="cart-page-title">
        <div class="title-left">
            <h2>Keranjang Belanja</h2>
            <span class="item-count-badge"><?php echo $total_items; ?> Item</span>
        </div>

        <div class="title-right">
            <form action="" method="GET" class="sort-form">
                <span class="sort-label">Urutkan:</span>
                <select name="sort" onchange="this.form.submit()" class="sort-select">
                    <option value="" <?php if($sort_option == '') echo 'selected'; ?>>Default</option>
                    <option value="latest" <?php if($sort_option == 'latest') echo 'selected'; ?>>Waktu (Terbaru)</option>
                    <option value="price_asc" <?php if($sort_option == 'price_asc') echo 'selected'; ?>>Harga Terendah</option>
                    <option value="price_desc" <?php if($sort_option == 'price_desc') echo 'selected'; ?>>Harga Tertinggi</option>
                </select>
            </form>
        </div>
    </div>

    <?php
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $product_ids = array_keys($_SESSION['cart']);
        $ids_string = implode(',', array_map('intval', $product_ids));
        
        // Update Query dengan ORDER BY
        $sql = "SELECT id, name, price, image_url, stock_quantity FROM products WHERE id IN ($ids_string)" . $order_clause;
        $result = $conn->query($sql);
        $total_belanja = 0;

        if ($result && $result->num_rows > 0) {
            ?>
            <div class="cart-labels">
                <div>Produk</div>
                <div style="text-align: center;">Kuantitas</div>
                <div>Harga Total</div>
                <div style="text-align: right;"></div>
            </div>

            <div class="cart-items-container">
            <?php
            while ($row = $result->fetch_assoc()) {
                $pid = $row['id'];
                $qty = $_SESSION['cart'][$pid];
                $subtotal = $row['price'] * $qty;
                $total_belanja += $subtotal;
                $max_stock = $row['stock_quantity'];
                ?>
                
                <div class="cart-row">
                    <div class="product-info">
                        <div class="p-img-box">
                            <img src="/assets/images/<?php echo htmlspecialchars($row['image_url']); ?>" alt="Produk">
                        </div>
                        <div class="p-details">
                            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                            <p>Harga Satuan: Rp <?php echo number_format($row['price']); ?></p>
                        </div>
                    </div>

                    <div class="qty-control">
                        <button type="button" class="qty-btn" onclick="updateQty(<?php echo $pid; ?>, <?php echo $qty; ?>, -1)">−</button>
                        <span class="qty-val"><?php echo $qty; ?></span>
                        <button type="button" class="qty-btn" onclick="updateQty(<?php echo $pid; ?>, <?php echo $qty; ?>, 1, <?php echo $max_stock; ?>)">+</button>
                    </div>

                    <div class="price-col">
                        Rp <?php echo number_format($subtotal); ?>
                    </div>

                    <div class="delete-col">
                        <form action="actions/hapus_keranjang.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                            <button type="submit" class="btn-trash" title="Hapus Produk">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <?php
            }
            ?>
            </div>

            <div class="cart-footer">
                <a href="produk.php" class="back-link">
                    ← Lanjut Belanja
                </a>

                <div class="checkout-area">
                    <div>
                        <span class="total-label">Total Belanja:</span>
                        <span class="total-amount">Rp <?php echo number_format($total_belanja); ?></span>
                    </div>
                    <form action="checkout.php" method="GET">
                        <button type="submit" class="btn-checkout">Bayar Sekarang</button>
                    </form>
                </div>
            </div>

            <?php
        } else {
             echo "<div class='empty-state'>Data produk tidak ditemukan.</div>";
        }
    } else {
        // TAMPILAN JIKA KERANJANG KOSONG
        ?>
        <div class="empty-state">
            <svg class="empty-icon" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
            <h3>Keranjang Belanja Kosong</h3>
            <p>Anda belum memilih obat apapun.</p>
            <a href="produk.php" class="btn-checkout">Mulai Belanja</a>
        </div>
        <?php
    }
    ?>
</div>

<?php 
$conn->close();
include 'includes/footer.php'; 
?>