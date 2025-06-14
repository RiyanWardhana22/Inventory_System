<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Tambah User';
$active_menu = 'settings';
$active_submenu = 'user';

$roles_stmt = $conn->prepare("SELECT id, title FROM roles ORDER BY title ASC");
$roles_stmt->execute();
$roles_result = $roles_stmt->get_result();
$roles = [];
while ($row = $roles_result->fetch_assoc()) {
            $roles[] = $row;
}

$default_photo = 'default.svg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role_id = $_POST['role_id'];
            $photo_filename = $default_photo;

            if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role_id)) {
                        $_SESSION['error'] = 'Semua bidang wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['error'] = 'Format email tidak valid.';
            } elseif ($password !== $confirm_password) {
                        $_SESSION['error'] = 'Password dan konfirmasi password tidak cocok.';
            } elseif (strlen($password) < 6) {
                        $_SESSION['error'] = 'Password minimal 6 karakter.';
            } else {
                        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                        $stmt_check->bind_param('ss', $username, $email);
                        $stmt_check->execute();
                        $result_check = $stmt_check->get_result();
                        if ($result_check->num_rows > 0) {
                                    $_SESSION['error'] = 'Username atau Email sudah terdaftar.';
                        } else {
                                    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                                                $target_dir = __DIR__ . "/../../../assets/images/profile_photos/";
                                                if (!is_dir($target_dir)) {
                                                            mkdir($target_dir, 0777, true);
                                                }

                                                $imageFileType = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                                                $new_filename = uniqid('profile_') . '.' . $imageFileType;
                                                $target_file = $target_dir . $new_filename;
                                                $uploadOk = 1;

                                                $check = getimagesize($_FILES['photo']['tmp_name']);
                                                if ($check === false) {
                                                            $_SESSION['error'] = 'File bukan gambar.';
                                                            $uploadOk = 0;
                                                }
                                                if ($_FILES['photo']['size'] > 5000000) {
                                                            $_SESSION['error'] = 'Ukuran file terlalu besar (Maks: 5MB).';
                                                            $uploadOk = 0;
                                                }
                                                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                                                            $_SESSION['error'] = 'Hanya JPG, JPEG, PNG & GIF yang diizinkan.';
                                                            $uploadOk = 0;
                                                }

                                                if ($uploadOk == 0) {
                                                            // Error already set, retain default photo
                                                } else {
                                                            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                                                                        $photo_filename = $new_filename;
                                                            } else {
                                                                        $_SESSION['error'] = 'Gagal mengunggah foto. Menggunakan foto default.';
                                                            }
                                                }
                                    }
                                    if (!isset($_SESSION['error'])) {
                                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                                $stmt_insert = $conn->prepare("INSERT INTO users (name, username, email, password, role_id, photo) VALUES (?, ?, ?, ?, ?, ?)");
                                                $stmt_insert->bind_param('ssssis', $name, $username, $email, $hashed_password, $role_id, $photo_filename);
                                                if ($stmt_insert->execute()) {
                                                            $_SESSION['success'] = 'User baru berhasil ditambahkan!';
                                                            header('Location: index.php');
                                                            exit;
                                                } else {
                                                            $_SESSION['error'] = 'Gagal menambahkan user: ' . $conn->error;
                                                }
                                    }
                        }
            }
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah User Baru</h6>
            </div>
            <div class="card-body">
                        <?php include __DIR__ . '/../../../includes/alert.php';
                        ?>
                        <form method="POST" enctype="multipart/form-data">
                                    <div class="form-group mb-3">
                                                <label for="photo">Foto Profile (Opsional)</label>
                                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                                <small class="form-text text-muted">Abaikan jika ingin menggunakan foto default. Maks: 5MB. Format: JPG, PNG, GIF.</small>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="name">Nama Lengkap <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="username">Username <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="email">Email <span style="color: red;">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="password">Password <span style="color: red;">*</span></label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="confirm_password">Konfirmasi Password <span style="color: red;">*</span></label>
                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="role_id">Role <span style="color: red;">*</span></label>
                                                <select class="form-control" id="role_id" name="role_id" required>
                                                            <option value="">Pilih Role</option>
                                                            <?php foreach ($roles as $role) { ?>
                                                                        <option value="<?= $role['id']; ?>" <?= (isset($_POST['role_id']) && $_POST['role_id'] == $role['id']) ? 'selected' : '' ?>>
                                                                                    <?= htmlspecialchars($role['title']); ?>
                                                                        </option>
                                                            <?php } ?>
                                                </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah User</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </form>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
ob_end_flush();
?>