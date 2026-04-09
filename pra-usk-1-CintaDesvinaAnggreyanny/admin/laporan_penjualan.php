<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); 
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'); 

$query = "SELECT t.*, u.nama_lengkap FROM transaksi t JOIN users u ON t.user_id = u.id WHERE DATE(t.tanggal) BETWEEN ? AND ? ORDER BY t.tanggal DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$riwayat = $stmt->get_result();

$total_pendapatan = 0;
$total_transaksi = 0;
while ($row = $riwayat->fetch_assoc()) {
    $total_pendapatan += $row['total_harga'];
    $total_transaksi++;
}

$riwayat->data_seek(0);

require_once '../includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-arrow-left me-2"></i> Laporan Penjualan</a>
  </div>
</nav>

<div class="container mt-4 mb-5">
    
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-filter me-2 text-primary"></i>Filter Periode Penjualan</h5>
            <form action="laporan_penjualan.php" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" value="<?= htmlspecialchars($start_date) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" value="<?= htmlspecialchars($end_date) ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i>Tampilkan Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-success text-white border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <h5 class="mb-2">Total Pendapatan</h5>
                    <h2 class="fw-bold m-0">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-info text-white border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <h5 class="mb-2">Total Transaksi</h5>
                    <h2 class="fw-bold m-0"><?= number_format($total_transaksi, 0, ',', '.') ?> Struk</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fas fa-list-alt me-2 text-secondary"></i>Detail Transaksi</h5>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print me-1"></i> Cetak Laporan</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col">No. Invoice</th>
                            <th scope="col">Waktu Transaksi</th>
                            <th scope="col">Nama Kasir</th>
                            <th scope="col" class="text-end">Total Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total_transaksi > 0): ?>
                            <?php $no = 1; while($row = $riwayat->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold text-primary"><?= htmlspecialchars($row['kode_invoice']) ?></td>
                                <td><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td class="fw-bold text-success text-end">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <tr class="table-group-divider">
                                <td colspan="4" class="text-end fw-bold">TOTAL KESELURUHAN</td>
                                <td class="text-end fw-bold fs-5">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Berdasarkan filter tanggal yang dipilih, belum ada transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <style>
                @media print {
                    nav, .btn, form { display: none !important; }
                    .card, .container { box-shadow: none !important; border: none !important; margin-top: 0 !important;}
                    body { background: white; padding-top: 0; }
                }
            </style>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
