<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

$query = "SELECT m.*, k.nama_kategori FROM menu m LEFT JOIN kategori k ON m.kategori_id = k.id ORDER BY m.id DESC";
$result = $conn->query($query);

require_once '../includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-arrow-left me-2"></i> Kelola Menu</a>
  </div>
</nav>

<div class="container mt-4">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0"><i class="fas fa-utensils me-2 text-secondary"></i>Daftar Menu Produk</h4>
                <a href="tambah_menu.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Menu Baru</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Menu</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Sisa Stok</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $no = 1; while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama_menu']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                                <td class="text-success fw-bold">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if($row['stok'] > 10): ?>
                                        <span class="badge bg-success"><?= $row['stok'] ?></span>
                                    <?php elseif($row['stok'] > 0): ?>
                                        <span class="badge bg-warning"><?= $row['stok'] ?> Peringatan</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Habis!</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="edit_menu.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="aksi_menu.php?action=hapus&id=<?= $row['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')"><i class="fas fa-trash"></i> Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data menu. Klik "Tambah Menu Baru".</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
