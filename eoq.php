<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db_config.php'; // Gantikan dengan file koneksi sesuai pengaturan Anda

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 2, ',', '.');
}

// START KEBUTUHAN BAHAN BAKU

// Ambil data hubungan produk dan bahan baku dari database
$sql = "SELECT id_material, nama_material, penggunaan FROM material";
$stmt = $conn->query($sql);
$product_materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
function getNamaMaterial($id_material, $materials) {
    foreach ($materials as $material) {
        if ($material['id_material'] == $id_material) {
            return $material['nama_material'];
        }
    }
    return "Unknown"; // Jika id tidak ditemukan
}

$year = date('Y');

// Hitung permintaan bahan baku berdasarkan hasil ramalan yang disimpan di database
$sql = "SELECT id_product, year, SUM(predicted_production) as total_production
        FROM forecast_penjualan
        WHERE year = :year
        GROUP BY id_product";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':year', $year); // Mengikat parameter
$stmt->execute();
$forecast_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_production = [];
$kebutuhan = [];

foreach ($forecast_rows as $fr) {
    $total_production[$fr["id_product"]] = $fr["total_production"];
}

if ($total_production) {
    foreach ($product_materials as $pm) {
        if (in_array($pm["id_material"], [1,2,3])) {
            $kebutuhan[$pm["id_material"]] = $total_production[$pm["id_material"]] / $pm["penggunaan"];
        } else{
            $rumus_atas = array_sum($total_production);
            $kebutuhan[$pm["id_material"]] = $rumus_atas / $pm["penggunaan"];
        }
    }
}


// END KEBUTUHAN BAHAN BAKU




// START EOQ

// dirumus ini = D => jumlah_permintaan
$sql = "SELECT id_material, jumlah_permintaan FROM permintaan WHERE year = :year";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':year', $year); // Mengikat parameter
$stmt->execute();
$permintaan_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// dirumus ini = S => biaya_transport + biaya_telepon
$sql = "SELECT * FROM material";
$stmt = $conn->query($sql);
$material_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// dirumus ini = 
$sql = "SELECT id_biayalain, biaya_kebersihan, biaya_listrik FROM biaya_lain";
$stmt = $conn->query($sql);
$biaya_lain_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($biaya_lain_rows as $blr) {
    $holding_cost_year = ($blr['biaya_kebersihan'] + $blr['biaya_listrik']) * 12;
}

$variabel_D_nya = [];
$variabel_S_nya = [];
$variabel_H_nya = [];
$eoq = [];

if ($permintaan_rows) {
    foreach ($permintaan_rows as $pr) {
        $variabel_D_nya[$pr['id_material']] = $pr['jumlah_permintaan'];
    }

    $total_D =  array_sum($variabel_D_nya);

    foreach ($material_rows as $mr) {
        // hitung S
        $variabel_S = $mr['biaya_transport'] + $mr['biaya_telepon'];
        // hitung H
        if ($total_D) {
            $persentase = $variabel_D_nya[$mr['id_material']] / $total_D * 100;
            $persentase = round($persentase);
            $holding_cost_material = $holding_cost_year * $persentase / 100;
            $variabel_H = $holding_cost_material / $variabel_D_nya[$mr['id_material']];
            $variabel_H = round($variabel_H);
            // hitung eoq
            $eoq[$mr['id_material']] = round(sqrt((2 * $variabel_D_nya[$mr['id_material']] * $variabel_S) / $variabel_H));

            $variabel_S_nya[$mr['id_material']] = $variabel_S;
            $variabel_H_nya[$mr['id_material']] = $variabel_H;
        }else{
            $eoq[$mr['id_material']] = '-';
            $variabel_S_nya[$mr['id_material']] = 0;
            $variabel_H_nya[$mr['id_material']] = 0;
        }
        
        
    }
}

// END EOQ


// START : FREKUENSI PEMBELIAN, SS, ROP, TIC

$frekuensi_pembelian = [];
$variabel_SS_nya = [];
$variabel_ROP_nya = [];
$variabel_TIC_nya = [];


