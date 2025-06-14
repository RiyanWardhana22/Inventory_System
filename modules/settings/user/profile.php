<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Edit Profile';
$active_menu = 'settings';
$active_submenu = 'profile';

if (!isset($_SESSION['user_id'])) {
            header('Location: /login.php');
            exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, name, username, email, password, photo, role_id FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
            $_SESSION['error'] = 'Data pengguna tidak ditemukan.';
            header('Location: /dashboard.php');
            exit;
}

$current_name = htmlspecialchars($user_data['name']);
$current_username = htmlspecialchars($user_data['username']);
$current_email = htmlspecialchars($user_data['email']);
$current_photo = $user_data['photo'];
$current_role_name = 'User';

if (isset($user_data['role_id'])) {
            $role_id = $user_data['role_id'];
            $stmt_role = $conn->prepare("SELECT title FROM roles WHERE id = ?");
            $stmt_role->bind_param('i', $role_id);
            $stmt_role->execute();
            $result_role = $stmt_role->get_result();
            $role_data = $result_role->fetch_assoc();
            if ($role_data) {
                        $current_role_name = htmlspecialchars($role_data['title']);
                        $_SESSION['roles'] = $current_role_name;
            }
}


if (isset($_POST['update_password'])) {
            $current_password_input = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            if (!password_verify($current_password_input, $user_data['password'])) {
                        $_SESSION['error'] = 'Password saat ini salah.';
            } elseif (empty($new_password) || empty($confirm_password)) {
                        $_SESSION['error'] = 'Password baru dan konfirmasi password tidak boleh kosong.';
            } elseif ($new_password !== $confirm_password) {
                        $_SESSION['error'] = 'Password baru dan konfirmasi password tidak cocok.';
            } elseif (strlen($new_password) < 6) {
                        $_SESSION['error'] = 'Password baru minimal 6 karakter.';
            } else {
                        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $update_stmt->bind_param('si', $hashed_new_password, $user_id);

                        if ($update_stmt->execute()) {
                                    $_SESSION['success'] = 'Password berhasil diperbarui!';
                                    header('Location: ' . $_SERVER['PHP_SELF']);
                                    exit;
                        } else {
                                    $_SESSION['error'] = 'Gagal memperbarui password: ' . $conn->error;
                        }
            }
}

if (isset($_POST['update_profile'])) {
            $name = trim($_POST['name']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $photo_filename = $current_photo;
            if ($username !== $user_data['username']) {
                        $stmt_check_username = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                        $stmt_check_username->bind_param('si', $username, $user_id);
                        $stmt_check_username->execute();
                        $result_check_username = $stmt_check_username->get_result();
                        if ($result_check_username->num_rows > 0) {
                                    $_SESSION['error'] = 'Nama pengguna sudah digunakan.';
                                    goto end_profile_update;
                        }
            }

            if ($email !== $user_data['email']) {
                        $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                        $stmt_check_email->bind_param('si', $email, $user_id);
                        $stmt_check_email->execute();
                        $result_check_email = $stmt_check_email->get_result();
                        if ($result_check_email->num_rows > 0) {
                                    $_SESSION['error'] = 'Email sudah digunakan.';
                                    goto end_profile_update;
                        }
            }

            if (empty($name)) {
                        $_SESSION['error'] = 'Nama lengkap tidak boleh kosong.';
            } elseif (empty($username)) {
                        $_SESSION['error'] = 'Nama pengguna tidak boleh kosong.';
            } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $_SESSION['error'] = 'Email tidak valid.';
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
                                                // Error sudah diatur, tidak perlu melakukan apa-apa lagi
                                    } else {
                                                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                                                            if ($current_photo && file_exists($target_dir . $current_photo) && $current_photo !== 'default.svg') { // Sesuaikan 'default.svg' jika nama file default Anda berbeda
                                                                        unlink($target_dir . $current_photo);
                                                            }
                                                            $photo_filename = $new_filename;
                                                } else {
                                                            $_SESSION['error'] = 'Gagal mengunggah foto.';
                                                            $photo_filename = $current_photo;
                                                }
                                    }
                        }
                        if (!isset($_SESSION['error'])) {
                                    $update_stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, photo = ? WHERE id = ?");
                                    $update_stmt->bind_param('ssssi', $name, $username, $email, $photo_filename, $user_id);

                                    if ($update_stmt->execute()) {
                                                $_SESSION['success'] = 'Profil berhasil diperbarui!';
                                                $_SESSION['user_name'] = $name;
                                                $_SESSION['user_username'] = $username;
                                                $_SESSION['user_email'] = $email;
                                                $_SESSION['user_photo'] = $photo_filename;
                                                header('Location: ' . $_SERVER['PHP_SELF']);
                                                exit;
                                    } else {
                                                $_SESSION['error'] = 'Gagal memperbarui profil: ' . $conn->error;
                                    }
                        }
            }
            end_profile_update:
}
?>

