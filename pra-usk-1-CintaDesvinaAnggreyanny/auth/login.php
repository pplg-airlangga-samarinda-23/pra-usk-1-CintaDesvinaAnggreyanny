<?php
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-bg">
    <div class="container pe-none">
        <div class="row justify-content-center pe-auto" style="pointer-events: auto;">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card auth-card border-0 shadow-lg my-5">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h3 text-gray-900 mb-2 fw-bold">Warteg 88</h1>
                            <p class="text-muted">Gunakan username dan password Anda untuk masuk.</p>
                        </div>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger rounded-pill text-center">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form class="user" action="<?= BASE_URL ?>/auth/process_login.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control rounded-pill px-4" id="username" name="username" placeholder="Username" required autofocus>
                                <label for="username" class="px-4">Username</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control rounded-pill px-4" id="password" name="password" placeholder="Password" required>
                                <label for="password" class="px-4">Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user w-100 rounded-pill py-3 fw-bold shadow-sm">
                                LOGIN
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
