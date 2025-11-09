<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Apotek Arshaka">
  <meta name="keywords" content="Apotek Arshaka">
  <title>Apotek Arshaka</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💕</text></svg>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      scroll-behavior: smooth;
      background: linear-gradient(135deg, #e7e7f9 0%, #70b9ddff 50%, #5092b3ff 100%);
      color: #1f2937;
      overflow-x: hidden;
      position: relative;
    }

    /* Background Decoration */
    .bg {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: -1;
      background:
        radial-gradient(400px 300px at 15% 20%, rgba(46, 93, 129, 0.3), transparent 60%),
        radial-gradient(500px 400px at 85% 30%, hsla(189, 100%, 65%, 0.20), transparent 60%),
        radial-gradient(600px 500px at 50% 90%, rgba(53, 86, 137, 0.12), transparent 60%);
      }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Scroll Progress Bar */
    .scroll-progress {
      position: fixed;
      top: 0;
      left: 0;
      width: 0%;
      height: 4px;
      background: linear-gradient(to right, #1b3270, #457B9D);
      z-index: 9998;
      transition: width 0.1s;
    }

    /* Back to Top Button */
    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #1b3270, #457B9D);
      color: white;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s;
      z-index: 1000;
    }

    .back-to-top.visible {
      opacity: 1;
      visibility: visible;
    }

    .back-to-top:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.3);
    }

    .container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 1rem;
    }


    /* Hamburger Menu */
    .hamburger {
      display: none;
      flex-direction: column;
      cursor: pointer;
      z-index: 1001;
    }

    .hamburger span {
      width: 25px;
      height: 3px;
      background: #457B9D;
      margin: 3px 0;
      transition: 0.3s;
      border-radius: 2px;
    }

    .hamburger.active span:nth-child(1) {
      transform: rotate(45deg) translate(8px, 8px);
    }

    .hamburger.active span:nth-child(2) {
      opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
      transform: rotate(-45deg) translate(7px, -6px);
    }

    /* Main Layout */
    .main-container {
      display: flex;
      flex-direction: column;
    }



    /* Main Content */
    .main-content {
      width: 100%;
    }

    /* Hero Section */
    /* --- Tambahan CSS untuk Animasi di Hero Section --- */

    /* Membuat video menutupi seluruh background Hero Section */
    .hero-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover; /* Memastikan video mengisi seluruh area tanpa distorsi */
        z-index: 0; /* Menempatkan video di belakang .section-content (z-index: 1) */
    }

    /* Pastikan teks berada di atas video */
    .about-section .section-content {
        position: relative;
        z-index: 1; /* Di atas video */
    }

    /* Opsional: Efek visual pada video (contoh: meredupkan) */
    .hero-animation {
        opacity: 20%;
        /* filter: brightness(70%); */ /* Anda bisa menghapus ini jika video Anda sudah cukup gelap */
    }
    .about-section {
      min-height: 100vh;
      display: flex;
      align-items: center;
      background: linear-gradient(135deg, #3d759fff, #73b4dcff);
      background-attachment: fixed;
      background-size: 100%;
      padding: 2rem;
      background-position: center;
      color: #ffffff;
      position: relative;
    }
    .about-decor {
      position: absolute;
      right: 0;
      bottom: 0;
      width: 100%; /* Change to relative unit */
      max-width: 250px; /* Limit maximum size */
      height: auto;
      z-index: 1;
    }

    @media (max-width: 768px) {
      .about-decor {
        max-width: 300px; /* Smaller size for tablets */
      }
    }

    @media (max-width: 480px) {
      .about-decor {
        max-width: 200px; /* Even smaller for phones */
      }
    }


    .about-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
      animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }

    .section-content {
      width: 100%;
      padding: 2rem;
      position: relative;
      z-index: 1;
    }

    .section-title {
      font-size: 2.25rem;
      font-weight: 700;
      color: #60a9d6ff;
      margin-bottom: 3rem;
      text-align: center;
    }

    .section-title-atas {
      font-size: 2.25rem;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 3rem;
      text-align: center;
    }

    .section-text {
      font-size: 1.25rem;
      margin-bottom: 1.5rem;
      animation: fadeInUp 1s ease 0.2s backwards;
    }

    .section-button {
      display: inline-block;
      background-color: #ffffff;
      color: #60a9d6ff;
      padding: 0.75rem 2rem;
      border-radius: 9999px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      animation: fadeInUp 1s ease 0.4s backwards;
    }

    .section-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(255,255,255,0.3);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Sections */
    /* =============================
INFO CONTACT SECTION STYLES
============================= */

