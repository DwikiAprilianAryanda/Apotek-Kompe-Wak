<?php include 'includes/header.php'; ?>

<section id="about" class="about-section">
    <div class="container section-content text-center reveal">
      <h2 class="section-title-atas">Selamat Datang di Apotek Arshaka</h2>
      <p class="section-text" style="font-size: 1.25rem;">Solusi kesehatan terpercaya Anda di Tenggarong.</p>
      <a href="#info-contact" class="section-button">Hubungi Kami</a>
    </div>
    <video class="hero-animation" autoplay loop muted playsinline>
        <source src="assets/images/obat.mp4" type="video/mp4"> 
        Maaf, browser Anda tidak mendukung tag video.
    </video>
</section>

<section id="layanan" class="section">
    <h2 class="reveal">Layanan Kami</h2>
    <p class="reveal">Kami hadir untuk melayani kebutuhan kesehatan Anda dengan profesional.</p>
    
    <div class="grid">
        <div class="card reveal-left">
            <h3>Penjualan Obat</h3>
            <p>Menyediakan obat bebas (OTC) dan obat resep dokter yang lengkap dan terjamin keasliannya.</p>
            <a href="produk.php" class="btn btn-primary" style="margin: 0 20px 20px;">Lihat Produk</a>
        </div>
        
        <div class="card reveal">
            <h3>Unggah Resep Online</h3>
            <p>Tidak sempat ke apotek? Cukup unggah resep Anda melalui website dan kami akan siapkan obatnya.</p>
            <a href="unggah_resep.php" class="btn btn-primary" style="margin: 0 20px 20px;">Unggah Sekarang</a>
        </div>
        
        <div class="card reveal-right">
            <h3>Konsultasi Apoteker</h3>
            <p>Tim apoteker kami siap membantu memberikan informasi dan konsultasi mengenai penggunaan obat Anda.</p>
            <a href="#info-contact" class="btn btn-secondary" style="margin: 0 20px 20px;">Hubungi Kami</a>
        </div>
    </div>
</section>

<section id="tentang-kami" class="section" style="background: #fff;"> <h2 class="reveal">Tentang Kami</h2>
    <div class="about-us-container">
        <div class="about-us-image reveal-left">
            <img src="assets/images/logo_apotek.jpg" alt="Tampak Depan Apotek Arshaka" class="responsive-img">
        </div>
        <div class="about-us-text reveal-right">
            <h3 class="info-subtitle">Apotek Terpercaya di Tenggarong</h3>
            <p style="text-align: left;">Apotek Arshaka adalah apotek lokal Anda di Jantung Tenggarong, berlokasi strategis di Jl. Loa Ipuh. Kami berkomitmen untuk menyediakan layanan kefarmasian yang berkualitas, obat-obatan yang lengkap, dan harga yang terjangkau bagi masyarakat.</p>
            <p style="text-align: left;">Dengan jam operasional dari jam 8 pagi hingga 11 malam, kami siap melayani Anda kapanpun Anda membutuhkan. Kesehatan Anda adalah prioritas kami.</p>
        </div>
    </div>
</section>

<section id="info-contact" class="info-contact-section section">
    <div class="container section-content">
        <h2 class="section-title reveal" style="color: #1D3557;">Informasi Kontak & Lokasi</h2>
        
        <div class="info-container">
            <div class="info-left reveal-left">
                <h3 class="info-subtitle">Alamat Apotek</h3>
                <p class="info-address">Kec. Tenggarong, Kabupaten Kutai Kartanegara, Kalimantan Timur<br>
                   HXMF+8WR, Jl. Loa Ipuh, Loa Ipuh, Kec. Tenggarong, Kabupaten Kutai Kartanegara, Kalimantan Timur 75513</p>
                
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.712833350147!2d116.97220407356798!3d-0.41664049957927407!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df6654869ab89cf%3A0x9a7cc00a781e9177!2sAPOTEK%20ARSHAKA!5e0!3m2!1sid!2sid!4v1762665565064!5m2!1sid!2sid" 
                            width="100%" height="100%" style="border:0;" 
                            allowfullscreen="" loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="info-right reveal-right">
                <h3 class="info-subtitle">Hubungi Kami</h3>
                <p class="info-text" style="text-align: left;">Silakan hubungi kami untuk konsultasi obat lebih lanjut.</p>
                <div class="contact-info">
                    <p><strong>Email:</strong> <a href="mailto:arshaka.apotek@gmail.com" class="contact-link">arshaka.apotek@gmail.com</a></p>
                    <p><strong>Jam Operasional:</strong> 8 pagi - 11 malam</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>