document.addEventListener('DOMContentLoaded', function() {

    // Loading Screen (Anggap ada di file terpisah karena kode PHP telah hilang)
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

    // Hamburger Menu
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links'); // Pastikan kelas ini sesuai
    const links = document.querySelectorAll('.nav-links a'); // Pastikan kelas ini sesuai

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
                fadeObserver.unobserve(entry.target); // Hanya muncul sekali
            }
        });
    }, { threshold: 0.1 });
    fadeElements.forEach(element => fadeObserver.observe(element));

    // Lightbox Gallery
    const lightbox = document.querySelector('.lightbox');
    const lightboxImg = document.querySelector('.lightbox-img');
    const lightboxCaption = document.querySelector('.lightbox-caption');
    const lightboxClose = document.querySelector('.lightbox-close');
    const lightboxPrev = document.querySelector('.lightbox-prev');
    const lightboxNext = document.querySelector('.lightbox-next');
    
    const galleryItems = document.querySelectorAll('.painting-card, .activity-card');
    let currentIndex = 0;
    const images = [];

    galleryItems.forEach((item) => {
      // Pastikan data-index ada di elemen card
      const index = parseInt(item.dataset.index);

      const img = item.querySelector('.gallery-img');
      const title = item.querySelector('.overlay-title').textContent;
      const text = item.querySelector('.overlay-text').textContent;
      
      images.push({
        src: img.src,
        alt: img.alt,
        caption: `${title} - ${text}`
      });

      item.addEventListener('click', () => {
        currentIndex = index; // Menggunakan index yang sudah diparse
        openLightbox();
      });
    });

    function openLightbox() {
      if (!lightbox) return;
      lightbox.classList.add('active');
      updateLightboxImage();
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
      if (!lightbox) return;
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
    }

    function updateLightboxImage() {
      const image = images[currentIndex];
      if (image && lightboxImg) {
        lightboxImg.src = image.src;
        lightboxImg.alt = image.alt;
        lightboxCaption.textContent = image.caption;
      }
    }

    function nextImage() {
      currentIndex = (currentIndex + 1) % images.length;
      updateLightboxImage();
    }

    function prevImage() {
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      updateLightboxImage();
    }

    if (lightbox) {
        lightboxClose.addEventListener('click', closeLightbox);
        lightboxNext.addEventListener('click', nextImage);
        lightboxPrev.addEventListener('click', prevImage);
        
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
        });
    }


    // Smooth scroll with offset for navbar
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          const navbarHeight = 64; // Tinggi navbar yang diasumsikan 4rem * 16px = 64px
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
          const navbarHeight = 64; // 4rem
          const profileHeight = profile.offsetHeight;
          const windowHeight = window.innerHeight;
          const padding = 20;

          // Batas atas (di bawah navbar)
          const minTop = navbarHeight + padding; 

          // Batas bawah (agar tidak melewati viewport di bagian bawah)
          const maxTop = windowHeight - profileHeight - padding;
          
          let newTop = Math.max(minTop, Math.min(scrollY + padding, maxTop));
          
          profile.style.top = `${newTop}px`;
          lastScrollY = scrollY;
        }

        window.addEventListener('scroll', () => {
          requestAnimationFrame(updateProfilePosition);
        });

        // Panggil saat dimuat untuk penempatan awal
        window.addEventListener('resize', updateProfilePosition);
        updateProfilePosition();
      }
    }
});