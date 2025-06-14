<?php
$title = 'Tambah Jenis Barang';
$active_menu = 'master';
$active_submenu = 'jenis_barang';
require_once '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $jenis_barang = $_POST['jenis_barang'];

            $sql = "INSERT INTO jenis_barang (jenis_barang) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $jenis_barang);

            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Jenis barang berhasil ditambahkan';
                        header('Location: index.php');
                        exit;
            } else {
                        $_SESSION['error'] = 'Jenis barang gagal ditambahkan';
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Jenis Barang</h6>
            </div>
            <div class="card-body">
                        <form method="POST">
                                    <div class="mb-3">
                                                <label for="jenis_barang" class="form-label">Jenis Barang</label>
                                                <input type="text" class="form-control" id="jenis_barang" name="jenis_barang" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<?php
require_once '../../includes/footer.php';
?>