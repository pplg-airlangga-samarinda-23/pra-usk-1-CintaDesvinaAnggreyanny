<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

$kategori_query = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

require_once '../includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="kelola_menu.php"><i class="fas fa-arrow-left me-2"></i> Tambah Menu</a>
  </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 text-center">Form Menu Baru</h4>
                    
                    <form action="aksi_menu.php?action=tambah" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori Produk</label>
                            <select class="form-select" name="kategori_id" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                <?php while($k = $kategori_query->fetch_assoc()): ?>
                                    <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Menu</label>
                            <input type="text" class="form-control" name="nama_menu" placeholder="Contoh: Nasi Goreng Seafood" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Harga Satuan (Rp)</label>
                                <input type="number" class="form-control" name="harga" placeholder="0" min="0" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Stok Awal</label>
                                <input type="number" class="form-control" name="stok" placeholder="0" min="0" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill">SIMPAN MENU BARU</button>
                            <a href="kelola_menu.php" class="btn btn-light rounded-pill">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
