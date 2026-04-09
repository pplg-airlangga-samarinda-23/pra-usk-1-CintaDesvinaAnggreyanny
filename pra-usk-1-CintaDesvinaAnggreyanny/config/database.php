<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'db_kasir';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Jakarta');

$base_url = 'http://localhost/KASIR%20USK%20-%20CINTA';
if(!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}
?>
