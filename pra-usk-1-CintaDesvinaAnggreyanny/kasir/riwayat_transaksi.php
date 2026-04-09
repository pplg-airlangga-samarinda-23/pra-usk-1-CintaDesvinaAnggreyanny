<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

$stmt = $conn->prepare("SELECT t.*, u.nama_lengkap FROM transaksi t JOIN users u ON t.user_id = u.id ORDER BY t.tanggal DESC LIMIT 50");
$stmt->execute();
$riwayat = $stmt->get_result();

require_once '../includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-arrow-left me-2"></i> Riwayat Transaksi</a>
  </div>
</nav>

<div class="container mt-4">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0"><i class="fas fa-history me-2 text-secondary"></i>History Penjualan Terakhir</h4>
                <a href="transaksi.php" class="btn btn-outline-success"><i class="fas fa-plus me-1"></i> Transaksi Baru</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">No. Invoice</th>
                            <th scope="col">Tanggal & Waktu</th>
                            <th scope="col">Kasir</th>
                            <th scope="col">Total Transaksi</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($riwayat->num_rows > 0): ?>
                            <?php while($row = $riwayat->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= htmlspecialchars($row['kode_invoice']) ?></td>
                                <td><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td class="fw-bold text-success">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <a href="nota.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-receipt"></i> Lihat Nota
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td>
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
