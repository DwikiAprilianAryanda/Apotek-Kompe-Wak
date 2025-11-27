<?php
// Header admin (session, db, keamanan, layout)
include 'admin_header.php';

// 1. Ambil ID Resep
if (!isset($_GET['id'])) {
    header("Location: verifikasi_resep.php");
    exit;
}
$prescription_id = $_GET['id'];

// 2. Ambil Data Resep & Pelanggan
$sql = "SELECT p.*, u.name AS customer_name, u.email, u.id as user_id 
        FROM prescriptions p
        JOIN users u ON p.user_id = u.id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $prescription_id);
$stmt->execute();
$prescription = $stmt->get_result()->fetch_assoc();

if (!$prescription) {
    die("<div class='alert-box error'>Resep tidak ditemukan.</div>");
}

// 3. Ambil Daftar Produk
$sql_products = "SELECT id, name, price, stock_quantity FROM products ORDER BY name ASC";
$result_products = $conn->query($sql_products);
?>

<div class="page-header mb-30">
    <div class="header-left">
        <a href="verifikasi_resep.php" class="btn-back">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali
        </a>
        <div class="mt-3">
            <h1 class="page-title">Proses Resep #<?php echo $prescription['id']; ?></h1>
            <p class="page-subtitle">Verifikasi dan input obat untuk pesanan ini.</p>
        </div>
    </div>
    
    <div class="status-badge-lg status-pending">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span><?php echo htmlspecialchars($prescription['status']); ?></span>
    </div>
</div>

