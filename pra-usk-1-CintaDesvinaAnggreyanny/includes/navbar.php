<?php
$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama_lengkap'] ?? 'User';
?>
<nav class="navbar navbar-expand bg-white topbar mb-4 static-top shadow-sm px-4">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
        <i class="fa fa-bars"></i>
    </button>

    <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100">
        <h5 class="mb-0 text-gray-800 fw-bold">Selamat Datang, <?= htmlspecialchars($nama) ?>!</h5>
        <small class="text-muted"><?= date('l, d F Y') ?></small>
    </div>

    <ul class="navbar-nav ms-auto">
    
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small me-2"><?= htmlspecialchars($nama) ?></span>
                <img class="img-profile rounded-circle" src="<?= BASE_URL ?>/assets/img/default-avatar.png" width="32" height="32" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($nama) ?>&background=random'">
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                <li><h6 class="dropdown-header">Profil (<?= ucfirst($role) ?>)</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout.php">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
