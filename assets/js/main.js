document.addEventListener('DOMContentLoaded', function() {

    // 1. Loading Screen
    window.addEventListener('load', () => {
        const loadingScreen = document.querySelector('.loading-screen');
        if (loadingScreen) {
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 1000);
        }
    });

    // 2. Scroll Progress Bar
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        const progressBar = document.querySelector('.scroll-progress');
        if (progressBar) {
            progressBar.style.width = scrolled + '%';
        }
    });

    // 3. Back to Top Button
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

    // 4. Hamburger Menu
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Tutup menu saat link diklik
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    // 5. Fade-in Animation (Legacy/Cadangan)
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


    // 6. Profile Follow Scroll (Hanya jika ada elemen #profile)
    const profile = document.getElementById('profile');
    if (profile && window.innerWidth >= 1024) {
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

        window.addEventListener('scroll', () => requestAnimationFrame(updateProfilePosition));
        window.addEventListener('resize', updateProfilePosition);
        updateProfilePosition();
    }

    // ==========================================
    // 7. ANIMASI SCROLL MODERN (PERBAIKAN UTAMA)
    // ==========================================
    // Kode ini sekarang DI LUAR blok 'if (profile)', jadi akan selalu jalan.
    
    const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');

    const revealOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // observer.unobserve(entry.target); // Aktifkan jika ingin animasi sekali saja
            }
        });
    }, {
        threshold: 0.1, 
        rootMargin: "0px 0px -30px 0px"
    });

    reveals.forEach(reveal => {
        revealOnScroll.observe(reveal);
    });

});

// ==========================================
    // ANIMASI SCROLL INTERAKTIF (OBSERVER)
    // ==========================================
    
    // Targetkan semua elemen yang punya class 'reveal-...'
    const animatedElements = document.querySelectorAll('.reveal-up, .reveal-left, .reveal-right, .reveal-zoom, .reveal-special-img');

    const observerOptions = {
        threshold: 0.15, // Elemen akan muncul saat 15% terlihat di layar
        rootMargin: "0px 0px -50px 0px" // Offset sedikit agar animasi tidak terlalu cepat memicu
    };

    const revealOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Tambahkan class 'active' untuk memicu animasi CSS
                entry.target.classList.add('active');
                
                // Opsional: Stop mengamati setelah animasi selesai (agar tidak berulang)
                observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    animatedElements.forEach(el => {
        revealOnScroll.observe(el);
    });

// Script untuk mengubah Header saat Scroll
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    
    if (window.scrollY > 50) {
        header.classList.add('scrolled'); // Tambah class solid saat turun
    } else {
        header.classList.remove('scrolled'); // Hapus class (jadi transparan) saat di atas
    }
});

function sendToWhatsapp(e) {
    e.preventDefault();
    
    // Ambil data dari form
    var name = document.getElementById('waName').value;
    var phone = document.getElementById('waPhone').value;
    var email = document.getElementById('waEmail').value;
    var message = document.getElementById('waMessage').value;
    
    // Nomor Admin Apotek (Ganti dengan nomor asli, format 62...)
    var adminPhone = "6282188392309"; 
    
    // Format Pesan WhatsApp
    var text = "*Halo Admin Apotek Arshaka, saya ingin konsultasi.*%0A%0A" +
               "Nama: " + name + "%0A" +
               "No. HP: " + phone + "%0A" +
               "Email: " + (email ? email : "-") + "%0A" +
               "Pesan: " + message;
               
    // Buka WhatsApp
    window.open("https://wa.me/" + adminPhone + "?text=" + text, '_blank');
}