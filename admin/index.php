<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// --- 1. Ambil Statistik Dasar ---
$sql_stats = "
    SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = 'Shipped' THEN 1 ELSE 0 END) as shipped_orders,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
        SUM(CASE WHEN status = 'Completed' THEN total_amount ELSE 0 END) as total_revenue
    FROM orders";

$stats_result = $conn->query($sql_stats);
$stats = $stats_result->fetch_assoc();

// --- 2. Ambil Data Grafik Pendapatan (7 Hari Terakhir) ---
// Siapkan array tanggal 7 hari ke belakang
$revenue_labels = [];
$revenue_values = [];
$dates_check = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $display_date = date('d M', strtotime($date)); // Contoh: 27 Nov
    $revenue_labels[] = $display_date;
    $dates_check[$date] = 0; // Default nilai 0
}

// Query data
$sql_chart = "SELECT DATE(order_date) as tgl, SUM(total_amount) as total 
              FROM orders 
              WHERE status = 'Completed' AND order_date >= DATE(NOW()) - INTERVAL 6 DAY 
              GROUP BY DATE(order_date)";
$chart_result = $conn->query($sql_chart);

// Isi data dari database ke array
while ($row = $chart_result->fetch_assoc()) {
    if (isset($dates_check[$row['tgl']])) {
        $dates_check[$row['tgl']] = (float)$row['total'];
    }
}
// Ambil values yang sudah urut tanggal
$revenue_values = array_values($dates_check);


// --- 3. Ambil 5 Pesanan Terbaru ---
$sql_recent = "SELECT o.id, u.name, o.total_amount, o.status, o.order_date 
               FROM orders o
               JOIN users u ON o.user_id = u.id 
               ORDER BY o.order_date DESC 
               LIMIT 5";
$recent_result = $conn->query($sql_recent);

$conn->close(); 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard-header">
    <div>
        <h1 class="page-title">Dasbor Admin</h1>
        <p class="subtitle">Halo, pantau performa bisnis dan pesanan terbaru hari ini.</p>
    </div>
    <div class="date-display">
        <?php echo date("l, d F Y"); ?>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div class="stat-info">
            <h3>Total Pesanan</h3>
            <p class="stat-number"><?php echo number_format($stats['total_orders']); ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-yellow">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="stat-info">
            <h3>Perlu Proses</h3>
            <p class="stat-number"><?php echo number_format($stats['pending_orders']); ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div class="stat-info">
            <h3>Selesai</h3>
            <p class="stat-number"><?php echo number_format($stats['completed_orders']); ?></p>
        </div>
    </div>

    <div class="stat-card revenue-card">
        <div class="stat-icon bg-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="stat-info">
            <h3>Total Pendapatan</h3>
            <p class="stat-number">Rp <?php echo number_format($stats['total_revenue'] ?? 0); ?></p>
        </div>
    </div>
</div>

<div class="charts-row">
    
    <div class="content-card chart-large">
        <div class="card-header">
            <h3>Tren Pendapatan (7 Hari Terakhir)</h3>
        </div>
        <div style="height: 300px; position: relative;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="content-card chart-small">
        <div class="card-header">
            <h3>Status Pesanan</h3>
        </div>
        <div style="height: 250px; position: relative; display: flex; align-items: center; justify-content: center;">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>

</div>

