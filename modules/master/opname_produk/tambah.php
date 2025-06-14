<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Tambah Opname Produk';
$active_menu = 'master';
$active_submenu = 'opname_produk';

// Fetch products for the dropdown
$products_stmt = $conn->prepare("SELECT id, nama_barang FROM barang ORDER BY nama_barang ASC");
$products_stmt->execute();
$products_result = $products_stmt->get_result();
$products = [];
while ($row = $products_result->fetch_assoc()) {
            $products[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = trim($_POST['tanggal']);
            $id_produk = trim($_POST['id_produk']);
            $stok_awal = trim($_POST['stok_awal']);
            $stok_akhir = trim($_POST['stok_akhir']);
            $penjualan = trim($_POST['penjualan']);
            $bs = trim($_POST['bs']);

            if (empty($tanggal) || empty($id_produk) || !is_numeric($stok_awal) || !is_numeric($stok_akhir) || !is_numeric($penjualan) || !is_numeric($bs)) {
                        $_SESSION['error'] = 'Semua field harus diisi dengan benar';
            } else {
                        $stmt = $conn->prepare("INSERT INTO opname_produk (tanggal, id_produk, stok_awal, stok_akhir, penjualan, bs) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param('siiiii', $tanggal, $id_produk, $stok_awal, $stok_akhir, $penjualan, $bs);

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
                        <?php include __DIR__ . '/../../../includes/alert.php'; // Assuming you have an alert system 
                        ?>
                        <form method="POST">
                                    <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="id_produk">Nama Produk</label>
                                                <select class="form-control" id="id_produk" name="id_produk" required>
                                                            <option value="">Pilih Produk</option>
                                                            <?php foreach ($products as $product) { ?>
                                                                        <option value="<?= $product['id']; ?>" <?= (isset($_POST['id_produk']) && $_POST['id_produk'] == $product['id']) ? 'selected' : '' ?>>
                                                                                    <?= htmlspecialchars($product['nama_barang']); ?>
                                                                        </option>
                                                            <?php } ?>
                                                </select>
                                    </div>
                                    <div class="form-group">
                                                <label for="stok_awal">Stok Awal</label>
                                                <input type="number" class="form-control" id="stok_awal" name="stok_awal" value="<?= isset($_POST['stok_awal']) ? htmlspecialchars($_POST['stok_awal']) : '' ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="stok_akhir">Stok Akhir</label>
                                                <input type="number" class="form-control" id="stok_akhir" name="stok_akhir" value="<?= isset($_POST['stok_akhir']) ? htmlspecialchars($_POST['stok_akhir']) : '' ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="penjualan">Penjualan</label>
                                                <input type="number" class="form-control" id="penjualan" name="penjualan" value="<?= isset($_POST['penjualan']) ? htmlspecialchars($_POST['penjualan']) : '' ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="bs">BS (Barang Sisa/Rusak)</label>
                                                <input type="number" class="form-control" id="bs" name="bs" value="<?= isset($_POST['bs']) ? htmlspecialchars($_POST['bs']) : '' ?>" required>
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