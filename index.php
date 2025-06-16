<?php
$title = 'Dashboard';
$active_menu = 'dashboard';
require_once 'includes/header.php';
?>

<div class="row">
            <div class="col-md-3 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                                                    Produk</div>
                                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                                    <?php
                                                                                    $sql = "SELECT COUNT(*) as total FROM opname_produk";
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
require_once 'includes/footer.php';
?>