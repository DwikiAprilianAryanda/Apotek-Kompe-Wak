<?php
session_start();
include 'includes/header.php';
include 'includes/db_connect.php';

// Keamanan
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil Foto Profil untuk Sidebar
$stmt_user = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_data = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

// Logika Gambar
$foto_profil = 'assets/images/logo_apotek.jpg';
if (!empty($user_data['profile_pic'])) {
    $path = 'uploads/profiles/' . $user_data['profile_pic'];
    if (file_exists($path)) $foto_profil = $path;
}

// Ambil Data Pesanan
$stmt = $conn->prepare("SELECT id, total_amount, status, order_date, shipping_code 
                        FROM orders 
                        WHERE user_id = ? 
                        ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>

<div class="section-wrapper bg-light">
    <div class="container">
        
        <div class="profile-grid">
            
            <aside class="profile-sidebar">
                <div class="profile-card card-photo">
                    <div class="profile-img-wrapper">
                        <img src="<?php echo $foto_profil; ?>" alt="Foto Profil">
                    </div>
                    <h4 style="margin-bottom: 0; color: var(--primary); font-weight: 700;">
                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Pelanggan'); ?>
                    </h4>
                </div>

                <div class="profile-menu-group">
                    <a href="akun.php" class="btn-menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <span>Biodata Diri</span>
                    </a>

                    <a href="riwayat_pesanan.php" class="btn-menu-item active">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <span>Riwayat Pesanan</span>
                        <div class="menu-arrow">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>

                    <a href="actions/logout.php" class="btn-menu-item btn-logout-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        </div>
                        <span>Keluar</span>
                    </a>
                </div>
            </aside>

            <main class="profile-content">
                <div class="profile-card" style="padding: 0; overflow: hidden;">
                    
                    <div class="history-header">
                        <h3>Daftar Transaksi</h3>
                        <p>Pantau status pesanan obat Anda di sini.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Tanggal</th>
                                    <th>Total Belanja</th>
                                    <th>Status</th>
                                    <th>Resi</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($orders->num_rows > 0): ?>
                                    <?php while ($row = $orders->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-bold text-primary">#<?php echo $row['id']; ?></td>
                                        <td><?php echo date('d M Y', strtotime($row['order_date'])); ?></td>
                                        <td class="fw-bold">Rp <?php echo number_format($row['total_amount']); ?></td>
                                        <td>
                                            <?php 
                                                $status = $row['status'];
                                                $badgeClass = 'badge-secondary';
                                                if ($status == 'Completed') $badgeClass = 'badge-success';
                                                elseif ($status == 'Pending') $badgeClass = 'badge-warning';
                                                elseif ($status == 'Cancelled') $badgeClass = 'badge-danger';
                                                elseif ($status == 'Shipped') $badgeClass = 'badge-info';
                                            ?>
                                            <span class="status-badge <?php echo $badgeClass; ?>">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo empty($row['shipping_code']) ? '<span class="text-muted">-</span>' : '<span class="resi-code">'.$row['shipping_code'].'</span>'; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="detail_pesanan.php?id=<?php echo $row['id']; ?>" class="btn-detail-outline" title="Lihat Detail">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="empty-history">
                                            <img src="assets/images/obat.png" alt="Kosong" style="width: 60px; opacity: 0.5; margin-bottom: 10px;">
                                            <p>Belum ada riwayat pesanan.</p>
                                            <a href="produk.php" style="color: var(--primary); font-weight: 600;">Mulai Belanja</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </main>

        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>