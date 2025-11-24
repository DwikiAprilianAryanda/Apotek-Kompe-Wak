<?php include 'includes/header.php'; ?>

<section class="hero-full-screen">
    <div class="hero-overlay"></div>
    
    <div class="hero-content">
        <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; letter-spacing: -1px; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">
            APOTEK ARSHAKA
        </h1>
        <p style="font-size: 1.25rem; margin-bottom: 40px; font-weight: 300; text-shadow: 1px 1px 5px rgba(0,0,0,0.5);">
            Solusi Kesehatan Keluarga Terpercaya di Tenggarong
        </p>
        <a href="produk.php" class="btn" style="background: #ffc107; color: #1b3270; padding: 15px 50px; font-weight: 800; font-size: 1rem; text-transform: uppercase; border-radius: 5px; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);">
            Belanja Sekarang
        </a>
    </div>
</section>

<div class="section-wrapper" id="layanan" style="background-color: #f9fbfd;">
    <div class="container">
        <h2 class="section-title">Layanan Kami</h2>
        <p class="section-subtitle">Kami menyediakan solusi kesehatan lengkap untuk kebutuhan keluarga Anda.</p>
        
        <div class="grid-3">
            <div class="service-card">
                <img src="assets/images/obat.png" alt="Obat Lengkap" class="service-icon">
                <h3>Obat Lengkap</h3>
                <p>Menyediakan berbagai macam obat bebas, vitamin, suplemen, hingga alat kesehatan yang terjamin keasliannya.</p>
            </div>

            <div class="service-card">
                <img src="assets/images/resep_icon.png" alt="Upload Resep" class="service-icon"> 
                <h3>Tebus Resep Kilat</h3>
                <p>Layanan tebus resep dokter yang praktis dan cepat. Cukup unggah foto resep, kami siapkan obatnya untuk Anda.</p>
            </div>

            <div class="service-card">
                <img src="assets/images/consult_icon.png" alt="Konsultasi" class="service-icon"> 
                <h3>Konsultasi Apoteker</h3>
                <p>Dapatkan informasi yang tepat mengenai penggunaan obat, efek samping, dan dosis langsung dari Apoteker kami.</p>
            </div>
        </div>
    </div>
</div>

<div class="about-section-wrapper">
    <div class="container about-container">
        
        <div class="about-text-content">
            <h2 class="about-title">Lebih dari Sekadar Apotek</h2>
            <span class="about-subtitle">A place to heal, to consult, and to trust.</span>
            
            <p class="about-desc">
                Didirikan dengan komitmen tinggi, Apotek Arshaka lahir dari keinginan sederhana: menciptakan akses kesehatan yang terpercaya bagi siapa saja di Tenggarong. Kami memadukan ketersediaan obat yang lengkap dengan pelayanan kefarmasian yang hangat dan profesional.
            </p>
            
            <p class="about-desc">
                Setiap sudut pelayanan kami didesain untuk kenyamanan Anda. Mulai dari konsultasi obat yang ramah hingga proses penebusan resep yang cepat, kami hadir untuk memastikan kesehatan Anda dan keluarga selalu terjaga.
            </p>
        </div>

        <div class="about-image-wrapper">
            <div class="about-image-bg" style="background-color: #457B9D;"></div> <img src="assets/images/logo_apotek.jpg" alt="Suasana Apotek Arshaka" class="about-img-main">
        </div>

    </div>
</div>

<div class="consultation-section">
    
    <div class="consult-overlay"></div>

    <div class="container consult-grid">
        <div class="consult-content">
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

        <div class="consult-card">
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

<div class="section-wrapper" id="contact">
    <div class="container">
        <h2 class="section-title">Pusat Bantuan</h2>
        <p class="section-subtitle">Punya pertanyaan? Temukan jawabannya di sini atau hubungi kami langsung.</p>

        <div class="contact-grid">
            <div>
                <h3 style="margin-bottom: 20px; color: #1b3270; font-weight: 700;">Pertanyaan Umum (FAQ)</h3>
                
                <div class="faq-item">
                    <div class="faq-question">Bagaimana cara menebus resep online?</div>
                    <span class="faq-answer">Login akun Anda > Pilih menu "Unggah Resep" > Foto resep > Tunggu konfirmasi admin via WhatsApp/Website.</span>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Apakah bisa bayar di tempat (COD)?</div>
                    <span class="faq-answer">Ya, kami melayani COD khusus untuk area dalam kota Tenggarong.</span>
                </div>

                <div class="faq-item">
                    <div class="faq-question">Berapa lama pengiriman obat?</div>
                    <span class="faq-answer">Pesanan yang masuk sebelum jam 17.00 WITA akan dikirim di hari yang sama.</span>
                </div>
            </div>

            <div>
                <h3 style="margin-bottom: 20px; color: #1b3270; font-weight: 700;">Kunjungi Kami</h3>
                <div style="background: #f8f9fa; padding: 30px; border: 1px solid #eee;">
                    <p style="margin-bottom: 15px;"><strong>Alamat:</strong><br>
                    Jl. Loa Ipuh, Tenggarong, Kutai Kartanegara, Kalimantan Timur.</p>
                    
                    <p style="margin-bottom: 20px;">
                        <strong>WhatsApp:</strong> 0812-3456-7890<br>
                        <strong>Email:</strong> cs@arshaka.com
                    </p>
                    
                    <div style="height: 250px; background: #ddd; position: relative;">
                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.712833350147!2d116.97220407356798!3d-0.41664049957927407!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df6654869ab89cf%3A0x9a7cc00a781e9177!2sAPOTEK%20ARSHAKA!5e0!3m2!1sid!2sid!4v1762665565064!5m2!1sid!2sid" 
                            width="100%" height="100%" style="border:0;" 
                            allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>