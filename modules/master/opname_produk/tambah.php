<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Tambah Opname Produk';
$active_menu = 'master';
$active_submenu = 'opname_produk';

$products_stmt = $conn->prepare("SELECT id, nama_barang FROM barang ORDER BY nama_barang ASC");
$products_stmt->execute();
$products_result = $products_stmt->get_result();
$products = [];
while ($row = $products_result->fetch_assoc()) {
            $products[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = trim($_POST['tanggal']);
            $nama_produk = trim($_POST['nama_produk']);
            $stok_awal = trim($_POST['stok_awal']);
            $stok_akhir = trim($_POST['stok_akhir']);
            $penjualan = trim($_POST['penjualan']);
            $bs = trim($_POST['bs']);

            if (empty($tanggal) || empty($nama_produk)) {
                        $_SESSION['error'] = 'Tanggal dan Nama Produk harus diisi.';
            } else {
                        $penjualan = empty($penjualan) ? 0 : (int)$penjualan;

                        $stmt = $conn->prepare("INSERT INTO opname_produk (tanggal, nama_produk, stok_awal, stok_akhir, penjualan, bs) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param('ssssis', $tanggal, $nama_produk, $stok_awal, $stok_akhir, $penjualan, $bs);
                        if ($stmt->execute()) {
                                    $_SESSION['success'] = 'Opname produk berhasil ditambahkan';
                                    header("Location: index.php");
                                    exit;
                        } else {
                                    $_SESSION['error'] = 'Opname produk gagal ditambahkan: ' . $conn->error;
                        }
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Opname Produk</h6>
            </div>
            <div class="card-body">
                        <?php include __DIR__ . '/../../../includes/alert.php';
                        ?>
                        <form method="POST">
                                    <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="nama_produk">Nama Produk</label>
                                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk']) : '' ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="stok_awal">Stok Awal</label>
                                                <input type="text" class="form-control" id="stok_awal" name="stok_awal" value="<?= isset($_POST['stok_awal']) ? htmlspecialchars($_POST['stok_awal']) : '' ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="stok_akhir">Stok Akhir</label>
                                                <input type="text" class="form-control" id="stok_akhir" name="stok_akhir" value="<?= isset($_POST['stok_akhir']) ? htmlspecialchars($_POST['stok_akhir']) : '' ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="penjualan">Penjualan</label>
                                                <input type="number" class="form-control" id="penjualan" name="penjualan" value="<?= isset($_POST['penjualan']) ? htmlspecialchars($_POST['penjualan']) : '' ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="bs">BS (Barang Sisa/Rusak)</label>
                                                <input type="text" class="form-control" id="bs" name="bs" value="<?= isset($_POST['bs']) ? htmlspecialchars($_POST['bs']) : '' ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
ob_end_flush();
?>