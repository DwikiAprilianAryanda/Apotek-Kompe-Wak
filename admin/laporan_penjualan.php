<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// KEAMANAN TAMBAHAN: Hanya Admin (bukan Resepsionis) yang boleh melihat laporan keuangan
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("<div class='admin-content'><h2>Akses Ditolak. Hanya Admin yang dapat melihat laporan.</h2></div>");
}

// 1. Ambil Total Pendapatan dari pesanan yang sudah Selesai (Completed)
$sql_total = "SELECT SUM(total_amount) AS total_revenue FROM orders WHERE status = 'Completed'";
$result_total = $conn->query($sql_total);
$total_revenue = $result_total->fetch_assoc()['total_revenue'] ?? 0;

// 2. Ambil Semua Pesanan yang sudah Selesai (Completed)
$sql_orders = "SELECT o.id, u.name, o.total_amount, o.order_date, o.status 
               FROM orders o
               JOIN users u ON o.user_id = u.id
               WHERE o.status = 'Completed' OR o.status = 'Shipped' 
               ORDER BY o.order_date DESC";
$result_orders = $conn->query($sql_orders);
?>

<h1 class="page-title">Laporan Penjualan & Pendapatan</h1>

<div style="display: flex; gap: 20px; margin-bottom: 30px;">
    <div style="flex: 1; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-left: 5px solid #28a745;">
        <p style="margin: 0; color: #555; font-weight: 600;">Total Pendapatan (Pesanan Selesai)</p>
        <h2 style="color: #28a745; margin: 10px 0 0;">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></h2>
    </div>
    </div>

<div class="admin-table-container">
    <h3 style="margin-top: 0; color: #1D3557;">Detail Transaksi Terakhir (Selesai/Dikirim)</h3>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Pelanggan</th>
                <th>Total Belanja</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_orders->num_rows > 0): ?>
                <?php while ($row = $result_orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>Rp <?php echo number_format($row['total_amount'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn-detail">Lihat</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Belum ada pesanan yang selesai/dikirim.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
include 'admin_footer.php';
?>