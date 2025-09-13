<?php
// config.php - Database connection
$host = 'localhost';
$dbname = 'cek_status';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Admin password
define('ADMIN_PASSWORD', 'cekSK0323');

// Status descriptions
$status_descriptions = [
    1 => 'SK-mu sedang diproses Staf Bagian Hukum',
    2 => 'SK-mu sedang ditandatangani Kepala Bagian Hukum',
    3 => 'SK-mu sedang ditandatangani Sekretaris Daerah',
    4 => 'SK-mu sedang ditandatangani Wali Kota',
    5 => 'SK-mu sudah selesai',
    6 => 'Yay! SK-mu sudah bisa diambil. Silakan datang ke Bagian Hukum untuk mengambil SK-mu. ^_^'
];
?>