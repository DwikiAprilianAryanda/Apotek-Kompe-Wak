<?php
// Tampilkan error (bagus untuk debugging)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sertakan file header dan koneksi database
include 'includes/header.php';
include 'includes/db_connect.php';

$where_clause = '';
$search_param = '';

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    // Ambil input search dan amankan dari SQL Injection
    $search_param = $conn->real_escape_string(trim($_GET['search']));

    // Tambahkan kondisi WHERE untuk mencari di kolom 'name' atau 'description'
    $where_clause = " AND (name LIKE '%$search_param%' OR description LIKE '%$search_param%')";

    // Mengisi kembali nilai input search di header (sudah ditangani oleh $searchTerm di header)
    // Untuk memastikan $searchTerm terisi, Anda bisa menambah baris ini:
    $searchTerm = htmlspecialchars($search_param);
}

$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'cart_added') {
    $message = '<div class="alert-success">Produk berhasil ditambahkan ke keranjang!</div>';
}
if (isset($_GET['error']) && $_GET['error'] == 'cart_fail') {
    // Ganti alert-danger yang tidak ada di style.css dengan alert-success berwarna merah
    $message = '<div class="alert-danger-custom">Gagal menambahkan produk.</div>';
}
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="section">
    <h2>Katalog Produk Kami</h2>
    <p style="text-align: center; margin-bottom: 40px;">Temukan obat dan vitamin yang Anda butuhkan.</p>

    <?php echo $message; ?>

    <div class="grid">
        <?php
            // Query Anda sudah bagus, kita tambahkan 'stock_quantity'
            $sql = "SELECT id, name, price, image_url, stock_quantity, description FROM products WHERE stock_quantity > 0" . $where_clause . " ORDER BY name ASC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>"; // Ganti class 'card' dengan 'product-card'

                    // Path gambar (sudah benar dari DB Anda)
                    if (!empty($row['image_url'])) {
                        echo "<img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    } else {
                        // Gambar placeholder jika tidak ada
                        echo "<img src='assets/images/placeholder.jpg' alt='Gambar tidak tersedia'>";
                    }

                    echo "<div class='product-info'>"; // Container untuk info produk (nama, harga)
                        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                        echo "<p class='product-price'>Rp " . number_format($row['price']) . "</p>";
                        // Tambahkan stok info
                        echo "<p class='product-stock'>Stok: " . $row['stock_quantity'] . "</p>";
                    echo "</div>"; // Akhir .product-info

                    // --- PERUBAHAN UTAMA: FORM KERANJANG ---
                    // Memindahkan form ke bawah info produk
                    echo "<form action='actions/tambah_keranjang.php' method='POST' class='product-cart-form'>";
                        // Kirim ID produk secara tersembunyi
                        echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";

                        // Input untuk Kuantitas (Qty)
                        echo "<div class='quantity-selector'>";
                        echo "<label for='quantity_" . $row['id'] . "'>Jumlah:</label>";
                        // Batasi jumlah maks berdasarkan stok
                        echo "<input type='number' id='quantity_" . $row['id'] . "' name='quantity' value='1' min='1' max='" . $row['stock_quantity'] . "' class='quantity-input'>";
                        echo "</div>";

                        // Tombol Submit
                        echo "<button type='submit' class='btn btn-primary btn-add-to-cart'>Tambah ke Keranjang</button>";

                    echo "</form>";
                    // --- AKHIR FORM KERANJANG ---

                    echo "</div>"; // Akhir .product-card
                }
            } else {
                // Pesan jika tidak ada produk yang ditemukan
                echo "<div class='no-products-message'>"; // Tambahkan class untuk styling
                echo "<div class='card'>"; // Gunakan card dari style.css untuk konsistensi
                echo "<p style='text-align:center; padding: 40px;'>Tidak ada produk yang tersedia saat ini.</p>";
                echo "</div>";
                echo "</div>";
            }

            $conn->close();
        ?>
    </div>
</div>

<style>
/* CSS untuk produk.php */

/* Gaya untuk kartu produk */
.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-info {
    padding: 15px; /* Kurangi padding atas dan bawah */
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-info h3 {
    color: #1D3557;
    font-size: 1.5rem;
    margin: 0 0 8px 0; /* Kurangi margin bawah */
}

.product-price {
    font-size: 1.3rem;
    font-weight: bold;
    color: #1D3557;
    margin: 0 0 4px 0; /* Kurangi margin bawah */
}

.product-stock {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0; /* Tanpa margin */
    font-weight: 500; /* Tambahkan ketebalan sedikit */
}

/* Gaya untuk form keranjang di bawah info */
.product-cart-form {
    padding: 0 15px 15px 15px; /* Kurangi padding */
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 8px; /* Kurangi gap antar elemen */
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 8px; /* Kurangi gap */
}

.quantity-selector label {
    font-weight: 600;
    color: #333;
    margin: 0;
    white-space: nowrap;
    font-size: 0.95rem; /* Sedikit kecilkan ukuran font */
}

.quantity-input {
    width: 60px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    text-align: center;
    font-family: inherit; /* Gunakan font keluarga yang sama */
}

/* Gaya untuk tombol "Tambah ke Keranjang" */
.btn-add-to-cart {
    width: 100%;
    padding: 10px 15px; /* Sesuaikan padding */
    background: linear-gradient(135deg, #1b3270, #457B9D); /* Warna dasar tombol, sama seperti hover sebelumnya */
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Tambahkan bayangan halus */
}

.btn-add-to-cart:hover {
    background: linear-gradient(135deg, #1e40af, #3b82f6); /* Warna hover yang lebih cerah */
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Bayangan lebih tebal saat hover */
}

/* Gaya untuk pesan tidak ada produk */
.no-products-message {
    grid-column: 1 / -1;
    text-align: center;
}

/* Gaya untuk alert error */
.alert-danger-custom {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 600;
    text-align: center;
    border: 1px solid #f5c6cb;
}
</style>

<?php include 'includes/footer.php'; ?>