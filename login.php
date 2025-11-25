<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }

// Logika Pesan Error
$error_msg = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'empty': $error_msg = 'Email dan password wajib diisi.'; break;
        case 'wrongpass': $error_msg = 'Password yang Anda masukkan salah.'; break;
        case 'nouser': $error_msg = 'Email tidak terdaftar.'; break;
        default: $error_msg = 'Terjadi kesalahan sistem.';
    }
}

// Nomor WA Admin (Sesuaikan jika berbeda)
$admin_wa = "6282188392309"; 
$pesan_lupa = "Halo Admin Apotek Arshaka, saya lupa password akun saya. Mohon bantuannya untuk reset password.";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Apotek Arshaka</title>
    <link rel="stylesheet" href="assets/css/style.css?v=5.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-body">

    <header class="auth-header">
        <a href="index.php" class="auth-logo-text">Apotek Arshaka</a>
    </header>

    <div class="auth-content">
        
        <div class="auth-left">
            <img src="assets/images/logo_apotek.jpg" alt="Ilustrasi Masuk">
            <h3>Selamat Datang Kembali</h3>
            <p>Masuk untuk mengakses riwayat pesanan dan konsultasi kesehatan Anda.</p>
        </div>

        <div class="auth-right">
            <div class="auth-card">
                <h2 class="auth-title">Masuk Akun</h2>
                <p class="auth-subtitle">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>

                <?php if (!empty($error_msg)): ?>
                    <div style="background:#fee2e2; color:#b91c1c; padding:12px; border-radius:8px; font-size:0.9rem; margin-bottom:20px; text-align:left; border: 1px solid #fca5a5;">
                        ⚠️ <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>

                <form action="actions/login_process.php" method="POST">
                    
                    <div class="auth-form-group">
                        <label class="auth-label">Alamat Email</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <input type="email" name="email" class="auth-input has-icon" placeholder="Contoh: email@anda.com" required>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-label">Password</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" name="password" class="auth-input has-icon" placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">Masuk Sekarang</button>
                </form>

                <div class="divider"><span>atau</span></div>
                
                <p style="font-size: 0.85rem; color: #6b7280;">
                    Lupa password? 
                    <a href="https://wa.me/<?php echo $admin_wa; ?>?text=<?php echo rawurlencode($pesan_lupa); ?>" target="_blank" style="color:var(--primary); font-weight:600;">
                        Hubungi Admin
                    </a>
                </p>
            </div>
        </div>
    </div>

    <footer class="auth-footer">
        &copy; 2025 Apotek Arshaka. All rights reserved.
    </footer>

</body>
</html>