<div class="process-grid">
    
    <div class="col-details">
        <div class="content-card h-full">
            <div class="card-header border-bottom">
                <h3>Informasi Resep</h3>
            </div>
            <div class="card-body p-20">
                <ul class="info-list compact-list">
                    <li class="info-item">
                        <div class="icon-wrapper">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="info-content">
                            <span class="label">Nama Pelanggan</span>
                            <span class="value"><?php echo htmlspecialchars($prescription['customer_name']); ?></span>
                        </div>
                    </li>
                    <li class="info-item">
                        <div class="icon-wrapper">
                           <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div class="info-content">
                            <span class="label">Tanggal Upload</span>
                            <span class="value"><?php echo date('d M Y, H:i', strtotime($prescription['uploaded_at'])); ?></span>
                        </div>
                    </li>
                     <li class="info-item">
                        <div class="icon-wrapper">
                           <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <div class="info-content">
                            <span class="label">Catatan</span>
                            <span class="value fst-italic text-muted">"<?php echo empty($prescription['catatan']) ? '-' : htmlspecialchars($prescription['catatan']); ?>"</span>
                        </div>
                    </li>
                </ul>

                <div class="file-download-card mt-20">
                    <div class="file-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                    </div>
                    <div class="file-info">
                        <span class="file-label">Lampiran Foto</span>
                        <span class="file-name"><?php echo htmlspecialchars($prescription['original_name']); ?></span>
                    </div>
                    <a href="../uploads/resep/<?php echo htmlspecialchars($prescription['file_name']); ?>" target="_blank" class="btn-download">Lihat</a>
                </div>
                
                <form action="../actions/proses_resep_action.php" method="POST" class="mt-20">
                    <input type="hidden" name="prescription_id" value="<?php echo $prescription['id']; ?>">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn-reject-block" onclick="return confirm('Yakin ingin menolak resep ini?')">
                        Tolak Resep Ini
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-form">
        <div class="content-card h-full highlight-card">
            <div class="card-header border-blue">
                <h3 class="text-blue">Input Obat</h3>
            </div>
            <div class="card-body p-25">
                <div class="form-group mb-20">
                    <label class="form-label">Pilih Obat / Produk</label>
                    <div class="select-wrapper">
                        <select id="medicine_select" class="form-input">
                            <option value="">-- Cari Nama Obat --</option>
                            <?php while($prod = $result_products->fetch_assoc()): ?>
                                <option value="<?php echo $prod['id']; ?>" data-name="<?php echo htmlspecialchars($prod['name']); ?>" data-price="<?php echo $prod['price']; ?>">
                                    <?php echo htmlspecialchars($prod['name']); ?> (Stok: <?php echo $prod['stock_quantity']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row-2 mb-25">
                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <div class="input-group-modern">
                             <span class="input-prefix">Qt</span>
                            <input type="number" id="medicine_quantity" class="form-input with-prefix" min="1" value="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Satuan</label>
                         <div class="input-group-modern">
                             <span class="input-prefix">Rp</span>
                            <input type="number" id="medicine_price" class="form-input with-prefix" min="0" placeholder="0">
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-add-action" onclick="addMedicine()">Tambahkan ke Daftar</button>
            </div>
        </div>
    </div>
</div>

<div class="content-card mt-30">
    <div class="card-header">
        <h3>Rincian Obat</h3>
    </div>
    <div class="table-responsive">
        <form action="../actions/proses_resep_action.php" method="POST" id="prescriptionForm">
            <input type="hidden" name="prescription_id" value="<?php echo $prescription_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $prescription['user_id']; ?>">
            
            <table class="modern-table spacious-table" id="medicineTable">
                <thead>
                    <tr>
                        <th width="40%"><div class="th-flex">NAMA OBAT</div></th>
                        <th width="15%"><div class="th-flex justify-center">JUMLAH</div></th>
                        <th width="20%"><div class="th-flex justify-end">HARGA SATUAN</div></th>
                        <th width="20%"><div class="th-flex justify-end">SUBTOTAL</div></th>
                        <th width="5%"><div class="th-flex justify-center">AKSI</div></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="emptyRow">
                        <td colspan="5" class="empty-state py-5"><div class="empty-content"><p>Belum ada obat yang ditambahkan.</p></div></td>
                    </tr>
                </tbody>
                <tfoot id="tableFooter" style="display: none;">
                    <tr class="row-total">
                        <td colspan="3" class="text-right label-total">TOTAL ESTIMASI</td>
                        <td colspan="2" class="text-right total-amount-lg" id="grandTotal">Rp 0</td>
                    </tr>
                </tfoot>
            </table>

            <div class="action-footer">
                 <button type="submit" class="btn-save-final">Simpan & Proses Resep</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* CSS SAMA SEPERTI SEBELUMNYA + FIXED WARNA TOMBOL */
    :root { --blue-primary: #2563eb; --blue-hover: #1d4ed8; --blue-light: #eff6ff; --text-dark: #0f172a; --text-gray: #64748b; --border-color: #e2e8f0; --red-danger: #ef4444; --red-bg: #fef2f2; }
    .mt-3 { margin-top: 10px; } .mt-20 { margin-top: 20px; } .mt-30 { margin-top: 30px; } .mb-20 { margin-bottom: 20px; } .mb-25 { margin-bottom: 25px; } .mb-30 { margin-bottom: 30px; } .p-20 { padding: 20px; } .p-25 { padding: 25px; }
    .process-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px; } @media (max-width: 900px) { .process-grid { grid-template-columns: 1fr; } }
    .h-full { height: 100%; } .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; } .header-left { display: flex; flex-direction: column; gap: 5px; } .page-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin: 0; } .page-subtitle { color: var(--text-gray); } .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-gray); font-weight: 600; text-decoration: none; font-size: 0.9rem; } .btn-back:hover { color: var(--blue-primary); }
    .status-badge-lg { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 50px; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; } .status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; }
    .content-card { background: white; border-radius: 12px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); } .card-header { padding: 15px 20px; border-bottom: 1px solid var(--border-color); background: #fff; } .card-header h3 { margin: 0; font-size: 1.1rem; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }
    .info-list { list-style: none; padding: 0; margin: 0; } .info-item { display: flex; align-items: flex-start; gap: 15px; padding: 12px 0; border-bottom: 1px dashed #e2e8f0; } .info-item:last-child { border-bottom: none; }
    .icon-wrapper { width: 36px; height: 36px; background: #eff6ff; color: var(--blue-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; } .info-content { display: flex; flex-direction: column; } .info-content .label { font-size: 0.75rem; color: var(--text-gray); text-transform: uppercase; margin-bottom: 2px; } .info-content .value { font-size: 0.95rem; font-weight: 600; color: var(--text-dark); } .fst-italic { font-style: italic; }
    .file-download-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 15px; display: flex; align-items: center; gap: 12px; } .file-name { display: block; font-weight: 600; color: var(--blue-primary); }
    .highlight-card { background: #fbfdff; border-color: #dbeafe; } .text-blue { color: var(--blue-primary); } .border-blue { border-bottom-color: #e0f2fe; }
    .form-label { font-size: 0.9rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; display: block; } .form-input { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; font-weight: 500; color: var(--text-dark); background: #fff; transition: border-color 0.2s; } .form-input:focus { border-color: var(--blue-primary); outline: none; }
    .input-group-modern { position: relative; display: flex; align-items: center; } .input-prefix { position: absolute; left: 0; top: 0; bottom: 0; width: 45px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-right: 2px solid #e2e8f0; border-radius: 8px 0 0 8px; font-weight: 600; color: var(--text-gray); font-size: 0.85rem; z-index: 2; } .with-prefix { padding-left: 60px; } .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    /* FIXED BUTTONS */
    .btn-add-action { width: 100%; padding: 14px; font-size: 1rem; font-weight: 700; background-color: #2563eb !important; color: #ffffff; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 10px; } .btn-add-action:hover { background-color: #1d4ed8 !important; }
    .btn-save-final { display: inline-flex; align-items: center; gap: 10px; padding: 14px 30px; font-size: 1rem; font-weight: 700; background-color: #10b981 !important; color: white; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); } .btn-save-final:hover { background-color: #059669 !important; transform: translateY(-2px); }
    .btn-reject-block { display: block; width: 100%; padding: 10px; background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; margin-top: 15px; } .btn-reject-block:hover { background: #fee2e2; border-color: #ef4444; }
    .modern-table { width: 100%; border-collapse: collapse; } .spacious-table th { padding: 15px 25px; font-size: 0.8rem; letter-spacing: 0.05em; background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left; } .spacious-table td { padding: 18px 25px; font-size: 0.95rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .th-flex { display: flex; align-items: center; width: 100%; height: 100%; } .justify-center { justify-content: center; } .justify-end { justify-content: flex-end; } .text-right { text-align: right; } .text-center { text-align: center; }
    .label-total { font-weight: 800; font-size: 1rem; color: var(--text-dark); } .total-amount-lg { font-size: 1.2rem; font-weight: 800; color: var(--blue-primary); } .action-footer { padding: 20px 25px; border-top: 1px solid #e2e8f0; text-align: right; background: #fff; }
    .btn-delete-row { background: #fef2f2; color: #ef4444; width: 32px; height: 32px; border-radius: 6px; border: 1px solid #fee2e2; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; } .btn-delete-row:hover { background: #ef4444; color: white; border-color: #ef4444; } .empty-state { text-align: center; color: var(--text-gray); font-style: italic; padding: 30px; }
    .btn-download { display: inline-flex; padding: 5px 12px; background: #eff6ff; color: var(--blue-primary); border-radius: 6px; font-weight: 600; font-size: 0.85rem; text-decoration: none; } .btn-download:hover { background: #dbeafe; }
</style>

<script>
    let medicineCount = 0;
    document.getElementById('medicine_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        if (price) { document.getElementById('medicine_price').value = price; } 
        else { document.getElementById('medicine_price').value = ''; }
    });

    function addMedicine() {
        const medicineSelect = document.getElementById('medicine_select');
        const medicineId = medicineSelect.value;
        const medicineName = medicineSelect.options[medicineSelect.selectedIndex].getAttribute('data-name');
        const quantity = parseInt(document.getElementById('medicine_quantity').value);
        const price = parseFloat(document.getElementById('medicine_price').value);

        if (!medicineId || !medicineName) { alert("Silakan pilih obat terlebih dahulu."); return; }
        if (isNaN(quantity) || quantity <= 0) { alert("Jumlah harus lebih dari 0."); return; }
        if (isNaN(price) || price < 0) { alert("Harga tidak valid."); return; }

        medicineCount++;
        const subtotal = quantity * price;
        const tableBody = document.querySelector('#medicineTable tbody');
        const emptyRow = document.getElementById('emptyRow');

        if (emptyRow) emptyRow.style.display = 'none';
        document.getElementById('tableFooter').style.display = 'table-row-group';

        const newRow = `
            <tr id="row_${medicineCount}">
                <td>
                    <span class="fw-bold text-dark">${medicineName}</span>
                    <input type="hidden" name="medicines[${medicineCount}][id]" value="${medicineId}">
                    <input type="hidden" name="medicines[${medicineCount}][name]" value="${medicineName}">
                </td>
                <td class="text-center">
                    ${quantity}
                    <input type="hidden" name="medicines[${medicineCount}][quantity]" value="${quantity}">
                </td>
                <td class="text-right">
                    Rp ${price.toLocaleString('id-ID')}
                    <input type="hidden" name="medicines[${medicineCount}][price]" value="${price}">
                </td>
                <td class="text-right fw-bold text-blue subtotal-cell">
                    Rp ${subtotal.toLocaleString('id-ID')}
                    <input type="hidden" class="subtotal-input" value="${subtotal}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn-delete-row mx-auto" onclick="removeMedicine(${medicineCount})">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        updateGrandTotal();
        resetForm();
    }

    function removeMedicine(rowId) {
        document.getElementById(`row_${rowId}`).remove();
        const tableBody = document.querySelector('#medicineTable tbody');
        let hasData = false;
        const rows = tableBody.querySelectorAll('tr');
        rows.forEach(row => { if (row.id !== 'emptyRow') hasData = true; });
        if (!hasData) {
            document.getElementById('emptyRow').style.display = 'table-row';
            document.getElementById('tableFooter').style.display = 'none';
        }
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => { total += parseFloat(input.value); });
        document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function resetForm() {
        document.getElementById('medicine_select').value = "";
        document.getElementById('medicine_quantity').value = 1;
        document.getElementById('medicine_price').value = "";
    }
</script>

<?php
$conn->close();
include 'admin_footer.php';
?>