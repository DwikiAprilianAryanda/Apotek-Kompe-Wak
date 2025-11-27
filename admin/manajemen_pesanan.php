<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// --- PAGINATION & LIMIT LOGIC ---
$available_limits = [10, 20, 50]; 
$default_limit = 10;
$limit = isset($_GET['show']) && in_array((int)$_GET['show'], $available_limits) ? (int)$_GET['show'] : $default_limit;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Hitung Total Data
$sql_count = "SELECT COUNT(*) as total FROM orders";
$result_count = $conn->query($sql_count);
$total_orders = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_orders / $limit);
if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
$offset = ($page - 1) * $limit;

// --- QUERY UTAMA ---
$sql = "SELECT o.id, u.name, o.total_amount, o.status, o.order_date 
        FROM orders o
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.order_date DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Pesanan</h1>
        <p class="page-subtitle">Kelola semua transaksi pesanan yang masuk.</p>
    </div>
</div>

<div class="content-card">
    
    <div class="table-controls">
        <div class="limit-wrapper">
            <span class="control-label">Tampilkan</span>
            <form action="manajemen_pesanan.php" method="GET">
                <select name="show" onchange="this.form.submit()" class="custom-select">
                    <?php foreach($available_limits as $val): ?>
                        <option value="<?php echo $val; ?>" <?php if($limit == $val) echo 'selected'; ?>><?php echo $val; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="page" value="1">
            </form>
            <span class="control-label">baris</span>
        </div>
        
        <div class="data-summary">
            Total <strong><?php echo $total_orders; ?></strong> Pesanan
        </div>
    </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th width="10%">
                        <div class="th-flex">
                            ORDER ID
                        </div>
                    </th>
                    
                    <th width="22%">
                        <div class="th-flex">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            PELANGGAN
                        </div>
                    </th>
                    
                    <th width="20%">
                        <div class="th-flex justify-end">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            TOTAL
                        </div>
                    </th>
                    
                    <th width="15%">
                        <div class="th-flex justify-center">
                            STATUS
                        </div>
                    </th>
                    
                    <th width="23%">
                        <div class="th-flex">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            TANGGAL
                        </div>
                    </th>
                    
                    <th width="10%">
                        <div class="th-flex justify-center">
                            AKSI
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="id-cell">#<?php echo $row['id']; ?></td>
                        
                        <td class="font-medium text-dark">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>
                        
                        <td class="text-right font-bold text-blue">
                            Rp <?php echo number_format($row['total_amount']); ?>
                        </td>
                        
                        <td class="text-center">
                            <?php 
                                $status = $row['status'];
                                $badgeClass = 'status-default';
                                if ($status == 'Completed') $badgeClass = 'status-success';
                                elseif ($status == 'Pending') $badgeClass = 'status-warning';
                                elseif ($status == 'Cancelled') $badgeClass = 'status-danger';
                                elseif ($status == 'Shipped') $badgeClass = 'status-info';
                            ?>
                            <span class="status-badge <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        
                        <td class="text-muted text-sm">
                            <?php echo date('d M Y, H:i', strtotime($row['order_date'])); ?>
                        </td>
                        
                        <td class="text-center">
                            <a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn-icon" title="Lihat Detail">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            Tidak ada data pesanan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination-container">
        <div class="pagination-info">
            Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?>
        </div>
        <div class="pagination-links">
            <?php 
            $base_link = "manajemen_pesanan.php?show=$limit&page=";
            if ($page > 1) echo '<a href="'.$base_link.($page-1).'" class="page-btn prev">←</a>';
            
            for ($i = 1; $i <= $total_pages; $i++): 
                if ($i == 1 || $i == $total_pages || ($i >= $page - 1 && $i <= $page + 1)):
                    $active = ($i == $page) ? 'active' : '';
                    echo '<a href="'.$base_link.$i.'" class="page-btn '.$active.'">'.$i.'</a>';
                elseif ($i == $page - 2 || $i == $page + 2):
                    echo '<span class="page-dots">...</span>';
                endif;
            endfor;

            if ($page < $total_pages) echo '<a href="'.$base_link.($page+1).'" class="page-btn next">→</a>';
            ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<style>
    /* Variabel Warna (Blue Palette) */
    :root {
        --blue-primary: #2563eb;
        --blue-hover: #1d4ed8;
        --blue-light: #eff6ff;
        --blue-border: #dbeafe;
        --text-dark: #0f172a; /* Lebih gelap untuk kontras */
        --text-gray: #64748b;
        --border-color: #e2e8f0;
    }

    /* Layout Dasar */
    .page-header { margin-bottom: 25px; }
    .page-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.5px; }
    .page-subtitle { color: var(--text-gray); margin-top: 5px; font-size: 0.95rem; }

    /* Card Container */
    .content-card {
        background: white;
        border-radius: 16px; /* Lebih rounded */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    /* Table Controls */
    .table-controls {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    .limit-wrapper { display: flex; align-items: center; gap: 8px; color: var(--text-gray); font-size: 0.85rem; font-weight: 500; }
    .custom-select {
        padding: 6px 30px 6px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: var(--text-dark);
        font-size: 0.85rem;
        cursor: pointer;
        background-color: #fff;
        font-weight: 600;
    }
    .custom-select:focus { outline: 2px solid var(--blue-light); border-color: var(--blue-primary); }
    .data-summary { font-size: 0.85rem; color: var(--text-gray); font-weight: 500; }

    /* Modern Table */
    .table-responsive { width: 100%; overflow-x: auto; }
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    
    .modern-table th {
        background: #f8fafc;
        padding: 16px 25px; /* Padding lebih besar */
        text-align: left;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: 1px solid var(--border-color);
    }

    /* Flexbox Helpers untuk Header Table */
    .th-flex { display: flex; align-items: center; gap: 8px; width: 100%; }
    .justify-end { justify-content: flex-end; }   /* Untuk meratakan header ke kanan */
    .justify-center { justify-content: center; } /* Untuk meratakan header ke tengah */

    .modern-table td {
        padding: 18px 25px; /* Padding lebih besar */
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
        font-size: 0.9rem;
        vertical-align: middle;
        transition: background 0.15s;
    }

    .modern-table tr:hover td { background-color: #f8fafc; } /* Hover effect halus */
    .modern-table tr:last-child td { border-bottom: none; }

    /* Typography & Utilities */
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-medium { font-weight: 600; }
    .font-bold { font-weight: 700; }
    .text-blue { color: var(--blue-primary); }
    .text-dark { color: var(--text-dark); }
    .text-muted { color: #94a3b8; }
    .text-sm { font-size: 0.85rem; }
    
    .id-cell { 
        font-family: 'Courier New', monospace; 
        font-weight: 700; 
        color: var(--blue-primary); 
        letter-spacing: -0.5px;
    }

    /* Status Badges (Minimalist Pill) */
    .status-badge {
        display: inline-flex;
        padding: 6px 14px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        line-height: 1;
    }
    .status-warning { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; } /* Orange lembut */
    .status-success { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; } /* Hijau lembut */
    .status-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; } /* Merah lembut */
    .status-info { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; } /* Biru lembut */
    .status-default { background: #f1f5f9; color: #475569; }

    /* Action Button (Eye Icon) */
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        color: var(--text-gray);
        background: transparent;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    .btn-icon:hover {
        background: var(--blue-light);
        color: var(--blue-primary);
        border-color: var(--blue-border);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.15);
    }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px !important; color: var(--text-gray); font-style: italic; }

    /* Pagination (Minimalist) */
    .pagination-container {
        padding: 20px 25px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    .pagination-info { font-size: 0.85rem; color: var(--text-gray); font-weight: 500; }
    .pagination-links { display: flex; gap: 6px; }
    
    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 8px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-gray);
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
        background: #fff;
    }
    .page-btn:hover { border-color: var(--blue-primary); color: var(--blue-primary); background: var(--blue-light); }
    .page-btn.active { background: var(--blue-primary); color: white; border-color: var(--blue-primary); box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3); }
    .page-btn.prev, .page-btn.next { font-size: 1rem; }
    .page-dots { padding: 8px; color: var(--text-gray); }

    /* Responsive */
    @media (max-width: 768px) {
        .table-controls { flex-direction: column; gap: 15px; align-items: flex-start; }
        .pagination-container { flex-direction: column; gap: 15px; }
        .modern-table th, .modern-table td { padding: 12px 15px; }
    }
</style>

<?php
$conn->close();
include 'admin_footer.php';
?>