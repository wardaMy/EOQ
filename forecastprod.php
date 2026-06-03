<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
require 'vendor/autoload.php';

use Phpml\Regression\LeastSquares;

include 'db_config.php'; // Gantikan dengan file koneksi sesuai pengaturan Anda

// Ambil data produksi dari database
$sql = "SELECT id_product, YEAR(tgl_penjualan) AS year, MONTH(tgl_penjualan) AS month, SUM(jml_penjualan) AS total_production 
        FROM penjualan 
        GROUP BY id_product, YEAR(tgl_penjualan), MONTH(tgl_penjualan)";
$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengelompokkan data per id_product
$data = [];
foreach ($rows as $row) {
    $id_product = $row['id_product'];
    $year = $row['year'];
    $month = $row['month'];
    $total_production = $row['total_production'];
    
    if (!isset($data[$id_product])) {
        $data[$id_product] = [];
    }
    $data[$id_product][] = [(($year - 2000) * 12) + $month, $total_production];
}

// Ramal produksi untuk setiap id_product
$forecast = [];
foreach ($data as $id_product => $values) {
    $samples = [];
    $targets = [];
    
    foreach ($values as $value) {
        $samples[] = [$value[0]];
        $targets[] = $value[1];
    }
    
    $regression = new LeastSquares();
    $regression->train($samples, $targets);
    
    // Meramalkan 12 bulan ke depan
    for ($i = 1; $i <= 12; $i++) {
        $next_month = end($samples)[0] + $i;
        $predicted_production = ceil($regression->predict([$next_month]));
        $year = 2000 + floor(($next_month - 1)/ 12);
        $month = $next_month % 12 == 0 ? 12 : $next_month % 12;
        
        $forecast[$id_product][] = [
            'year' => $year,
            'month' => $month,
            'prediction' => $predicted_production
        ];
    }
}

function getNamaBulan($bulan) {
    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    
    return $namaBulan[$bulan];
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
        <title>Hasil Ramal</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>
        <?php include 'sidenav.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4" style="padding-top: 30px;">Ramalan Penjualan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ramalan Penjualan</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            Berikut adalah Ramalan Penjualan selama satu tahun ke depan
                        </div>
                    </div>
                    <div class="card mb-4">
                        <form action="save_forecast.php" method="POST">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Ramalan Penjualan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Batik Oblong (pcs)</th>
                                                <th>Batik Jempol (pcs)</th>
                                                <th>Batik Lar(pcs)</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if ($forecast): ?>
                                                <?php for ($i = 0; $i < 12; $i++): ?>
                                                    <tr>
                                                        <td><?php echo getNamaBulan($forecast[1][$i]['month']); ?></td>
                                                        <td><?php echo $forecast[1][$i]['year']; ?></td>
                                                        <td><?php echo $forecast[1][$i]['prediction']; ?></td>
                                                        <td><?php echo $forecast[2][$i]['prediction']; ?></td>
                                                        <td><?php echo $forecast[3][$i]['prediction']; ?></td>
                                                        <?php for ($id_product = 1; $id_product <= 3; $id_product++): ?>
                                                            <input type="hidden" name="forecast[]" value="<?php echo htmlspecialchars(json_encode([
                                                                'id_product' => $id_product,
                                                                'year' => $forecast[$id_product][$i]['year'],
                                                                'month' => $forecast[$id_product][$i]['month'],
                                                                'prediction' => $forecast[$id_product][$i]['prediction']
                                                            ])); ?>">
                                                        <?php endfor; ?>
                                                    </tr>
                                                <?php endfor; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Simpan Hasil Ramal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <?php include 'footer.html'; ?>
            </footer>
        </div>
      
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="assets/demo/chart-pie-demo.js"></script>
    </body>
</html>
