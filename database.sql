-- Database: cek_status
-- Buat database terlebih dahulu di phpMyAdmin

CREATE DATABASE IF NOT EXISTS cek_status;
USE cek_status;

-- Tabel untuk menyimpan data status
CREATE TABLE status_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    status_1 TINYINT(1) DEFAULT 0,
    status_2 TINYINT(1) DEFAULT 0,
    status_3 TINYINT(1) DEFAULT 0,
    status_4 TINYINT(1) DEFAULT 0,
    status_5 TINYINT(1) DEFAULT 0,
    status_6 TINYINT(1) DEFAULT 0,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO status_data (nama, status_1, status_2, status_3, status_4, status_5, status_6, keterangan) VALUES
('John Doe', 1, 1, 1, 0, 0, 0, 'SK-mu sedang diproses Staf Bagian Hukum'),
('Jane Smith', 1, 1, 1, 1, 1, 0, 'SK-mu sedang ditandatangani Wali Kota'),
('Ahmad Rahman', 1, 0, 0, 0, 0, 0, 'SK-mu sedang ditandatangani Kepala Bagian Hukum'),
('Siti Nurhaliza', 1, 1, 1, 1, 1, 1, 'Yay! SK-mu sudah bisa diambil. Silakan datang ke Bagian Hukum untuk mengambil SK-mu. ^_^');