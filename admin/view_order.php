<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// 1. Ambil Order ID dari URL
if (!isset($_GET['id'])) {
    header("Location: manajemen_pesanan.php");
    exit;
}
$order_id = $_GET['id'];

// 2. Ambil Info Pesanan Utama (dan data pelanggan)
$sql_order = "SELECT orders.*, users.name, users.email, users.address, users.phone_number 
              FROM orders 
              JOIN users ON orders.user_id = users.id 
              WHERE orders.id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    echo "<div class='alert-box error'>Pesanan tidak ditemukan.</div>";
    include 'admin_footer.php';
    exit;
}

// 3. Ambil Item Produk
$sql_items = "SELECT products.name, products.image_url, order_items.quantity, order_items.price_per_item 
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              WHERE order_items.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<div class="page-header">
    <div class="header-left">
        <a href="manajemen_pesanan.php" class="btn-back">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali
        </a>
        <div style="margin-top: 10px;">
            <h1 class="page-title">Detail Pesanan #<?php echo $order['id']; ?></h1>
            <p class="page-subtitle">
                Dipesan pada: <strong><?php echo date('d F Y, H:i', strtotime($order['order_date'])); ?></strong>
            </p>
        </div>
    </div>
    
    <?php 
        $status = $order['status'];
        $statusClass = 'status-default';
        $statusIcon = '';
        $statusLabel = '';

        if ($status == 'Completed') {
            $statusClass = 'status-success';
            $statusIcon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            $statusLabel = 'Pesanan Selesai';
        } elseif ($status == 'Pending') {
            $statusClass = 'status-warning';
            $statusIcon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
            $statusLabel = 'Menunggu Konfirmasi';
        } elseif ($status == 'Cancelled') {
            $statusClass = 'status-danger';
            $statusIcon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
            $statusLabel = 'Pesanan Dibatalkan';
        }
    ?>
    <div class="status-panel-row">
        <span class="status-desc-row"><?php echo $statusLabel; ?></span>
        <div class="status-pill-lg <?php echo $statusClass; ?>">
            <?php echo $statusIcon; ?>
            <span><?php echo htmlspecialchars($status); ?></span>
        </div>
    </div>
</div>

