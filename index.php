<?php 
include 'includes/header.php'; 
include 'includes/db_connect.php'; // Koneksi DB
include 'includes/settings.php';   // Load Helper Settings
?>

<section class="hero-full-screen" style="background: url('<?php echo get_setting('home_bg_image'); ?>') no-repeat center center; background-size: cover; background-attachment: fixed;">
    <div class="hero-overlay"></div>
    
    <div class="hero-content">
        <h1 class="reveal-zoom" style="font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; letter-spacing: -1px; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">
            <?php echo htmlspecialchars(get_setting('home_hero_title')); ?>
        </h1>
        <p class="reveal-up delay-200" style="font-size: 1.25rem; margin-bottom: 40px; font-weight: 300; text-shadow: 1px 1px 5px rgba(0,0,0,0.5);">
            <?php echo htmlspecialchars(get_setting('home_hero_subtitle')); ?>
        </p>
        <a href="produk.php" class="btn btn-hero-cta reveal-up delay-300">
            <span>Belanja Sekarang</span>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </a>
    </div>
</section>

<div class="section-wrapper" id="layanan" style="background-color: #f9fbfd;">
    <div class="container">
        <h2 class="section-title reveal-up delay-100">Layanan Kami</h2>
        <p class="section-subtitle reveal-up delay-200">Kami menyediakan solusi kesehatan lengkap untuk kebutuhan keluarga Anda.</p>
        
        <div class="grid-3">
            <div class="service-card reveal-up delay-100">
                <img src="<?php echo get_setting('service_icon_1'); ?>" alt="Service 1" class="service-icon">
                <h3><?php echo htmlspecialchars(get_setting('service_title_1')); ?></h3>
                <p><?php echo htmlspecialchars(get_setting('service_desc_1')); ?></p>
            </div>

            <div class="service-card reveal-up delay-200">
                <img src="<?php echo get_setting('service_icon_2'); ?>" alt="Service 2" class="service-icon"> 
                <h3><?php echo htmlspecialchars(get_setting('service_title_2')); ?></h3>
                <p><?php echo htmlspecialchars(get_setting('service_desc_2')); ?></p>
            </div>

            <div class="service-card reveal-up delay-300">
                <img src="<?php echo get_setting('service_icon_3'); ?>" alt="Service 3" class="service-icon"> 
                <h3><?php echo htmlspecialchars(get_setting('service_title_3')); ?></h3>
                <p><?php echo htmlspecialchars(get_setting('service_desc_3')); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="about-section-wrapper">
    <div class="container about-container">
        
        <div class="about-text-content reveal-left">
            <h2 class="about-title"><?php echo htmlspecialchars(get_setting('home_about_title')); ?></h2>
            <span class="about-subtitle"><?php echo htmlspecialchars(get_setting('home_about_subtitle')); ?></span>
            
            <p class="about-desc">
                <?php echo nl2br(htmlspecialchars(get_setting('home_about_desc'))); ?>
            </p>
        </div>

        <div class="about-image-wrapper">
            <img src="<?php echo get_setting('home_about_image'); ?>" alt="Tentang Kami" class="about-img-main reveal-special-img">
        </div>

    </div>
</div>

<div class="consultation-section" id="konsultasi">
    <div class="consult-overlay"></div>

    <div class="container consult-grid">
        <div class="consult-content reveal-left">
            <h2>Konsultasi Apoteker</h2>
            <span class="consult-subtitle">Jangan ragu untuk bertanya tentang kesehatanmu.</span>
            
            <p class="consult-desc">
                Bingung dengan gejala yang Anda rasakan? Isi formulir di samping, dan Apoteker kami akan segera membantu menjawab kebutuhan Anda via WhatsApp.
            </p>
            
            <ul class="consult-features">
                <li><span class="check-icon">✓</span> Respon cepat & ramah</li>
                <li><span class="check-icon">✓</span> Privasi terjamin aman</li>
                <li><span class="check-icon">✓</span> Tanpa biaya konsultasi (Gratis)</li>
            </ul>
        </div>

        <div class="consult-card reveal-right">
            <form id="waForm" onsubmit="sendToWhatsapp(event)" class="consult-form">
                <label>Nama Lengkap</label>
                <input type="text" id="waName" class="consult-input" placeholder="Contoh: Budi Santoso" required>

                <label>Nomor WhatsApp</label>
                <input type="text" id="waPhone" class="consult-input" placeholder="Contoh: 08123456789" required>

                <label>Alamat Email (Opsional)</label>
                <input type="email" id="waEmail" class="consult-input" placeholder="email@contoh.com">

                <label>Pesan / Request Khusus</label>
                <textarea id="waMessage" class="consult-input" rows="4" placeholder="Contoh: Saya ingin konsultasi obat..." required></textarea>

                <button type="submit" class="btn-wa-consult">
                    Kirim ke WhatsApp
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.463 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>