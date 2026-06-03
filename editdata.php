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
    header("Location: historyprod.php");
    exit;
}
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $id_penjualan = $_POST['id_penjualan'];
    $tgl_penjualan = $_POST['tgl_penjualan'];
    $id_product = $_POST['id_product'];
    $jml_penjualan = $_POST['jml_penjualan'];
    $id_user = $_POST['id_user'];

    // Query untuk mengupdate data penjualan berdasarkan id_penjualan
    $sql = "UPDATE penjualan SET 
            tgl_penjualan = :tgl_penjualan, 
            id_product = :id_product, 
            jml_penjualan = :jml_penjualan, 
            id_user = :id_user 
            WHERE id_penjualan = :id_penjualan";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tgl_penjualan', $tgl_penjualan);
    $stmt->bindParam(':id_product', $id_product);
    $stmt->bindParam(':jml_penjualan', $jml_penjualan);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':id_penjualan', $id_penjualan, PDO::PARAM_INT);

    // Eksekusi query dan cek apakah update berhasil
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman riwayat produksi
        header("Location: historyprod.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal mengupdate data!";
    }
}

if (isset($_GET['id_penjualan'])) {
    $id_penjualan = $_GET['id_penjualan'];

    // Query untuk menghapus data berdasarkan id
    $sql = "SELECT * FROM penjualan WHERE id_penjualan = :id_penjualan";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_penjualan', $id_penjualan, PDO::PARAM_INT);
    $stmt->execute();
    // Mengambil hasil query
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
} else {
    // Jika tidak ada parameter id di URL, redirect ke halaman riwayat produksi
    header("Location: historyprod.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Tambah Penjualan</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; 
            include 'sidenav.php'?>
            <div id="layoutSidenav_content">
                <main>
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card">
                                <div class="card-header">
                                    Edit Data Penjualan
                                </div>
                                <div class="card-body">
                                    <form action="editdata.php" method="POST">
                                        <div class="form-group">
                                            <label for="tgl_penjualan">Tanggal Penjualan</label>
                                            <input type="date" class="form-control" id="tgl_penjualan" name="tgl_penjualan" value="<?=$row['tgl_penjualan']?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_product">Nama Produk</label>
                                            <input type="hidden" name='id_penjualan' id='id_penjualan' value=<?=$row['id_penjualan']?>>
                                            <input type="hidden" name='id_user' id='id_user' value=<?=$row['id_user']?>>
                                            <select class="form-control" id="id_product" name="id_product" required>
                                                <option value="">Pilih Produk</option>
                                                <?php
                                                    // Query untuk mengambil data produk dari tabel product
                                                    $sql = "SELECT id_product, nama_product FROM product";
                                                    $stmt = $conn->query($sql);
                                                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    // Loop untuk menampilkan opsi produk
                                                    foreach ($products as $product) {
                                                        if ($row['id_product'] == $product['id_product']) {
                                                            echo "<option value='{$product['id_product']}' selected>{$product['nama_product']}</option>";
                                                        }else {
                                                            echo "<option value='{$product['id_product']}'>{$product['nama_product']}</option>";
                                                        }
                                                        
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="jml_penjualan">Jumlah</label>
                                            <input type="number" class="form-control" id="jml_penjualan" name="jml_penjualan" min="1" step="1" value="<?=$row['jml_penjualan']?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <?php include 'footer.html' ; ?>
                </footer>
            </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