.info-contact-section {
    padding: 50px 0;
    background: linear-gradient(135deg, #e2f7fcff 0%, #b8e5f7ff 100%);
}

.info-contact-section .section-title {
    text-align: center;
    color: #1D3557;
    margin-bottom: 30px;
}

.info-left,
.info-right {
    display: flex;
    flex-direction: column;
}

.info-subtitle {
    color: #1D3557;
    font-size: 1.3rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.info-address {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 20px;
    line-height: 1.6;
}

.info-text {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 20px;
    line-height: 1.6;
}

.map-container {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}

.map-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.contact-link {
    color: #60a9d6ff;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.contact-link:hover {
    color: #E2E8F0;
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .info-contact-section {
        padding: 30px 0;
    }

    .info-left,
    .info-right {
        flex: 1 1 100%;
        padding: 15px;
    }

    .info-left {
        padding-right: 0;
        margin-bottom: 20px;
    }

    .info-right {
        padding-left: 0;
    }

    .map-container {
        height: 250px;
    }
}

@media (max-width: 480px) {
    .info-contact-section {
        padding: 20px 0;
    }

    .info-subtitle {
        font-size: 1.2rem;
    }

    .info-address,
    .info-text {
        font-size: 1rem;
    }

    .map-container {
        height: 200px;
    }
}


    /* Animations */
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Responsive Adjustments */
    @media (min-width: 1024px) {
      .main-container {
        flex-direction: row;
      }

      .main-content {
        width: 100%;
      }
    }

    @media (max-width: 1024px) {
      .main-container {
        flex-direction: column;
      }

      .main-content {
        width: 100%;
      }


      }

    @media (max-width: 768px) {
      .navbar-links {
        position: fixed;
        top: 64px;
        right: -100%;
        width: 70%;
        height: calc(100vh - 64px);
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        flex-direction: column;
        padding: 2rem;
        transition: right 0.3s;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
      }

      .navbar-links.active {
        right: 0;
      }

      .hamburger {
        display: flex;
      }

      .section-title, .section-title-atas {
        font-size: 1.75rem;
      }

      .section-text {
        font-size: 1rem;
      }




      .activity-item {
        padding: 1.5rem;
      }

      .activity-title {
        font-size: 1.25rem;
      }

      .activity-text {
        font-size: 0.875rem;
      }

      .section-button, .profile-button {
        padding: 0.5rem 1.5rem;
        font-size: 0.875rem;
      }

    }

    @media (max-width: 480px) {
      .container {
        padding: 0 0.5rem;
      }

      .navbar-title {
        font-size: 1.25rem;
      }

      .section-title, .section-title-atas {
        font-size: 1.5rem;
      }

      .section-text {
        font-size: 0.875rem;
      }



      .activity-title {
        font-size: 1.125rem;
      }

      .activity-text {
        font-size: 0.75rem;
      }

      .overlay-title {
        font-size: 1.25rem;
      }

      .overlay-text {
        font-size: 0.875rem;
      }

      .contact-text {
        font-size: 1rem;
      }

      .social-icon-svg {
        width: 1.5rem;
        height: 1.5rem;
      }

      .back-to-top {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
        bottom: 20px;
        right: 20px;
      }

      .about-section {
        min-height: 80vh;
        background-attachment: scroll;
      }

      .section-content {
        padding: 1rem;
      }

      
      .bg {
        background:
          radial-gradient(400px 300px at 15% 20%, rgba(57, 86, 155, 0.15), transparent 60%),
          radial-gradient(500px 400px at 85% 30%, rgba(102, 232, 255, 0.2), transparent 60%),
          radial-gradient(600px 500px at 50% 90%, rgba(50, 90, 164, 0.12), transparent 60%);
      }
    }
  </style>
</head>
<body>
  
  <?php // include 'includes/header.php'; // Dihilangkan ?>
  
  <div class="bg"></div> 

  <div class="scroll-progress"></div>

  <button class="back-to-top" aria-label="Back to top">↑</button>

  <div class="main-container">


    <main class="main-content">
      <section id="about" class="about-section">
        <div class="container section-content text-center">
          <h2 class="section-title-atas">Selamat Datang di Apotek Arshaka</h2>
          <p class="section-text">Ingin Konsul Lebih Lanjut?</p>
          <a href="#contact" class="section-button">Hubungi Kami</a>
        </div>
    <video class="hero-animation" autoplay loop muted playsinline>
        <source src="assets/images/obat.mp4" type="video/mp4"> 
        Maaf, browser Anda tidak mendukung tag video.
    </video>
      </section>


<section id="info-contact" class="info-contact-section">
    <div class="container section-content">
        <h2 class="section-title">Informasi Kontak & Lokasi</h2>
        
        <!-- Bagian Kiri: Alamat & Peta -->
        <div class="info-left" style="flex: 1; padding-right: 20px;">
            <h3 class="info-subtitle">Alamat Apotek</h3>
            <p class="info-address">Kec. Tenggarong, Kabupaten Kutai Kartanegara, Kalimantan Timur<br>
               HXMF+8WR, Jl. Loa Ipuh, Loa Ipuh, Kec. Tenggarong, Kabupaten Kutai Kartanegara, Kalimantan Timur 75513</p>
            
            <div class="map-container" style="width: 100%; height: 300px; margin-top: 15px; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.712833350147!2d116.97220407356798!3d-0.41664049957927407!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df6654869ab89cf%3A0x9a7cc00a781e9177!2sAPOTEK%20ARSHAKA!5e0!3m2!1sid!2sid!4v1762665565064!5m2!1sid!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- Bagian Kanan: Hubungi Kami -->
        <div class="info-right" style="flex: 1; padding-left: 20px; background: #f8f9fa; padding: 25px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <h3 class="info-subtitle">Hubungi Kami</h3>
            <p class="info-text">Silakan hubungi kami untuk konsultasi obat lebih lanjut.</p>
            
            <div class="contact-info" style="margin-top: 20px; font-size: 1.1rem; line-height: 1.6;">
                <p><strong>Email:</strong> <a href="mailto:apotek.kompe@gmail.com" class="contact-link">apotek.kompe@gmail.com</a></p>
                <p><strong>Jam Operasional:</strong> 8 pagi - 11 malam</p>
            </div>
        </div>
    </div>
</section>
    </main>
  </div>


  <script>

    document.addEventListener('DOMContentLoaded', () => {
        // Loading Screen
        window.addEventListener('load', () => {
            const loadingScreen = document.querySelector('.loading-screen');
            if (loadingScreen) {
                setTimeout(() => {
                    loadingScreen.classList.add('hidden');
                }, 1000);
            }
        });

        // Scroll Progress Bar
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            const progressBar = document.querySelector('.scroll-progress');
            if (progressBar) {
                progressBar.style.width = scrolled + '%';
            }
        });

        // Back to Top Button
        const backToTop = document.querySelector('.back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });
            backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Hamburger Menu (Elemen harus ada di header.php jika digunakan)
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.navbar-links');
        const links = document.querySelectorAll('.nav-link');

        if (hamburger && navLinks) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });

            links.forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                });
            });
        }


        // Fade-in Animation
        const fadeElements = document.querySelectorAll('.fade-in');
        const fadeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    fadeObserver.unobserve(entry.target); 
                }
            });
        }, { threshold: 0.1 });
        fadeElements.forEach(element => fadeObserver.observe(element));


        // Smooth scroll with offset for navbar
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
          anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
              const navbarHeight = 64; 
              const offsetTop = target.offsetTop - navbarHeight; 
              window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
              });
              // Tutup menu hamburger setelah klik (untuk mobile)
              if (hamburger && navLinks) {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
              }
            }
          });
        });


    });
  </script>
</body>
</html>

    <?php include 'includes/footer.php'; ?>