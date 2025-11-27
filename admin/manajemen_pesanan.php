<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// --- PAGINATION & LIMIT LOGIC ---
$available_limits = [10, 20, 50]; // Opsi untuk dropdown
$default_limit = 10;

// 1. Tentukan Limit
$limit = isset($_GET['show']) && in_array((int)$_GET['show'], $available_limits) ? (int)$_GET['show'] : $default_limit;

// 2. Tentukan Page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// 3. Hitung Total Pesanan
$sql_count = "SELECT COUNT(*) as total FROM orders";
$result_count = $conn->query($sql_count);
$total_orders = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_orders / $limit);

// Pastikan halaman tidak melebihi total halaman
if ($page > $total_pages && $total_pages > 0) {
    $page = $total_pages;
}

// 4. Hitung Offset
$offset = ($page - 1) * $limit;

// --- MAIN SQL QUERY (UPDATED with LIMIT & OFFSET) ---
$sql = "SELECT o.id, u.name, o.total_amount, o.status, o.order_date 
        FROM orders o
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.order_date DESC
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    die("Error Query SQL: " . htmlspecialchars($conn->error));
}
?>

<h1 class="page-title">Manajemen Pesanan</h1>
<p style="margin-bottom: 20px;">Kelola dan pantau semua pesanan yang masuk ke apotek Anda.</p>

<div class="admin-table-container">
    
    <div class="table-controls-header">
        <div class="limit-selector">
            <form action="manajemen_pesanan.php" method="GET" class="show-form">
                <label for="show-limit">Tampilkan:</label>
                <select name="show" id="show-limit" onchange="this.form.submit()">
                    <?php foreach($available_limits as $val): ?>
                        <option value="<?php echo $val; ?>" <?php if($limit == $val) echo 'selected'; ?>>
                            <?php echo $val; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="page" value="1">
            </form>
        </div>
        <div class="total-display">
            Menampilkan <?php echo min($total_orders, $offset + $limit); ?> dari <?php echo $total_orders; ?> pesanan.
        </div>
    </div>
    
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
                    echo "<td class='status-cell'>";
                    echo "<span class='status-badge $status_class'>" . htmlspecialchars($row['status']) . "</span>";
                    echo "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td><a href='view_order.php?id=" . $row['id'] . "' class='btn-detail'>Detail</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada pesanan yang masuk.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
    <div class="pagination-footer">
        <div class="pagination">
            <?php
            $base_link = "manajemen_pesanan.php?show=$limit&page=";
            ?>

            <?php if ($page > 1): ?>
                <a href="<?php echo $base_link . ($page - 1); ?>" class="page-link">← Prev</a>
            <?php endif; ?>

            <?php 
            for ($i = 1; $i <= $total_pages; $i++): 
                if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)):
            ?>
                <a href="<?php echo $base_link . $i; ?>" class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php 
                elseif ($i == $page - 3 || $i == $page + 3):
                    echo '<span class="page-link" style="pointer-events: none;">...</span>';
                endif;
            endfor;
            ?>

            <?php if ($page < $total_pages): ?>
                <a href="<?php echo $base_link . ($page + 1); ?>" class="page-link">Next →</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php
$conn->close();
include 'admin_footer.php';
?>