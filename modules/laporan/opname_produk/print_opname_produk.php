<?php
require_once __DIR__ . '/../../../config/database.php'; // Hanya butuh koneksi database
// require_once __DIR__ . '/../../../includes/functions.php'; // Untuk formatTanggal() jika ada

// Ambil tanggal awal dan akhir dari parameter GET
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
$sql .= " ORDER BY tanggal ASC"; // Urutkan tanggal agar lebih baik untuk laporan

$stmt = $conn->prepare($sql);

if (!empty($bind_params)) {
            call_user_func_array([$stmt, 'bind_param'], array_merge([$bind_types], $bind_params));
}

$stmt->execute();
$result = $stmt->get_result();
$data_laporan = [];
while ($row = $result->fetch_assoc()) {
            $data_laporan[] = $row;
}

// Informasi periode laporan untuk header print
$periode = "Semua Tanggal";
if (!empty($start_date) && !empty($end_date)) {
            $periode = "Dari " . formatTanggal($start_date) . " Hingga " . formatTanggal($end_date);
} elseif (!empty($start_date)) {
            $periode = "Mulai " . formatTanggal($start_date);
} elseif (!empty($end_date)) {
            $periode = "Hingga " . formatTanggal($end_date);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cetak Laporan Opname Produk</title>
            <style>
                        body {
                                    font-family: Arial, sans-serif;
                                    margin: 20mm;
                        }

                        h1,
                        h2 {
                                    text-align: center;
                        }

                        table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-top: 20px;
                        }

                        th,
                        td {
                                    border: 1px solid #000;
                                    padding: 8px;
                                    text-align: left;
                        }

                        th {
                                    background-color: #f2f2f2;
                        }

                        .text-center {
                                    text-align: center;
                        }
            </style>
</head>

<body>
            <h1>Laporan Opname Produk</h1>
            <h2 class="text-center"><?= htmlspecialchars($periode) ?></h2>
            <table>
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
            <script>
                        window.onload = function() {
                                    window.print(); // Otomatis memicu dialog print
                                    // Opsional: Tutup jendela setelah mencetak
                                    // window.onafterprint = function() { window.close(); }
                        }
            </script>
</body>

</html>