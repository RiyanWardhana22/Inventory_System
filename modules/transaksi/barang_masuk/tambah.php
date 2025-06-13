<?php
$title = 'Tambah Barang Masuk';
$active_menu = 'transaksi';
$active_submenu = 'barang_masuk';
require_once '../../includes/header.php';
require_once '../../../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode_barang_masuk = generateKode('BM', 'barang_masuk', 'kode_barang_masuk');
            $tanggal_masuk = $_POST['tanggal_masuk'];
            $customer_id = $_POST['customer_id'];
            $barang_id = $_POST['barang_id'];
            $jumlah_masuk = $_POST['jumlah_masuk'];

            $sql = "INSERT INTO barang_masuk (kode_barang_masuk, tanggal_masuk, customer_id, barang_id, jumlah_masuk, created_by) 
            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssiiii', $kode_barang_masuk, $tanggal_masuk, $customer_id, $barang_id, $jumlah_masuk, $user['id']);

            if ($stmt->execute()) {
                        // Update stok barang
                        $update_sql = "UPDATE barang SET stok_awal = stok_awal + ? WHERE id = ?";
                        $update_stmt = $conn->prepare($update_sql);
                        $update_stmt->bind_param('ii', $jumlah_masuk, $barang_id);
                        $update_stmt->execute();

                        $_SESSION['success'] = 'Barang masuk berhasil ditambahkan';
                        header('Location: index.php');
                        exit;
            } else {
                        $_SESSION['error'] = 'Barang masuk gagal ditambahkan';
            }
}

// Get customers and barang for dropdown
$customers = $conn->query("SELECT * FROM customers ORDER BY nama_customer ASC");
$barang = $conn->query("SELECT b.*, j.jenis_barang, s.satuan_barang, m.merk_barang 
                        FROM barang b
                        JOIN jenis_barang j ON b.jenis_barang_id = j.id
                        JOIN satuan_barang s ON b.satuan_barang_id = s.id
                        JOIN merk_barang m ON b.merk_barang_id = m.id
                        ORDER BY b.nama_barang ASC");
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Barang Masuk</h6>
            </div>
            <div class="card-body">
                        <form method="POST">
                                    <div class="row">
                                                <div class="col-md-6 mb-3">
                                                            <label for="kode_barang_masuk" class="form-label">Kode Barang Masuk</label>
                                                            <input type="text" class="form-control" id="kode_barang_masuk" value="<?php echo generateKode('BM', 'barang_masuk', 'kode_barang_masuk'); ?>" readonly>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                    </div>

                                    <div class="mb-3">
                                                <label for="customer_id" class="form-label">Customer</label>
                                                <select class="form-select" id="customer_id" name="customer_id" required>
                                                            <option value="">-- Pilih Customer --</option>
                                                            <?php while ($customer = $customers->fetch_assoc()): ?>
                                                                        <option value="<?php echo $customer['id']; ?>"><?php echo $customer['nama_customer']; ?></option>
                                                            <?php endwhile; ?>
                                                </select>
                                    </div>

                                    <div class="mb-3">
                                                <label for="barang_id" class="form-label">Barang</label>
                                                <select class="form-select" id="barang_id" name="barang_id" required>
                                                            <option value="">-- Pilih Barang --</option>
                                                            <?php while ($item = $barang->fetch_assoc()): ?>
                                                                        <option value="<?php echo $item['id']; ?>" data-jenis="<?php echo $item['jenis_barang']; ?>"
                                                                                    data-satuan="<?php echo $item['satuan_barang']; ?>" data-merk="<?php echo $item['merk_barang']; ?>">
                                                                                    <?php echo $item['nama_barang']; ?> (<?php echo $item['kode_barang']; ?>)
                                                                        </option>
                                                            <?php endwhile; ?>
                                                </select>
                                    </div>

                                    <div class="row">
                                                <div class="col-md-4 mb-3">
                                                            <label class="form-label">Jenis Barang</label>
                                                            <input type="text" class="form-control" id="jenis_barang" readonly>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label class="form-label">Satuan Barang</label>
                                                            <input type="text" class="form-control" id="satuan_barang" readonly>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                            <label class="form-label">Merk Barang</label>
                                                            <input type="text" class="form-control" id="merk_barang" readonly>
                                                </div>
                                    </div>

                                    <div class="mb-3">
                                                <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
                                                <input type="number" class="form-control" id="jumlah_masuk" name="jumlah_masuk" min="1" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<script>
            $(document).ready(function() {
                        $('#barang_id').change(function() {
                                    var selected = $(this).find('option:selected');
                                    $('#jenis_barang').val(selected.data('jenis'));
                                    $('#satuan_barang').val(selected.data('satuan'));
                                    $('#merk_barang').val(selected.data('merk'));
                        });
            });
</script>

<?php
require_once '../../includes/footer.php';
?>