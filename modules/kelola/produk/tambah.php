<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Tambah Data Produk';
$active_menu = 'master';
$active_submenu = 'produk';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = trim($_POST['tanggal']);
            $nama_produk = trim($_POST['nama_produk']);
            $kode_produk = trim($_POST['kode_produk']);
            $jumlah_produk = trim($_POST['jumlah_produk']);
            $keterangan = trim($_POST['keterangan']);

            $jumlah_produk = empty($jumlah_produk) ? 0 : (int)$jumlah_produk;

            $stmt = $conn->prepare("INSERT INTO produk (tanggal, nama_produk, kode_produk, jumlah_produk, keterangan) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssds', $tanggal, $nama_produk, $kode_produk, $jumlah_produk, $keterangan);
            $stmt->bind_param('sssis', $tanggal, $nama_produk, $kode_produk, $jumlah_produk, $keterangan);
            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Data produk berhasil ditambahkan';
                        header("Location: index.php");
                        exit;
            } else {
                        $_SESSION['error'] = 'Data produk gagal ditambahkan: ' . $conn->error;
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Data Produk</h6>
            </div>
            <div class="card-body">
                        <?php include __DIR__ . '/../../../includes/alert.php'; ?>
                        <form method="POST">
                                    <div class="form-group">
                                                <label for="tanggal">Tanggal <span style="color: red;">*</span></label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="nama_produk">Nama Produk <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk']) : '' ?>" required>
                                    </div>
                                    <div class="form-group">
                                                <label for="kode_produk">Kode Produk</label>
                                                <input type="text" class="form-control" id="kode_produk" name="kode_produk" value="<?= isset($_POST['kode_produk']) ? htmlspecialchars($_POST['kode_produk']) : '' ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="jumlah_produk">Jumlah Produk</label>
                                                <input type="number" class="form-control" id="jumlah_produk" name="jumlah_produk" value="<?= isset($_POST['jumlah_produk']) ? htmlspecialchars($_POST['jumlah_produk']) : '' ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : '' ?></textarea>
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