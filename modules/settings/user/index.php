<?php
$title = 'Manajemen User';
$active_menu = 'settings';
$active_submenu = 'user';
require_once __DIR__ . '/../../../includes/header.php';

$sql = "SELECT u.*, r.title as role_title FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.name ASC";
$users = $conn->query($sql);
?>

<style>
            .btn {
                        margin-bottom: 5px;
            }
</style>

<div class="container-fluid ">
            <div class="card shadow border-0 rounded-lg mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                                    <h6 class="m-0 font-weight-bold text-primary">Manajemen User</h6>
                                    <a href="tambah.php" class="btn btn-primary d-flex align-items-center">
                                                <i class="bi bi-plus me-2"></i> Tambah User
                                    </a>
                        </div>
                        <div class="card-body">
                                    <div class="table-responsive">
                                                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                                            <thead class="bg-light">
                                                                        <tr>
                                                                                    <th class="text-center">NO</th>
                                                                                    <th>NAMA LENGKAP</th>
                                                                                    <th>USERNAME</th>
                                                                                    <th>EMAIL</th>
                                                                                    <th>ROLE</th>
                                                                                    <th class="text-center">AKSI</th>
                                                                        </tr>
                                                            </thead>
                                                            <tbody>
                                                                        <?php $no = 1;
                                                                        while ($user_row = $users->fetch_assoc()): ?>
                                                                                    <tr>
                                                                                                <td class="text-center"><?php echo $no++; ?></td>
                                                                                                <td><?php echo htmlspecialchars($user_row['name']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($user_row['username']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($user_row['email']); ?></td>
                                                                                                <td><?php echo htmlspecialchars($user_row['role_title']); ?></td>
                                                                                                <td class="text-center">
                                                                                                            <a href="edit.php?id=<?php echo $user_row['id']; ?>" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                                                                                                        <i class="bi bi-pencil"></i>
                                                                                                            </a>
                                                                                                            <?php
                                                                                                            if (isset($_SESSION['user_id']) && $user_row['id'] != $_SESSION['user_id']): ?>
                                                                                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="hapus.php?id=<?= $user_row['id']; ?>" title="Hapus">
                                                                                                                                    <i class="bi bi-trash"></i>
                                                                                                                        </button>
                                                                                                            <?php endif; ?>
                                                                                                </td>
                                                                                    </tr>
                                                                        <?php endwhile; ?>
                                                            </tbody>
                                                </table>
                                    </div>
                        </div>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>