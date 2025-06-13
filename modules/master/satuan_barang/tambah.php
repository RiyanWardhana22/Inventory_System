<?php
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Tambah Satuan Barang';
$active_menu = 'master';
$active_submenu = 'satuan_barang';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $satuan = $_POST['satuan_barang'];

            $stmt = $conn->prepare("INSERT INTO satuan_barang (satuan_barang) VALUES (?)");
            $stmt->bind_param('s', $satuan);

            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Satuan barang berhasil ditambahkan';
                        header('Location: index.php');
                        exit;
            } else {
                        $_SESSION['error'] = 'Satuan barang gagal ditambahkan';
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Satuan Barang</h6>
            </div>
            <div class="card-body">
                        <form method="POST">
                                    <div class="form-group">
                                                <label for="satuan_barang">Nama Satuan Barang</label>
                                                <input type="text" class="form-control" id="satuan_barang" name="satuan_barang" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>