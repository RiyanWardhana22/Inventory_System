<?php
require_once __DIR__ . '/../../../config/database.php';

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
$sql .= " ORDER BY tanggal ASC";

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
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
            <style>
                        :root {
                                    --primary-color: #4361ee;
                                    --secondary-color: #3f37c9;
                                    --accent-color: #4895ef;
                                    --light-color: #f8f9fa;
                                    --dark-color: #212529;
                                    --success-color: #4cc9f0;
                                    --danger-color: #f72585;
                                    --border-radius: 8px;
                                    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        }

                        * {
                                    margin: 0;
                                    padding: 0;
                                    box-sizing: border-box;
                        }

                        body {
                                    font-family: 'Poppins', sans-serif;
                                    line-height: 1.6;
                                    color: var(--dark-color);
                                    background-color: #fff;
                                    padding: 20px;
                        }

                        .container {
                                    max-width: 100%;
                                    margin: 0 auto;
                                    padding: 20px;
                                    background-color: white;
                                    border-radius: var(--border-radius);
                                    box-shadow: var(--box-shadow);
                        }

                        .header {
                                    text-align: center;
                                    margin-bottom: 30px;
                                    padding-bottom: 20px;
                                    border-bottom: 1px solid #eee;
                        }

                        .header h1 {
                                    color: var(--primary-color);
                                    font-size: 28px;
                                    font-weight: 600;
                                    margin-bottom: 10px;
                        }

                        .header .periode {
                                    color: var(--accent-color);
                                    font-size: 16px;
                                    font-weight: 500;
                        }

                        .logo {
                                    max-width: 80px;
                                    margin-bottom: 15px;
                        }

                        table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin: 20px 0;
                                    font-size: 14px;
                                    overflow-x: auto;
                        }

                        th {
                                    background-color: var(--primary-color);
                                    color: white;
                                    font-weight: 500;
                                    padding: 12px 8px;
                                    text-align: left;
                        }

                        td {
                                    padding: 10px 8px;
                                    border-bottom: 1px solid #eee;
                        }

                        tr:nth-child(even) {
                                    background-color: #f8f9fa;
                        }

                        tr:hover {
                                    background-color: #f1f3ff;
                        }

                        .no-data {
                                    text-align: center;
                                    padding: 20px;
                                    color: var(--danger-color);
                                    font-style: italic;
                        }

                        .footer {
                                    text-align: center;
                                    margin-top: 30px;
                                    padding-top: 20px;
                                    border-top: 1px solid #eee;
                                    font-size: 12px;
                                    color: #6c757d;
                        }

                        @media print {
                                    body {
                                                padding: 0;
                                                background: none;
                                    }

                                    .container {
                                                box-shadow: none;
                                    }

                                    .no-print {
                                                display: none;
                                    }
                        }

                        @media (max-width: 768px) {
                                    table {
                                                font-size: 12px;
                                    }

                                    th,
                                    td {
                                                padding: 8px 5px;
                                    }

                                    .header h1 {
                                                font-size: 22px;
                                    }
                        }

                        @media (max-width: 480px) {
                                    body {
                                                padding: 10px;
                                    }

                                    .container {
                                                padding: 10px;
                                    }

                                    table {
                                                display: block;
                                                overflow-x: auto;
                                                white-space: nowrap;
                                    }

                                    .header h1 {
                                                font-size: 18px;
                                    }

                                    .header .periode {
                                                font-size: 14px;
                                    }
                        }
            </style>
</head>

<body>
            <div class="container">
                        <div class="header">
                                    <!-- Tambahkan logo perusahaan jika ada -->
                                    <!-- <img src="path/to/logo.png" alt="Logo Perusahaan" class="logo"> -->
                                    <h1>LAPORAN OPNAME PRODUK</h1>
                                    <div class="periode"><?= htmlspecialchars($periode) ?></div>
                        </div>

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
                                                                        <td colspan="7" class="no-data">Tidak ada data untuk ditampilkan</td>
                                                            </tr>
                                                <?php endif; ?>
                                    </tbody>
                        </table>

                        <div class="footer">
                                    <p>Dicetak pada <?= date('d-m-Y H:i:s') ?> | Sistem Manajemen Inventori</p>
                        </div>
            </div>

            <script>
                        window.onload = function() {
                                    window.print();
                        }
            </script>
</body>

</html>