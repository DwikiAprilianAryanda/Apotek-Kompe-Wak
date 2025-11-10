<?php
// Hapus session_start() jika ada
include 'includes/header.php';
include 'includes/db_connect.php';

// Keamanan: Pastikan hanya pengguna yang login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua pesanan milik pengguna ini, diurutkan dari yang terbaru
// Kolom shipping_code sudah ditambahkan
$stmt = $conn->prepare("SELECT id, total_amount, status, order_date, shipping_code 
                        FROM orders 
                        WHERE user_id = ? 
                        ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>

<div class="section" style="max-width: 1000px; margin-top: 30px;">
    
    <h2 style="color: #1D3557; text-align: left; margin-bottom: 10px;">Akun Saya</h2>
    
    <p style="margin-bottom: 20px;">
        <a href="akun.php" style="margin-right: 15px; font-weight: bold; color: #1D3557;">Update Profil</a>
        <span style="font-weight: bold; color: #457B9D;">Riwayat Pesanan</span>
    </p>

    <hr>

    <h3 style="color: #333; margin-top: 30px;">Riwayat Pesanan Saya</h3>
    
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow-x: auto;"> 
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="padding: 12px;">Order ID</th>
                    <th style="padding: 12px;">Tanggal Pesan</th>
                    <th style="padding: 12px;">Total Belanja</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Nomor Resi</th>
                    <th style="padding: 12px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 12px;">#<?php echo $row['id']; ?></td>
                        <td style="padding: 12px;"><?php echo date('d-M-Y', strtotime($row['order_date'])); ?></td>
                        <td style="padding: 12px;">Rp <?php echo number_format($row['total_amount']); ?></td>
                        <td style="padding: 12px;">
                            <span style="font-weight: bold; color: 
                            <?php 
                                // Memberi warna status
                                if ($row['status'] == 'Completed') echo '#28a745';
                                else if ($row['status'] == 'Pending') echo '#ffc107';
                                else echo '#dc3545'; // Default untuk 'Cancelled'
                            ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <?php echo empty($row['shipping_code']) ? '—' : htmlspecialchars($row['shipping_code']); ?>
                        </td>
                        <td style="padding: 12px;">
                            <a href="detail_pesanan.php?id=<?php echo $row['id']; ?>" style="color: #1D3557; text-decoration: none; border-bottom: 1px solid;">Detail</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center;">Anda belum memiliki riwayat pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$conn->close();
include 'includes/footer.php'; 
?>