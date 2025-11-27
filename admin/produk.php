<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// --- Logika Pesan Notifikasi ---
$message = '';
if (isset($_GET['status'])) {
    $statusType = $_GET['status'];
    $msgText = '';
    $msgClass = '';
    
    if ($statusType == 'added') {
        $msgText = 'Produk berhasil ditambahkan!';
        $msgClass = 'success';
    } else if ($statusType == 'updated') {
        $msgText = 'Produk berhasil diperbarui!';
        $msgClass = 'success';
    } else if ($statusType == 'deleted') {
        $msgText = 'Produk berhasil dihapus!';
        $msgClass = 'success';
    } else if (isset($_GET['error'])) {
        $msgText = 'Terjadi kesalahan: ' . htmlspecialchars($_GET['msg'] ?? 'Gagal memproses.');
        $msgClass = 'error';
    }

    if ($msgText) {
        $message = "<div class='alert-box $msgClass'>
                        <svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M22 11.08V12a10 10 0 1 1-5.93-9.14'></path><polyline points='22 4 12 14.01 9 11.01'></polyline></svg>
                        <span>$msgText</span>
                    </div>";
    }
}

// --- Logika Filter Pencarian ---
$search_term = "";
$where_clause = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $where_clause = " WHERE name LIKE '%$search_term%'";
}

// Ambil data produk
$sql = "SELECT id, name, price, stock_quantity, image_url, description FROM products" . $where_clause . " ORDER BY name ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Error Query SQL: " . htmlspecialchars($conn->error));
}
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Produk</h1>
        <p class="page-subtitle">Kelola inventaris obat dan alat kesehatan.</p>
    </div>
    
    <a href="tambah_produk.php" class="btn-primary-add">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Tambah Produk
    </a>
</div>

<?php echo $message; ?>

