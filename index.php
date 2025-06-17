<?php
$title = 'Dashboard';
$active_menu = 'dashboard';
require_once 'includes/header.php';
?>

<style>
            :root {
                        --primary: #3A7BD5;
                        --primary-gradient: linear-gradient(to right, #00D2FF, #3A7BD5);
                        --success: #4CAF50;
                        --info: #00BCD4;
                        --warning: #FFC107;
                        --danger: #F44336;
                        --purple: #9C27B0;
                        --dark: #343A40;
                        --light: #F8F9FA;
            }

            body {
                        font-family: 'Poppins', sans-serif;
                        background-color: #F5F7FA;
            }

            .card {
                        border: none;
                        border-radius: 12px;
                        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
                        transition: transform 0.3s ease, box-shadow 0.3s ease;
                        overflow: hidden;
            }

            .stat-card {
                        color: white;
                        position: relative;
                        overflow: hidden;
            }

            .stat-card::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: var(--primary-gradient);
                        opacity: 0.9;
                        z-index: 1;
            }

            .stat-card .card-body {
                        position: relative;
                        z-index: 2;
            }

            .stat-card .icon {
                        font-size: 2.5rem;
                        opacity: 0.3;
                        position: absolute;
                        right: 20px;
                        top: 20px;
            }

            .card-primary::before {
                        background: var(--primary-gradient);
            }

            .card-success::before {
                        background: linear-gradient(to right, #4CAF50, #8BC34A);
            }

            .card-info::before {
                        background: linear-gradient(to right, #00BCD4, #03A9F4);
            }

            .card-warning::before {
                        background: linear-gradient(to right, #FFC107, #FF9800);
            }

            .card-purple::before {
                        background: linear-gradient(to right, #9C27B0, #673AB7);
            }

            .card-dark::before {
                        background: linear-gradient(to right, #343A40, #495057);
            }

            .chart-container {
                        position: relative;
                        height: 350px;
                        /* Tinggi chart diperbesar */
            }

            .table-responsive {
                        border-radius: 12px;
                        overflow: hidden;
            }

            .table {
                        border-collapse: separate;
                        border-spacing: 0;
            }

            .table thead th {
                        background-color: var(--primary);
                        color: white;
                        border: none;
            }

            .table tbody tr:hover {
                        background-color: rgba(58, 123, 213, 0.1);
            }

            .section-title {
                        font-weight: 600;
                        color: var(--dark);
                        position: relative;
                        padding-bottom: 10px;
            }

            .section-title::after {
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        width: 50px;
                        height: 3px;
                        background: var(--primary-gradient);
                        border-radius: 3px;
            }

            @media (max-width: 768px) {
                        .stat-card .icon {
                                    font-size: 2rem;
                        }

                        .chart-container {
                                    height: 250px;
                        }
            }
</style>

<div class="container-fluid">
            <!-- Header -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <!-- Stat Cards -->
            <div class="row">
                        <!-- Total Produk -->
                        <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card card-primary h-100">
                                                <div class="card-body">
                                                            <div class="row no-gutters align-items-center">
                                                                        <div class="col mr-2">
                                                                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                                                                                Total Produk</div>
                                                                                    <div class="h2 mb-0 font-weight-bold text-white">
                                                                                                <?php
                                                                                                $sql = "SELECT COUNT(*) as total FROM opname_produk";
                                                                                                $result = $conn->query($sql);
                                                                                                $row = $result->fetch_assoc();
                                                                                                echo $row['total'];
                                                                                                ?>
                                                                                    </div>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                                    <i class="bi bi-tags icon"></i>
                                                                        </div>
                                                            </div>
                                                </div>
                                    </div>
                        </div>

                        <!-- Total User -->
                        <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card card-success h-100">
                                                <div class="card-body">
                                                            <div class="row no-gutters align-items-center">
                                                                        <div class="col mr-2">
                                                                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                                                                                Total User</div>
                                                                                    <div class="h2 mb-0 font-weight-bold text-white">
                                                                                                <?php
                                                                                                $sql = "SELECT COUNT(*) as total FROM users";
                                                                                                $result = $conn->query($sql);
                                                                                                $row = $result->fetch_assoc();
                                                                                                echo $row['total'];
                                                                                                ?>
                                                                                    </div>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                                    <i class="bi bi-people icon"></i>
                                                                        </div>
                                                            </div>
                                                </div>
                                    </div>
                        </div>

                        <!-- Total Opname -->
                        <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card card-info h-100">
                                                <div class="card-body">
                                                            <div class="row no-gutters align-items-center">
                                                                        <div class="col mr-2">
                                                                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                                                                                Total Opname</div>
                                                                                    <div class="h2 mb-0 font-weight-bold text-white">
                                                                                                <?php
                                                                                                $sql = "SELECT COUNT(DISTINCT tanggal) as total_opname FROM opname_produk";
                                                                                                $result = $conn->query($sql);
                                                                                                $row = $result->fetch_assoc();
                                                                                                echo $row['total_opname'];
                                                                                                ?>
                                                                                    </div>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                                    <i class="bi bi-clipboard-check icon"></i>
                                                                        </div>
                                                            </div>
                                                </div>
                                    </div>
                        </div>

                        <!-- Total Produk Masuk -->
                        <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card card-purple h-100">
                                                <div class="card-body">
                                                            <div class="row no-gutters align-items-center">
                                                                        <div class="col mr-2">
                                                                                    <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                                                                                Total Produk Masuk</div>
                                                                                    <div class="h2 mb-0 font-weight-bold text-white">
                                                                                                <?php
                                                                                                $sql = "SELECT SUM(jumlah_produk) as total_masuk FROM produk";
                                                                                                $result = $conn->query($sql);
                                                                                                $row = $result->fetch_assoc();
                                                                                                echo $row['total_masuk'];
                                                                                                ?>
                                                                                    </div>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                                    <i class="bi bi-box-seam icon"></i>
                                                                        </div>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>

            <!-- Chart Penjualan Mingguan (Full Width) -->
            <div class="row mb-4">
                        <div class="col-12">
                                    <div class="card">
                                                <div class="card-header bg-white border-0">
                                                            <h5 class="section-title">Penjualan Produk Mingguan</h5>
                                                </div>
                                                <div class="card-body">
                                                            <div class="chart-container">
                                                                        <canvas id="penjualanMingguanChart"></canvas>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>

            <!-- Tables Row -->
            <div class="row">
                        <!-- Produk Terbaru -->
                        <div class="col-lg-6 mb-4">
                                    <div class="card">
                                                <div class="card-header bg-white border-0">
                                                            <h5 class="section-title">Produk Terbaru Ditambahkan</h5>
                                                </div>
                                                <div class="card-body">
                                                            <div class="table-responsive">
                                                                        <table class="table table-hover">
                                                                                    <thead>
                                                                                                <tr>
                                                                                                            <th>Nama Produk</th>
                                                                                                            <th>Jumlah</th>
                                                                                                            <th>Tanggal Masuk</th>
                                                                                                </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                                <?php
                                                                                                $sql_new_products = "SELECT nama_produk, jumlah_produk, tanggal FROM produk ORDER BY created_at DESC LIMIT 5";
                                                                                                $result_new_products = $conn->query($sql_new_products);
                                                                                                if ($result_new_products->num_rows > 0) {
                                                                                                            while ($row_new = $result_new_products->fetch_assoc()) {
                                                                                                                        echo "<tr>";
                                                                                                                        echo "<td>" . htmlspecialchars($row_new['nama_produk']) . "</td>";
                                                                                                                        echo "<td>" . htmlspecialchars($row_new['jumlah_produk']) . "</td>";
                                                                                                                        echo "<td>" . htmlspecialchars($row_new['tanggal']) . "</td>";
                                                                                                                        echo "</tr>";
                                                                                                            }
                                                                                                } else {
                                                                                                            echo "<tr><td colspan='3'>Tidak ada produk terbaru.</td></tr>";
                                                                                                }
                                                                                                ?>
                                                                                    </tbody>
                                                                        </table>
                                                            </div>
                                                </div>
                                    </div>
                        </div>

                        <!-- Produk Terlaris -->
                        <div class="col-lg-6 mb-4">
                                    <div class="card">
                                                <div class="card-header bg-white border-0">
                                                            <h5 class="section-title">Produk Terlaris</h5>
                                                </div>
                                                <div class="card-body">
                                                            <div class="table-responsive">
                                                                        <table class="table table-hover">
                                                                                    <thead>
                                                                                                <tr>
                                                                                                            <th>Nama Produk</th>
                                                                                                            <th>Total Penjualan</th>
                                                                                                </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                                <?php
                                                                                                $sql_top_selling = "SELECT nama_produk, SUM(penjualan) AS total_penjualan FROM opname_produk GROUP BY nama_produk ORDER BY total_penjualan DESC LIMIT 5";
                                                                                                $result_top_selling = $conn->query($sql_top_selling);
                                                                                                if ($result_top_selling->num_rows > 0) {
                                                                                                            while ($row_top = $result_top_selling->fetch_assoc()) {
                                                                                                                        echo "<tr>";
                                                                                                                        echo "<td>" . htmlspecialchars($row_top['nama_produk']) . "</td>";
                                                                                                                        echo "<td>" . htmlspecialchars($row_top['total_penjualan']) . "</td>";
                                                                                                                        echo "</tr>";
                                                                                                            }
                                                                                                } else {
                                                                                                            echo "<tr><td colspan='2'>Tidak ada data penjualan.</td></tr>";
                                                                                                }
                                                                                                ?>
                                                                                    </tbody>
                                                                        </table>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>
</div>

<?php
require_once 'includes/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
            <?php
            $penjualan_data = [];
            $labels_penjualan = [];
            for ($i = 6; $i >= 0; $i--) {
                        $date = date('Y-m-d', strtotime("-$i days"));
                        $sql_daily_sales = "SELECT SUM(penjualan) as daily_total FROM opname_produk WHERE tanggal = '$date'";
                        $result_daily_sales = $conn->query($sql_daily_sales);
                        $row_daily_sales = $result_daily_sales->fetch_assoc();
                        $penjualan_data[] = $row_daily_sales['daily_total'] ? $row_daily_sales['daily_total'] : 0;
                        $labels_penjualan[] = date('d M', strtotime($date));
            }
            ?>

            var ctxPenjualan = document.getElementById("penjualanMingguanChart");
            var penjualanMingguanChart = new Chart(ctxPenjualan, {
                        type: 'line',
                        data: {
                                    labels: <?php echo json_encode($labels_penjualan); ?>,
                                    datasets: [{
                                                label: "Penjualan",
                                                lineTension: 0.3,
                                                backgroundColor: "rgba(58, 123, 213, 0.05)",
                                                borderColor: "rgba(58, 123, 213, 1)",
                                                pointRadius: 3,
                                                pointBackgroundColor: "rgba(58, 123, 213, 1)",
                                                pointBorderColor: "rgba(58, 123, 213, 1)",
                                                pointHoverRadius: 3,
                                                pointHoverBackgroundColor: "rgba(58, 123, 213, 1)",
                                                pointHoverBorderColor: "rgba(58, 123, 213, 1)",
                                                pointHitRadius: 10,
                                                pointBorderWidth: 2,
                                                data: <?php echo json_encode($penjualan_data); ?>,
                                    }],
                        },
                        options: {
                                    maintainAspectRatio: false,
                                    layout: {
                                                padding: {
                                                            left: 10,
                                                            right: 25,
                                                            top: 25,
                                                            bottom: 0
                                                }
                                    },
                                    scales: {
                                                x: {
                                                            time: {
                                                                        unit: 'date'
                                                            },
                                                            grid: {
                                                                        display: false,
                                                                        drawBorder: false
                                                            },
                                                            ticks: {
                                                                        maxTicksLimit: 7
                                                            }
                                                },
                                                y: {
                                                            ticks: {
                                                                        maxTicksLimit: 5,
                                                                        padding: 10,
                                                                        callback: function(value, index, values) {
                                                                                    return '' + value;
                                                                        }
                                                            },
                                                            grid: {
                                                                        color: "rgb(234, 236, 244)",
                                                                        zeroLineColor: "rgb(234, 236, 244)",
                                                                        drawBorder: false,
                                                                        borderDash: [2],
                                                                        zeroLineBorderDash: [2]
                                                            }
                                                },
                                    },
                                    legend: {
                                                display: false
                                    },
                                    tooltips: {
                                                backgroundColor: "rgb(255,255,255)",
                                                bodyFontColor: "#858796",
                                                titleMarginBottom: 10,
                                                titleFontColor: '#6e707e',
                                                titleFontSize: 14,
                                                borderColor: '#dddfeb',
                                                borderWidth: 1,
                                                xPadding: 15,
                                                yPadding: 15,
                                                displayColors: false,
                                                intersect: false,
                                                mode: 'index',
                                                caretPadding: 10,
                                                callbacks: {
                                                            label: function(tooltipItem, chart) {
                                                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                                                        return datasetLabel + ': ' + tooltipItem.yLabel;
                                                            }
                                                }
                                    }
                        }
            });
</script>