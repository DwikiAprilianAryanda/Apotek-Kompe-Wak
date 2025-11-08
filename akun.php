<?php
session_start();
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, address, phone_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'updated') {
    $message = '<div class="alert-success">Profil Anda berhasil diperbarui!</div>';
}
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">

<div class="section">
    <h2>Akun Saya</h2>

    <div class="account-container" style="display: grid; grid-template-columns: 250px 1fr; gap: 30px; margin-top: 30px;">
        <div class="account-sidebar" style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <a href="akun.php" class="btn btn-primary" style="display: block; margin-bottom: 10px; text-align: center;">Update Profil</a>
            <a href="riwayat_pesanan.php" class="btn btn-secondary" style="display: block; text-align: center;">Riwayat Pesanan</a>
        </div>

        <div class="account-content" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <h3 style="color: #1e40af; margin-bottom: 10px;">Update Profil</h3>
            <p style="margin-bottom: 20px; color: #666;">Kelola informasi profil dan alamat Anda.</p>
            
            <?php echo $message; ?>

            <form action="/actions/update_profile.php" method="POST" class="contact-form" style="margin: 0; padding: 0; background: transparent; box-shadow: none;">
                <div>
                    <label for="name">Nama Lengkap:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div>
                    <label for="phone_number">No. Telepon:</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                </div>
                <div>
                    <label for="address">Alamat Pengiriman:</label>
                    <textarea id="address" name="address" rows="4"><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>