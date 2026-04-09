<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action'])) {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'tambah') {
        $kategori_id = $_POST['kategori_id'];
        $nama_menu = $_POST['nama_menu'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        
        $stmt = $conn->prepare("INSERT INTO menu (kategori_id, nama_menu, harga, stok) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $kategori_id, $nama_menu, $harga, $stok);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Menu '$nama_menu' berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambah menu: " . $conn->error;
        }
        
    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        $kategori_id = $_POST['kategori_id'];
        $nama_menu = $_POST['nama_menu'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        
        $stmt = $conn->prepare("UPDATE menu SET kategori_id = ?, nama_menu = ?, harga = ?, stok = ? WHERE id = ?");
        $stmt->bind_param("isiii", $kategori_id, $nama_menu, $harga, $stok, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Menu '$nama_menu' berhasil diupdate.";
        } else {
            $_SESSION['error'] = "Gagal mengubah menu: " . $conn->error;
        }
        
    } elseif ($action === 'hapus' && isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("SELECT nama_menu FROM menu WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result) {
            $nama = $result['nama_menu'];
            $stmt_del = $conn->prepare("DELETE FROM menu WHERE id = ?");
            $stmt_del->bind_param("i", $id);
            if ($stmt_del->execute()) {
                 $_SESSION['success'] = "Menu '$nama' berhasil dihapus.";
            } else {
                 $_SESSION['error'] = "Gagal menghapus menu. Kemungkinan data menu ini berkaitan dengan riwayat transaksi yang ada.";
            }
        }
    }
}

header("Location: kelola_menu.php");
exit;
?>
