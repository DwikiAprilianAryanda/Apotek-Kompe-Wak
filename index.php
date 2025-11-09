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
      padding-top: 5rem;
    }

    /* Profile Sidebar */
    .follow-scroll {
      position: fixed;
      top: 5rem;
      right: 0;
      transition: top 0.3s ease;
      width: 100%;
      max-width: 15rem;
      background-color: #ffffff;
      padding: 1.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }

    .profile-content {
      text-align: center;
    }

    .profile-img {
      width: 8rem;
      height: 8rem;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto 1rem;
      border: 4px solid #60a9d6ff;
      transition: transform 0.3s;
    }

    .profile-img:hover {
      transform: scale(1.05) rotate(5deg);
    }

    .profile-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #60a9d6ff;
      margin-bottom: 0.5rem;
    }

    .profile-text {
      color: #4b5563;
      font-size: 1rem;
      margin-bottom: 0.5rem;
    }

    .profile-button {
      display: inline-block;
      background: linear-gradient(135deg, #1b3270, #457B9D);
      color: #ffffff;
      padding: 0.5rem 1rem;
      border-radius: 9999px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      margin-top: 1rem;
    }

    .profile-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(72, 75, 236, 0.4);
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
    .activities-section {
      padding: 5rem 0;
      background-color: #ffffff;
    }

    .contact-section {
      padding: 5rem 0;
      background: linear-gradient(135deg, #e2f7fcff 0%, #b8e5f7ff 100%);
    }


    /* Contact */
    .contact-links {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin: 2rem 0;
    }

    .contact-text {
      font-size: 1.125rem;
      color: #4b5563;
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
        width: 75%;
      }

      .follow-scroll {
        margin-right: 2rem;
      }
    }

    @media (max-width: 1024px) {
      .main-container {
        flex-direction: column;
      }

      .main-content {
        width: 100%;
      }

      .follow-scroll {
          position: relative;
          top: 0;
          max-width: 90%; 
          margin: 1rem auto; 
          padding: 1rem;
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

      .profile-img {
        width: 6rem;
        height: 6rem;
      }

      .profile-title {
        font-size: 1.25rem;
      }

      .profile-text {
        font-size: 0.875rem;
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

      .profile-img {
        width: 5rem;
        height: 5rem;
      }

      .profile-title {
        font-size: 1rem;
      }

      .profile-text {
        font-size: 0.75rem;
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
    <aside id="profile" class="follow-scroll">
      <div class="profile-content">
        <img src="foto/diri.jpg" alt="Apotek Arshaka" class="profile-img">
        <h2 class="profile-title">Apotek Arshaka</h2>
        <p class="profile-text">Jl. Loa Ipuh Tenggarong</p>
        <p class="profile-text">Jam Operasional : 8 pagi - 11 malam</p>
        <a href="#contact" class="profile-button">Hubungi Kami</a>
      </div>
    </aside>

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


<section id="activities" class="activities-section">
        <div class="container section-content">
          <h2 class="section-title">Alamat Apotek</h2>
          <div class="activity-item fade-in">
            <h3 class="activity-title">Kec. Tenggarong, Kabupaten Kutai Kartanegara, Kalimantan Timur</h3>
            <p class="activity-text">HXMF+8WR, Jl. Loa Ipuh, Loa Ipuh, Kec. Tenggarong, Kabupaten Kutai Kartanegara, Kalimantan Timur 75513</p>
                <video width="800" height="700" controls>
                  <source src="assets/images/map.mp4" type="video/mp4">
                  Maaf, browser Anda tidak mendukung tag video.
                </video>
            </div>
        </div>
      </section>

      <section id="contact" class="contact-section">
        <div class="container section-content text-center">
          <h2 class="section-title">Hubungi Kami</h2>
          <p class="section-text">Silakan hubungi kami untuk konsultasi obat lebih lanjut</p>
          <div class="contact-links fade-in">
            <p class="contact-text">Email: <a href="mailto:apotek.kompe@gmail.com" class="contact-link">apotek.kompe@gmail.com</a></p>
          </div>
          <p class="section-text">Jam operasional kami : 8 pagi- 11 malam</p>
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

        // Profile follow scroll (desktop only)
        if (window.innerWidth >= 1024) {
          const profile = document.getElementById('profile');
          if (profile) {
            let lastScrollY = window.scrollY;

            function updateProfilePosition() {
              const scrollY = window.scrollY;
              const navbarHeight = 64; 
              const profileHeight = profile.offsetHeight;
              const windowHeight = window.innerHeight;
              const padding = 20;

              const minTop = navbarHeight + padding; 

              const maxTop = windowHeight - profileHeight - padding;
              
              let newTop = Math.max(minTop, Math.min(scrollY + padding, maxTop));
              
              profile.style.top = `${newTop}px`;
              lastScrollY = scrollY;
            }

            window.addEventListener('scroll', () => {
              requestAnimationFrame(updateProfilePosition);
            });

            window.addEventListener('resize', updateProfilePosition);
            updateProfilePosition();
          }
        }
    });
  </script>
</body>
</html>

    <?php include 'includes/footer.php'; ?>