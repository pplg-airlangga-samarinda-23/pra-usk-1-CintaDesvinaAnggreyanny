<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

require_once '../includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Dashboard Kasir</a>
    <div class="d-flex align-items-center">
        <span class="navbar-text me-3 text-white">
            <i class="fas fa-user-circle me-1"></i> Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>
        </span>
        <a href="<?= BASE_URL ?>/auth/logout.php" class="btn btn-danger btn-sm">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-3">Selamat Datang, Kasir!</h2>
                    <p class="text-muted">Ini adalah halaman dashboard untuk kasir. Di sini Anda bisa memproses transaksi pemesanan menu makanan & minuman.</p>
                    <hr>
                    <div class="d-flex gap-3 mt-4">
                        <a href="transaksi.php" class="btn btn-success"><i class="fas fa-cash-register me-2"></i>Mulai Transaksi Baru</a>
                        <a href="riwayat_transaksi.php" class="btn btn-secondary"><i class="fas fa-history me-2"></i>Riwayat Transaksi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
