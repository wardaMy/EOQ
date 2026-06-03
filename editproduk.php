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
    header("Location: produk.php");
    exit;
}
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $id_user = $_SESSION['id_user'];
    $id_product = $_POST['id_product'];
    $nama_product = $_POST['nama_product'];
    $satuan = $_POST['satuan'];
    $stock_product = $_POST['stock_product'];
    

    // Query untuk mengupdate data product berdasarkan id
    $sql = "UPDATE product SET 
            nama_product = :nama_product, 
            satuan = :satuan, 
            stock_product = :stock_product
            
            WHERE id_product = :id_product";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nama_product', $nama_product);
    $stmt->bindParam(':satuan', $satuan);
    $stmt->bindParam(':stock_product', $stock_product);
   
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);

    // Eksekusi query dan cek apakah update berhasil
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman riwayat produksi
        header("Location: produk.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal mengupdate data!";
    }
}
if (isset($_GET['id_product'])) {
    $id_product = $_GET['id_product'];

    // Query untuk menghapus data berdasarkan id
    $sql = "SELECT * FROM product WHERE id_product = :id_product";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->execute();
    // Mengambil hasil query
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
} else {
    // Jika tidak ada parameter id di URL, redirect ke halaman riwayat produksi
    header("Location: produk.php");
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
        <title>Produk</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; 
            include 'sidenav.php'?>
            <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                        <h1 class="mt-4" style="padding-top: 30px;">Edit Produk</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit Produk</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
                                Edit data yang anda inginkan
                            </div>
                        </div>
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        Edit Data Produk
                                    </div>
                                    <div class="card-body">
                                        <form action="editproduk.php" method="POST">
                                        <input type="hidden" name='stock_product' id='stock_product' value=<?=$row['stock_product']?>>
                                        <input type="hidden" name='id_product' id='id_product' value=<?=$row['id_product']?>>
                                            <div class="form-group">
                                                <label for="nama_product">Nama produk</label>
                                                <input  class="form-control" id="nama_product" name="nama_product" value="<?=$row['nama_product']?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="satuan">Satuan</label>
                                                <input class="form-control" name='satuan' id='satuan' value=<?=$row['satuan']?>>
                                                
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                                        </form>
                                    </div>
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
