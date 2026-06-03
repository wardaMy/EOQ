<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">BAJUMI</div>
                    <a class="nav-link" href="dashboard.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProduction" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Data Penjualan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseProduction" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="produk.php">Produk</a>
                            <a class="nav-link" href="historyprod.php">Riwayat Penjualan</a>
                            <a class="nav-link" href="addprod.php">Tambah Penjualan</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Data Bahan Baku
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="stock.php">Bahan Baku</a>
                            <a class="nav-link" href="historystock.php">Riwayat Pembelian</a>
                            <a class="nav-link" href="addstock.php">Tambah Stock </a>
                        </nav>
                    </div>
                    <a class="nav-link" href="forecastprod.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Ramalan Penjualan
                    </a>
                    <a class="nav-link" href="eoq.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Economic Order Quantity
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                <?php
                    if(isset($_SESSION['username'])) {
                        echo htmlspecialchars($_SESSION['username']);
                    } else {
                        echo "Guest";
                    }
                ?>
            </div>
        </nav>
    </div>    
</div>