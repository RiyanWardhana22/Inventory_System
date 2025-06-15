<?php
ob_start(); // Mulai output buffering
require_once __DIR__ . '/../../../includes/header.php'; // Path ke file header Anda

$title = 'Laporan Opname Produk';
$active_menu = 'laporan';
$active_submenu = 'lap_opname_produk'; // Submenu baru untuk laporan ini

// Ambil tanggal awal dan akhir dari parameter GET untuk filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Bangun query SQL dasar
$sql = "SELECT * FROM opname_produk";
$where_clauses = [];
$bind_types = '';
$bind_params = [];

// Tambahkan filter tanggal jika ada
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

// Gabungkan klausa WHERE jika ada
if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

// Tambahkan pengurutan
$sql .= " ORDER BY tanggal DESC";

$stmt = $conn->prepare($sql);

if (!empty($bind_params)) {
            // Memanggil bind_param secara dinamis
            call_user_func_array([$stmt, 'bind_param'], array_merge([$bind_types], $bind_params));
}

$stmt->execute();
$result = $stmt->get_result();

$data_laporan = [];
while ($row = $result->fetch_assoc()) {
            $data_laporan[] = $row;
}

?>

<div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Laporan Opname Produk</h1>
            </div>

            <div class="card shadow mb-4">
                        <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
                        </div>
                        <div class="card-body">
                                    <form method="GET" action="">
                                                <div class="row g-3 align-items-end">
                                                            <div class="col-md-4 col-lg-3">
                                                                        <label for="start_date" class="form-label">Tanggal Awal</label>
                                                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                                                            </div>
                                                            <div class="col-md-4 col-lg-3">
                                                                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                                                                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                                                            </div>
                                                            <div class="col-md-4 col-lg-6 d-flex justify-content-start gap-2">
                                                                        <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Filter</button>
                                                                        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                                                                        <!-- Tombol Print dan PDF -->
                                                                        <button type="button" class="btn btn-info print-btn" onclick="printReport()"><i class="bi bi-printer"></i> Print</button>
                                                                        <button type="button" class="btn btn-danger pdf-btn" onclick="generatePdf()"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
                                                            </div>
                                                </div>
                                    </form>
                        </div>
            </div>

            <div class="card shadow mb-4">
                        <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Laporan Opname Produk</h6>
                        </div>
                        <div class="card-body">
                                    <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                            <thead>
                                                                        <tr>
                                                                                    <th width="5%">NO</th>
                                                                                    <th>TANGGAL</th>
                                                                                    <th>NAMA PRODUK</th>
                                                                                    <th>STOK AWAL</th>
                                                                                    <th>STOK AKHIR</th>
                                                                                    <th>PENJUALAN</th>
                                                                                    <th>BS</th>
                                                                        </tr>
                                                            </thead>
                                                            <tbody>
                                                                        <?php $no = 1;
                                                                        if (!empty($data_laporan)):
                                                                                    foreach ($data_laporan as $row): ?>
                                                                                                <tr>
                                                                                                            <td><?= $no++; ?></td>
                                                                                                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))); ?></td>
                                                                                                            <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                                                                                                            <td><?= htmlspecialchars($row['stok_awal']); ?></td>
                                                                                                            <td><?= htmlspecialchars($row['stok_akhir']); ?></td>
                                                                                                            <td><?= htmlspecialchars($row['penjualan']); ?></td>
                                                                                                            <td><?= htmlspecialchars($row['bs']); ?></td>
                                                                                                </tr>
                                                                                    <?php endforeach;
                                                                        else: ?>
                                                                                    <tr>
                                                                                                <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan.</td>
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

<script>
            // Fungsi untuk Print Laporan (membuka jendela baru untuk tampilan print-friendly)
            function printReport() {
                        let startDate = document.getElementById('start_date').value;
                        let endDate = document.getElementById('end_date').value;
                        let queryString = '';
                        if (startDate) queryString += '&start_date=' + startDate;
                        if (endDate) queryString += '&end_date=' + endDate;

                        // Membuka jendela baru dengan versi laporan khusus print
                        let printWindow = window.open('print_opname_produk.php?' + queryString.substring(1), '_blank');
                        printWindow.focus();
            }

            // Fungsi untuk Generate PDF
            function generatePdf() {
                        let startDate = document.getElementById('start_date').value;
                        let endDate = document.getElementById('end_date').value;
                        let queryString = '';
                        if (startDate) queryString += '&start_date=' + startDate;
                        if (endDate) queryString += '&end_date=' + endDate;

                        // Arahkan ke skrip yang akan menghasilkan PDF
                        // Ini akan membutuhkan library PDF seperti FPDF/Dompdf di sisi server
                        window.location.href = 'generate_pdf_opname_produk.php?' + queryString.substring(1);
            }
</script>

<?php ob_end_flush(); ?>