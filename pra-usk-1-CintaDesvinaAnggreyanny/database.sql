CREATE DATABASE IF NOT EXISTS db_kasir;
USE db_kasir;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'karyawan') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `kategori` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_kategori` VARCHAR(100) NOT NULL
);

CREATE TABLE `menu` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kategori_id` INT,
  `nama_menu` VARCHAR(100) NOT NULL,
  `harga` DECIMAL(10,2) NOT NULL,
  `stok` INT NOT NULL DEFAULT 0,
  `gambar` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`kategori_id`) REFERENCES `kategori`(`id`) ON DELETE SET NULL
);

CREATE TABLE `transaksi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_invoice` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` INT NOT NULL,
  `tanggal` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `total_harga` DECIMAL(12,2) NOT NULL,
  `bayar` DECIMAL(12,2) NOT NULL,
  `kembalian` DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE `detail_transaksi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaksi_id` INT NOT NULL,
  `menu_id` INT NOT NULL,
  `qty` INT NOT NULL,
  `harga_satuan` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`menu_id`) REFERENCES `menu`(`id`)
);

INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`) VALUES ('admin', '$2y$10$7qr4CGfWJTyas8N6rWa7aOBIXiKQEW44IuLErF19lhIRQAL.3IAla', 'Administrator', 'admin');
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`) VALUES ('kasir', '$2y$10$oOi3vpsqWwV1pW1UwmzYaOuy1R0c7Lf9a984sIxpNTaNivuckBaZK', 'Kasir Cinta', 'karyawan');

INSERT INTO `kategori` (`nama_kategori`) VALUES 
('Makanan Utama'), 
('Minuman Dingin'), 
('Minuman Panas'), 
('Cemilan'), 
('Dessert');

INSERT INTO `menu` (`kategori_id`, `nama_menu`, `harga`, `stok`) VALUES 
(1, 'Nasi Goreng Spesial', 25000.00, 50),
(1, 'Mie Goreng Seafood', 28000.00, 40),
(1, 'Ayam Bakar Madu', 30000.00, 30),
(2, 'Es Teh Manis', 5000.00, 100),
(2, 'Es Jeruk Peras', 8000.00, 100),
(2, 'Kopi Susu Gula Aren Es', 15000.00, 50),
(3, 'Teh Tarik Panas', 10000.00, 50),
(3, 'Kopi Hitam', 8000.00, 60),
(4, 'Kentang Goreng', 15000.00, 45),
(4, 'Pisang Bakar Keju', 18000.00, 30),
(5, 'Puding Coklat', 12000.00, 20),
(5, 'Es Krim Vanilla', 15000.00, 25);
