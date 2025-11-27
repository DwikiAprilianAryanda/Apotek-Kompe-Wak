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
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #2c3e50;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-box h2 {
            margin: 0 0 20px;
            color: #1D3557;
        }
        .login-box p {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.9em;
        }
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        .input-group input:focus {
            border-color: #1D3557;
            outline: none;
        }
        .btn-login {
            background: #1D3557;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #457B9D;
        }
        .alert {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            border: 1px solid #fca5a5;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #888;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .back-link:hover { color: white; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Administrator Panel</h2>
        <p>Silakan masuk untuk mengelola apotek.</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert">
                <?php 
                if ($_GET['error'] == 'empty') echo "Email dan password wajib diisi.";
                elseif ($_GET['error'] == 'wrong') echo "Email atau password salah.";
                elseif ($_GET['error'] == 'access') echo "Akun ini bukan akun Admin!";
                ?>
            </div>
        <?php endif; ?>

        <form action="auth_process.php" method="POST">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="admin@apotek.com" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">MASUK</button>
        </form>
    </div>

</body>
</html>