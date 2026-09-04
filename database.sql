CREATE DATABASE IF NOT EXISTS voucher_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE voucher_app;

CREATE TABLE produk (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama_produk VARCHAR(150) NOT NULL,
 kategori ENUM('Pulsa','Internet') NOT NULL,
 operator VARCHAR(50) NOT NULL,
 nominal VARCHAR(100) DEFAULT '',
 harga_modal DECIMAL(15,2) NOT NULL DEFAULT 0,
 harga_jual DECIMAL(15,2) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE voucher_stok (
 id INT AUTO_INCREMENT PRIMARY KEY,
 barcode VARCHAR(150) NOT NULL UNIQUE,
 produk_id INT NOT NULL,
 status ENUM('tersedia','terjual') DEFAULT 'tersedia',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 sold_at DATETIME NULL,
 FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

CREATE TABLE transaksi (
 id INT AUTO_INCREMENT PRIMARY KEY,
 kode_transaksi VARCHAR(40) NOT NULL UNIQUE,
 total DECIMAL(15,2) NOT NULL DEFAULT 0,
 bayar DECIMAL(15,2) NOT NULL DEFAULT 0,
 kembalian DECIMAL(15,2) NOT NULL DEFAULT 0,
 metode VARCHAR(30) DEFAULT 'Tunai',
 status ENUM('selesai','batal') DEFAULT 'selesai',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transaksi_detail (
 id INT AUTO_INCREMENT PRIMARY KEY,
 transaksi_id INT NOT NULL,
 voucher_id INT NOT NULL,
 harga DECIMAL(15,2) NOT NULL,
 FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
 FOREIGN KEY (voucher_id) REFERENCES voucher_stok(id)
);

INSERT INTO produk(nama_produk,kategori,operator,nominal,harga_modal,harga_jual) VALUES
('Pulsa Telkomsel 10.000','Pulsa','Telkomsel','10.000',10000,12000),
('Paket Internet Telkomsel 5GB','Internet','Telkomsel','5 GB',22000,25000),
('Pulsa XL 25.000','Pulsa','XL','25.000',24000,27000);
