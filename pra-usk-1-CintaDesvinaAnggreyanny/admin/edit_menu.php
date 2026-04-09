<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kelola_menu.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$menu_data = $stmt->get_result()->fetch_assoc();

if (!$menu_data) {
    $_SESSION['error'] = "Data menu tidak ditemukan.";
    header("Location: kelola_menu.php");
    exit;
}

$kategori_query = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

require_once '../includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="kelola_menu.php"><i class="fas fa-arrow-left me-2"></i> Edit Menu</a>
  </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 text-center">Edit Data Menu</h4>
                    
                    <form action="aksi_menu.php?action=edit" method="POST">
                        <input type="hidden" name="id" value="<?= $menu_data['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori Produk</label>
                            <select class="form-select" name="kategori_id" required>
                                <?php while($k = $kategori_query->fetch_assoc()): ?>
                                    <option value="<?= $k['id'] ?>" <?= $k['id'] == $menu_data['kategori_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($k['nama_kategori']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Menu</label>
                            <input type="text" class="form-control" name="nama_menu" value="<?= htmlspecialchars($menu_data['nama_menu']) ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Harga Satuan (Rp)</label>
                                <input type="number" class="form-control" name="harga" value="<?= $menu_data['harga'] ?>" min="0" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Stok</label>
                                <input type="number" class="form-control" name="stok" value="<?= $menu_data['stok'] ?>" min="0" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill">UPDATE MENU</button>
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
