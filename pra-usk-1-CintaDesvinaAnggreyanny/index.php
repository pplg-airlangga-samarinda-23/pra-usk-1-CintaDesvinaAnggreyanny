<?php
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header("Location: " . BASE_URL . "/admin/index.php");
} else {
    header("Location: " . BASE_URL . "/kasir/index.php");
}
exit;
?>
