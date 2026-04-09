<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

$query = "SELECT m.*, k.nama_kategori FROM menu m LEFT JOIN kategori k ON m.kategori_id = k.id";
$result = $conn->query($query);
$menus = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
}

require_once '../includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-arrow-left me-2"></i> Transaksi Baru</a>
  </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Daftar Menu</h5>
                    <div class="row g-3">
                        <?php foreach($menus as $menu): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100 border pe-auto shadow-sm">
                                <div class="card-body text-center d-flex flex-column">
                                    <h6 class="card-title fw-bold"><?= htmlspecialchars($menu['nama_menu']) ?></h6>
                                    <p class="text-muted small mb-2"><?= htmlspecialchars($menu['nama_kategori']) ?></p>
                                    <p class="fs-5 text-success fw-bold mb-auto">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                                    <p class="text-muted small">Stok: <?= $menu['stok'] ?></p>
                                    <button type="button" class="btn btn-outline-success btn-sm mt-3 w-100" 
                                        onclick="addToCart(<?= $menu['id'] ?>, '<?= htmlspecialchars(addslashes($menu['nama_menu'])) ?>', <?= $menu['harga'] ?>)"
                                        <?= $menu['stok'] <= 0 ? 'disabled' : '' ?>>
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($menus)): ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Manajemen belum menambahkan menu.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 sticky-top" style="top: 20px;">
                <form action="proses_transaksi.php" method="POST" id="formTransaksi">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        <ul class="list-group list-group-flush" id="cartList">
                            <li class="list-group-item text-center py-4 text-muted" id="emptyCartMsg">Keranjang masih kosong</li>
                        </ul>
                    </div>
                    <div class="card-footer bg-white border-top p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga</span>
                            <span class="fw-bold fs-5" id="totalHargaTxt">Rp 0</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Jumlah Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control form-control-lg fw-bold" id="inputBayar" name="bayar" required min="0" onkeyup="calculateChange()">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Kembalian</span>
                            <span class="fw-bold text-primary fs-5" id="kembalianTxt">Rp 0</span>
                        </div>
                        
                        <input type="hidden" name="total_harga" id="inputTotalHarga" value="0">
                        
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-pill" id="btnSubmit" disabled>
                            PROSES PEMBAYARAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

function addToCart(id, name, price) {
    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        existingItem.qty++;
    } else {
        cart.push({ id, name, price, qty: 1 });
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    renderCart();
}

function changeQty(id, delta) {
    const item = cart.find(item => item.id === id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) {
            removeFromCart(id);
        } else {
            renderCart();
        }
    }
}

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function renderCart() {
    const cartList = document.getElementById('cartList');
    const emptyCartMsg = document.getElementById('emptyCartMsg');
    const btnSubmit = document.getElementById('btnSubmit');
    
    cartList.innerHTML = '';
    
    let total = 0;
    
    if (cart.length === 0) {
        cartList.innerHTML = '<li class="list-group-item text-center py-4 text-muted" id="emptyCartMsg">Keranjang masih kosong</li>';
        btnSubmit.disabled = true;
    } else {
        btnSubmit.disabled = false;
        
        cart.forEach(item => {
            const subtotal = item.price * item.qty;
            total += subtotal;
            
            const li = document.createElement('li');
            li.className = 'list-group-item py-3';
            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">${item.name}</h6>
                        <small class="text-muted">Rp ${formatRupiah(item.price)}</small>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold d-block mb-2">Rp ${formatRupiah(subtotal)}</span>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary" onclick="changeQty(${item.id}, -1)">-</button>
                            <span class="btn btn-outline-secondary disabled px-3">${item.qty}</span>
                            <button type="button" class="btn btn-outline-secondary" onclick="changeQty(${item.id}, 1)">+</button>
                        </div>
                    </div>
                </div>
                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="menu_id[]" value="${item.id}">
                <input type="hidden" name="qty[]" value="${item.qty}">
                <input type="hidden" name="harga_satuan[]" value="${item.price}">
            `;
            cartList.appendChild(li);
        });
    }
    
    document.getElementById('totalHargaTxt').innerText = 'Rp ' + formatRupiah(total);
    document.getElementById('inputTotalHarga').value = total;
    calculateChange();
}

function calculateChange() {
    const total = parseInt(document.getElementById('inputTotalHarga').value) || 0;
    const bayar = parseInt(document.getElementById('inputBayar').value) || 0;
    const kembalian = bayar - total;
    
    const kembalianTxt = document.getElementById('kembalianTxt');
    
    if (bayar > 0 && kembalian >= 0) {
        kembalianTxt.innerText = 'Rp ' + formatRupiah(kembalian);
        kembalianTxt.classList.remove('text-danger');
        kembalianTxt.classList.add('text-primary');
        if (cart.length > 0) document.getElementById('btnSubmit').disabled = false;
    } else if (bayar > 0 && kembalian < 0) {
        kembalianTxt.innerText = 'Kurang Rp ' + formatRupiah(Math.abs(kembalian));
        kembalianTxt.classList.remove('text-primary');
        kembalianTxt.classList.add('text-danger');
        document.getElementById('btnSubmit').disabled = true;
    } else {
        kembalianTxt.innerText = 'Rp 0';
        kembalianTxt.classList.remove('text-danger', 'text-primary');
        if (bayar === 0 && cart.length > 0) document.getElementById('btnSubmit').disabled = true;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
