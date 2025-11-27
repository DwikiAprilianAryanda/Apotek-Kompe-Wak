<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID Produk tidak valid. <a href='produk.php'>Kembali</a>");
}
$product_id = $_GET['id'];

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) { die("Produk tidak ditemukan."); }
$product = $result->fetch_assoc();
$stmt->close();

// Daftar Kategori
$categories = ['Kepala', 'Jantung & Darah', 'Pernapasan', 'Pencernaan', 'Kulit', 'Vitamin', 'Antibiotik', 'Metabolisme', 'Alergi', 'Lainnya'];

// Pesan Error
$error_msg = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'uploadfail') $error_msg = 'Gagal upload gambar.';
    elseif ($_GET['error'] == 'dbfail') $error_msg = 'Gagal update database.';
}
?>

<div class="page-header mb-30">
    <div class="header-left">
        <a href="produk.php" class="btn-back">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali ke Daftar
        </a>
        <div class="mt-3">
            <h1 class="page-title">Edit Produk</h1>
            <p class="page-subtitle">Perbarui informasi: <strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
        </div>
    </div>
</div>

<?php if (!empty($error_msg)): ?>
    <div class="alert-box error mb-20">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span><?php echo $error_msg; ?></span>
    </div>
<?php endif; ?>

<form action="../actions/edit_produk_process.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($product['image_url']); ?>">

    <div class="edit-grid">
        
        <div class="col-main">
            <div class="content-card">
                <div class="card-header border-bottom">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Informasi Produk
                    </h3>
                </div>
                <div class="card-body p-25">
                    
                    <div class="form-group mb-20">
                        <label class="form-label">Nama Produk</label>
                        <div class="input-with-icon">
                            <span class="icon-box"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg></span>
                            <input type="text" name="name" class="form-input with-prefix" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group mb-20">
                        <label class="form-label">Kategori</label>
                        <div class="input-with-icon">
                            <span class="icon-box"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></span>
                            <select name="category" class="form-input with-prefix" required>
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat; ?>" <?php if($product['category'] == $cat) echo 'selected'; ?>>
                                        <?php echo $cat; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-2 mb-20">
                        <div class="form-group">
                            <label class="form-label">Harga (Rp)</label>
                            <div class="input-with-icon">
                                <span class="icon-box">Rp</span>
                                <input type="number" name="price" class="form-input with-prefix" min="0" value="<?php echo $product['price']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok</label>
                            <div class="input-with-icon">
                                <span class="icon-box">Qty</span>
                                <input type="number" name="stock_quantity" class="form-input with-prefix" min="0" value="<?php echo $product['stock_quantity']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-input textarea-lg" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-sidebar">
            <div class="content-card mb-20">
                <div class="card-header border-bottom">
                    <h3>Foto Produk</h3>
                </div>
                <div class="card-body p-20 text-center">
                    <div class="image-preview-container mb-20">
                        <img src="../assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" id="imgPreview" class="preview-img" alt="Preview" onerror="this.src='../assets/images/default.jpg'">
                    </div>
                    
                    <div class="file-upload-wrapper">
                        <label for="image" class="btn-upload-outline">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Ganti Foto
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                        <p class="helper-text mt-10">Maks 2MB.</p>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-body p-20">
                    <button type="submit" class="btn-save-block">Simpan Perubahan</button>
                    <a href="produk.php" class="btn-cancel-block mt-10">Batal</a>
                </div>
            </div>
        </div>

    </div>
</form>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){ document.getElementById('imgPreview').src = reader.result; }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<style>
    /* Style sama seperti tambah_produk.php */
    :root { --blue-primary: #2563eb; --blue-light: #eff6ff; --text-dark: #0f172a; --text-gray: #64748b; --border-color: #e2e8f0; --green-success: #10b981; --red-bg: #fef2f2; --red-danger: #ef4444; }
    .edit-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
    @media (max-width: 900px) { .edit-grid { grid-template-columns: 1fr; } }
    .mb-20 { margin-bottom: 20px; } .mb-30 { margin-bottom: 30px; } .mt-3 { margin-top: 10px; } .mt-10 { margin-top: 10px; } .p-20 { padding: 20px; } .p-25 { padding: 25px; }
    .page-header { display: flex; flex-direction: column; align-items: flex-start; }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-gray); font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
    .btn-back:hover { color: var(--blue-primary); }
    .page-title { font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .page-subtitle { color: var(--text-gray); font-size: 0.95rem; }
    .content-card { background: white; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; }
    .card-header { padding: 15px 20px; background: #fff; border-bottom: 1px solid var(--border-color); }
    .card-header h3 { margin: 0; font-size: 1.1rem; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }
    .form-label { font-size: 0.9rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; display: block; }
    .input-with-icon { position: relative; display: flex; align-items: stretch; border: 2px solid var(--border-color); border-radius: 10px; overflow: hidden; background: #fff; transition: border-color 0.2s; }
    .input-with-icon:focus-within { border-color: var(--blue-primary); box-shadow: 0 0 0 4px var(--blue-light); }
    .icon-box { background: #f8fafc; padding: 0 15px; display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--text-gray); border-right: 1px solid var(--border-color); min-width: 50px; }
    .form-input { width: 100%; padding: 12px 15px; border: none; font-size: 1rem; font-weight: 500; color: var(--text-dark); outline: none; background: transparent; }
    .textarea-lg { border: 2px solid var(--border-color); border-radius: 10px; resize: vertical; line-height: 1.6; }
    .textarea-lg:focus { border-color: var(--blue-primary); box-shadow: 0 0 0 4px var(--blue-light); outline: none; }
    .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .image-preview-container { width: 100%; height: 200px; background: #f8fafc; border: 2px dashed var(--border-color); border-radius: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .preview-img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .file-upload-wrapper input[type="file"] { display: none; }
    .btn-upload-outline { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border: 2px solid var(--blue-primary); color: var(--blue-primary); border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
    .btn-upload-outline:hover { background: var(--blue-light); }
    .helper-text { font-size: 0.8rem; color: var(--text-gray); margin: 0; }
    .btn-save-block { width: 100%; padding: 14px; background: var(--green-success); color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2); }
    .btn-save-block:hover { background: #059669; transform: translateY(-2px); }
    .btn-cancel-block { display: block; width: 100%; padding: 12px; text-align: center; background: #fff; color: var(--text-gray); border: 1px solid var(--border-color); border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .btn-cancel-block:hover { background: #f1f5f9; color: var(--text-dark); }
    .alert-box { padding: 15px; border-radius: 8px; display: flex; align-items: center; gap: 10px; font-weight: 600; }
    .error { background: var(--red-bg); color: var(--red-danger); border: 1px solid #fee2e2; }
</style>

<?php include 'admin_footer.php'; ?>