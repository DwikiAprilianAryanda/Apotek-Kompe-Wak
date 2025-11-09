<?php include 'includes/header.php'; ?>

<div class="section">
    <div class="success-card card"> <!-- Gunakan class card dari style.css -->
        <div class="success-icon">✓</div> <!-- Ikon sukses -->
        <h2 class="success-title">Terima Kasih!</h2> <!-- Judul -->
        <p class="success-message">Pesanan Anda telah berhasil kami terima.</p> <!-- Pesan -->

        <?php
        if (isset($_GET['order_id'])) {
            $order_id = htmlspecialchars($_GET['order_id']);
            // Gunakan elemen spesifik untuk nomor pesanan
            echo "<div class='order-id-container'>";
            echo "<p class='order-id-label'>Nomor pesanan Anda:</p>";
            echo "<p class='order-id-number'>#" . $order_id . "</p>";
            echo "</div>";
        }
        ?>

        <p class="success-note">Silakan lakukan pembayaran agar pesanan Anda dapat segera kami proses.</p> <!-- Catatan -->

        <a href="produk.php" class="btn btn-primary">← Kembali Berbelanja</a> <!-- Tombol -->
    </div>
</div>

<style>
/* CSS untuk halaman order_success */

/* Atur section agar konten selalu di tengah */
.section {
    display: flex;
    justify-content: center;
    align-items: center;
    /* Hapus min-height: 100vh; agar section tidak memaksakan tinggi */
    padding: 0; /* Hilangkan padding default section */
}

.success-card {
    text-align: center;
    /* Gunakan min-height: 100vh; agar card memiliki tinggi minimal 100% viewport, tapi bisa lebih tinggi jika diperlukan */
    min-height: 100vh;
    /* Gunakan max-width: 500px; untuk batas lebar maksimum */
    max-width: 500px;
    /* Tengahkan card */
    margin: 0 auto;
    /* Gunakan display: flex; dan flex-direction: column; untuk menyusun elemen secara vertikal */
    display: flex;
    flex-direction: column;
    /* Gunakan justify-content: center; agar elemen-elemen di dalamnya berada di tengah card */
    justify-content: center;
    /* Gunakan align-items: center; agar elemen-elemen di dalamnya berada di tengah horizontal */
    align-items: center;
    /* Gunakan gap: 10px; untuk jarak antar elemen */
    gap: 10px;
    /* Gunakan padding: 20px; untuk padding dalam card */
    padding: 20px;
}

.success-icon {
    font-size: 2.5rem; /* Ukuran ikon */
    color: #10b981; /* Warna hijau sukses */
    /* Hapus margin default */
    margin: 0;
}

.success-title {
    color: #1e40af; /* Warna judul */
    /* Hapus margin default */
    margin: 0;
    font-size: 1.5rem; /* Ukuran font */
}

.success-message,
.success-note {
    /* Hapus margin default */
    margin: 0;
    font-size: 1rem; /* Ukuran font */
}

.success-note {
    color: #666; /* Warna catatan */
    font-style: italic; /* Gaya miring */
}

/* Container untuk nomor pesanan */
.order-id-container {
    background: #f0f9ff; /* Warna background */
    /* Kurangi padding */
    padding: 5px;
    border-radius: 8px; /* Radius sudut lebih kecil */
    /* Kurangi margin */
    margin: 5px 0;
    border: 2px solid #3b82f6; /* Border */
    /* Gunakan display: flex; dan flex-direction: column; untuk menyusun elemen secara vertikal */
    display: flex;
    flex-direction: column;
    /* Gunakan align-items: center; agar elemen-elemen di dalamnya berada di tengah horizontal */
    align-items: center;
    /* Gunakan gap: 1px; untuk jarak antar label dan nomor */
    gap: 1px;
}

.order-id-label {
    /* Hapus margin default */
    margin: 0;
    font-size: 0.9rem; /* Ukuran font label */
    font-weight: 600; /* Tebalkan label */
    color: #333; /* Warna label */
}

.order-id-number {
    /* Hapus margin default */
    margin: 0;
    font-size: 1.1rem; /* Ukuran font nomor */
    font-weight: bold; /* Tebalkan nomor */
    color: #1e40af; /* Warna nomor */
}

/* Tombol */
/* Gunakan class .btn dan .btn-primary dari style.css */
/* Tidak perlu menambahkan inline style untuk tombol */
.btn-primary {
    /* Hapus margin default */
    margin-top: 10px; /* Beri sedikit jarak dari elemen sebelumnya */
    /* Ganti warna dasar tombol agar kontras dengan background */
    background: linear-gradient(135deg, #1b3270, #457B9D); /* Warna dasar tombol */
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Tambahkan efek hover */
.btn-primary:hover {
    background: linear-gradient(135deg, #1e40af, #3b82f6); /* Warna hover */
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Responsif */
@media (max-width: 768px) {
    .section {
        padding: 10px 0; /* Tambahkan padding atas dan bawah */
    }

    .success-card {
        min-height: auto; /* Hilangkan min-height: 100vh; di mobile */
        max-width: 100%; /* Isi seluruh lebar layar */
        padding: 20px 15px;
        margin: 10px;
    }

    .success-icon {
        font-size: 2rem;
    }

    .success-title {
        font-size: 1.3rem;
    }

    .success-message,
    .success-note {
        font-size: 0.9rem;
    }

    .order-id-container {
        padding: 4px;
        margin: 4px 0;
    }

    .order-id-number {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .success-card {
        padding: 15px 10px;
        margin: 5px;
    }

    .success-icon {
        font-size: 1.8rem;
    }

    .success-title {
        font-size: 1.2rem;
    }

    .order-id-container {
        padding: 3px;
    }

    .order-id-label {
        font-size: 0.8rem;
    }

    .order-id-number {
        font-size: 0.9rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>