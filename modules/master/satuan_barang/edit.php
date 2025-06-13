<?php
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Edit Satuan Barang';
$active_menu = 'master';
$active_submenu = 'satuan_barang';

if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM satuan_barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
            $_SESSION['error'] = 'Data tidak ditemukan';
            header('Location: index.php');
            exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $satuan = $_POST['satuan_barang'];

            $stmt = $conn->prepare("UPDATE satuan_barang SET satuan_barang = ? WHERE id = ?");
            $stmt->bind_param('si', $satuan, $id);

            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Satuan barang berhasil diperbarui';
                        header('Location: ./index.php');
                        exit;
            } else {
                        $_SESSION['error'] = 'Satuan barang gagal diperbarui';
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Satuan Barang</h6>
            </div>
            <div class="card-body">
                        <form method="POST">
                                    <div class="form-group">
                                                <label for="satuan_barang">Nama Satuan Barang</label>
                                                <input type="text" class="form-control" id="satuan_barang" name="satuan_barang"
                                                            value="<?= htmlspecialchars($data['satuan_barang']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>