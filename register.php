<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Apotek Arshaka</title>
    <link rel="stylesheet" href="assets/css/style.css?v=5.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-body">

    <header class="auth-header">
        <a href="index.php" class="auth-logo-text">Apotek Arshaka</a>
    </header>

    <div class="auth-content">
        <div class="auth-left">
            <img src="assets/images/logo_apotek.jpg" alt="Ilustrasi Daftar">
            <h3>Bergabung Bersama Kami</h3>
            <p>Nikmati kemudahan layanan kesehatan digital terpercaya untuk Anda dan keluarga.</p>
        </div>

        <div class="auth-right">
            <div class="auth-card">
                <h2 class="auth-title">Buat Akun Baru</h2>
                <p class="auth-subtitle">Sudah punya akun? <a href="login.php">Masuk disini</a></p>

                <form action="actions/register_process.php" method="POST">
                    
                    <div class="auth-form-group">
                        <label class="auth-label">Nama Lengkap</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <input type="text" name="name" class="auth-input has-icon" placeholder="Contoh: Budi Santoso" required>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-label">Jenis Kelamin</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <select name="gender" class="auth-input has-icon" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="auth-form-group">
                            <label class="auth-label">Tinggi Badan</label>
                            <div class="input-suffix-wrapper">
                                <input type="number" name="height" class="auth-input" placeholder="0" required>
                                <span class="input-suffix">cm</span>
                            </div>
                        </div>
                        <div class="auth-form-group">
                            <label class="auth-label">Berat Badan</label>
                            <div class="input-suffix-wrapper">
                                <input type="number" name="weight" class="auth-input" placeholder="0" required>
                                <span class="input-suffix">kg</span>
                            </div>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-label">Riwayat Penyakit <span style="font-weight:400; color:#999;">(Opsional)</span></label>
                        <textarea name="medical_history" class="auth-input" rows="2" placeholder="Tuliskan jika ada (contoh: Asma)"></textarea>
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-label">Alamat Email</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <input type="email" name="email" class="auth-input has-icon" placeholder="email@anda.com" required>
                        </div>
                    </div>

                    <div class="auth-form-group">
                        <label class="auth-label">Password</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" name="password" class="auth-input has-icon" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">Daftar Sekarang</button>
                </form>

                <p style="font-size: 0.8rem; color: #6c757d; margin-top: 20px; line-height: 1.5;">
                    Dengan mendaftar, Anda menyetujui <a href="#" style="color:var(--primary);">Syarat & Ketentuan</a> kami.
                </p>
            </div>
        </div>
    </div>

    <footer class="auth-footer">
        &copy; 2025 Apotek Arshaka. All rights reserved.
    </footer>

</body>
</html>