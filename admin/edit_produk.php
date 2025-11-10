<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// 1. Validasi ID Produk
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID Produk tidak valid. <a href='produk.php'>Kembali</a>");
}
$product_id = $_GET['id'];

// 2. Ambil data produk yang ada dari database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Produk tidak ditemukan. <a href='produk.php'>Kembali</a>");
}
// Ambil data produknya
$product = $result->fetch_assoc();
$stmt->close();

// Logika untuk menampilkan pesan error jika ada
$error_msg = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'uploadfail') {
        $error_msg = 'Gagal meng-upload gambar baru.';
    } else if ($_GET['error'] == 'dbfail') {
        $error_msg = 'Gagal menyimpan data ke database.';
    }
}
?>

<h1 class="page-title">Edit Produk: <?php echo htmlspecialchars($product['name']); ?></h1>

<div class="admin-form-container">

    <?php if (!empty($error_msg)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <form action="edit_produk_process.php" method="POST" enctype="multipart/form-data">
        
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($product['image_url']); ?>">

        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        
        <label for="description">Deskripsi Produk:</label>
        <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
        
        <label for="price">Harga (Rp):</label>
        <input type="number" id="price" name="price" min="0" value="<?php echo $product['price']; ?>" required>
        
        <label for="stock_quantity">Stok:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="<?php echo $product['stock_quantity']; ?>" required>
        
        <label>Gambar Saat Ini:</label>
        <img src="/assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="Gambar saat ini" class="product-thumbnail" style="margin-bottom: 15px;">
        
        <label for="image">Ganti Gambar (Opsional):</label>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 100%; box-sizing: border-box;">
        <p style="font-size: 0.9em; color: #666; margin-top: 5px;">*Kosongkan jika tidak ingin mengganti gambar.</p>
        
        <br><br>
        <button type="submit" class="btn-primary" style="width: 100%;">Simpan Perubahan</button>
    </form>
    
    <a href="produk.php" style="display: block; text-align: center; margin-top: 20px;">Batal</a>
</div>

<?php
$conn->close();
include 'admin_footer.php';
?>