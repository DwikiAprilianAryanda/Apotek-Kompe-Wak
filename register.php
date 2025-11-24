<?php include 'includes/header.php'; ?>

<div class="section-wrapper bg-light">
    <div class="container">
        <div class="form-wrapper">
            <h2 class="text-center" style="margin-bottom: 30px; color: #1b3270;">Buat Akun Baru</h2>
            
            <form action="actions/register_process.php" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required placeholder="Sesuai KTP">
                </div>

                <div class="form-group">
                    <label>Nomor KTP (NIK)</label>
                    <input type="number" name="no_ktp" class="form-control" required placeholder="16 digit NIK">
                </div>

                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" name="height" class="form-control" placeholder="Contoh: 170">
                    </div>
                    <div>
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="weight" class="form-control" placeholder="Contoh: 65">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary full-width" style="margin-top: 10px;">DAFTAR SEKARANG</button>
            </form>
            
            <p class="text-center" style="margin-top: 20px;">
                Sudah punya akun? <a href="login.php" style="color: var(--secondary); font-weight: bold;">Masuk Disini</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>