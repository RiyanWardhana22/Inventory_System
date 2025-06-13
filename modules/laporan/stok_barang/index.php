<?php
$title = 'Laporan Stok Barang';
$active_menu = 'laporan';
$active_submenu = 'lap_stok_barang';
require_once '../../includes/header.php';

// Filter tanggal
$filter = '';
if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
            $tanggal_awal = $_GET['tanggal_awal'];
            $tanggal_akhir = $_GET['tanggal_akhir'];
            $filter = "WHERE b.created_at BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Laporan Stok Barang</h6>
                        <div>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                                                <i class="bi bi-filter"></i> Filter
                                    </button>
                                    <a href="cetak.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="btn btn-success btn-sm" target="_blank">
                                                <i class="bi bi-printer"></i> Cetak
                                    </a>
                        </div>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                            <tr>
                                                                        <th>NO</th>
                                                                        <th>KODE BARANG</th>
                                                                        <th>BARANG</th>
                                                                        <th>STOK AWAL</th>
                                                                        <th>JUMLAH MASUK</th>
                                                                        <th>JUMLAH KELUAR</th>
                                                                        <th>TOTAL</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php
                                                            $sql = "SELECT b.id, b.kode_barang, b.nama_barang, b.stok_awal,
                            COALESCE(SUM(bm.jumlah_masuk), 0) as jumlah_masuk,
                            COALESCE(SUM(bk.jumlah_keluar), 0) as jumlah_keluar
                            FROM barang b
                            LEFT JOIN barang_masuk bm ON b.id = bm.barang_id
                            LEFT JOIN barang_keluar bk ON b.id = bk.barang_id
                            $filter
                            GROUP BY b.id";
                                                            $result = $conn->query($sql);
                                                            $no = 1;
                                                            while ($row = $result->fetch_assoc()) {
                                                                        $total = $row['stok_awal'] + $row['jumlah_masuk'] - $row['jumlah_keluar'];
                                                            ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo $row['kode_barang']; ?></td>
                                                                                    <td><?php echo $row['nama_barang']; ?></td>
                                                                                    <td><?php echo $row['stok_awal']; ?></td>
                                                                                    <td><?php echo $row['jumlah_masuk']; ?></td>
                                                                                    <td><?php echo $row['jumlah_keluar']; ?></td>
                                                                                    <td><?php echo $total; ?></td>
                                                                        </tr>
                                                            <?php } ?>
                                                </tbody>
                                    </table>
                        </div>
            </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                        <div class="modal-content">
                                    <div class="modal-header">
                                                <h5 class="modal-title" id="filterModalLabel">Filter Tanggal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="GET">
                                                <div class="modal-body">
                                                            <div class="mb-3">
                                                                        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                                                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="<?php echo isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : ''; ?>">
                                                            </div>
                                                            <div class="mb-3">
                                                                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                                                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : ''; ?>">
                                                            </div>
                                                </div>
                                                <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                                </div>
                                    </form>
                        </div>
            </div>
</div>

<?php
require_once '../../includes/footer.php';
?>