<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Edit Opname Produk';
$active_menu = 'master';
$active_submenu = 'opname_produk';

if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM opname_produk WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
            $_SESSION['error'] = 'Data opname produk tidak ditemukan';
            header('Location: index.php');
            exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = trim($_POST['tanggal']);
            $nama_produk = trim($_POST['nama_produk']);
            $stok_awal = trim($_POST['stok_awal']);
            $stok_akhir = trim($_POST['stok_akhir']);
            $penjualan = trim($_POST['penjualan']);
            $bs = trim($_POST['bs']);

            $penjualan = empty($penjualan) ? 0 : (int)$penjualan;
            $stmt = $conn->prepare("UPDATE opname_produk SET tanggal = ?, nama_produk = ?, stok_awal = ?, stok_akhir = ?, penjualan = ?, bs = ? WHERE id = ?");
            $stmt->bind_param('ssssisi', $tanggal, $nama_produk, $stok_awal, $stok_akhir, $penjualan, $bs, $id);
            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Opname produk berhasil diperbarui';
                        header('Location: ./index.php');
                        exit;
            } else {
                        $_SESSION['error'] = 'Opname produk gagal diperbarui: ' . $conn->error;
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Opname Produk</h6>
            </div>
            <div class="card-body">
                        <?php include __DIR__ . '/../../../includes/alert.php'; ?>
                        <form method="POST">
                                    <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($data['tanggal']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="nama_produk">Nama Produk</label>
                                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="stok_awal">Stok Awal</label>
                                                <input type="text" class="form-control" id="stok_awal" name="stok_awal" value="<?= htmlspecialchars($data['stok_awal']); ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="stok_akhir">Stok Akhir</label>
                                                <input type="text" class="form-control" id="stok_akhir" name="stok_akhir" value="<?= htmlspecialchars($data['stok_akhir']); ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="penjualan">Penjualan</label>
                                                <input type="number" class="form-control" id="penjualan" name="penjualan" value="<?= htmlspecialchars($data['penjualan']); ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="bs">BS (Barang Sisa/Rusak)</label>
                                                <input type="text" class="form-control" id="bs" name="bs" value="<?= htmlspecialchars($data['bs']); ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
ob_end_flush();
?>