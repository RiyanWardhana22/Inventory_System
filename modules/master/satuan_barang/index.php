<?php
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Satuan Barang';
$active_menu = 'master';
$active_submenu = 'satuan_barang';
?>

<div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Satuan Barang</h1>
            </div>

            <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Satuan Barang</h6>
                                    <a href="tambah.php" class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus"></i> Tambah Data
                                    </a>
                        </div>
                        <div class="card-body">
                                    <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                            <thead>
                                                                        <tr>
                                                                                    <th width="5%">NO</th>
                                                                                    <th>SATUAN</th>
                                                                                    <th width="15%">ACTION</th>
                                                                        </tr>
                                                            </thead>
                                                            <tbody>
                                                                        <?php
                                                                        $sql = "SELECT * FROM satuan_barang ORDER BY satuan_barang ASC";
                                                                        $result = $conn->query($sql);
                                                                        $no = 1;
                                                                        while ($row = $result->fetch_assoc()) {
                                                                        ?>
                                                                                    <tr>
                                                                                                <td><?= $no++; ?></td>
                                                                                                <td><?= htmlspecialchars($row['satuan_barang']); ?></td>
                                                                                                <td>
                                                                                                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                                                                                                        <i class="bi bi-pencil"></i>
                                                                                                            </a>
                                                                                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-url="hapus.php?id=<?= $row['id']; ?>">
                                                                                                                        <i class="bi bi-trash"></i>
                                                                                                            </button>
                                                                                                </td>
                                                                                    </tr>
                                                                        <?php } ?>
                                                            </tbody>
                                                </table>
                                    </div>
                        </div>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>