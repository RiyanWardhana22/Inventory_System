<?php
require_once '../../../config/database.php';
require_once '../../../config/functions.php';

$filter = '';
if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
            $tanggal_awal = $_GET['tanggal_awal'];
            $tanggal_akhir = $_GET['tanggal_akhir'];
            $filter = "WHERE b.created_at BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

$sql = "SELECT b.id, b.kode_barang, b.nama_barang, b.stok_awal,
        COALESCE(SUM(bm.jumlah_masuk), 0) as jumlah_masuk,
        COALESCE(SUM(bk.jumlah_keluar), 0) as jumlah_keluar
        FROM barang b
        LEFT JOIN barang_masuk bm ON b.id = bm.barang_id
        LEFT JOIN barang_keluar bk ON b.id = bk.barang_id
        $filter
        GROUP BY b.id";
$result = $conn->query($sql);

require_once '../../../assets/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Stok Barang</h1>';

if (isset($_GET['tanggal_awal']) && isset($_GET['tanggal_akhir'])) {
            $html .= '<p>Periode: ' . formatTanggal($_GET['tanggal_awal']) . ' s/d ' . formatTanggal($_GET['tanggal_akhir']) . '</p>';
} else {
            $html .= '<p>Semua Tanggal</p>';
}

$html .= '
    <table>
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
        <tbody>';

$no = 1;
while ($row = $result->fetch_assoc()) {
            $total = $row['stok_awal'] + $row['jumlah_masuk'] - $row['jumlah_keluar'];
            $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . $row['kode_barang'] . '</td>
                <td>' . $row['nama_barang'] . '</td>
                <td>' . $row['stok_awal'] . '</td>
                <td>' . $row['jumlah_masuk'] . '</td>
                <td>' . $row['jumlah_keluar'] . '</td>
                <td>' . $total . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
    <div style="margin-top: 20px; text-align: right;">
        <div style="margin-bottom: 60px;">Mengetahui,</div>
        <div>_________________________</div>
    </div>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('Laporan_Stok_Barang.pdf', 'I');
