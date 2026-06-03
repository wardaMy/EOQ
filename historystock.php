<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Check if the user is an owner
$is_owner = ($_SESSION['role'] == 'Owner');

// Koneksi ke database
include 'db_config.php'; 
$sql = "SELECT b.id_pembelian, b.tgl_pembelian, m.nama_material, b.jumlah_pembelian, m.satuan_material, u.username
        FROM data_beli b
        LEFT JOIN material m ON b.id_material = m.id_material
        LEFT JOIN user u ON b.id_user = u.id_user";

$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>History stock</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; 
            include 'sidenav.php'?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4" style="padding-top: 30px;">Riwayat Stock</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Riwayat stock</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
                                Berikut adalah riwayat stock.
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Riwayat stock
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Bahan Baku</th>
                                                <th>Jumlah</th>
                                                <th>Satuan</th>
                                                <th>Keterangan</th>
                                                <?php if ($is_owner): ?>
                                                    <th>Aksi</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['tgl_pembelian']); ?></td>
                                                <td><?php echo htmlspecialchars($row['nama_material']); ?></td>
                                                <td><?php echo htmlspecialchars($row['jumlah_pembelian']); ?></td>
                                                <td><?php echo htmlspecialchars($row['satuan_material']); ?></td>
                                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                <?php if ($is_owner): ?>
                                                    <td>
                                                        <a href="editstock.php?id_pembelian=<?php echo $row['id_pembelian']; ?>" class="btn btn-outline-warning">Edit</a>
                                                        <a href="deletestock.php?id_pembelian=<?php echo $row['id_pembelian']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <div class="card-footer text-center">
                                    <div class="small"><a href="addstock.php">Tambah stock bahan baku</a></div>
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
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
