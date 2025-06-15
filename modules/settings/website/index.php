<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

$title = 'Pengaturan Website';
$active_menu = 'settings';
$active_submenu = 'website';

if (!isset($user) || ($user['role_title'] ?? 'User') !== 'Admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini.';
            header('Location: /dashboard.php');
            exit;
}

$settings_stmt = $conn->prepare("SELECT * FROM website_settings WHERE id = 1");
$settings_stmt->execute();
$settings_result = $settings_stmt->get_result();
$settings = $settings_result->fetch_assoc();

if (!$settings) {
            $insert_default = $conn->prepare("INSERT INTO website_settings (id, site_title, site_description, site_logo) VALUES (1, ?, ?, ?)");
            $insert_default->bind_param('sss', $default_site_title, $default_site_description, $default_site_logo);
            $default_site_title = 'Silmarils Cookies Dessert';
            $default_site_description = 'Mengelola Data Barang Masuk & Barang Keluar';
            $default_site_logo = 'logo.png';
            $insert_default->execute();
            $settings_stmt->execute();
            $settings = $settings_stmt->get_result()->fetch_assoc();
}

$current_site_title = htmlspecialchars($settings['site_title']);
$current_site_description = htmlspecialchars($settings['site_description']);
$current_site_logo = $settings['site_logo'];

$default_logo_filename = 'default_logo.svg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_site_title = trim($_POST['site_title'] ?? '');
            $new_site_description = trim($_POST['site_description'] ?? '');
            $logo_filename = $current_site_logo;
            if (empty($new_site_title)) {
                        $_SESSION['error'] = 'Judul Website tidak boleh kosong.';
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        exit;
            }
            if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
                        $target_dir = __DIR__ . "/../../../assets/images/logo/";
                        if (!is_dir($target_dir)) {
                                    mkdir($target_dir, 0777, true);
                        }

                        $imageFileType = strtolower(pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION));
                        $unique_logo_filename = uniqid('logo_') . '.' . $imageFileType;
                        $target_file = $target_dir . $unique_logo_filename;
                        $uploadOk = 1;

                        $check = getimagesize($_FILES['site_logo']['tmp_name']);
                        if ($check === false) {
                                    $_SESSION['error'] = 'File bukan gambar.';
                                    $uploadOk = 0;
                        }
                        if ($_FILES['site_logo']['size'] > 2000000) {
                                    $_SESSION['error'] = 'Ukuran file logo terlalu besar (Maks: 2MB).';
                                    $uploadOk = 0;
                        }
                        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                                    $_SESSION['error'] = 'Hanya JPG, JPEG, PNG & GIF yang diizinkan untuk logo.';
                                    $uploadOk = 0;
                        }

                        if ($uploadOk == 0) {
                                    // Error already set, retain old logo
                        } else {
                                    if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $target_file)) {
                                                // Hapus logo lama jika ada dan bukan logo default
                                                if ($current_site_logo && file_exists($target_dir . $current_site_logo) && $current_site_logo !== $default_logo_filename) {
                                                            unlink($target_dir . $current_site_logo);
                                                }
                                                $logo_filename = $unique_logo_filename;
                                    } else {
                                                $_SESSION['error'] = 'Gagal mengunggah logo. Menggunakan logo yang ada.';
                                    }
                        }
            }

            // Update settings in database
            if (!isset($_SESSION['error'])) {
                        $update_stmt = $conn->prepare("UPDATE website_settings SET site_title = ?, site_description = ?, site_logo = ? WHERE id = 1");
                        $update_stmt->bind_param('sss', $new_site_title, $new_site_description, $logo_filename);

                        if ($update_stmt->execute()) {
                                    $_SESSION['success'] = 'Pengaturan website berhasil diperbarui!';
                                    // Penting: Refresh pengaturan di sesi/global agar perubahan langsung terlihat
                                    // Ini akan dibahas di bagian selanjutnya (memuat pengaturan global di header.php)
                                    header('Location: ' . $_SERVER['PHP_SELF']);
                                    exit;
                        } else {
                                    $_SESSION['error'] = 'Gagal memperbarui pengaturan website: ' . $conn->error;
                        }
            }
}
?>

<div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Pengaturan Website</h1>
            </div>

            <?php include __DIR__ . '/../../../includes/alert.php'; // Include alert system 
            ?>

            <div class="row">
                        <!-- Panel Profil Website (Preview) -->
                        <div class="col-lg-5 mb-4">
                                    <div class="card shadow">
                                                <div class="card-header py-3">
                                                            <h6 class="m-0 font-weight-bold text-primary">Profil Website</h6>
                                                </div>
                                                <div class="card-body text-center">
                                                            <?php
                                                            $display_logo_path = base_url('assets/images/logo/' . htmlspecialchars($current_site_logo ?? $default_logo_filename));
                                                            $fallback_logo_path = base_url('assets/images/' . $default_logo_filename);
                                                            ?>
                                                            <img class="img-fluid mb-3"
                                                                        src="<?= $display_logo_path ?>"
                                                                        alt="Logo Website"
                                                                        style="max-width: 150px; height: auto; object-fit: contain;"
                                                                        onerror="this.onerror=null;this.src='<?= $fallback_logo_path ?>'">
                                                            <h5 class="font-weight-bold text-gray-800 mb-1"><?= $current_site_title ?></h5>
                                                            <p class="text-muted small"><?= $current_site_description ?></p>
                                                </div>
                                    </div>
                        </div>

                        <!-- Panel Ubah Pengaturan (Form) -->
                        <div class="col-lg-7 mb-4">
                                    <div class="card shadow">
                                                <div class="card-header py-3">
                                                            <h6 class="m-0 font-weight-bold text-primary">Ubah Pengaturan</h6>
                                                </div>
                                                <div class="card-body">
                                                            <form method="POST" enctype="multipart/form-data">
                                                                        <div class="form-group mb-3">
                                                                                    <label for="site_logo">Logo Website</label>
                                                                                    <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                                                                                    <small class="form-text text-muted">Maks: 2MB. Format: JPG, JPEG, PNG, GIF. Kosongkan jika tidak ingin mengubah logo.</small>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                                    <label for="site_title">Judul Website</label>
                                                                                    <input type="text" class="form-control" id="site_title" name="site_title" value="<?= $current_site_title ?>" required>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                                    <label for="site_description">Deskripsi Website</label>
                                                                                    <textarea class="form-control" id="site_description" name="site_description" rows="3"><?= $current_site_description ?></textarea>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                                                            </form>
                                                </div>
                                    </div>
                        </div>
            </div>
</div>

<?php
require_once __DIR__ . '/../../../includes/footer.php';
ob_end_flush();
?>