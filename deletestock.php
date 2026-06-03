<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Memeriksa apakah pengguna adalah owner
if ($_SESSION['role'] !== 'Owner') {
    // Jika pengguna bukan owner, redirect ke halaman sebelumnya atau halaman error
    header("Location: historystock.php");
    exit;
}

// Koneksi ke database
include 'db_config.php';

// Memeriksa apakah parameter id ada di URL
if (isset($_GET['id_pembelian'])) {
    $id_pembelian = $_GET['id_pembelian'];

    // Query untuk menghapus data berdasarkan id
    $sql = "DELETE FROM data_beli WHERE id_pembelian = :id_pembelian";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_pembelian', $id_pembelian);
    
    // query dan cek apakah penghapusan berhasil
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman riwayat produksi
        header("Location: historystock.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        $error = "Gagal menghapus data!";
    }
} else {
    // Jika tidak ada parameter id di URL, redirect ke halaman riwayat produksi
    header("Location: historystock.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Data</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Delete Data</h2>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
