<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db_config.php'; // Gantikan dengan file koneksi sesuai pengaturan Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $forecasts = $_POST['forecast'];

    foreach ($forecasts as $forecast) {
        $forecast = json_decode($forecast, true);

        // Periksa apakah $forecast memiliki nilai yang valid
        if (isset($forecast['id_product'], $forecast['year'], $forecast['month'], $forecast['prediction'])) {
            $id_product = $forecast['id_product'];
            $year = $forecast['year'];
            $month = $forecast['month'];
            $predicted_production = $forecast['prediction'];

            // Simpan hasil ramalan ke dalam database
            $sql = "INSERT INTO forecast_penjualan (id_product, year, month, predicted_production) VALUES (:id_product, :year, :month, :predicted_production)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_product', $id_product);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':predicted_production', $predicted_production);
            $stmt->execute();
        } else {
            echo "Data ramalan tidak valid.";
            // Handle error case
        }
    }

    echo "Ramalan produksi berhasil disimpan.";
    header("Location: eoq.php");
    exit;
}
?>
