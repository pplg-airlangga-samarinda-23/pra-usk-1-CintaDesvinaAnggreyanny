<?php
$role = $_SESSION['role'] ?? '';
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4 text-white text-decoration-none" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-utensils fs-3"></i>
        </div>
        <div class="sidebar-brand-text mx-3 fw-bold fs-5">Warteg 88</div>
    </a>

    <hr class="sidebar-divider my-0 text-white-50">

    <?php if ($role === 'admin'): ?>
        <li class="nav-item <?= ($current_dir == 'admin' && $current_page == 'index.php') ? 'active' : '' ?>">
            <a class="nav-link text-white" href="<?= BASE_URL ?>/admin/index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item <?= ($current_dir == 'admin' && ($current_page == 'kategori.php' || strpos($current_page, 'menu') !== false)) ? 'active' : '' ?>">
            <a class="nav-link text-white" href="<?= BASE_URL ?>/admin/menu.php">
                <i class="fas fa-fw fa-hamburger"></i>
                <span>Manajemen Menu</span>
            </a>
        </li>

        <li class="nav-item <?= ($current_dir == 'admin' && $current_page == 'users.php') ? 'active' : '' ?>">
            <a class="nav-link text-white" href="<?= BASE_URL ?>/admin/users.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Manajemen Karyawan</span>
            </a>
        </li>

        <li class="nav-item <?= ($current_dir == 'admin' && $current_page == 'laporan.php') ? 'active' : '' ?>">
            <a class="nav-link text-white" href="<?= BASE_URL ?>/admin/laporan.php">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Laporan Penjualan</span>
            </a>
        </li>

    <?php elseif ($role === 'karyawan'): ?>
        <li class="nav-item <?= ($current_dir == 'kasir' && $current_page == 'index.php') ? 'active' : '' ?>">
            <a class="nav-link text-white" href="<?= BASE_URL ?>/kasir/index.php">
                <i class="fas fa-fw fa-calculator"></i>
                <span>Transaksi POS</span>
            </a>
        </li>
        <li class="nav-item <?= ($current_dir == 'kasir' && $current_page == 'riwayat.php') ? 'active' : '' ?>">
            <a class="nav-link text-white" href="<?= BASE_URL ?>/kasir/riwayat.php">
                <i class="fas fa-fw fa-history"></i>
                <span>Riwayat Transaksi</span>
            </a>
        </li>
    <?php endif; ?>

    <hr class="sidebar-divider d-none d-md-block text-white-50">

    <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>/auth/logout.php">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

</ul>
