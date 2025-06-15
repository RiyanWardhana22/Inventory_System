<?php
// Pastikan Composer autoloader disertakan jika menggunakan Composer
require_once __DIR__ . '/../../../vendor/autoload.php'; // Sesuaikan jalur jika perlu

use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../includes/functions.php'; // Untuk formatTanggal() jika ada

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

// Informasi periode laporan untuk header PDF
$periode = "Semua Tanggal";
if (!empty($start_date) && !empty($end_date)) {
            $periode = "Dari " . formatTanggal($start_date) . " Hingga " . formatTanggal($end_date);
} elseif (!empty($start_date)) {
            $periode = "Mulai " . formatTanggal($start_date);
} elseif (!empty($end_date)) {
            $periode = "Hingga " . formatTanggal($end_date);
}


// HTML content untuk PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Opname Produk</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; margin: 20mm; }
        h1, h2 { text-align: center; margin-bottom: 5px; }
        .periode { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        /* Pastikan font mendukung karakter khusus jika ada */
        @font-face {
            font-family: "DejaVu Sans";
            src: url("' . base_url('assets/fonts/DejaVuSans.ttf') . '") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: "DejaVu Sans";
            src: url("' . base_url('assets/fonts/DejaVuSans-Bold.ttf') . '") format("truetype");
            font-weight: bold;
            font-style: normal;
        }
    </style>
</head>
<body>
    <h1>Laporan Opname Produk</h1>
    <h2 class="periode">' . htmlspecialchars($periode) . '</h2>
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
        <tbody>';

$no = 1;
if (!empty($data_laporan)) {
            foreach ($data_laporan as $row) {
                        $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))) . '</td>
                <td>' . htmlspecialchars($row['nama_produk']) . '</td>
                <td>' . htmlspecialchars($row['stok_awal']) . '</td>
                <td>' . htmlspecialchars($row['stok_akhir']) . '</td>
                <td>' . htmlspecialchars($row['penjualan']) . '</td>
                <td>' . htmlspecialchars($row['bs']) . '</td>
            </tr>';
            }
} else {
            $html .= '
        <tr>
            <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan.</td>
        </tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>';

// Konfigurasi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Penting jika Anda menggunakan gambar atau font eksternal
// Contoh untuk mengaktifkan font DejaVu Sans jika ada masalah dengan karakter non-Latin
// $options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// (Opsional) Atur ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render HTML sebagai PDF
$dompdf->render();

// Keluarkan PDF ke browser (force download atau inline display)
$dompdf->stream("Laporan_Opname_Produk_" . date('Ymd_His') . ".pdf", ["Attachment" => false]);
// Set Attachment to true for direct download, false for inline display in browser
