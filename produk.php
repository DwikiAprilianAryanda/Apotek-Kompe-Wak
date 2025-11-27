<?php
include 'includes/header.php';
include 'includes/db_connect.php';

// --- 1. LOGIKA FILTER & PENCARIAN ---
$where_clause = " WHERE stock_quantity > 0"; // Default: hanya tampilkan stok > 0

// Cek Pencarian
$search_term = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $where_clause .= " AND name LIKE '%$search_term%'";
}

// Cek Kategori
$selected_cat = "Semua"; 
if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $cat_param = $conn->real_escape_string($_GET['kategori']);
    if($cat_param != 'Semua') {
        $where_clause .= " AND category = '$cat_param'";
        $selected_cat = $cat_param;
    }
}

// --- 2. LOGIKA PAGINATION (BARU) ---
$limit = 12; // Batas produk per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Hitung Total Produk (untuk menentukan jumlah halaman)
$sql_count = "SELECT COUNT(*) as total FROM products" . $where_clause;
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_products = $row_count['total'];
$total_pages = ceil($total_products / $limit);

// --- 3. DAFTAR KATEGORI ---
$categories = [
    ['name' => 'Semua', 'icon' => '💊'],
    ['name' => 'Kepala', 'icon' => '🤕'],
    ['name' => 'Jantung & Darah', 'icon' => '🩸'],
    ['name' => 'Pernapasan', 'icon' => '🫁'],
    ['name' => 'Pencernaan', 'icon' => '🤢'],
    ['name' => 'Kulit', 'icon' => '🧴'],
    ['name' => 'Vitamin', 'icon' => '🍊'],
    ['name' => 'Antibiotik', 'icon' => '🧬'],
    ['name' => 'Metabolisme', 'icon' => '⚡'],
    ['name' => 'Alergi', 'icon' => '🤧'],
    ['name' => 'Lainnya', 'icon' => '📦']
];

// Notifikasi Cart
$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'cart_added') {
    $message = '<div class="alert-success" style="margin-bottom: 20px;">Produk berhasil ditambahkan ke keranjang!</div>';
}
?>

<div class="section-wrapper" style="padding-top: 40px; background-color: #fcfcfc;">
    <div class="container">
        
        <?php echo $message; ?>

        <div class="catalog-wrapper">
            
            <aside class="catalog-sidebar">
                <?php foreach($categories as $cat): ?>
                    <?php 
                        $isActive = ($selected_cat == $cat['name']) ? 'active' : '';
                        $link = ($cat['name'] == 'Semua') ? 'produk.php' : 'produk.php?kategori=' . urlencode($cat['name']);
                    ?>
                    <a href="<?php echo $link; ?>" class="cat-item <?php echo $isActive; ?>">
                        <div class="cat-icon"><?php echo $cat['icon']; ?></div>
                        <span><?php echo $cat['name']; ?></span>
                    </a>
                <?php endforeach; ?>
            </aside>

            <main class="catalog-content">
                
                <form action="produk.php" method="GET" class="catalog-search">
                    <?php if($selected_cat != 'Semua'): ?>
                        <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($selected_cat); ?>">
                    <?php endif; ?>
                    <input type="text" name="search" placeholder="Cari obat..." value="<?php echo htmlspecialchars($search_term); ?>">
                </form>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="color: #1b3270; margin: 0;">Kategori: <?php echo htmlspecialchars($selected_cat); ?></h3>
                </div>

                <div class="grid-products">
                    <?php
                    // Query Utama dengan LIMIT dan OFFSET
                    $sql = "SELECT * FROM products" . $where_clause . " ORDER BY name ASC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <div class="product-card-simple">
                                <div class="prod-img-wrapper">
                                    <img src="/assets/images/<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </div>
                                <h3 class="prod-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p class="prod-stock">Stok: <?php echo $row['stock_quantity']; ?></p>
                                <div class="prod-price">Rp <?php echo number_format($row['price']); ?></div>
                                
                                <form action="actions/tambah_keranjang.php" method="POST" style="margin-top:auto;">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    
                                    <div class="quantity-controls">
                                        <button type="button" class="qty-btn minus-btn" data-id="<?php echo $row['id']; ?>">-</button>
                                        
                                        <input type="number" 
                                            name="quantity" 
                                            value="1" 
                                            min="1" 
                                            max="<?php echo $row['stock_quantity']; ?>" 
                                            id="qty-input-<?php echo $row['id']; ?>" 
                                            class="qty-input"
                                            inputmode="numeric">
                                            
                                        <button type="button" class="qty-btn plus-btn" data-id="<?php echo $row['id']; ?>">+</button>
                                    </div>

                                <button type="submit" class="btn-cart-sm">Tambah Ke Keranjang</button>
                            </form>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div style='grid-column: 1/-1; text-align:center; padding: 40px; background: white; border: 1px solid #eee;'>";
                        echo "<p style='color:#666;'>Produk tidak ditemukan.</p>";
                        echo "<a href='produk.php' class='btn-cart-sm' style='display:inline-block; width:auto; padding: 8px 20px;'>Lihat Semua Produk</a>";
                        echo "</div>";
                    }
                    ?>
                </div>

                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php
                    // Bangun URL dasar (pertahankan filter pencarian & kategori)
                    $params = $_GET;
                    unset($params['page']); // Hapus page lama
                    $query_str = http_build_query($params);
                    $base_link = "produk.php?" . ($query_str ? $query_str . "&" : "") . "page=";
                    ?>

                    <?php if ($page > 1): ?>
                        <a href="<?php echo $base_link . ($page - 1); ?>" class="page-link">← Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="<?php echo $base_link . $i; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="<?php echo $base_link . ($page + 1); ?>" class="page-link">Next →</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</div>

<?php 
$conn->close();
include 'includes/footer.php'; 
?>