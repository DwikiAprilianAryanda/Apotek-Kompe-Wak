<?php
session_start();
include 'includes/db_connect.php';
if (!isset($_SESSION['user_id'])) { header("Location: /login.php"); exit; }

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, address, phone_number, no_ktp, height, weight FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<?php include 'includes/header.php'; ?>

<div class="section-wrapper">
    <div class="container">
        <h2 class="section-title">Profil Saya</h2>
        
        <div class="form-wrapper" style="border: none; padding: 0;">
            <?php if (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px;">Profil berhasil diperbarui!</div>
            <?php endif; ?>

            <form action="/actions/update_profile.php" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>NIK (KTP)</label>
                    <input type="text" name="no_ktp" class="form-control" value="<?php echo htmlspecialchars($user['no_ktp'] ?? ''); ?>">
                </div>

                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" name="height" class="form-control" value="<?php echo htmlspecialchars($user['height']); ?>">
                    </div>
                    <div>
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="weight" class="form-control" value="<?php echo htmlspecialchars($user['weight']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly style="background: #eee;">
                </div>

                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                </div>

                <div class="form-group">
                    <label>Alamat Pengiriman</label>
                    <textarea name="address" class="form-control" rows="4"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">SIMPAN PERUBAHAN</button>
                <a href="actions/logout.php" class="btn btn-outline" style="margin-left: 10px;">LOGOUT</a>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>