<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">
<div class="section" style="max-width: 500px;">
    <h2>Registrasi Akun Baru</h2>
    <p style="text-align: center; margin-bottom: 30px;">Silakan isi form di bawah ini untuk mendaftar.</p>
    
    <div class="contact-form">
        <form action="actions/register_process.php" method="POST">
            <div>
                <label for="name">Nama Lengkap:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; margin-bottom: 0;">
            Sudah punya akun? <a href="login.php" style="color: #1e40af; font-weight: 600;">Login di sini</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>