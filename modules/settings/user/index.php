<?php
$title = 'Manajemen User';
$active_menu = 'settings';
$active_submenu = 'user';
require_once __DIR__ . '/../../../includes/header.php';

$sql = "SELECT u.*, r.title as role_title FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.name ASC";
$users = $conn->query($sql);
?>

<div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
                        <a href="tambah.php" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus"></i> Tambah User
                        </a>
            </div>
            <div class="card-body">
                        <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                            <tr>
                                                                        <th>NO</th>
                                                                        <th>NAMA LENGKAP</th>
                                                                        <th>USERNAME</th>
                                                                        <th>EMAIL</th>
                                                                        <th>ROLE</th>
                                                                        <th>ACTION</th>
                                                            </tr>
                                                </thead>
                                                <tbody>
                                                            <?php $no = 1;
                                                            while ($user_row = $users->fetch_assoc()): ?>
                                                                        <tr>
                                                                                    <td><?php echo $no++; ?></td>
                                                                                    <td><?php echo htmlspecialchars($user_row['name']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($user_row['username']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($user_row['email']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($user_row['role_title']); ?></td>
                                                                                    <td>
                                                                                                <a href="edit.php?id=<?php echo $user_row['id']; ?>" class="btn btn-warning btn-sm">
                                                                                                            <i class="bi bi-pencil"></i>
                                                                                                </a>
                                                                                                <?php
                                                                                                if (isset($_SESSION['user_id']) && $user_row['id'] != $_SESSION['user_id']): ?>
                                                                                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-url="hapus.php?id=<?= $user_row['id']; ?>">
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

<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>