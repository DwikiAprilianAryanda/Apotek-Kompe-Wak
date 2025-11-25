document.addEventListener('DOMContentLoaded', function() {

    // --- 1. LOGIKA HEADER SCROLL (PENTING) ---
    const header = document.querySelector('header');
    
    // Hanya aktifkan listener jika kita ada di halaman Home (header tidak punya header-solid)
    if (!header.classList.contains('header-solid')) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled'); 
            } else {
                header.classList.remove('scrolled'); 
            }
        });
    }

    // --- 2. ANIMASI SCROLL (INTERSECTION OBSERVER) ---
    // Targetkan semua elemen yang memiliki class reveal
    const animatedElements = document.querySelectorAll('.reveal-up, .reveal-left, .reveal-right, .reveal-zoom, .reveal-special-img');

    const observerOptions = {
        threshold: 0.15, // Pemicu saat 15% elemen terlihat
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

    // --- 3. HAMBURGER MENU (MOBILE) ---
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-menu'); // Gunakan .nav-menu

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            // Logika untuk menampilkan menu mobile jika diperlukan
        });
    }
    
    // --- 4. LOGIKA QUANTITY CONTROL (PLUS/MINUS) ---
    // Menggunakan event delegation pada body agar berfungsi di elemen yang dimuat dinamis
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('qty-btn')) {
            const btn = e.target;
            const isPlus = btn.classList.contains('plus-btn');
            // Ambil ID dari atribut data-id (qty-input-1, qty-input-2, dst)
            const productId = btn.getAttribute('data-id'); 
            const qtyInput = document.getElementById('qty-input-' + productId);
            
            if (!qtyInput) return;
            
            let currentValue = parseInt(qtyInput.value) || 1;
            const maxStock = parseInt(qtyInput.getAttribute('max'));
            const minStock = parseInt(qtyInput.getAttribute('min'));

            if (isPlus) {
                // Tambah, tapi jangan lebih dari stok maksimal
                if (currentValue < maxStock) {
                    qtyInput.value = currentValue + 1;
                }
            } else {
                // Kurangi, tapi jangan kurang dari batas minimal
                if (currentValue > minStock) {
                    qtyInput.value = currentValue - 1;
                }
            }
        }
    });

    // Menangani input manual agar tetap mematuhi batas stok
    document.body.addEventListener('change', function(e) {
        if (e.target.classList.contains('qty-input')) {
            let value = parseInt(e.target.value);
            const min = parseInt(e.target.getAttribute('min'));
            const max = parseInt(e.target.getAttribute('max'));
            
            if (isNaN(value) || value < min) {
                e.target.value = min;
            } else if (value > max) {
                e.target.value = max;
            }
        }
    });

});

// --- FUNGSI WHATSAPP (Di luar DOMContentLoaded agar bisa diakses global oleh onsubmit) ---
function sendToWhatsapp(e) {
    e.preventDefault();
    var name = document.getElementById('waName').value;
    var phone = document.getElementById('waPhone').value;
    var email = document.getElementById('waEmail').value;
    var message = document.getElementById('waMessage').value;
    
    // Nomor HP Admin yang sudah Anda berikan sebelumnya
    var adminPhone = "6281234567890"; 
    
    var text = "*Halo Admin Apotek Arshaka, saya ingin konsultasi.*%0A%0A" +
               "Nama: " + name + "%0A" +
               "No. HP: " + phone + "%0A" +
               "Email: " + (email ? email : "-") + "%0A" +
               "Pesan: " + message;
               
    window.open("https://wa.me/" + adminPhone + "?text=" + text, '_blank');
}

/* =========================================
   CART FUNCTION: UPDATE QUANTITY
   ========================================= */
function updateQty(productId, currentQty, change, maxStock = 999) {
    let newQty = currentQty + change;
    
    // Validasi Minimal 1
    if (newQty < 1) return;
    
    // Validasi Maksimal Stok (Jika parameter maxStock dikirim)
    if (newQty > maxStock) {
        alert("Maaf, stok hanya tersedia " + maxStock + " item.");
        return;
    }

    // Isi Form Hidden yang ada di keranjang.php
    var inputId = document.getElementById('hidden_product_id');
    var inputQty = document.getElementById('hidden_quantity');
    var form = document.getElementById('updateQtyForm');

    if (inputId && inputQty && form) {
        inputId.value = productId;
        inputQty.value = newQty;
        form.submit();
    } else {
        console.error("Form update quantity tidak ditemukan di halaman.");
    }
}