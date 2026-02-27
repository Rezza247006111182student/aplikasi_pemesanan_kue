-- Buat database
CREATE DATABASE IF NOT EXISTS aplikasi_pemesanan_kue;

-- Gunakan database
USE aplikasi_pemesanan_kue;

-- Tabel untuk kue
CREATE TABLE IF NOT EXISTS kue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    gambar VARCHAR(255),
    stok INT DEFAULT 0
);

-- Tabel untuk pengguna
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Tabel untuk pesanan
CREATE TABLE IF NOT EXISTS pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    status ENUM('pending', 'confirmed', 'delivered', 'canceled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel untuk detail pesanan
CREATE TABLE IF NOT EXISTS detail_pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT,
    kue_id INT,
    quantity INT,
    harga DECIMAL(10,2),
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id),
    FOREIGN KEY (kue_id) REFERENCES kue(id)
);