<div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">PROFILE SETTINGS</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= $_SESSION['success']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= $_SESSION['error']; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="row">
                        <div class="col-lg-4 mb-4">
                                    <div class="card shadow">
                                                <div class="card-body text-center">
                                                            <?php
                                                            $display_photo_path = '';
                                                            if ($current_photo && $current_photo !== 'default.svg') {
                                                                        $display_photo_path = base_url('assets/images/profile_photos/' . htmlspecialchars($current_photo));
                                                            } else {
                                                                        $display_photo_path = base_url('assets/images/default.svg');
                                                            }
                                                            ?>
                                                            <img class="img-fluid rounded-circle mb-3" src="<?= $display_photo_path ?>" alt="Foto Profil" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ddd;">
                                                            <h4 class="font-weight-bold text-gray-800 mb-1"><?= $current_name ?></h4>
                                                            <small class="text-secondary"><?php echo $current_role_name; ?></small> <!-- Menampilkan nama peran -->
                                                </div>
                                    </div>
                        </div>

                        <div class="col-lg-8 mb-4">
                                    <div class="card shadow">
                                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                                            <h6 class="m-0 font-weight-bold text-primary">Edit Profile Information</h6>
                                                </div>
                                                <div class="card-body">
                                                            <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                                                                        <li class="nav-item" role="presentation">
                                                                                    <button class="nav-link active" id="profile-details-tab" data-bs-toggle="tab" data-bs-target="#profile-details" type="button" role="tab" aria-controls="profile-details" aria-selected="true">Profile Details</button>
                                                                        </li>
                                                                        <li class="nav-item" role="presentation">
                                                                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">Change Password</button>
                                                                        </li>
                                                            </ul>
                                                            <div class="tab-content" id="profileTabsContent">
                                                                        <div class="tab-pane fade show active" id="profile-details" role="tabpanel" aria-labelledby="profile-details-tab">
                                                                                    <form method="POST" enctype="multipart/form-data">
                                                                                                <div class="mb-3">
                                                                                                            <label for="name" class="form-label">Nama Lengkap</label>
                                                                                                            <input type="text" class="form-control" id="name" name="name" value="<?= $current_name ?>" required>
                                                                                                </div>
                                                                                                <div class="mb-3">
                                                                                                            <label for="username" class="form-label">Username</label>
                                                                                                            <input type="text" class="form-control" id="username" name="username" value="<?= $current_username ?>" required>
                                                                                                </div>
                                                                                                <div class="mb-3">
                                                                                                            <label for="email" class="form-label">Email address</label>
                                                                                                            <input type="email" class="form-control" id="email" name="email" value="<?= $current_email ?>" required>
                                                                                                </div>
                                                                                                <div class="mb-3">
                                                                                                            <label for="photo" class="form-label">Foto Profile</label>
                                                                                                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                                                                                            <small class="form-text text-muted">Ukuran file maksimal: 5MB. Format yang diizinkan: JPG, JPEG, PNG, GIF.</small>
                                                                                                </div>
                                                                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                                                                                            <button type="submit" name="update_profile" class="btn btn-primary me-md-2">Simpan</button>
                                                                                                            <a href="javascript:history.back()" class="btn btn-secondary">Batal</a>
                                                                                                </div>
                                                                                    </form>
                                                                        </div>

                                                                        <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                                                                    <form method="POST">
                                                                                                <div class="mb-3">
                                                                                                            <label for="current_password" class="form-label">Password Saat Ini</label>
                                                                                                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Masukkan password saat ini" required>
                                                                                                </div>
                                                                                                <div class="mb-3">
                                                                                                            <label for="new_password" class="form-label">Password Baru</label>
                                                                                                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukkan password baru (min 6 karakter)" required>
                                                                                                </div>
                                                                                                <div class="mb-3">
                                                                                                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                                                                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password baru" required>
                                                                                                </div>
                                                                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                                                                                            <button type="submit" name="update_password" class="btn btn-primary me-md-2">Ubah Password</button>
                                                                                                            <a href="javascript:history.back()" class="btn btn-secondary">Batal</a>
                                                                                                </div>
                                                                                    </form>
                                                                        </div>
                                                            </div>
                                                </div>
                                    </div>
                        </div>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
ob_end_flush();
?>

<script>
            document.addEventListener('DOMContentLoaded', function() {
                        var photoInput = document.getElementById('photo');
                        if (photoInput) {
                                    photoInput.addEventListener('change', function(e) {
                                                var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose file';
                                                var nextSibling = e.target.nextElementSibling;
                                                if (nextSibling && nextSibling.classList.contains('custom-file-label')) {
                                                            nextSibling.innerText = fileName;
                                                }
                                    });
                        }
            });
</script>