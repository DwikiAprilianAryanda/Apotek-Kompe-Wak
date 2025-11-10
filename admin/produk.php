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

// Ambil semua data produk dari database
$sql = "SELECT id, name, price, stock_quantity, image_url FROM products ORDER BY name ASC";
$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    die("Error Query SQL: " . htmlspecialchars($conn->error));
}
?>

<h1 class="page-title">Manajemen Produk</h1>
<p style="margin-bottom: 20px;">Kelola semua produk yang akan dijual di apotek Anda.</p>

<a href="tambah_produk.php" class="btn-primary">
    + Tambah Produk Baru
</a>

<?php echo $message; ?>

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
                    echo '<img src="/assets/images/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="product-thumbnail">';
                    echo "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>Rp " . number_format($row['price']) . "</td>";
                    
                    $stock_style = ($row['stock_quantity'] == 0) ? 'color: #dc3545; font-weight: bold;' : '';
                    echo "<td style='" . $stock_style . "'>" . $row['stock_quantity'] . "</td>";
                    
                    echo "<td>";
                    // Link Edit
                    echo '<a href="edit_produk.php?id=' . $row['id'] . '" class="btn-edit">Edit</a>';
                    
                    // --- PERUBAHAN DI SINI ---
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