<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';
// Load Helper Settings
include '../includes/settings.php';

// Notifikasi Sukses
$msg = '';
if (isset($_GET['status']) && $_GET['status'] == 'updated') {
    $msg = "
    <div class='alert-box success'>
        <svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'><polyline points='20 6 9 17 4 12'></polyline></svg>
        <span>Pengaturan website berhasil diperbarui!</span>
    </div>";
}
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Pengaturan Website</h1>
        <p class="page-subtitle">Sesuaikan tampilan Beranda, informasi kontak, dan lainnya.</p>
    </div>
</div>

<?php echo $msg; ?>

<form action="../actions/update_settings_process.php" method="POST" enctype="multipart/form-data">
    
    <div class="settings-card">
        <div class="card-header">
            <h3>
                <div class="icon-header bg-blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                </div>
                Banner Utama (Hero)
            </h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Judul Utama (Headline)</label>
                <input type="text" name="settings[home_hero_title]" class="form-input" value="<?php echo htmlspecialchars(get_setting('home_hero_title')); ?>" placeholder="Contoh: APOTEK ARSHAKA">
            </div>
            
            <div class="form-group">
                <label>Sub Judul</label>
                <input type="text" name="settings[home_hero_subtitle]" class="form-input" value="<?php echo htmlspecialchars(get_setting('home_hero_subtitle')); ?>" placeholder="Contoh: Solusi Kesehatan Keluarga">
            </div>

            <div class="form-group">
                <label>Gambar Background</label>
                <div class="preview-box wide">
                    <img id="img_hero" src="../<?php echo get_setting('home_bg_image'); ?>" onerror="this.src='../assets/images/default.jpg'">
                </div>
                <div class="file-input-wrapper mt-10">
                    <input type="file" name="home_bg_image" id="bg_file" class="input-file" accept="image/*" onchange="previewImage(this, 'img_hero')">
                    <label for="bg_file" class="btn-upload">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Ganti Background
                    </label>
                    <span class="file-info">Format: JPG/PNG/WEBP (Maks 2MB)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="settings-card">
        <div class="card-header">
            <h3>
                <div class="icon-header bg-green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                </div>
                Tentang Kami
            </h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label>Judul Bagian</label>
                    <input type="text" name="settings[home_about_title]" class="form-input" value="<?php echo htmlspecialchars(get_setting('home_about_title')); ?>">
                </div>
                <div class="form-group">
                    <label>Sub Judul (Kecil)</label>
                    <input type="text" name="settings[home_about_subtitle]" class="form-input" value="<?php echo htmlspecialchars(get_setting('home_about_subtitle')); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Lengkap</label>
                <textarea name="settings[home_about_desc]" class="form-input" rows="5"><?php echo htmlspecialchars(get_setting('home_about_desc')); ?></textarea>
            </div>

            <div class="form-group">
                <label>Gambar Samping</label>
                <div class="upload-flex-container">
                    <div class="preview-box square">
                        <img id="img_about" src="../<?php echo get_setting('home_about_image'); ?>" onerror="this.src='../assets/images/default.jpg'">
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" name="home_about_image" id="about_file" class="input-file" accept="image/*" onchange="previewImage(this, 'img_about')">
                        <label for="about_file" class="btn-upload">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Ganti Gambar
                        </label>
                        <span class="file-info d-block mt-5">Maks 2MB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="settings-card">
        <div class="card-header">
            <h3>
                <div class="icon-header bg-purple">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                Layanan Kami
            </h3>
        </div>
        <div class="card-body">
            <div class="services-grid">
                <?php for($i=1; $i<=3; $i++): ?>
                <div class="service-item">
                    <div class="service-header">
                        <span class="service-badge">Layanan #<?php echo $i; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label>Judul Layanan</label>
                        <input type="text" name="settings[service_title_<?php echo $i; ?>]" class="form-input" value="<?php echo htmlspecialchars(get_setting('service_title_'.$i)); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="settings[service_desc_<?php echo $i; ?>]" class="form-input" rows="3"><?php echo htmlspecialchars(get_setting('service_desc_'.$i)); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Ikon Layanan</label>
                        <div class="icon-upload-row">
                            <div class="preview-box icon-preview">
                                <img id="img_service_<?php echo $i; ?>" src="../<?php echo get_setting('service_icon_'.$i); ?>" onerror="this.src='../assets/images/default.jpg'">
                            </div>
                            <div class="file-input-wrapper">
                                <label class="btn-upload-mini">
                                    Pilih Ikon
                                    <input type="file" name="service_icon_<?php echo $i; ?>" class="input-file" accept="image/*" onchange="previewImage(this, 'img_service_<?php echo $i; ?>')">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <div class="settings-card">
        <div class="card-header">
            <h3>
                <div class="icon-header bg-orange">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                Footer & Kontak
            </h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Brand Footer</label>
                    <input type="text" name="settings[footer_brand]" class="form-input" value="<?php echo htmlspecialchars(get_setting('footer_brand')); ?>">
                </div>
                <div class="form-group">
                    <label>Teks Copyright</label>
                    <input type="text" name="settings[footer_copyright]" class="form-input" value="<?php echo htmlspecialchars(get_setting('footer_copyright')); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Footer</label>
                <textarea name="settings[footer_desc]" class="form-input" rows="2"><?php echo htmlspecialchars(get_setting('footer_desc')); ?></textarea>
            </div>

            <div class="form-group">
                <label>Alamat (Gunakan &lt;br&gt; untuk baris baru)</label>
                <textarea name="settings[footer_address]" class="form-input" rows="2"><?php echo htmlspecialchars(get_setting('footer_address')); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>No. Telepon / WhatsApp</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon">📞</span>
                        <input type="text" name="settings[footer_phone]" class="form-input has-icon" value="<?php echo htmlspecialchars(get_setting('footer_phone')); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Support</label>
                    <div class="input-icon-wrap">
                        <span class="input-icon">✉️</span>
                        <input type="text" name="settings[footer_email]" class="form-input has-icon" value="<?php echo htmlspecialchars(get_setting('footer_email')); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="save-bar">
        <div class="save-info">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <span>Pastikan semua data sudah benar sebelum menyimpan.</span>
        </div>
        <button type="submit" class="btn-save-floating">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Simpan Perubahan
        </button>
    </div>

</form>

<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.getElementById(previewId);
                if(img) { img.src = e.target.result; }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    /* Reset & Base */
    .settings-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .card-header {
        padding: 15px 25px;
        border-bottom: 1px solid #f1f5f9;
        background-color: #fff;
    }
    .card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .icon-header {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-blue { background: #eff6ff; color: #2563eb; }
    .bg-green { background: #f0fdf4; color: #16a34a; }
    .bg-purple { background: #f5f3ff; color: #7c3aed; }
    .bg-orange { background: #fff7ed; color: #ea580c; }

    .card-body { padding: 25px; }
    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }
    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
        box-sizing: border-box; 
    }
    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    /* GRID UNTUK INPUT TEXT */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* FLEXBOX KHUSUS UPLOAD GAMBAR AGAR RAPAT */
    .upload-flex-container {
        display: flex;
        align-items: center;
        gap: 20px; /* Jarak pas antara gambar dan tombol */
        justify-content: flex-start;
    }

    /* Image Preview Styling */
    .preview-box {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .preview-box.wide { height: 180px; width: 100%; margin-bottom: 10px; }
    .preview-box.square { height: 140px; width: 140px; flex-shrink: 0; }
    .preview-box.icon-preview { height: 50px; width: 50px; padding: 5px; border-style: solid; border-color: #e2e8f0; }
    .icon-preview img { object-fit: contain; }

    /* Custom File Upload Button */
    .file-input-wrapper { display: flex; flex-direction: column; gap: 5px; }
    .input-file { display: none; }
    .btn-upload {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #fff;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
        width: fit-content;
    }
    .btn-upload:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
    .btn-upload-mini {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
    }
    .btn-upload-mini:hover { background: #f1f5f9; }
    .file-info { font-size: 0.8rem; color: #94a3b8; }
    .d-block { display: block; }
    .mt-5 { margin-top: 5px; }
    .mt-10 { margin-top: 10px; }

    /* Services Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .service-item {
        background: #f8fafc;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .service-header { margin-bottom: 15px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 10px; }
    .service-badge {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        background: #e0e7ff;
        color: #4338ca;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .icon-upload-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Inputs with Icons */
    .input-icon-wrap { position: relative; }
    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.1rem;
        pointer-events: none;
    }
    .form-input.has-icon { padding-left: 40px; }

    /* Floating Save Bar */
    .save-bar {
        position: sticky;
        bottom: 20px;
        background: #1e293b;
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        margin-top: 30px;
        z-index: 999;
    }
    .save-info { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #cbd5e1; }
    .btn-save-floating {
        background: #2563eb;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        transition: background 0.2s;
    }
    .btn-save-floating:hover { background: #1d4ed8; }

    /* Alert Box */
    .alert-box {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
    }
    .alert-box.success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }

    /* Responsive */
    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .save-bar { flex-direction: column; gap: 15px; text-align: center; }
        .btn-save-floating { width: 100%; justify-content: center; }
    }
</style>

<?php include 'admin_footer.php'; ?>