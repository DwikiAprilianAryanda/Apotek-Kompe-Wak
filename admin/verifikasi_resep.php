<?php
include 'admin_header.php';

// Ambil resep yang statusnya 'Pending'
$sql = "SELECT p.id, u.name AS customer_name, p.original_name, p.catatan, p.uploaded_at, p.file_name
        FROM prescriptions p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'Pending'
        ORDER BY p.uploaded_at ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Error Query SQL: " . htmlspecialchars($conn->error));
}
?>

<h1 class="page-title">Verifikasi Resep Masuk</h1>

<div class="admin-table-container">
    <p>Di halaman ini, Anda dapat melihat daftar resep yang diunggah oleh pelanggan dan menunggu untuk diverifikasi dan diproses.</p>
    
    <table>
        <thead>
            <tr>
                <th>ID Resep</th>
                <th>Nama Pelanggan</th>
                <th>Nama File</th>
                <th>Catatan</th>
                <th>Diunggah Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>#" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                    echo "<td><a href='../uploads/resep/" . htmlspecialchars($row['file_name']) . "' target='_blank'>" . htmlspecialchars($row['original_name']) . "</a></td>";
                    echo "<td>" . (empty($row['catatan']) ? '-' : htmlspecialchars($row['catatan'])) . "</td>";
                    echo "<td>" . $row['uploaded_at'] . "</td>";
                    // LINK PROSES RESEP
                    echo "<td><a href='proses_resep.php?id=" . $row['id'] . "' class='btn-warning'>Proses</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada resep yang menunggu verifikasi.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include 'admin_footer.php';
?>