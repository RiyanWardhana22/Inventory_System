<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Edit User';
$active_menu = 'settings';
$active_submenu = 'user';

if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit;
}

$user_id_to_edit = $_GET['id'];

$stmt = $conn->prepare("SELECT id, name, username, email, password, role_id, photo FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id_to_edit);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
            $_SESSION['error'] = 'Data user tidak ditemukan.';
            header('Location: index.php');
            exit;
}

$current_name = htmlspecialchars($user_data['name']);
$current_username = htmlspecialchars($user_data['username']);
$current_email = htmlspecialchars($user_data['email']);
$current_role_id = $user_data['role_id'];
$current_photo = $user_data['photo'];

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
            $new_password = $_POST['password'];
            $role_id = $_POST['role_id'];
            $photo_filename = $current_photo;

            $update_fields = [];
            $bind_types = '';
            $bind_params = [];

            if (!empty($name)) {
                        $update_fields[] = 'name = ?';
                        $bind_types .= 's';
                        $bind_params[] = $name;
            }

            if (!empty($username)) {
                        if ($username !== $user_data['username']) {
                                    $stmt_check_username = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                                    $stmt_check_username->bind_param('si', $username, $user_id_to_edit);
                                    $stmt_check_username->execute();
                                    $result_check_username = $stmt_check_username->get_result();
                                    if ($result_check_username->num_rows > 0) {
                                                $_SESSION['error'] = 'Username sudah digunakan oleh user lain.';
                                                goto end_user_edit;
                                    }
                        }
                        $update_fields[] = 'username = ?';
                        $bind_types .= 's';
                        $bind_params[] = $username;
            }

            if (!empty($email)) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $_SESSION['error'] = 'Format email tidak valid.';
                                    goto end_user_edit;
                        }
                        if ($email !== $user_data['email']) {
                                    $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                                    $stmt_check_email->bind_param('si', $email, $user_id_to_edit);
                                    $stmt_check_email->execute();
                                    $result_check_email = $stmt_check_email->get_result();
                                    if ($result_check_email->num_rows > 0) {
                                                $_SESSION['error'] = 'Email sudah digunakan oleh user lain.';
                                                goto end_user_edit;
                                    }
                        }
                        $update_fields[] = 'email = ?';
                        $bind_types .= 's';
                        $bind_params[] = $email;
            }
            if (!empty($new_password)) {
                        if (strlen($new_password) < 6) {
                                    $_SESSION['error'] = 'Password baru minimal 6 karakter.';
                                    goto end_user_edit;
                        }
                        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $update_fields[] = 'password = ?';
                        $bind_types .= 's';
                        $bind_params[] = $hashed_new_password;
            }
            if (!empty($role_id)) {
                        $update_fields[] = 'role_id = ?';
                        $bind_types .= 'i';
                        $bind_params[] = $role_id;
            }
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
                                    // Error already set, retain old photo or default
                        } else {
                                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                                                if ($current_photo && file_exists($target_dir . $current_photo) && $current_photo !== $default_photo) {
                                                            unlink($target_dir . $current_photo);
                                                }
                                                $photo_filename = $new_filename;
                                                $update_fields[] = 'photo = ?';
                                                $bind_types .= 's';
                                                $bind_params[] = $photo_filename;
                                    } else {
                                                $_SESSION['error'] = 'Gagal mengunggah foto. Menggunakan foto yang ada.';
                                    }
                        }
            }
            if (!empty($update_fields) && !isset($_SESSION['error'])) {
                        $sql_update = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";
                        $bind_types .= 'i';
                        $bind_params[] = $user_id_to_edit;

                        $update_stmt = $conn->prepare($sql_update);
                        call_user_func_array([$update_stmt, 'bind_param'], array_merge([$bind_types], $bind_params));
                        if ($update_stmt->execute()) {
                                    $_SESSION['success'] = 'User berhasil diperbarui!';
                                    if ($user_id_to_edit == $_SESSION['user_id']) {
                                                $_SESSION['user_name'] = $name;
                                                $_SESSION['user_username'] = $username;
                                                $_SESSION['user_email'] = $email;
                                                $_SESSION['user_photo'] = $photo_filename;
                                                if ($current_role_id != $role_id) {
                                                            $stmt_role_name_update = $conn->prepare("SELECT title FROM roles WHERE id = ?");
                                                            $stmt_role_name_update->bind_param('i', $role_id);
                                                            $stmt_role_name_update->execute();
                                                            $result_role_name_update = $stmt_role_name_update->get_result();
                                                            $role_name_data_update = $result_role_name_update->fetch_assoc();
                                                            if ($role_name_data_update) {
                                                                        $_SESSION['roles'] = htmlspecialchars($role_name_data_update['title']);
                                                            }
                                                }
                                    }
                                    header('Location: index.php');
                                    exit;
                        } else {
                                    $_SESSION['error'] = 'Gagal memperbarui user: ' . $conn->error;
                        }
            } elseif (empty($update_fields) && !isset($_SESSION['error'])) {
                        $_SESSION['error'] = 'Tidak ada perubahan yang dikirimkan.';
            }

            end_user_edit:
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit User</h6>
            </div>
            <div class="card-body">
                        <?php include __DIR__ . '/../../../includes/alert.php';
                        ?>
                        <form method="POST" enctype="multipart/form-data">
                                    <div class="form-group mb-3 text-center">
                                                <?php
                                                $display_photo_path = base_url('assets/images/profile_photos/' . htmlspecialchars($user_data['photo'] ?? $default_photo));
                                                $fallback_image = base_url('assets/images/' . $default_photo);
                                                ?>
                                                <img class="img-fluid rounded-circle mb-3"
                                                            src="<?= $display_photo_path ?>"
                                                            alt="Foto Profile"
                                                            style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;"
                                                            onerror="this.onerror=null;this.src='<?= $fallback_image ?>'">
                                                <p class="text-muted small">Current Photo</p>
                                    </div>

                                    <div class="form-group mb-3">
                                                <label for="photo">Ganti Foto Profile (Opsional)</label>
                                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                                <small class="form-text text-muted">Maks: 5MB. Format: JPG, PNG, GIF. Kosongkan jika tidak ingin mengubah foto.</small>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="name">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="name" name="name" value="<?= $current_name ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="<?= $current_username ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?= $current_email ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="password">Password Baru (Reset Password)</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mereset password">
                                                <small class="form-text text-muted">Isi bidang ini untuk mereset password user. Minimal 6 karakter.</small>
                                    </div>
                                    <div class="form-group mb-3">
                                                <label for="role_id">Role</label>
                                                <select class="form-control" id="role_id" name="role_id">
                                                            <option value="">Pilih Role</option>
                                                            <?php foreach ($roles as $role) { ?>
                                                                        <option value="<?= $role['id']; ?>" <?= ($current_role_id == $role['id']) ? 'selected' : '' ?>>
                                                                                    <?= htmlspecialchars($role['title']); ?>
                                                                        </option>
                                                            <?php } ?>
                                                </select>
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