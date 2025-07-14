<?php
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Opname Produk';
$active_menu = 'opname_produk';
?>

<style>
            .btn {
                        margin-bottom: 5px;
            }
</style>

<div class="container-fluid ">
            <div class="card shadow border-0 rounded-lg mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Opname Produk</h6>
                                    <a href="tambah.php" class="btn btn-primary d-flex align-items-center">
                                                <i class="bi bi-plus me-2"></i>
                                                Tambah Data
                                    </a>
                        </div>
                        <div class="card-body">
                                    <div class="table-responsive">
                                                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                                            <thead class="bg-light">
                                                                        <tr>
                                                                                    <th class="text-center">NO</th>
                                                                                    <th>TANGGAL</th>
                                                                                    <th>NAMA PRODUK</th>
                                                                                    <th class="text-end">STOK AWAL</th>
                                                                                    <th class="text-end">STOK AKHIR</th>
                                                                                    <th class="text-end">PENJUALAN</th>
                                                                                    <th class="text-end">BS</th>
                                                                                    <th class="text-center">AKSI</th>
                                                                        </tr>
                                                            </thead>
                                                            <tbody>
                                                                        <?php
                                                                        $sql = "SELECT op.* FROM opname_produk op ORDER BY op.tanggal DESC";
                                                                        $result = $conn->query($sql);
                                                                        $no = 1;
                                                                        while ($row = $result->fetch_assoc()) {
                                                                        ?>
                                                                                    <tr>
                                                                                                <td class="text-center"><?= $no++; ?></td>
                                                                                                <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))); ?></td>
                                                                                                <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                                                                                <td class="text-center"><?= htmlspecialchars($row['stok_awal']); ?></td>
                                                                                                <td class="text-center"><?= htmlspecialchars($row['stok_akhir']); ?></td>
                                                                                                <td class="text-center"><?= htmlspecialchars($row['penjualan']); ?></td>
                                                                                                <td class="text-center"><?= htmlspecialchars($row['bs']); ?></td>
                                                                                                <td class="d-flex justify-content-center gap-2">
                                                                                                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                                                                                                        <i class="bi bi-pencil"></i>
                                                                                                            </a>
                                                                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="hapus.php?id=<?= $row['id']; ?>" title="Hapus">
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