<div class="content-card full-width-table">
    <div class="card-header">
        <h3>Transaksi Terbaru</h3>
        <a href="manajemen_pesanan.php" class="view-all-link">Lihat Semua &rarr;</a>
    </div>
    
    <div class="mini-table-container">
        <table class="mini-table">
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_result->num_rows > 0): ?>
                    <?php while($row = $recent_result->fetch_assoc()): ?>
                    <tr>
                        <td style="font-weight: bold;">#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td style="font-weight: 600;">Rp <?php echo number_format($row['total_amount']); ?></td>
                        <td style="color: #64748b;"><?php echo date('d M, H:i', strtotime($row['order_date'])); ?></td>
                        <td>
                            <?php
                            $status_bg = '#f3f4f6'; $status_color = '#374151';
                            if($row['status'] == 'Pending') { $status_bg = '#fffbeb'; $status_color = '#d97706'; }
                            if($row['status'] == 'Completed') { $status_bg = '#dcfce7'; $status_color = '#15803d'; }
                            if($row['status'] == 'Cancelled') { $status_bg = '#fee2e2'; $status_color = '#b91c1c'; }
                            if($row['status'] == 'Shipped') { $status_bg = '#eff6ff'; $status_color = '#1d4ed8'; }
                            ?>
                            <span class="status-pill" style="background: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>;">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn-mini-detail">Detail</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 20px; color:#999;">Belum ada pesanan masuk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 25px;
    }
    .subtitle { color: #64748b; margin: 5px 0 0; font-size: 0.95rem; }
    .date-display { font-weight: 600; color: #1e293b; background: #fff; padding: 8px 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        border: 1px solid #e2e8f0;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .bg-blue { background: #eff6ff; color: #3b82f6; }
    .bg-yellow { background: #fffbeb; color: #f59e0b; }
    .bg-green { background: #f0fdf4; color: #22c55e; }
    .bg-primary { background: #f8fafc; color: #0f172a; }

    .stat-info h3 { margin: 0 0 4px; font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-number { margin: 0; font-size: 1.4rem; font-weight: 700; color: #0f172a; }

    /* Charts Row */
    .charts-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }
    .chart-large, .chart-small {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    /* Table Section */
    .full-width-table {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
    }
    .card-header h3 { margin: 0; font-size: 1.1rem; color: #1e293b; font-weight: 700; }
    .view-all-link { font-size: 0.9rem; color: #3b82f6; text-decoration: none; font-weight: 600; }

    /* Table Styling */
    .mini-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .mini-table th { text-align: left; color: #64748b; font-size: 0.85rem; padding: 12px 15px; font-weight: 600; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .mini-table th:first-child { border-top-left-radius: 8px; }
    .mini-table th:last-child { border-top-right-radius: 8px; }
    .mini-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; color: #334155; vertical-align: middle; }
    .mini-table tr:last-child td { border-bottom: none; }

    .status-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .btn-mini-detail {
        color: #3b82f6;
        font-weight: 600;
        text-decoration: none;
        padding: 5px 10px;
        background: #eff6ff;
        border-radius: 6px;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .btn-mini-detail:hover { background: #3b82f6; color: white; }

    @media(max-width: 1024px) {
        .charts-row { grid-template-columns: 1fr; }
    }
</style>

<script>
    // --- GRAFIK 1: LINE CHART (PENDAPATAN) ---
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    
    // Data dari PHP
    const revenueLabels = <?php echo json_encode($revenue_labels); ?>;
    const revenueData = <?php echo json_encode($revenue_values); ?>;

    const revenueChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueData,
                borderColor: '#3b82f6', // Biru
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointRadius: 4,
                tension: 0.4, // Membuat garis melengkung halus
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: '#f1f5f9' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000) + 'k';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // --- GRAFIK 2: DOUGHNUT CHART (STATUS) ---
    const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
    const statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Dikirim', 'Selesai', 'Batal'],
            datasets: [{
                data: [
                    <?php echo $stats['pending_orders']; ?>,
                    <?php echo $stats['shipped_orders']; ?>,
                    <?php echo $stats['completed_orders']; ?>,
                    <?php echo $stats['cancelled_orders']; ?>
                ],
                backgroundColor: [
                    '#f59e0b', // Kuning (Pending)
                    '#3b82f6', // Biru (Shipped)
                    '#22c55e', // Hijau (Completed)
                    '#ef4444'  // Merah (Cancelled)
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 20, font: { size: 11 } }
                }
            },
            cutout: '75%', 
        }
    });
</script>

<?php
include 'admin_footer.php'; 
?>