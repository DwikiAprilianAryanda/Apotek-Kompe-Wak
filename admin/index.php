<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// Ambil beberapa statistik dasar
$sql_stats = "
    SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN status = 'Completed' THEN total_amount ELSE 0 END) as total_revenue
    FROM orders";

$stats_result = $conn->query($sql_stats);
$stats = $stats_result->fetch_assoc();

// Menutup koneksi database setelah mengambil data yang dibutuhkan
$conn->close(); 
?>

<h1 class="page-title">Dasbor Admin</h1>
<p style="margin-bottom: 20px;">Selamat datang di Dasbor Apotek Arshaka. Berikut ringkasan performa Anda.</p>

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
    <h2 style="margin-top: 0; margin-bottom: 15px;">Pintasan Cepat</h2>
    <p>Lihat detail dan kelola semua pesanan di halaman Manajemen Pesanan.</p>
    <a href="manajemen_pesanan.php" class="btn-primary" style="margin-bottom: 0; width: auto; padding: 10px 20px;">Buka Manajemen Pesanan &rarr;</a>
</div>

<style>
.admin-stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
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

/* Gaya untuk status badge (Diambil dari admin_style.css yang sudah ada) */
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
include 'admin_footer.php'; 
?>