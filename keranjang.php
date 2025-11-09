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
                echo '<table class="cart-table">'; // Gunakan class untuk styling
                echo '<thead><tr>';
                echo '<th>Gambar</th>';
                echo '<th>Produk</th>';
                echo '<th>Harga Satuan</th>';
                echo '<th>Kuantitas</th>';
                echo '<th>Total Harga</th>';
                echo '<th>Aksi</th>';
                echo '</tr></thead><tbody>';
                
                $total_belanja_keseluruhan = 0;
                
                while ($row = $result->fetch_assoc()) {
                    $product_id = $row['id'];
                    $quantity = $_SESSION['cart'][$product_id];
                    $total_harga_produk = $row['price'] * $quantity;
                    $total_belanja_keseluruhan += $total_harga_produk;

                    echo '<tr>';
                    echo '<td><img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="cart-item-image"></td>';
                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td>Rp ' . number_format($row['price']) . '</td>';
                    echo '<td>' . $quantity . '</td>';
                    echo '<td>Rp ' . number_format($total_harga_produk) . '</td>';
                    echo '<td>';
                    echo '<form action="actions/hapus_keranjang.php" method="POST" style="margin:0;">';
                    echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                    echo '<button type="submit" class="btn btn-secondary cart-delete-btn">Hapus</button>'; // Gunakan class CSS
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table></div>';

                echo '<div class="cart-summary">'; // Container untuk total dan checkout
                    echo '<h3 style="color: #1e40af; font-size: 1.8rem; margin-bottom: 30px;">Total Belanja: <span style="color: #2563eb;">Rp ' . number_format($total_belanja_keseluruhan) . '</span></h3>';
                    echo '<a href="actions/place_order.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 30px;">Proses ke Checkout →</a>';
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

<style>
/* CSS untuk halaman keranjang */

/* Gaya untuk tabel keranjang */
.cart-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.cart-table th,
.cart-table td {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    vertical-align: middle;
}

.cart-table th {
    background: linear-gradient(135deg, #1b3270, #457B9D); /* Warna header sesuai palet situs */
    color: white;
    font-weight: 600;
    text-align: center; /* Rata tengah header */
}

.cart-table tr:hover {
    background-color: #f8f9fa;
}

/* Gaya untuk gambar produk */
.cart-item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}

/* Gaya untuk tombol hapus */
.cart-delete-btn {
    padding: 8px 16px;
    font-size: 0.9rem;
}

/* Gaya untuk ringkasan keranjang */
.cart-summary {
    background: white; /* Background container putih */
    padding: 30px;
    margin-top: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: right;
}

.cart-summary h3 {
    margin: 0 0 20px 0;
    font-size: 1.8rem;
    color: #1e40af; /* Warna teks */
}

.cart-summary .btn-primary {
    font-size: 1.1rem;
    padding: 15px 30px;
    /* Ganti warna dasar tombol agar kontras */
    background: linear-gradient(135deg, #1b3270, #457B9D); /* Warna dasar tombol, sama seperti hover sebelumnya */
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease; /* Transisi untuk efek hover */
    text-decoration: none; /* Karena ini link */
    display: inline-block; /* Agar padding dan border-radius bekerja */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Bayangan halus */
}

.cart-summary .btn-primary:hover {
    background: linear-gradient(135deg, #1e40af, #3b82f6); /* Warna hover yang lebih cerah */
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Bayangan lebih tebal saat hover */
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-table {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .cart-table th,
    .cart-table td {
        padding: 10px;
        font-size: 0.9rem;
    }

    .cart-item-image {
        width: 50px;
        height: 50px;
    }

    .cart-delete-btn {
        padding: 6px 12px;
        font-size: 0.8rem;
    }

    .cart-summary {
        padding: 20px;
    }

    .cart-summary h3 {
        font-size: 1.5rem;
    }

    .cart-summary .btn-primary {
        font-size: 1rem;
        padding: 12px 24px;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .cart-table {
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .cart-table th,
    .cart-table td {
        padding: 8px;
        font-size: 0.8rem;
    }

    .cart-item-image {
        width: 40px;
        height: 40px;
    }

    .cart-delete-btn {
        padding: 5px 10px;
        font-size: 0.75rem;
    }

    .cart-summary {
        padding: 15px;
    }

    .cart-summary h3 {
        font-size: 1.3rem;
    }

    .cart-summary .btn-primary {
        font-size: 0.9rem;
        padding: 10px 20px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>