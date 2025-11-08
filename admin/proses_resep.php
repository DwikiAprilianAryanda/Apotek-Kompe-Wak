<?php
include 'admin_header.php';

// Pastikan ID Resep ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID Resep tidak valid.");
}

$resep_id = $_GET['id'];

// Ambil data resep dan data pelanggan
$stmt = $conn->prepare("SELECT p.id, p.user_id, p.file_name, p.catatan, u.name AS customer_name
                        FROM prescriptions p
                        JOIN users u ON p.user_id = u.id
                        WHERE p.id = ? AND p.status = 'Pending'");
$stmt->bind_param("i", $resep_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Resep tidak ditemukan atau sudah diproses.");
}

$resep = $result->fetch_assoc();
$stmt->close();

// Ambil semua produk untuk dropdown
$product_query = $conn->query("SELECT id, name, price, stock_quantity FROM products WHERE stock_quantity > 0 ORDER BY name ASC");
$products = [];
while ($p = $product_query->fetch_assoc()) {
    $products[] = $p;
}
?>

<h1 class="page-title">Proses Resep #<?php echo $resep['id']; ?></h1>
<h2 class="sub-title">Pelanggan: <?php echo htmlspecialchars($resep['customer_name']); ?></h2>

<div class="resep-process-container" style="display: flex; gap: 20px;">
    
    <div class="resep-display" style="flex: 1;">
        <h3>Gambar Resep</h3>
        <?php 
        $file_path = '../uploads/resep/' . $resep['file_name'];
        $file_extension = pathinfo($resep['file_name'], PATHINFO_EXTENSION);
        $is_image = in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif']);
        
        if ($is_image):
        ?>
            <img src="<?php echo htmlspecialchars($file_path); ?>" alt="Gambar Resep" style="max-width: 100%; height: auto; border: 1px solid #ccc;">
        <?php else: ?>
            <p>File resep bukan gambar. <a href="<?php echo htmlspecialchars($file_path); ?>" target="_blank">Klik di sini untuk melihat/mengunduh file (PDF/lainnya)</a>.</p>
        <?php endif; ?>
        
        <?php if (!empty($resep['catatan'])): ?>
        <p><strong>Catatan Pelanggan:</strong> <?php echo nl2br(htmlspecialchars($resep['catatan'])); ?></p>
        <?php endif; ?>
        
        <a href="verifikasi_resep.php" class="btn-secondary" style="margin-top: 20px;">Kembali ke Daftar Resep</a>
    </div>
    
    <div class="resep-form" style="flex: 2;">
        <div class="admin-form-container">
            <h3>Tambahkan Produk Resep</h3>
            <p>Pilih obat dan kuantitasnya, lalu verifikasi pesanan.</p>
            
            <form action="../actions/proses_resep_action.php" method="POST">
                
                <input type="hidden" name="user_id" value="<?php echo $resep['user_id']; ?>">
                <input type="hidden" name="resep_id" value="<?php echo $resep['id']; ?>">
                <input type="hidden" name="action" value="verify">

                <div id="item-list">
                    <div class="item-resep" style="border-bottom: 1px dashed #ddd; padding-bottom: 15px; margin-bottom: 15px;">
                        <label>Pilih Obat:</label>
                        <select name="products[1][id]" id="product_id_1" required style="width: 50%; padding: 10px; margin-right: 10px; border-radius: 8px;">
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?php echo $p['id']; ?>" data-stock="<?php echo $p['stock_quantity']; ?>" data-price="<?php echo $p['price']; ?>">
                                    <?php echo htmlspecialchars($p['name']) . ' (Stok: ' . $p['stock_quantity'] . ') - Rp ' . number_format($p['price']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label>Qty:</label>
                        <input type="number" name="products[1][qty]" id="qty_1" value="1" min="1" required style="width: 15%; padding: 10px; border-radius: 8px;">
                    </div>
                </div>

                <button type="button" id="add-item-btn" class="btn-secondary" style="margin-top: 10px; padding: 8px 15px;">+ Tambah Item Obat Lain</button>
                
                <hr style="margin: 25px 0;">
                <button type="submit" class="btn-primary" style="width: 100%; padding: 15px;">
                    VERIFIKASI & BUAT PESANAN
                </button>
            </form>
            
            <form action="../actions/proses_resep_action.php" method="POST" style="margin-top: 15px;">
                <input type="hidden" name="resep_id" value="<?php echo $resep['id']; ?>">
                <input type="hidden" name="action" value="reject">
                <button type="submit" name="action" value="reject" class="btn-delete" onclick="return confirm('Yakin tolak resep ini?')" style="width: 100%; padding: 10px;">
                    Tolak Resep
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// ... (Kode JavaScript tetap sama)
let itemCounter = 1;
const productOptions = <?php echo json_encode($products); ?>;

document.getElementById('add-item-btn').addEventListener('click', function() {
    itemCounter++;
    const itemList = document.getElementById('item-list');
    
    const newItemDiv = document.createElement('div');
    newItemDiv.className = 'item-resep';
    newItemDiv.style.cssText = "border-bottom: 1px dashed #ddd; padding-bottom: 15px; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;";

    // Membuat HTML Select Options
    let selectHtml = `<select name="products[${itemCounter}][id]" id="product_id_${itemCounter}" required style="width: 50%; padding: 10px; margin-right: 10px; border-radius: 8px;">`;
    selectHtml += '<option value="">-- Pilih Produk --</option>';
    productOptions.forEach(p => {
        selectHtml += `<option value="${p.id}" data-stock="${p.stock_quantity}" data-price="${p.price}">`;
        selectHtml += `${p.name} (Stok: ${p.stock_quantity}) - Rp ${Number(p.price).toLocaleString('id-ID')}`;
        selectHtml += '</option>';
    });
    selectHtml += '</select>';

    // Membuat HTML Input Qty
    let inputHtml = `<input type="number" name="products[${itemCounter}][qty]" id="qty_${itemCounter}" value="1" min="1" required style="width: 15%; padding: 10px; border-radius: 8px;">`;
    
    // Membuat Tombol Hapus
    let deleteBtn = `<button type="button" class="btn-delete" onclick="this.closest('.item-resep').remove()" style="padding: 8px 15px;">Hapus Item</button>`;

    newItemDiv.innerHTML = `
        <label>Pilih Obat:</label>
        ${selectHtml}
        <label>Qty:</label>
        ${inputHtml}
        ${deleteBtn}
    `;

    itemList.appendChild(newItemDiv);
});
</script>

<?php
// Tutup koneksi
$conn->close();
include 'admin_footer.php';
?>