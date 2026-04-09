<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'])) {
    
    $user_id = $_SESSION['user_id'];
    $total_harga = $_POST['total_harga'];
    $bayar = $_POST['bayar'];
    $kembalian = $bayar - $total_harga;
    
    $kode_invoice = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
    
    $conn->begin_transaction();
    
    try {
        $stmt = $conn->prepare("INSERT INTO transaksi (kode_invoice, user_id, total_harga, bayar, kembalian) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siddd", $kode_invoice, $user_id, $total_harga, $bayar, $kembalian);
        $stmt->execute();
        
        $transaksi_id = $conn->insert_id;
        
        $menu_ids = $_POST['menu_id'];
        $qtys = $_POST['qty'];
        $harga_satuans = $_POST['harga_satuan'];
        
        $stmt_detail = $conn->prepare("INSERT INTO detail_transaksi (transaksi_id, menu_id, qty, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt_update_stok = $conn->prepare("UPDATE menu SET stok = stok - ? WHERE id = ?");
        
        for ($i = 0; $i < count($menu_ids); $i++) {
            $menu_id = $menu_ids[$i];
            $qty = $qtys[$i];
            $harga = $harga_satuans[$i];
            $subtotal = $qty * $harga;
            
            $stmt_detail->bind_param("iiidd", $transaksi_id, $menu_id, $qty, $harga, $subtotal);
            $stmt_detail->execute();
            
            $stmt_update_stok->bind_param("ii", $qty, $menu_id);
            $stmt_update_stok->execute();
        }
        
        $conn->commit();
        
        header("Location: nota.php?id=" . $transaksi_id);
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        
        header("Location: transaksi.php?error=TransactionFailed");
        exit;
    }
} else {
    
    header("Location: transaksi.php");
    exit;
}
?>
