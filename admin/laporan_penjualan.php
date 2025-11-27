<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// KEAMANAN: Hanya Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("<div class='admin-content'><h2>Akses Ditolak.</h2></div>");
}

// --- 1. LOGIKA DATA METRIK ---
// Total Pendapatan
$sql_total = "SELECT SUM(total_amount) AS total_revenue, COUNT(*) as total_trx 
              FROM orders WHERE status = 'Completed'";
$result_total = $conn->query($sql_total);
$data_total = $result_total->fetch_assoc();

$total_revenue = $data_total['total_revenue'] ?? 0;
$total_trx = $data_total['total_trx'] ?? 0;
$avg_order = ($total_trx > 0) ? ($total_revenue / $total_trx) : 0;

// --- 2. LOGIKA DATA GRAFIK (7 Hari Terakhir) ---
$dates = [];
$totals = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[$date] = 0; // Inisialisasi 0
}

$sql_chart = "SELECT DATE(order_date) as tgl, SUM(total_amount) as subtotal 
              FROM orders 
              WHERE status = 'Completed' AND order_date >= DATE(NOW()) - INTERVAL 7 DAY 
              GROUP BY DATE(order_date)";
$res_chart = $conn->query($sql_chart);

while ($row = $res_chart->fetch_assoc()) {
    $dates[$row['tgl']] = (float)$row['subtotal'];
}

// Pisahkan untuk Chart.js
$chart_labels = array_keys($dates);
$chart_data = array_values($dates);
// Format tanggal label jadi lebih cantik (27 Nov)
$chart_labels_formatted = array_map(function($date) {
    return date('d M', strtotime($date));
}, $chart_labels);


// --- 3. DATA TABEL (Riwayat Transaksi) ---
$sql_orders = "SELECT o.id, u.name, o.total_amount, o.order_date, o.status 
               FROM orders o
               JOIN users u ON o.user_id = u.id
               WHERE o.status = 'Completed' OR o.status = 'Shipped' 
               ORDER BY o.order_date DESC LIMIT 10";
$result_orders = $conn->query($sql_orders);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Penjualan</h1>
        <p class="page-subtitle">Analisa pendapatan dan riwayat transaksi selesai.</p>
    </div>
    <div class="date-badge">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        <?php echo date("d F Y"); ?>
    </div>
</div>

<div class="metrics-grid">
    <div class="metric-card card-blue">
        <div class="metric-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Total Pendapatan</span>
            <h3 class="metric-value">Rp <?php echo number_format($total_revenue); ?></h3>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon icon-light">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Transaksi Selesai</span>
            <h3 class="metric-value"><?php echo number_format($total_trx); ?> <small>Pesanan</small></h3>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon icon-light">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
        </div>
        <div class="metric-info">
            <span class="metric-label">Rata-rata Order</span>
            <h3 class="metric-value">Rp <?php echo number_format($avg_order); ?></h3>
        </div>
    </div>
</div>

