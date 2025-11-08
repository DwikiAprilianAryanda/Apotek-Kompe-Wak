<?php
// Tidak perlu session_start() di sini karena sudah ada di header.php
include 'includes/header.php'; 

// Jika pengguna sudah login, redirect ke index (mencegah loop)
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Logika untuk menampilkan pesan error (dari actions/login_process.php)
$error_msg = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'wrongpass') {
        $error_msg = 'Password yang Anda masukkan salah.';
    } else if ($_GET['error'] == 'nouser') {
        $error_msg = 'Email tidak terdaftar.';
    } else if ($_GET['error'] == 'empty') {
        $error_msg = 'Email dan Password tidak boleh kosong.';
    } else if ($_GET['error'] == 'loginrequired') {
        $error_msg = 'Anda harus login untuk mengakses halaman tersebut.';
    } else {
        $error_msg = 'Terjadi kesalahan. Silakan coba lagi.';
    }
}
?>

<div class="section" style="padding-top: 50px; padding-bottom: 50px;">
    <h2 style="text-align: center; color: #1D3557; margin-bottom: 30px;">Login Pelanggan</h2>
    
    <div style="max-width: 450px; margin: 0 auto; padding: 40px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-top: 5px solid #1D3557;">
        
        <?php if (!empty($error_msg)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="actions/login_process.php" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            
            <div>
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <div>
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1.1rem; margin-top: 10px;">
                Login
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 25px; color: #666; font-size: 0.95rem;">
            Belum punya akun? <a href="register.php" style="color: #1D3557; font-weight: 600; text-decoration: none;">Daftar di sini</a>.
        </p>
    </div>
    
</div>

<?php include 'includes/footer.php'; ?>