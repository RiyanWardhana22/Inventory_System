<?php
$title = 'Edit Jenis Barang';
$active_menu = 'master';
$active_submenu = 'jenis_barang';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'ID jenis barang tidak ditemukan';
            header('Location: index.php');
            exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM jenis_barang WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$jenis_barang = $result->fetch_assoc();

if (!$jenis_barang) {
            $_SESSION['error'] = 'Data jenis barang tidak ditemukan';
            header('Location: index.php');
            exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jenis_barang_name = $_POST['jenis_barang'];

            $sql = "UPDATE jenis_barang SET jenis_barang = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $jenis_barang_name, $id);

            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Jenis barang berhasil diperbarui';
                        header('Location: index.php');
                        exit;
            } else {
                        $_SESSION['error'] = 'Jenis barang gagal diperbarui';
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Jenis Barang</h6>
            </div>
            <div class="card-body">
                        <form method="POST">
                                    <div class="mb-3">
                                                <label for="jenis_barang" class="form-label">Jenis Barang</label>
                                                <input type="text" class="form-control" id="jenis_barang" name="jenis_barang" value="<?php echo $jenis_barang['jenis_barang']; ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<?php
require_once '../../includes/footer.php';
?>