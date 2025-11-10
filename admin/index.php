<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// Ambil semua pesanan, diurutkan dari yang terbaru
$sql = "SELECT orders.id, users.name, orders.total_amount, orders.status, orders.order_date 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.order_date DESC";

$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    die("Error Query SQL: " . htmlspecialchars($conn->error));
}

// Ambil beberapa statistik dasar (opsional)
$sql_stats = "
    SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN status = 'Completed' THEN total_amount ELSE 0 END) as total_revenue
    FROM orders";

$stats_result = $conn->query($sql_stats);
$stats = $stats_result->fetch_assoc();
?>

<h1 class="page-title">Dasbor Admin - Manajemen Pesanan</h1>

<!-- Tambahkan ringkasan statistik -->
<div class="admin-stats-container">
    <div class="admin-stat-card">
        <h3>Total Pesanan</h3>
        <p class="stat-number"><?php echo $stats['total_orders']; ?></p>
    </div>
    <div class="admin-stat-card">
        <h3>Pesanan Baru</h3>
        <p class="stat-number"><?php echo $stats['pending_orders']; ?></p>
    </div>
    <div class="admin-stat-card">
        <h3>Selesai</h3>
        <p class="stat-number"><?php echo $stats['completed_orders']; ?></p>
    </div>
    <div class="admin-stat-card">
        <h3>Total Pendapatan</h3>
        <p class="stat-number">Rp <?php echo number_format($stats['total_revenue'] ?? 0); ?></p>
    </div>
</div>

<div class="admin-table-container">
    <h2 style="margin-top: 0; margin-bottom: 15px;">Daftar Pesanan Terbaru</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama Pelanggan</th>
                <th>Total Belanja</th>
                <th>Status</th>
                <th>Tanggal Pesan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>#" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>Rp " . number_format($row['total_amount']) . "</td>";
                    // Tambahkan warna status berdasarkan nilai
                    $status_class = '';
                    switch (strtolower($row['status'])) {
                        case 'pending':
                            $status_class = 'status-pending';
                            break;
                        case 'shipped':
                            $status_class = 'status-shipped';
                            break;
                        case 'completed':
                            $status_class = 'status-completed';
                            break;
                        case 'cancelled':
                            $status_class = 'status-cancelled';
                            break;
                        default:
                            $status_class = 'status-other';
                    }
                    echo "<td class='status-cell'>"; // Beri class baru untuk <td>
                    echo "<span class='status-badge $status_class'>" . htmlspecialchars($row['status']) . "</span>"; // Pindahkan badge ke <span>
                    echo "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td><a href='view_order.php?id=" . $row['id'] . "' class='btn-detail'>Detail</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada pesanan yang masuk.</td></tr>";
            }
            
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<style>
/* Tambahkan CSS untuk statistik dan status di sini */
.admin-stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Buat card responsif */
    gap: 20px;
    margin-bottom: 30px;
}

.admin-stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.2s ease;
}

.admin-stat-card:hover {
    transform: translateY(-5px);
}

.admin-stat-card h3 {
    margin: 0 0 10px 0;
    font-size: 1rem;
    color: #666;
}

.stat-number {
    margin: 0;
    font-size: 1.8rem;
    font-weight: bold;
    color: #1D3557;
}

/* Gaya untuk status badge */
.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9em;
    text-align: center;
    display: inline-block;
}

.status-pending {
    background-color: #ffc107;
    color: #333;
}

.status-completed {
    background-color: #28a745;
    color: white;
}

.status-cancelled {
    background-color: #dc3545;
    color: white;
}

.status-other {
    background-color: #6c757d;
    color: white;
}
</style>

<?php
// Load footer admin
include 'admin_footer.php'; 
?>