document.addEventListener('DOMContentLoaded', function() {

    // 1. Scroll Header Logic
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled'); 
        } else {
            header.classList.remove('scrolled'); 
        }
    });

    // 2. Animasi Scroll (Intersection Observer)
    const animatedElements = document.querySelectorAll('.reveal-up, .reveal-left, .reveal-right, .reveal-zoom, .reveal-special-img');

    const observerOptions = {
        threshold: 0.15, 
        rootMargin: "0px 0px -50px 0px"
    };

    const revealOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target); // Animasi sekali saja
            }
        });
    }, observerOptions);

    animatedElements.forEach(el => {
        revealOnScroll.observe(el);
    });

    // 3. Hamburger Menu (Mobile)
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }
});

// Fungsi WhatsApp Form
function sendToWhatsapp(e) {
    e.preventDefault();
    var name = document.getElementById('waName').value;
    var phone = document.getElementById('waPhone').value;
    var email = document.getElementById('waEmail').value;
    var message = document.getElementById('waMessage').value;
    
    var adminPhone = "6281234567890"; // Ganti Nomor HP Anda di sini
    
    var text = "*Halo Admin Apotek Arshaka, saya ingin konsultasi.*%0A%0A" +
               "Nama: " + name + "%0A" +
               "No. HP: " + phone + "%0A" +
               "Email: " + (email ? email : "-") + "%0A" +
               "Pesan: " + message;
               
    window.open("https://wa.me/" + adminPhone + "?text=" + text, '_blank');
}