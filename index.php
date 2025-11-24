<?php include 'includes/header.php'; ?>

<section style="position: relative; height: 500px; background: #1b3270; display: flex; align-items: center; justify-content: center; color: white; text-align: center;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3);"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; letter-spacing: -1px;">APOTEK ARSHAKA</h1>
        <p style="font-size: 1.25rem; margin-bottom: 40px; font-weight: 300;">Solusi Kesehatan Keluarga Terpercaya di Tenggarong</p>
        <a href="produk.php" class="btn" style="background: #ffc107; color: #1b3270; padding: 15px 50px; font-weight: 800; font-size: 1rem; text-transform: uppercase;">Belanja Sekarang</a>
    </div>
</section>

<div class="section-wrapper" id="layanan">
    <div class="container">
        <h2 class="section-title">Layanan Kami</h2>
        <p class="section-subtitle">Kami menyediakan berbagai layanan kesehatan farmasi untuk memenuhi kebutuhan Anda sehari-hari.</p>
        
        <div class="grid-3">
            <div class="service-card">
                <img src="assets/images/obat.png" alt="Obat Lengkap" class="service-icon">
                <h3>Obat Lengkap</h3>
                <p>Tersedia obat bebas, obat resep, vitamin, hingga alat kesehatan dengan keaslian terjamin.</p>
                <a href="produk.php" style="color: #457B9D; font-weight: bold; font-size: 0.9rem; margin-top: 10px; display: inline-block;">Lihat Katalog &rarr;</a>
            </div>

            <div class="service-card">
                <img src="assets/images/resep_icon.png" alt="Upload Resep" class="service-icon"> 
                <h3>Tebus Resep Kilat</h3>
                <p>Tidak perlu antre. Unggah foto resep dokter Anda, kami siapkan obatnya, Anda tinggal ambil.</p>
                <a href="unggah_resep.php" style="color: #457B9D; font-weight: bold; font-size: 0.9rem; margin-top: 10px; display: inline-block;">Unggah Resep &rarr;</a>
            </div>

            <div class="service-card">
                <img src="assets/images/consult_icon.png" alt="Konsultasi" class="service-icon"> 
                <h3>Konsultasi Apoteker</h3>
                <p>Apoteker kami siap memberikan edukasi dan informasi pemakaian obat yang aman untuk Anda.</p>
                <a href="#contact" style="color: #457B9D; font-weight: bold; font-size: 0.9rem; margin-top: 10px; display: inline-block;">Hubungi Kami &rarr;</a>
            </div>
        </div>
    </div>
</div>

<div class="section-wrapper bg-light">
    <div class="container" style="display: flex; align-items: center; gap: 50px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
             <img src="assets/images/logo_apotek.jpg" alt="Gedung Apotek" style="width: 100%; height: auto; border: 1px solid #ddd;">
        </div>
        <div style="flex: 1; min-width: 300px;">
            <h2 class="section-title" style="text-align: left;">Tentang Arshaka</h2>
            <p style="margin-bottom: 20px; font-size: 1.1rem; color: #555;">
                Apotek Arshaka hadir di jantung Tenggarong (Jl. Loa Ipuh) untuk melayani masyarakat dengan sepenuh hati. Kami berkomitmen menyediakan produk kesehatan berkualitas dengan harga yang terjangkau.
            </p>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span style="color: #28a745; margin-right: 10px;">&#10003;</span> Produk 100% Asli & BPOM
                </li>
                <li style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span style="color: #28a745; margin-right: 10px;">&#10003;</span> Apoteker Berpengalaman
                </li>
                <li style="margin-bottom: 10px; display: flex; align-items: center;">
                    <span style="color: #28a745; margin-right: 10px;">&#10003;</span> Buka Setiap Hari (08.00 - 23.00)
                </li>
            </ul>
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