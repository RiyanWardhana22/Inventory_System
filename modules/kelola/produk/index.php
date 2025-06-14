<?php
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Data Produk';
$active_menu = 'master';
$active_submenu = 'produk';
?>

<div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Data Produk</h1>
            </div>

            <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
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
                                                                                    <th>TANGGAL</th>
                                                                                    <th>NAMA PRODUK</th>
                                                                                    <th>KODE PRODUK</th>
                                                                                    <th>JUMLAH PRODUK</th>
                                                                                    <th>KETERANGAN</th>
                                                                                    <th width="15%">ACTION</th>
                                                                        </tr>
                                                            </thead>
                                                            <tbody>
                                                                        <?php
                                                                        $sql = "SELECT * FROM produk ORDER BY tanggal DESC, nama_produk ASC";
                                                                        $result = $conn->query($sql);
                                                                        $no = 1;
                                                                        while ($row = $result->fetch_assoc()) {
                                                                        ?>
                                                                                    <tr>
                                                                                                <td><?= $no++; ?></td>
                                                                                                <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))); ?></td>
                                                                                                <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                                                                                <td><?= htmlspecialchars($row['kode_produk']); ?></td>
                                                                                                <td><?= htmlspecialchars($row['jumlah_produk']); ?></td>
                                                                                                <td><?= htmlspecialchars($row['keterangan']); ?></td>
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