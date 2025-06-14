<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Edit Data Produk';
$active_menu = 'master';
$active_submenu = 'produk';

if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
            $_SESSION['error'] = 'Data produk tidak ditemukan';
            header('Location: index.php');
            exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = trim($_POST['tanggal']);
            $nama_produk = trim($_POST['nama_produk']);
            $kode_produk = trim($_POST['kode_produk']);
            $jumlah_produk = trim($_POST['jumlah_produk']);
            $keterangan = trim($_POST['keterangan']);
            $jumlah_produk = empty($jumlah_produk) ? 0 : (int)$jumlah_produk;

            $stmt = $conn->prepare("UPDATE produk SET tanggal = ?, nama_produk = ?, kode_produk = ?, jumlah_produk = ?, keterangan = ? WHERE id = ?");
            $stmt->bind_param('sssisd', $tanggal, $nama_produk, $kode_produk, $jumlah_produk, $keterangan, $id);
            $stmt->bind_param('sssiis', $tanggal, $nama_produk, $kode_produk, $jumlah_produk, $keterangan, $id);
            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Data produk berhasil diperbarui';
                        header('Location: ./index.php');
                        exit;
            } else {
                        $_SESSION['error'] = 'Data produk gagal diperbarui: ' . $conn->error;
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Data Produk</h6>
            </div>
            <div class="card-body">
                        <?php include __DIR__ . '/../../../includes/alert.php'; ?>
                        <form method="POST">
                                    <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($data['tanggal']); ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="nama_produk">Nama Produk</label>
                                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']); ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="kode_produk">Kode Produk</label>
                                                <input type="text" class="form-control" id="kode_produk" name="kode_produk" value="<?= htmlspecialchars($data['kode_produk']); ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="jumlah_produk">Jumlah Produk</label>
                                                <input type="number" class="form-control" id="jumlah_produk" name="jumlah_produk" value="<?= htmlspecialchars($data['jumlah_produk']); ?>">
                                    </div>
                                    <div class="form-group">
                                                <label for="keterangan">Keterangan</label>
                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= htmlspecialchars($data['keterangan']); ?></textarea>
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