<?php
// Mulai session untuk menampilkan pesan error
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Jika user sudah login, redirect ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Logika untuk menampilkan pesan error dari login_process.php
$error_msg = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'empty':
            $error_msg = 'Email dan password tidak boleh kosong.';
            break;
        case 'wrongpass':
            $error_msg = 'Password yang Anda masukkan salah.';
            break;
        case 'nouser':
            $error_msg = 'Email tidak terdaftar di sistem kami.';
            break;
        default:
            $error_msg = 'Terjadi kesalahan. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Apotek Arshaka</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Style tambahan agar form login terlihat bagus */
        .login-container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="section">
    <div class="login-container">
        <h2 style="text-align: center; color: #1D3557; margin-bottom: 30px;">Login Akun</h2>

        <?php if (!empty($error_msg)): ?>
            <div class="error-message">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="actions/login_process.php" class="contact-form" style="margin: 0; padding: 0; background: transparent; box-shadow: none;">
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Masukkan email Anda">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>

        <p style="text-align: center; margin-top: 20px; margin-bottom: 0;">
            Belum punya akun? <a href="register.php" style="color: #1e40af; font-weight: 600;">Daftar di sini</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>