<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Laporan Opname Produk';
$active_menu = 'laporan';

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$sql = "SELECT * FROM opname_produk";
$where_clauses = [];
$bind_types = '';
$bind_params = [];
if (!empty($start_date)) {
            $where_clauses[] = "tanggal >= ?";
            $bind_types .= 's';
            $bind_params[] = $start_date;
}
if (!empty($end_date)) {
            $where_clauses[] = "tanggal <= ?";
            $bind_types .= 's';
            $bind_params[] = $end_date;
}
if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$sql .= " ORDER BY tanggal DESC";
$stmt = $conn->prepare($sql);

if (!empty($bind_params)) {
            $refs = [];
            foreach ($bind_params as $key => $value) {
                        $refs[$key] = &$bind_params[$key];
            }
            array_unshift($refs, $bind_types);
            call_user_func_array([$stmt, 'bind_param'], $refs);
}

$stmt->execute();
$result = $stmt->get_result();

$data_laporan = [];
while ($row = $result->fetch_assoc()) {
            $data_laporan[] = $row;
}
?>

<div class="container-fluid">
            <div class="card shadow border-0 rounded-lg mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Laporan Opname Produk</h6>
                        </div>
                        <div class="card-body">
                                    <form class="mb-4" method="GET" action="">
                                                <div class="row g-3 align-items-end">
                                                            <div class="col-md-4 col-lg-3">
                                                                        <label for="start_date" class="form-label text-muted small">Tanggal Awal</label> <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                                                            </div>
                                                            <div class="col-md-4 col-lg-3">
                                                                        <label for="end_date" class="form-label text-muted small">Tanggal Akhir</label> <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                                                            </div>
                                                            <div class="col-md-4 col-lg-6 d-flex justify-content-start gap-2">
                                                                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                                                                                    <i class="bi bi-filter me-2"></i> Filter
                                                                        </button>
                                                                        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary d-flex align-items-center">
                                                                                    <i class="bi bi-arrow-clockwise me-2"></i> Reset
                                                                        </a>
                                                                        <button type="button" class="btn btn-info print-btn d-flex align-items-center" onclick="printReport()">
                                                                                    <i class="bi bi-printer me-2"></i> Print
                                                                        </button>
                                                            </div>
                                                </div>
                                    </form>
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
                                                                        </tr>
                                                            </thead>
                                                            <tbody>
                                                                        <?php $no = 1;
                                                                        if (!empty($data_laporan)):
                                                                                    foreach ($data_laporan as $row): ?>
                                                                                                <tr>
                                                                                                            <td class="text-center"><?= $no++; ?></td>
                                                                                                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))); ?></td>
                                                                                                            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                                                                                            <td class="text-end"><?= htmlspecialchars($row['stok_awal']); ?></td>
                                                                                                            <td class="text-end"><?= htmlspecialchars($row['stok_akhir']); ?></td>
                                                                                                            <td class="text-end"><?= htmlspecialchars($row['penjualan']); ?></td>
                                                                                                            <td class="text-end"><?= htmlspecialchars($row['bs']); ?></td>
                                                                                                </tr>
                                                                                    <?php endforeach;
                                                                        else: ?>
                                                                                    <tr>
                                                                                                <td colspan="7" class="text-center text-muted py-4">Tidak ada data untuk ditampilkan.</td>
                                                                                    </tr>
                                                                        <?php endif; ?>
                                                            </tbody>
                                                </table>
                                    </div>
                        </div>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>
<?php ob_end_flush(); ?>