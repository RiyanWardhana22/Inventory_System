<?php
$title = 'Barang Masuk';
$active_menu = 'transaksi';
$active_submenu = 'barang_masuk';
require_once '../../includes/header.php';
?>

<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Data Barang Masuk</h6>
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
                                                                        <th>TANGGAL MASUK</th>
                                                                        <th>KODE BARANG MASUK</th>
                                                                        <th>KODE BARANG</th>
                                                                        <th>CUSTOMER</th>
                                                                        <th>BARANG</th>
                                                                        <th>JUMLAH MASUK</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $sql = "SELECT bm.*, b.kode_barang, b.nama_barang, c.nama_customer 
                            FROM barang_masuk bm
                            JOIN barang b ON bm.barang_id = b.id
                            JOIN customers c ON bm.customer_id = c.id
                            ORDER BY bm.tanggal_masuk DESC";
                                                            $result = $conn->query($sql);
                                                            $no = 1;
                                                            while ($row = $result->fetch_assoc()) {
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo date('d F Y', strtotime($row['tanggal_masuk'])); ?></td>
                                                                                    <td><?php echo $row['kode_barang_masuk']; ?></td>
                                                                                    <td><?php echo $row['kode_barang']; ?></td>
                                                                                    <td><?php echo $row['nama_customer']; ?></td>
                                                                                    <td><?php echo $row['nama_barang']; ?></td>
                                                                                    <td><?php echo $row['jumlah_masuk']; ?></td>
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