<div class="content-card">
    
    <div class="table-toolbar">
        <form action="produk.php" method="GET" class="search-form">
            <div class="search-input-group">
                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" placeholder="Cari nama obat..." value="<?php echo htmlspecialchars($search_term); ?>">
            </div>
            <button type="submit" class="btn-search">Cari</button>
        </form>
        
        <div class="data-count">
            Menampilkan <strong><?php echo $result->num_rows; ?></strong> Produk
        </div>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th width="12%">GAMBAR</th>
                    <th width="20%">NAMA PRODUK</th>
                    <th width="30%">DESKRIPSI</th> 
                    <th width="15%">HARGA</th>
                    <th width="13%">
                        <div class="th-flex justify-center">STOK</div>
                    </th>
                    <th width="10%">
                        <div class="th-flex justify-center">AKSI</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="product-img-wrapper">
                                <img src="../assets/images/<?php echo htmlspecialchars($row['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                     onerror="this.onerror=null;this.src='../assets/images/default.jpg';"> 
                            </div>
                        </td>
                        
                        <td>
                            <span class="product-name"><?php echo htmlspecialchars($row['name']); ?></span>
                        </td>

                        <td class="desc-cell">
                            <?php 
                                $desc = htmlspecialchars($row['description'] ?? '');
                                echo (strlen($desc) > 80) ? substr($desc, 0, 80) . '...' : $desc; 
                            ?>
                        </td>
                        
                        <td class="font-medium text-blue">
                            Rp <?php echo number_format($row['price']); ?>
                        </td>
                        
                        <td class="text-center">
                            <?php
                            $qty = $row['stock_quantity'];
                            $badgeClass = 'badge-stock-safe'; 
                            $label = $qty . ' Pcs';

                            if ($qty == 0) {
                                $badgeClass = 'badge-stock-empty';
                                $label = 'Habis';
                            } elseif ($qty <= 10) {
                                $badgeClass = 'badge-stock-low';
                                $label = $qty . ' (Sisa Sedikit)';
                            }
                            ?>
                            <span class="stock-badge <?php echo $badgeClass; ?>">
                                <?php echo $label; ?>
                            </span>
                        </td>
                        
                        <td class="text-center">
                            <div class="action-buttons">
                                <a href="edit_produk.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-edit" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                
                                <a href="hapus_produk_process.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-delete" onclick="return confirm('Hapus produk ini?');" title="Hapus">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            <p>Produk tidak ditemukan.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* --- VARIABEL WARNA --- */
    :root {
        --blue-primary: #2563eb;
        --blue-hover: #1d4ed8;
        --blue-light: #eff6ff;
        --blue-border: #dbeafe;
        --text-dark: #0f172a;
        --text-gray: #64748b;
        --border-color: #e2e8f0;
        --red-danger: #ef4444;
        --red-bg: #fef2f2;
        --green-success: #10b981;
        --green-bg: #ecfdf5;
        --orange-warn: #f59e0b;
        --orange-bg: #fffbeb;
    }

    /* Layout Header */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.5px; }
    .page-subtitle { color: var(--text-gray); margin-top: 5px; font-size: 0.95rem; }

    /* Tombol Utama */
    .btn-primary-add {
        background: var(--blue-primary);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }
    .btn-primary-add:hover { background: var(--blue-hover); transform: translateY(-2px); }

    /* Content Card */
    .content-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    /* Toolbar & Search */
    .table-toolbar {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .search-form { display: flex; align-items: center; gap: 10px; flex-grow: 1; max-width: 500px; }
    .search-input-group { position: relative; flex-grow: 1; }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-gray); }
    .search-input-group input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border 0.2s;
    }
    .search-input-group input:focus { outline: none; border-color: var(--blue-primary); box-shadow: 0 0 0 3px var(--blue-light); }
    
    .btn-search {
        background: #f1f5f9;
        color: var(--text-dark);
        border: 1px solid #cbd5e1;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-search:hover { background: #e2e8f0; }
    .data-count { color: var(--text-gray); font-size: 0.9rem; font-weight: 500; }

    /* Tabel Styling */
    .table-responsive { width: 100%; overflow-x: auto; }
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    
    .modern-table th {
        background: #f8fafc;
        padding: 16px 25px;
        text-align: left;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: 1px solid var(--border-color);
    }

    .th-flex { display: flex; align-items: center; gap: 8px; width: 100%; }
    .justify-center { justify-content: center; } /* Memaksa header ke tengah */

    .modern-table td {
        padding: 16px 25px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
        font-size: 0.95rem;
        vertical-align: middle;
        transition: background 0.15s;
    }
    
    .modern-table tr:hover td { background-color: var(--blue-light); }
    .modern-table tr:last-child td { border-bottom: none; }

    /* Image Styling (Diperbesar jadi 70px) */
    .product-img-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Typography */
    .product-name { font-weight: 700; color: var(--text-dark); font-size: 1rem; }
    .desc-cell { color: var(--text-gray); font-size: 0.85rem; line-height: 1.5; }
    .text-center { text-align: center; }
    .font-medium { font-weight: 600; }
    .text-blue { color: var(--blue-primary); }

    /* Stock Badges */
    .stock-badge {
        display: inline-flex;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .badge-stock-safe { background: var(--green-bg); color: var(--green-success); border: 1px solid #d1fae5; }
    .badge-stock-low { background: var(--orange-bg); color: var(--orange-warn); border: 1px solid #fcd34d; }
    .badge-stock-empty { background: var(--red-bg); color: var(--red-danger); border: 1px solid #fca5a5; }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .btn-edit { color: var(--blue-primary); background: #eff6ff; border: 1px solid #dbeafe; }
    .btn-edit:hover { background: var(--blue-primary); color: white; }
    
    .btn-delete { color: var(--red-danger); background: #fef2f2; border: 1px solid #fee2e2; }
    .btn-delete:hover { background: var(--red-danger); color: white; }

    /* Alert Box */
    .alert-box {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .alert-box.success { background: var(--green-bg); color: var(--green-success); border: 1px solid #d1fae5; }
    .alert-box.error { background: var(--red-bg); color: var(--red-danger); border: 1px solid #fee2e2; }
    
    .empty-state { text-align: center; padding: 60px !important; color: var(--text-gray); }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .table-toolbar { flex-direction: column; align-items: stretch; }
        .search-form { max-width: 100%; }
        .desc-cell { display: none; } /* Sembunyikan deskripsi di HP agar tabel muat */
        .modern-table th:nth-child(3), .modern-table td:nth-child(3) { display: none; }
    }
</style>

<?php
$conn->close();
include 'admin_footer.php';
?>