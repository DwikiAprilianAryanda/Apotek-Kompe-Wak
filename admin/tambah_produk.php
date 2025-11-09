<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// Logika untuk menampilkan pesan error jika ada
$error_msg = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'uploadfail') {
        $error_msg = 'Gagal meng-upload gambar. Pastikan file adalah JPG/PNG dan ukurannya tidak terlalu besar.';
    } else if ($_GET['error'] == 'dbfail') {
        $error_msg = 'Gagal menyimpan data ke database.';
    } else {
        $error_msg = 'Terjadi kesalahan yang tidak diketahui.';
    }
}
?>

<h1 class="page-title">Tambah Produk Baru</h1>

<div class="admin-form-container">

    <?php if (!empty($error_msg)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <form action="../actions/tambah_produk_process.php" method="POST" enctype="multipart/form-data">
        
        <label for="name">Nama Produk:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="description">Deskripsi Produk:</label>
        <textarea id="description" name="description" rows="5"></textarea>
        
        <label for="price">Harga (Rp):</label>
        <input type="number" id="price" name="price" min="0" required>
        
        <label for="stock_quantity">Stok Awal:</label>
        <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="0" required>
        
        <label for="image">Gambar Produk:</label>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 100%; box-sizing: border-box;">
        
        <br><br>
        <button type="submit" class="btn-primary" style="width: 100%;">+ Tambah Produk</button>
    </form>
    
    <a href="produk.php" style="display: block; text-align: center; margin-top: 20px;">Batal</a>
</div>

<?php
include 'admin_footer.php';
?>