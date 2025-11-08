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
?>

<h1 class="page-title">Dasbor Admin - Manajemen Pesanan</h1>

<div class="admin-table-container">
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
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
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

<?php
// Load footer admin
include 'admin_footer.php'; 
?>