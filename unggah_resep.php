<?php
include 'includes/header.php';
include 'includes/db_connect.php';

// KEAMANAN
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=loginrequired");
    exit;
}

// Notifikasi
$message = '';
$alert_class = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'uploaded') {
        $message = 'Resep berhasil dikirim! Tim kami akan segera memprosesnya.';
        $alert_class = 'alert-success';
    } else if ($_GET['status'] == 'uploadfail') {
        $message = 'Gagal mengunggah. Pastikan file JPG/PNG/PDF maks 5MB.';
        $alert_class = 'alert-error';
    } else if ($_GET['status'] == 'dberror') {
        $message = 'Kesalahan sistem. Silakan coba lagi.';
        $alert_class = 'alert-error';
    }
}
?>

<div class="section-wrapper bg-light">
    <div class="container">
        
        <div class="upload-page-header">
            <h2>Layanan Resep Digital</h2>
            <p>Tebus obat resep dokter tanpa antre, langsung dari rumah.</p>
        </div>

        <div class="upload-grid-layout">
            
            <div class="upload-main-col">
                
                <?php if ($message): ?>
                    <div class="custom-alert <?php echo $alert_class; ?>">
                        <span><?php echo $message; ?></span>
                    </div>
                <?php endif; ?>

                <div class="upload-card">
                    <div class="card-header-simple">
                        <h3>Formulir Upload</h3>
                    </div>

                    <form action="actions/unggah_resep_process.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="form-group-upload">
                            <label class="upload-label-title">File Resep</label>
                            <div class="upload-zone" id="drop-zone">
                                <input type="file" id="resep_file" name="resep_file" class="file-input" accept="image/png, image/jpeg, application/pdf" required onchange="updateFileName()">
                                
                                <div class="upload-content">
                                    <div class="upload-icon-circle">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                    </div>
                                    <p class="upload-text">Klik atau seret foto resep ke sini</p>
                                    <p class="upload-hint">JPG, PNG, PDF (Maks. 5MB)</p>
                                    <p id="file-name-display" class="file-selected-text"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-upload">
                            <label for="catatan" class="upload-label-title">Catatan untuk Apoteker</label>
                            <textarea id="catatan" name="catatan" class="upload-textarea" rows="4" placeholder="Contoh: Mohon tebus obat antibiotik saja sebanyak 1 strip."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-upload-submit">
                            Kirim Resep Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <aside class="upload-sidebar-col">
                
                <div class="info-card">
                    <h4>Cara Kerja</h4>
                    <ul class="steps-list">
                        <li>
                            <div class="step-num">1</div>
                            <div class="step-text">
                                <strong>Foto Resep</strong>
                                <p>Pastikan tulisan dokter terlihat jelas dan tidak buram.</p>
                            </div>
                        </li>
                        <li>
                            <div class="step-num">2</div>
                            <div class="step-text">
                                <strong>Unggah & Kirim</strong>
                                <p>Isi form di samping dan kirimkan kepada kami.</p>
                            </div>
                        </li>
                        <li>
                            <div class="step-num">3</div>
                            <div class="step-text">
                                <strong>Verifikasi & Bayar</strong>
                                <p>Apoteker akan mengecek stok dan total harga.</p>
                            </div>
                        </li>
                        <li>
                            <div class="step-num">4</div>
                            <div class="step-text">
                                <strong>Obat Dikirim</strong>
                                <p>Setelah pembayaran, obat langsung dikirim ke alamat Anda.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="info-card highlight-card">
                    <h4>Kenapa di Arshaka?</h4>
                    <div class="feature-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Privasi Terjamin</span>
                    </div>
                    <div class="feature-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        <span>Apoteker Berlisensi</span>
                    </div>
                    <div class="feature-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18v-8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8z"></path>
                            <path d="M3 10 12 3l9 7"></path>
                            <path d="M9 21V9"></path>
                            <path d="M15 21V9"></path>
                        </svg>
                        <span>Ambil di Apotek (Tanpa Antre)</span>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</div>

<script>
    function updateFileName() {
        const input = document.getElementById('resep_file');
        const display = document.getElementById('file-name-display');
        const zone = document.getElementById('drop-zone');
        
        if (input.files && input.files.length > 0) {
            display.textContent = input.files[0].name;
            display.style.display = "inline-block";
            zone.style.borderColor = "#1b3270";
            zone.style.backgroundColor = "#f0f7ff";
        } else {
            display.style.display = "none";
        }
    }
</script>

<?php 
$conn->close();
include 'includes/footer.php'; 
?>