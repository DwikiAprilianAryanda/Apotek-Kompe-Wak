<?php
session_start();
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
// LOGIKA FOTO PROFIL
$foto_profil_default = 'assets/images/logo_apotek.jpg'; // Gambar bawaan
$foto_profil_user = $foto_profil_default;

if (!empty($user['profile_pic'])) {
    $path_cek = 'uploads/profiles/' . $user['profile_pic'];
    // Cek apakah file fisik benar-benar ada di server
    if (file_exists($path_cek)) {
        $foto_profil_user = $path_cek;
    }
}
$stmt->close();
?>

<div class="section-wrapper bg-light">
    <div class="container">
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
            <div class="alert-profile-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Data profil berhasil diperbarui!
            </div>
        <?php endif; ?>

        <form action="actions/update_profile.php" method="POST" enctype="multipart/form-data" class="profile-grid">
            
            <aside class="profile-sidebar">
                
                <div class="profile-card card-photo">
                    <div class="profile-img-wrapper">
                        <img src="<?php echo $foto_profil_user; ?>" alt="Foto Profil" id="profilePreview">
                    </div>
                    
                    <label for="upload_foto" class="btn-upload-photo">
                        Pilih Foto
                        <input type="file" id="upload_foto" name="profile_pic" accept="image/*" style="display:none;" onchange="previewImage(event)">
                    </label>
                    
                    <p class="photo-helper">
                        Besar file: maksimum 10MB. Ekstensi: .JPG .JPEG .PNG
                    </p>
                </div>

                <a href="actions/logout.php" class="btn-security btn-logout-profile">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Keluar
                </a>

            </aside>

            <main class="profile-content">
                <div class="profile-card card-form">
                    
                    <div class="profile-section-header">
                        <h3>Ubah Biodata Diri</h3>
                    </div>

                    <div class="form-row">
                        <label>Nama Lengkap</label>
                        <div class="input-group">
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="profile-input" required>
                            <span class="input-hint">Nama lengkap sesuai KTP agar memudahkan verifikasi.</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Jenis Kelamin</label>
                        <div class="input-group">
                            <select name="gender" class="profile-input">
                                <option value="Laki-laki" <?php if($user['gender'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php if($user['gender'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Fisik</label>
                        <div class="input-group grid-2">
                            <div class="input-with-unit">
                                <input type="number" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" class="profile-input">
                                <span class="unit">cm</span>
                            </div>
                            <div class="input-with-unit">
                                <input type="number" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" class="profile-input">
                                <span class="unit">kg</span>
                            </div>
                        </div>
                    </div>

                    <div style="margin: 30px 0; border-bottom: 1px solid #f0f0f0;"></div>

                    <div class="profile-section-header">
                        <h3>Ubah Kontak</h3>
                    </div>

                    <div class="form-row">
                        <label>Email</label>
                        <div class="input-group">
                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="profile-input disabled" readonly>
                            <span class="verified-badge">Terverifikasi</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Nomor HP</label>
                        <div class="input-group">
                            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" class="profile-input" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Alamat</label>
                        <div class="input-group">
                            <textarea name="address" class="profile-input" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save-profile">Simpan Perubahan</button>
                    </div>

                </div>
            </main>

        </form>
    </div>
</div>

<script>
// Script Preview Image Sederhana
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('profilePreview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php include 'includes/footer.php'; ?>