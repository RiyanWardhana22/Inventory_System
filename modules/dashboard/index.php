<?php
$title = 'Dashboard';
$active_menu = 'dashboard';
require_once '../../includes/header.php';
?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Jenis Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM jenis_barang";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-tags fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Satuan Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM satuan_barang";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box-seam fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Merk Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM merk_barang";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-bookmark-star fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM barang";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box2 fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Customer</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM customers";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Barang Masuk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM barang_masuk";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-arrow-down-circle fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                            Barang Keluar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM barang_keluar";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-arrow-up-circle fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            User</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $sql = "SELECT COUNT(*) as total FROM users";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>