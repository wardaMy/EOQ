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
    header("Location: stock.php");
    exit;
}
include 'db_config.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Koneksi ke database
    // Ambil nilai yang dikirim dari form
    $id_user = $_SESSION['id_user'];
    $id_material = $_POST['id_material'];
    $nama_material = $_POST['nama_material'];
    $satuan_material = $_POST['satuan_material'];
    $biaya_transport = $_POST['biaya_transport'];
    $biaya_telepon = $_POST['biaya_telepon'];
    $lead_time = $_POST['lead_time'];
    $penggunaan = $_POST['penggunaan'];
    $stock_material = $_POST['stock_material'];
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    // Query untuk mengupdate data berdasarkan id_material
    $sql = "UPDATE material SET 
            nama_material = :nama_material, 
            lead_time = :lead_time, 
            biaya_transport = :biaya_transport, 
            biaya_telepon = :biaya_telepon,
            satuan_material = :satuan_material,
            penggunaan = :penggunaan,
            stock_material = :stock_material
            WHERE  id_material= :id_material";
    
    $stmt = $conn->prepare($sql);

    // Bind parameter ke query
    $stmt->bindParam(':nama_material', $nama_material);
    $stmt->bindParam(':id_material', $id_material);
    $stmt->bindParam(':biaya_transport', $biaya_transport);
    $stmt->bindParam(':biaya_telepon', $biaya_telepon);
    $stmt->bindParam(':satuan_material', $satuan_material);
    $stmt->bindParam(':penggunaan', $penggunaan);
    $stmt->bindParam(':lead_time', $lead_time);
    $stmt->bindParam(':stock_material', $stock_material);
    // Eksekusi query
    if ($stmt->execute()) {
        // Jika berhasil disimpan, redirect ke halaman dashboard atau halaman sukses
        header("Location: stock.php");
    } else {
        // Jika gagal disimpan, bisa tambahkan pesan error atau tindakan lainnya
        echo "Gagal mengupdate data pembelian.";
    }
}
if (isset($_GET['id_material'])) {
    $id_material = $_GET['id_material'];

    // Query untuk mengambil data berdasarkan id
    $sql = "SELECT * FROM material WHERE id_material = :id_material";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_material', $id_material, PDO::PARAM_INT);
    $stmt->execute();
    // Mengambil hasil query
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
} else {
    // Jika tidak ada parameter id di URL, redirect ke halaman riwayat produksi
    header("Location: stock.php");
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
        <title>Edit bahan baku</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; 
            include 'sidenav.php'?>
            <div id="layoutSidenav_content">
                <main>
                <div class="container-fluid">
                        <h1 class="mt-4" style="padding-top: 30px;">Edit Bahan Baku</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit Bahan Baku</li>
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
                                    Edit Data Bahan Baku
                                </div>
                                <div class="card-body">
                                    <form action="editmaterial.php" method="POST">
                                        <div class="form-group">
                                        <input type="hidden" name='stock_material' id='stock_material' value=<?=$row['stock_material']?>>
                                        <input type="hidden" name='id_material' id='id_material' value=<?=$row['id_material']?>>
                                            <label for="nama_material">Nama Bahan Baku</label>
                                            <input class="form-control" id="nama_material" name="nama_material" value="<?=$row['nama_material']?>"required>
                                        </div>
                                        <div class="form-group">
                                            <label for="satuan_material">Satuan</label>
                                            <input class="form-control" id="satuan_material" name="satuan_material" value="<?=$row['satuan_material']?>"required>
                                        </div>
                                        <div class="form-group">
                                            <label for="biaya_transport">Biaya transport (tiap pembelian)</label>
                                            <input type="number" class="form-control" id="biaya_transport" name="biaya_transport" min="1" step="1" value="<?=$row['biaya_transport']?>"required>
                                        </div>
                                        <div class="form-group">
                                            <label for="biaya_telepon">Biaya Telepon (tiap pembelian)</label>
                                            <input type="number" class="form-control" id="biaya_telepon" name="biaya_telepon" min="0" step="0.01" value="<?=$row['biaya_telepon']?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="lead_time">Waktu pembelian (tiap pembelian)</label>
                                            <input type="number" class="form-control" id="lead_time" name="lead_time" min="1" step="0.01" value="<?=$row['lead_time']?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="penggunaan">Estimasi pemakaian tiap satuan</label>
                                            <input type="number" class="form-control" id="penggunaan" name="penggunaan" min="1" step="0.01" value="<?=$row['penggunaan']?>" required>
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