<div class="view-order-grid">
    
    <div class="col-main">
        <div class="content-card">
            <div class="card-header">
                <h3><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg> Item Pesanan</h3>
            </div>
            
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th width="50%">Produk</th>
                            <th width="15%" class="text-center">Qty</th>
                            <th width="20%" class="text-right">Harga Satuan</th>
                            <th width="15%" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $subtotal_all = 0;
                        while ($item = $items->fetch_assoc()): 
                            $total_item = $item['price_per_item'] * $item['quantity'];
                            $subtotal_all += $total_item;
                        ?>
                        <tr>
                            <td>
                                <div class="product-flex">
                                    <div class="img-wrapper">
                                        <img src="../assets/images/<?php echo htmlspecialchars($item['image_url']); ?>" alt="Produk" onerror="this.onerror=null;this.src='../assets/images/default.jpg';">
                                    </div>
                                    <span class="product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                </div>
                            </td>
                            <td class="text-center">x<?php echo $item['quantity']; ?></td>
                            <td class="text-right">Rp <?php echo number_format($item['price_per_item']); ?></td>
                            <td class="text-right font-medium text-nowrap">Rp <?php echo number_format($total_item); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr class="row-total">
                            <td colspan="3" class="text-right">Total Tagihan</td>
                            <td class="text-right total-amount">Rp <?php echo number_format($order['total_amount']); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-sidebar">
        
        <div class="content-card mb-20 compact-card">
            <div class="card-header bg-soft-blue compact-header">
                <h3><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Aksi Pesanan</h3>
            </div>
            <div class="card-body compact-body">
                <form action="../actions/update_order_status.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    
                    <div class="form-group-tight">
                        <label class="form-label-sm">Update Status:</label>
                        <div class="select-wrapper">
                            <select name="status" class="form-select-sm">
                                <option value="Pending" <?php if($order['status'] == 'Pending') echo 'selected'; ?>>🟡 Pending</option>
                                <option value="Completed" <?php if($order['status'] == 'Completed') echo 'selected'; ?>>🟢 Completed</option>
                                <option value="Cancelled" <?php if($order['status'] == 'Cancelled') echo 'selected'; ?>>🔴 Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-block btn-sm">Simpan</button>
                    
                    <p class="helper-text-xs">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        Pastikan pembayaran lunas sebelum mengubah ke "Completed".
                    </p>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header compact-header">
                <h3><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Data Pelanggan</h3>
            </div>
            <div class="card-body customer-info">
                <div class="info-row">
                    <span class="label">Nama</span>
                    <span class="value"><?php echo htmlspecialchars($order['name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value"><?php echo htmlspecialchars($order['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">No. Telepon</span>
                    <span class="value"><?php echo htmlspecialchars($order['phone_number'] ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Alamat</span>
                    <p class="value address-box">
                        <?php echo nl2br(htmlspecialchars($order['address'] ?? '-')); ?>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Layout Grid */
    .view-order-grid {
        display: grid;
        grid-template-columns: 2.2fr 1fr; /* Kiri lebih lebar */
        gap: 25px;
    }
    
    @media (max-width: 900px) {
        .view-order-grid { grid-template-columns: 1fr; }
        .col-sidebar { order: -1; }
    }

    /* Header Styles (Improved) */
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
    .header-left { display: flex; flex-direction: column; gap: 5px; }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-gray); font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
    .btn-back:hover { color: var(--blue-primary); }
    .page-title { font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .page-subtitle { color: var(--text-gray); font-size: 0.9rem; margin: 0; }

    /* STATUS PANEL ROW (Updated: Side by Side) */
    .status-panel-row { 
        display: flex; 
        align-items: center; 
        gap: 20px; /* Jarak renggang */
        background: #fff;
        padding: 10px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        border: 1px solid var(--border-color);
    }
    
    .status-desc-row { 
        font-size: 0.95rem; 
        color: var(--text-gray); 
        font-weight: 600; 
    }

    .status-pill-lg {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Cards */
    .content-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
        height: fit-content;
    }
    .mb-20 { margin-bottom: 20px; }
    
    .card-header { padding: 15px 20px; border-bottom: 1px solid var(--border-color); background: #fff; }
    .card-header h3 { margin: 0; font-size: 1.1rem; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }
    .bg-soft-blue { background: #f8fafc; }
    .card-body { padding: 20px; }

    /* COMPACT CARD STYLES */
    .compact-header { padding: 12px 20px; }
    .compact-header h3 { font-size: 1rem; }
    .compact-body { padding: 15px 20px; }
    
    .form-group-tight { margin-bottom: 15px; }
    .form-label-sm { display: block; margin-bottom: 6px; font-weight: 600; color: var(--text-dark); font-size: 0.85rem; }
    .form-select-sm {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        background-color: #fff;
    }
    .form-select-sm:focus { outline: none; border-color: var(--blue-primary); box-shadow: 0 0 0 3px var(--blue-light); }
    
    .btn-sm { padding: 10px; font-size: 0.9rem; }
    .helper-text-xs { font-size: 0.75rem; color: #94a3b8; margin-top: 12px; line-height: 1.4; display: flex; gap: 5px; align-items: flex-start; }

    /* Product Table */
    .product-flex { display: flex; align-items: center; gap: 15px; }
    .img-wrapper { width: 50px; height: 50px; border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8fafc; flex-shrink: 0; }
    .img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .product-name { font-weight: 600; color: var(--text-dark); font-size: 0.95rem; }
    
    .text-nowrap { white-space: nowrap; }
    .row-total td { border-top: 2px solid var(--border-color); padding-top: 15px; font-weight: 700; color: var(--text-dark); }
    .total-amount { font-size: 1.2rem; color: var(--blue-primary); white-space: nowrap; }

    /* Button Block */
    .btn-primary-block {
        display: block;
        width: 100%;
        background: var(--blue-primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-primary-block:hover { background: var(--blue-hover); }

    /* Customer Info */
    .customer-info .info-row { margin-bottom: 12px; border-bottom: 1px dashed var(--border-color); padding-bottom: 8px; }
    .customer-info .info-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .info-row .label { display: block; font-size: 0.75rem; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
    .info-row .value { font-size: 0.95rem; font-weight: 600; color: var(--text-dark); }
    .address-box { line-height: 1.4; font-weight: 500; font-size: 0.9rem; }

    /* Status Badges */
    .status-warning { background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; }
    .status-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    .status-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
</style>

<?php
$stmt_order->close();
$stmt_items->close();
$conn->close();
include 'admin_footer.php'; 
?>