<div class="report-layout">
    
    <div class="content-card chart-section">
        <div class="card-header">
            <h3>Grafik Pendapatan (7 Hari)</h3>
        </div>
        <div class="chart-wrapper">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="content-card table-section">
        <div class="card-header">
            <h3>Riwayat Transaksi Terakhir</h3>
            <a href="manajemen_pesanan.php" class="link-view">Lihat Semua</a>
        </div>
        
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_orders->num_rows > 0): ?>
                        <?php while ($row = $result_orders->fetch_assoc()): ?>
                            <tr>
                                <td class="id-cell">#<?php echo $row['id']; ?></td>
                                <td class="font-medium"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="text-right text-blue font-bold">Rp <?php echo number_format($row['total_amount']); ?></td>
                                <td class="text-center">
                                    <span class="status-badge status-success">Completed</span>
                                </td>
                                <td class="text-center">
                                    <a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="empty-state">Belum ada transaksi selesai.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Variabel Konsisten */
    :root {
        --blue-primary: #2563eb;
        --blue-dark: #1e40af;
        --blue-light: #eff6ff;
        --blue-border: #dbeafe;
        --text-dark: #0f172a;
        --text-gray: #64748b;
        --border-color: #e2e8f0;
    }

    /* Header */
    .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
    .page-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .page-subtitle { color: var(--text-gray); margin-top: 5px; font-size: 0.95rem; }
    .date-badge { display: flex; align-items: center; gap: 8px; background: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; color: var(--text-dark); border: 1px solid var(--border-color); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }

    /* Metrics Grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .metric-card {
        background: white;
        padding: 25px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s;
    }
    .metric-card:hover { transform: translateY(-3px); }

    .card-blue { background: var(--blue-primary); color: white; border: none; }
    .card-blue .metric-label { color: rgba(255,255,255,0.8); }
    .card-blue .metric-value { color: white; }
    .card-blue .metric-icon { background: rgba(255,255,255,0.2); color: white; }

    .metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-light { background: var(--blue-light); color: var(--blue-primary); }

    .metric-info { display: flex; flex-direction: column; }
    .metric-label { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--text-gray); letter-spacing: 0.5px; margin-bottom: 5px; }
    .metric-value { font-size: 1.5rem; font-weight: 800; margin: 0; color: var(--text-dark); }
    .metric-value small { font-size: 0.9rem; font-weight: 500; opacity: 0.7; }

    /* Report Layout (Chart + Table) */
    .report-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }
    
    @media (max-width: 1024px) {
        .report-layout { grid-template-columns: 1fr; }
    }

    .content-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header h3 { margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-dark); }
    .link-view { color: var(--blue-primary); font-size: 0.9rem; font-weight: 600; text-decoration: none; }

    .chart-wrapper { padding: 20px; height: 350px; position: relative; }

    /* Table Styling (Minimalist & Spacious) */
    .table-responsive { width: 100%; overflow-x: auto; }
    .modern-table { width: 100%; border-collapse: collapse; }
    
    .modern-table th {
        background: #f8fafc;
        padding: 15px 25px;
        text-align: left;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border-color);
    }

    .modern-table td {
        padding: 18px 25px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
        font-size: 0.9rem;
        vertical-align: middle;
    }
    
    .modern-table tr:hover td { background-color: var(--blue-light); }
    .modern-table tr:last-child td { border-bottom: none; }

    /* Utilities */
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-medium { font-weight: 600; }
    .font-bold { font-weight: 700; }
    .text-blue { color: var(--blue-primary); }
    .id-cell { font-family: monospace; font-weight: 700; color: var(--blue-primary); background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }

    .status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        color: var(--text-gray);
        background: transparent;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
    }
    .btn-icon:hover { background: var(--blue-light); color: var(--blue-primary); border-color: var(--blue-primary); }
    
    .empty-state { text-align: center; padding: 40px !important; color: var(--text-gray); font-style: italic; }
</style>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Data dari PHP
    const labels = <?php echo json_encode($chart_labels_formatted); ?>;
    const dataValues = <?php echo json_encode($chart_data); ?>;

    const salesChart = new Chart(ctx, {
        type: 'bar', // Menggunakan Bar Chart agar lebih solid
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: dataValues,
                backgroundColor: '#3b82f6', // Warna Biru Solid
                borderRadius: 4, // Rounded corners pada batang
                barPercentage: 0.6,
                categoryPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: '#e2e8f0' },
                    ticks: {
                        callback: function(value) { return 'Rp ' + (value/1000) + 'k'; },
                        font: { size: 11, family: 'Inter' },
                        color: '#64748b'
                    },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 11, family: 'Inter' },
                        color: '#64748b'
                    },
                    border: { display: false }
                }
            }
        }
    });
</script>

<?php
$conn->close();
include 'admin_footer.php';
?>