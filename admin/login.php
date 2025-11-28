<?php
session_start();
// Jika sudah login sebagai admin, langsung ke dashboard
if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'receptionist')) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - Apotek Arshaka</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- CSS Reset & Variables --- */
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --bg-dark: #0f172a;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --red-bg: #fef2f2;
            --red-text: #b91c1c;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            background-image: radial-gradient(circle at top right, #1e293b 0%, #0f172a 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
        }

        /* --- Card Style --- */
        .login-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* --- Logo & Header --- */
        .logo-wrapper {
            width: 64px;
            height: 64px;
            background: #eff6ff;
            color: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        p.subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        /* --- Form Elements --- */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.3s;
        }

        .form-input {
            width: 100%;
            padding: 12px 12px 12px 42px; /* Space for icon */
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* Change icon color on focus */
        .input-wrapper:focus-within .input-icon {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
        }

        /* --- Alert Box --- */
        .alert-box {
            background: var(--red-bg);
            border: 1px solid #fecaca;
            color: var(--red-text);
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
            line-height: 1.4;
        }

        /* --- Footer Link --- */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 25px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-wrapper">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        
        <h2>Administrator Panel</h2>
        <p class="subtitle">Masuk untuk mengelola apotek Anda.</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>
                    <?php 
                    if ($_GET['error'] == 'empty') echo "Email dan password wajib diisi.";
                    elseif ($_GET['error'] == 'wrong') echo "Email atau password salah.";
                    elseif ($_GET['error'] == 'access') echo "Akun ini tidak memiliki akses Admin.";
                    elseif ($_GET['error'] == 'use_admin_login') echo "Akses ditolak. Silakan login Admin dari halaman ini.";
                    else echo "Terjadi kesalahan login.";
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <form action="auth_process.php" method="POST">
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <input type="email" name="email" class="form-input" placeholder="admin@apotek.com" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-login">Masuk Dashboard</button>
        </form>

        <a href="../index.php" class="back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali ke Toko
        </a>
    </div>

</body>
</html>