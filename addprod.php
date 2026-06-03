<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db_config.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Koneksi ke database
    // Ambil nilai yang dikirim dari form
    $id_user = $_SESSION['id_user'];
    $tgl_penjualan = $_POST['tgl_penjualan'];
    $id_product = $_POST['id_product'];
    $jml_penjualan = $_POST['jml_penjualan'];

    // Query untuk memasukkan data ke dalam database
    $sql = "INSERT INTO penjualan (tgl_penjualan, id_product, jml_penjualan, id_user) VALUES (:tgl_penjualan, :id_product, :jml_penjualan, :id_user)";
    $stmt = $conn->prepare($sql);

    // Bind parameter ke query
    $stmt->bindParam(':tgl_penjualan', $tgl_penjualan);
    $stmt->bindParam(':id_product', $id_product);
    $stmt->bindParam(':jml_penjualan', $jml_penjualan);
    $stmt->bindParam(':id_user', $id_user);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika berhasil disimpan, redirect ke halaman dashboard atau halaman sukses
        header("Location: historyprod.php");
    } else {
        // Jika gagal disimpan, bisa tambahkan pesan error atau tindakan lainnya
        echo "Gagal menyimpan data produksi.";
    }
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
        <title>Tambah Produksi</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; 
            include 'sidenav.php'?>
            <div id="layoutSidenav_content">
                <main>
                <div class="container-fluid">
                        <h1 class="mt-4" style="padding-top: 30px;">Tambah data penjualan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tambah data penjualan </li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                Tambah data penjualan
                            </div>
                                <div class="card-body">
                                    <form action="addprod.php" method="POST">
                                        <div class="form-group">
                                            <label for="tgl_penjualan">Tanggal</label>
                                            <input type="date" class="form-control" id="tgl_penjualan" name="tgl_penjualan" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_product">Nama Produk</label>
                                            <select class="form-control" id="id_product" name="id_product" required>
                                                <option value="">Pilih Produk</option>
                                                <?php
                                                    // Query untuk mengambil data produk dari tabel product
                                                    $sql = "SELECT id_product, nama_product FROM product";
                                                    $stmt = $conn->query($sql);
                                                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    // Loop untuk menampilkan opsi produk
                                                    foreach ($products as $product) {
                                                        echo "<option value='{$product['id_product']}'>{$product['nama_product']}</option>";
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="jml_penjualan">Jumlah</label>
                                            <input type="number" class="form-control" id="jml_penjualan" name="jml_penjualan" min="1" step="1"required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="dashboard.php" class="btn btn-secondary">Keluar</a>
                                    </form>
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
