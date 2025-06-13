<?php
$title = 'Jenis Barang';
$active_menu = 'master';
$active_submenu = 'jenis_barang';
require_once '../../includes/header.php';
?>

<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Data Jenis Barang</h6>
                        <a href="tambah.php" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus"></i> Tambah Data
                        </a>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                            <tr>
                                                                        <th>NO</th>
                                                                        <th>JENIS BARANG</th>
                                                                        <th>ACTION</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $sql = "SELECT * FROM jenis_barang ORDER BY id DESC";
                                                            $result = $conn->query($sql);
                                                            $no = 1;
                                                            while ($row = $result->fetch_assoc()) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo $row['jenis_barang']; ?></td>
                                                                                    <td>
                                                                                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                                                                                            <i class="bi bi-pencil"></i>
                                                                                                </a>
                                                                                                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                                                                            <i class="bi bi-trash"></i>
                                                                                                </a>
                                                                                    </td>
                                                                        </tr>
                                                            <?php } ?>
                                                </tbody>
                                    </table>
                        </div>
            </div>
</div>

<?php
require_once '../../includes/footer.php';
?>