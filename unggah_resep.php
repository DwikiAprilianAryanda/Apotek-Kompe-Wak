<?php
// Tampilkan error
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sertakan file header (yang akan memulai session)
include 'includes/header.php';
include 'includes/db_connect.php';

// KEAMANAN: Pastikan pengguna sudah login
// Jika tidak, tendang ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=loginrequired");
    exit;
}

// --- Logika untuk menampilkan pesan notifikasi ---
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'uploaded') {
        $message = '<div class="alert-success">Resep Anda berhasil diunggah! Admin akan segera memverifikasinya.</div>';
    } else if ($_GET['status'] == 'uploadfail') {
        $message = '<div class="alert-danger">Gagal mengunggah file. Pastikan file adalah JPG/PNG/PDF dan ukurannya tidak terlalu besar.</div>';
    } else if ($_GET['status'] == 'dberror') {
        $message = '<div class="alert-danger">Terjadi kesalahan saat menyimpan data resep.</div>';
    }
}
?>

<link rel="stylesheet" href="assets/css/admin_style.css"> 

<div class="section">
    <h2 style="text-align: center; color: #1D3557; margin-bottom: 30px;">Unggah Resep Dokter</h2>
    
    <div class="admin-form-container" style="max-width: 600px; margin: 30px auto;">

        <p style="text-align: center; margin-bottom: 25px; color: #333;">
            Punya resep dari dokter? Unggah foto resep Anda di sini. Tim farmasi kami akan membantu menyiapkan obat Anda.
        </p>

        <?php echo $message; ?>

        <form action="actions/unggah_resep_process.php" method="POST" enctype="multipart/form-data">
            
            <div style="margin-bottom: 20px;">
                <label for="resep_file">File Resep (Foto atau PDF):</label>
                <input type="file" id="resep_file" name="resep_file" required 
                       accept="image/png, image/jpeg, application/pdf" 
                       style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 100%; box-sizing: border-box;">
                <p style="font-size: 0.9em; color: #666; margin-top: 5px;">*Tipe file: .jpg, .png, atau .pdf. Ukuran maks: 5MB.</p>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="catatan">Catatan Tambahan (Opsional):</label>
                <textarea id="catatan" name="catatan" rows="4" placeholder="Contoh: Tebus setengah resep saja."></textarea>
            </div>
            
            <button type="submit" class="btn-primary" style="width: 100%;">Unggah Resep</button>
        </form>
    </div>
</div>

<style>
.alert-danger {
    background: #f8d7da; 
    color: #721c24; 
    padding: 15px; 
    border-radius: 10px; 
    margin: 20px 0;
    font-weight: 600; 
    text-align: center;
    border: 1px solid #f5c6cb;
}
</style>

<?php 
$conn->close();
include 'includes/footer.php'; 
?>