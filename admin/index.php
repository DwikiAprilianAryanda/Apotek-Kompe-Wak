<?php
session_start();
include '../includes/db_connect.php';

// KEAMANAN: Periksa apakah pengguna sudah login DAN adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    // Jika bukan admin, tendang ke halaman login
    header("Location: ../login.php");
    exit;
}

// Ambil semua pesanan, diurutkan dari yang terbaru
// Kita JOIN dengan tabel 'users' untuk mendapatkan nama pemesan
$sql = "SELECT orders.id, users.name, orders.total_amount, orders.status, orders.order_date 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.order_date DESC";

$result = $conn->query($sql);
?>

<?php include '../includes/header.php'; ?>

<h1 class="page-title">Dasbor Admin - Manajemen Pesanan</h1>

<div class="admin-table-container">
    <table>
        <tr>
            <th>Order ID</th>
            <th>Nama Pelanggan</th>
            <th>Total Belanja</th>
            <th>Status</th>
            <th>Tanggal Pesan</th>
            <th>Aksi</th>
        </tr>
        
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>Rp " . number_format($row['total_amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td><a href='view_order.php?id=" . $row['id'] . "'>Detail</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada pesanan yang masuk.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>