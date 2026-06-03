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
    $tgl_pembelian = $_POST['tgl_pembelian'];
    $id_material = $_POST['id_material'];
    $jumlah_pembelian = $_POST['jumlah_pembelian'];
    $harga_beli = $_POST['harga_beli'];
    //$total_biaya = $_POST['total_biaya'];
    // Query untuk memasukkan data ke dalam database
    $sql = "INSERT INTO data_beli (tgl_pembelian, id_material, jumlah_pembelian, harga_beli, id_user) VALUES (:tgl_pembelian, :id_material, :jumlah_pembelian, :harga_beli, :id_user)";
    $stmt = $conn->prepare($sql);

    // Bind parameter ke query
    $stmt->bindParam(':tgl_pembelian', $tgl_pembelian);
    $stmt->bindParam(':id_material', $id_material);
    $stmt->bindParam(':jumlah_pembelian', $jumlah_pembelian);
    $stmt->bindParam(':harga_beli', $harga_beli);
    $stmt->bindParam(':id_user', $id_user);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika berhasil disimpan, redirect ke halaman dashboard atau halaman sukses
        header("Location: historystock.php");
    } else {
        // Jika gagal disimpan, bisa tambahkan pesan error atau tindakan lainnya
        echo "Gagal menyimpan data pembelian.";
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
        <title>Tambah stock</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; 
            include 'sidenav.php'?>
            <div id="layoutSidenav_content">
                <main>
                <div class="container-fluid">
                        <h1 class="mt-4" style="padding-top: 30px;">Tambah Stock</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tambah Stock</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
                                Masukkan data stock yang ingin anda tambahkan
                            </div>
                        </div>
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card">
                                <div class="card-header">
                                    Tambah Data Pembelian stock
                                </div>
                                <div class="card-body">
                                    <form action="addstock.php" method="POST">
                                        <div class="form-group">
                                            <label for="tgl_pembelian">Tanggal Pembelian</label>
                                            <input type="date" class="form-control" id="tgl_pembelian" name="tgl_pembelian" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_material">Nama Bahan Baku</label>
                                            <select class="form-control" id="id_material" name="id_material" required>
                                                <option value="">Pilih Bahan Baku</option>
                                                <?php
                                                    // Query untuk mengambil data produk dari tabel product
                                                    $sql = "SELECT id_material, nama_material, satuan_material FROM material";
                                                    $stmt = $conn->query($sql);
                                                    $material = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    // Loop untuk menampilkan opsi produk
                                                    foreach ($material as $material) {
                                                        echo "<option value='{$material['id_material']}'>{$material['nama_material']} ({$material['satuan_material']})</option>";
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="jumlah_pembelian">Jumlah Pembelian</label>
                                            <input type="number" class="form-control" id="jumlah_pembelian" name="jumlah_pembelian" min="1" step="1"required>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga_beli">Harga Bahan Baku(satuan)</label>
                                            <input type="number" class="form-control" id="harga_beli" name="harga_beli" min="1" step="0.01" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="dashboard.php" class="btn btn-secondary">Keluar</a>
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
