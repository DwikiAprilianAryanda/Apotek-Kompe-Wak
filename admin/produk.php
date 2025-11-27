<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// --- Logika Pesan Notifikasi ---
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'added') {
        $message = '<div class="alert-success">Produk baru berhasil ditambahkan!</div>';
    } else if ($_GET['status'] == 'updated') {
        $message = '<div class="alert-success">Produk berhasil diperbarui!</div>';
    } else if ($_GET['status'] == 'deleted') {
        $message = '<div class="alert-success">Produk berhasil dihapus!</div>';
    }
}

// --- 1. Logika Filter Pencarian ---
$search_term = "";
$where_clause = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Ambil dan bersihkan istilah pencarian
    $search_term = $conn->real_escape_string($_GET['search']);
    $where_clause = " WHERE name LIKE '%$search_term%'";
}

// 2. Ambil semua data produk dari database (Gunakan where_clause)
$sql = "SELECT id, name, price, stock_quantity, image_url FROM products" . $where_clause . " ORDER BY name ASC";
$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    die("Error Query SQL: " . htmlspecialchars($conn->error));
}
?>

<h1 class="page-title">Manajemen Produk</h1>
<p style="margin-bottom: 20px;">Kelola semua produk yang akan dijual di apotek Anda.</p>

<?php echo $message; ?>

<div class="product-actions-header">
    <form action="produk.php" method="GET" class="admin-search-form">
        <input type="text" name="search" placeholder="Cari nama produk..." value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit">Cari</button>
    </form>

    <a href="tambah_produk.php" class="btn-primary">
        + Tambah Produk Baru
    </a>
</div>

<div class="admin-table-container">
    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>";
                    // Tampilkan gambar
                    echo '<img src="../assets/images/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="product-thumbnail">';
                    echo "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>Rp " . number_format($row['price']) . "</td>";
                    
                    $stock_style = ($row['stock_quantity'] == 0) ? 'color: #dc3545; font-weight: bold;' : '';
                    echo "<td style='" . $stock_style . "'>" . $row['stock_quantity'] . "</td>";
                    
                    echo "<td>";
                    // Link Edit
                    echo '<a href="edit_produk.php?id=' . $row['id'] . '" class="btn-edit">Edit</a>';
                    
                    // Link "Hapus" sekarang mengarah ke file di folder yang sama
                    echo ' <a href="hapus_produk_process.php?id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'Anda yakin ingin menghapus produk ini?\');">Hapus</a>';
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Belum ada produk. Silakan tambahkan produk baru.</td></tr>";
            }
            
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<?php
include 'admin_footer.php';
?>