if ($permintaan_rows) {
    foreach ($material_rows as $mr) {

        if ($total_D) {
            //hitung frekuensi pembelian
            $frekuensi_pembelian[$mr['id_material']] = ceil($variabel_D_nya[$mr['id_material']] / $eoq[$mr['id_material']]);
        
            // hitung SS
            if (in_array($mr["id_material"], [1,2,3])) {
                $sql = "SELECT id_product, year, predicted_production
                FROM forecast_penjualan
                WHERE year = :year and id_product = :id_product";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':year', $year); // Mengikat parameter
                $stmt->bindParam(':id_product', $mr['id_material']); // Mengikat parameter
                $stmt->execute();
                $forecast_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $D_per_material = [];

                foreach ($forecast_rows as $fr) {
                    $D_per_material[] = $fr['predicted_production'] / $mr['penggunaan'];
                }
            }else{
                $sql = "SELECT month, year, sum(predicted_production) as total_produksi
                FROM forecast_penjualan
                WHERE year = :year
                GROUP BY month";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':year', $year); // Mengikat parameter
                $stmt->execute();
                $forecast_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $D_per_material = [];

                foreach ($forecast_rows as $fr) {
                    $D_per_material[] = $fr['total_produksi'] / $mr['penggunaan'];
                }
            }
            $max = max($D_per_material);
            $sum = array_sum($D_per_material);
            $count = count($D_per_material);
            $average = $sum / $count;

            $variabel_SS_nya[$mr["id_material"]] = ($max - $average) / $mr['lead_time'];

            // hitung ROP
            $variabel_ROP_nya[$mr["id_material"]] = ($variabel_D_nya[$mr["id_material"]] / 365) * $mr['lead_time'] + $variabel_SS_nya[$mr["id_material"]];

            // hitung TIC
            $variabel_TIC_nya[$mr["id_material"]] = ($variabel_D_nya[$mr["id_material"]] / $eoq[$mr["id_material"]] * $variabel_S_nya[$mr["id_material"]]) + ($eoq[$mr["id_material"]] / 2 * $variabel_H_nya[$mr["id_material"]]);
            
        }else{
            $frekuensi_pembelian[$mr['id_material']] = '-';
            $variabel_SS_nya[$mr["id_material"]] = '-';

            // hitung ROP
            $variabel_ROP_nya[$mr["id_material"]] = '-';

            // hitung TIC
            $variabel_TIC_nya[$mr["id_material"]] = 0;
        }

        
    }
}




// END : FREKUENSI PEMBELIAN, SS, ROP, TIC

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>EOQ</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .modal-header, .modal-body, .modal-footer {
            padding: 1.5rem;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <?php include 'sidenav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4" style="padding-top: 30px;">Economic Order Quantity</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">EOQ</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                        Berikut adalah data bahan baku yang dibutuhkan selama satu tahun dan analisis economic order quantity.
                    </div>
                </div>
                <div class="card mb-4">
                <form action="hitung_eoq.php" method="POST">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Kebutuhan Bahan Baku <?php echo $year?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered"  width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Bahan Baku</th>
                                        <th>Jumlah Kebutuhan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kebutuhan as $index => $value): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(getNamaMaterial($index, $product_materials)); ?></td>
                                            <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
                                            <td><?php echo htmlspecialchars(round($value)); ?></td>
                                            <input type="hidden" name="kebutuhan[<?php echo htmlspecialchars($index); ?>]" value="<?php echo htmlspecialchars(round($value)); ?>">
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Hitung EOQ</button>
                            
                        </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Economic Order Quantity <?php echo $year?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered"  width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Bahan Baku</th>
                                        <th>EOQ</th>
                                        <th>Frekuensi Pembelian</th>
                                        <th>SS</th>
                                        <th>ROP</th>
                                        <th>TIC</th>
                                        <!-- <th>D</th> -->
                                        <!-- <th>S</th> -->
                                        <!-- <th>H</th> -->

                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($eoq as $index => $value): ?>
                                        <tr>
                                        <td><?php echo htmlspecialchars(getNamaMaterial($index, $product_materials)); ?></td>
                                            <!-- <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>"> -->
                                            <td><?php echo htmlspecialchars($value); ?></td>
                                            <!-- <input type="hidden" name="kebutuhan[<?php echo htmlspecialchars($index); ?>]" value="<?php echo htmlspecialchars($value); ?>"> -->
                                            <td><?php echo htmlspecialchars($frekuensi_pembelian[$index]); ?></td>
                                            <td><?php echo htmlspecialchars($variabel_SS_nya[$index]); ?></td>
                                            <td><?php echo htmlspecialchars($variabel_ROP_nya[$index]); ?></td>
                                            <td><?php echo htmlspecialchars(formatRupiah(round($variabel_TIC_nya[$index]))); ?></td>
                                            <!-- <td><?php echo htmlspecialchars($variabel_D_nya[$index]); ?></td> -->
                                            <!-- <td><?php echo htmlspecialchars($variabel_S_nya[$index]); ?></td> -->
                                            <!-- <td><?php echo htmlspecialchars($variabel_H_nya[$index]); ?></td> -->
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <?php
                                        // Inisialisasi total TIC
                                        $total_variabel_TIC = 0;

                                        // Menghitung total variabel_TIC_nya
                                        foreach ($variabel_TIC_nya as $TIC_value) {
                                            $total_variabel_TIC += $TIC_value;
                                        }
                                        ?>
                                        <td colspan="5" style="text-align: right;"><strong>Total TIC:</strong></td>
                                        <td><?php echo htmlspecialchars(formatRupiah(round($total_variabel_TIC))); ?></td>
                                    </tr>
                                </tfoot>    
                            </table>                     
                        </div>
                        </div>
                    </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <?php include 'footer.html'; ?>
        </footer>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalku" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Notifikasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?php
                if (isset($_SESSION['modal_message'])) {
                    echo $_SESSION['modal_message'];
                    unset($_SESSION['modal_message']);
                }
                ?>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="assets/demo/datatables-demo.js"></script>
    <!-- Show modal if session message is set -->
    <?php if (isset($_SESSION['modal_message'])): ?> 
        <script type="text/javascript">
            $(document).ready(function(){
                $('#modalku').modal('show');
            });
        </script>
    <?php endif; ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#modalku').modal('show');
        });
    </script>
</body>
</html>
