<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db_config.php'; // Sesuaikan dengan file koneksi Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirim dari formulir
    $kebutuhan = $_POST['kebutuhan'];
    $year = $_POST['year'];

    // Simpan hasil permintaan ke dalam database
    foreach ($kebutuhan as $index => $value) {
        // Periksa apakah data sudah ada di database
        $sql_check = "SELECT COUNT(*) AS count FROM permintaan WHERE year = :year AND id_material = :id_material";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':year', $year);
        $stmt_check->bindParam(':id_material', $index);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {
            // Data belum ada, maka simpan ke database
            $sql_insert = "INSERT INTO permintaan (year, id_material, jumlah_permintaan) VALUES (:year, :id_material, :jumlah_permintaan)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bindParam(':year', $year);
            $stmt_insert->bindParam(':id_material', $index);
            $stmt_insert->bindParam(':jumlah_permintaan', $value);
            $stmt_insert->execute();
            $_SESSION['modal_message'] = "Data Sudah berhasil di input";
        } else {
            // Data sudah ada, lakukan tindakan yang sesuai (misalnya, abaikan atau tanggapi sesuai kebutuhan aplikasi)
            echo "Data untuk tahun $year dan material dengan ID $index sudah ada di database. Tidak ada tindakan dilakukan.";
            $_SESSION['modal_message'] = "Data Sudah ada sebelumnya";
        }
    }
    // Redirect kembali ke halaman form dengan modal message
    header("Location: eoq.php");
    exit;
}
?>
