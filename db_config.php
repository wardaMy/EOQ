<?php
$host = "localhost"; // Nama host atau IP server database
$dbname = "skripsi"; // Nama database
$user = "root"; // Nama pengguna database
$pass = ""; // Coba tanpa kata sandi

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Atur PDO error mode ke exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>