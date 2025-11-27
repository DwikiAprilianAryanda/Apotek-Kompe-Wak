<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// Ambil resep yang statusnya 'Pending'
$sql = "SELECT p.id, u.name AS customer_name, p.original_name, p.catatan, p.uploaded_at, p.file_name
        FROM prescriptions p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'Pending'
        ORDER BY p.uploaded_at ASC";

$result = $conn->query($sql);
$total_pending = $result->num_rows;

if (!$result) {
    die("Error Query SQL: " . htmlspecialchars($conn->error));
}
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Verifikasi Resep</h1>
        <p class="page-subtitle">Daftar resep masuk yang menunggu pemeriksaan apoteker.</p>
    </div>
</div>

<div class="content-card">
    
    <div class="table-controls">
        <div class="data-summary">
            Menunggu Verifikasi: <strong><?php echo $total_pending; ?></strong> Resep
        </div>
        </div>

    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th width="10%">#ID</th>
                    <th width="20%">
                        <div class="th-flex">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Pelanggan
                        </div>
                    </th>
                    <th width="25%">
                        <div class="th-flex">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                            File Resep
                        </div>
                    </th>
                    <th width="25%">
                        <div class="th-flex">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            Catatan
                        </div>
                    </th>
                    <th width="10%">
                        <div class="th-flex">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Waktu
                        </div>
                    </th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="id-cell">#<?php echo $row['id']; ?></td>
                        
                        <td class="font-medium text-dark">
                            <?php echo htmlspecialchars($row['customer_name']); ?>
                        </td>
                        
                        <td>
                            <a href="../uploads/resep/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank" class="file-link">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <?php 
                                    // Potong nama file jika terlalu panjang
                                    $orig_name = htmlspecialchars($row['original_name']);
                                    echo (strlen($orig_name) > 20) ? substr($orig_name, 0, 20) . '...' : $orig_name;
                                ?>
                            </a>
                        </td>
                        
                        <td class="text-muted text-sm">
                            <?php 
                            if (empty($row['catatan'])) {
                                echo '<span style="opacity:0.5; font-style:italic;">- Tidak ada catatan -</span>';
                            } else {
                                echo htmlspecialchars($row['catatan']); 
                            }
                            ?>
                        </td>
                        
                        <td class="text-muted text-sm">
                            <?php 
                                // Format tanggal lebih ringkas
                                echo date('d/m/y H:i', strtotime($row['uploaded_at'])); 
                            ?>
                        </td>
                        
                        <td class="text-center">
                            <a href="proses_resep.php?id=<?php echo $row['id']; ?>" class="btn-process">
                                Proses
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 10px;"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                            <p>Tidak ada resep baru yang perlu diverifikasi.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Variabel Warna */
    :root {
        --blue-primary: #2563eb;
        --blue-hover: #1d4ed8;
        --blue-light: #eff6ff;
        --blue-border: #dbeafe;
        --text-dark: #0f172a;
        --text-gray: #64748b;
        --border-color: #e2e8f0;
        --yellow-warning: #f59e0b;
        --yellow-bg: #fffbeb;
    }

    /* Layout Dasar */
    .page-header { margin-bottom: 25px; }
    .page-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; letter-spacing: -0.5px; }
    .page-subtitle { color: var(--text-gray); margin-top: 5px; font-size: 0.95rem; }

    /* Card Container */
    .content-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    /* Header Controls */
    .table-controls {
        padding: 15px 25px;
        border-bottom: 1px solid var(--border-color);
        background: #fff;
    }
    .data-summary { font-size: 0.9rem; color: var(--text-gray); }
    .data-summary strong { color: var(--blue-primary); }

    /* Modern Table */
    .table-responsive { width: 100%; overflow-x: auto; }
    .modern-table { width: 100%; border-collapse: collapse; }
    
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
    .justify-center { justify-content: center; }

    .modern-table td {
        padding: 18px 25px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
        font-size: 0.9rem;
        vertical-align: middle;
        transition: background 0.15s;
    }

    .modern-table tr:hover td { background-color: #f8fafc; }
    .modern-table tr:last-child td { border-bottom: none; }

    /* Typography & Utilities */
    .text-center { text-align: center; }
    .font-medium { font-weight: 600; }
    .text-muted { color: #94a3b8; }
    .text-sm { font-size: 0.85rem; }
    
    .id-cell { 
        font-family: 'Courier New', monospace; 
        font-weight: 700; 
        color: var(--blue-primary); 
        letter-spacing: -0.5px;
    }

    /* File Link Style */
    .file-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--blue-primary);
        text-decoration: none;
        font-weight: 500;
        padding: 4px 8px;
        background: var(--blue-light);
        border-radius: 6px;
        transition: all 0.2s;
        font-size: 0.85rem;
    }
    .file-link:hover {
        background: var(--blue-border);
        text-decoration: underline;
    }

    /* Process Button */
    .btn-process {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #fff;
        background: var(--blue-primary); /* Gunakan warna biru agar konsisten, atau orange untuk warning */
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }
    .btn-process:hover {
        background: var(--blue-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
    }

    /* Empty State */
    .empty-state { text-align: center; padding: 60px !important; color: var(--text-gray); }
</style>

<?php
include 'admin_footer.php';
?>