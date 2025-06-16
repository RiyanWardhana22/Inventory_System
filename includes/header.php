<?php
ob_start();

function base_url($path = '')
{
            $protocol = (!empty($_SERVER['HTTPS'])) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $project_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', realpath(dirname(__FILE__) . '/..')));
            return $protocol . '://' . $host . $project_path . '/' . ltrim($path, '/');
}
$title = isset($title) ? $title : '';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';
require_once __DIR__ . '/auth.php';

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
$current_role_name = 'wkwk';
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

$site_title_from_db = 'Silmarils Cookies Dessert';
$site_logo_from_db = 'default_logo.svg';
if (isset($conn)) {
            $settings_stmt = $conn->prepare("SELECT site_title, site_logo FROM website_settings WHERE id = 1");
            if ($settings_stmt) {
                        $settings_stmt->execute();
                        $settings_result = $settings_stmt->get_result();
                        $settings = $settings_result->fetch_assoc();
                        if ($settings) {
                                    if (!empty($settings['site_title'])) {
                                                $site_title_from_db = htmlspecialchars($settings['site_title']);
                                    }
                                    if (!empty($settings['site_logo'])) {
                                                $site_logo_from_db = htmlspecialchars($settings['site_logo']);
                                    }
                        }
            }
}

$favicon_path = base_url('assets/images/logo/' . $site_logo_from_db);
$default_favicon_path = base_url('assets/images/default_logo.svg');

?>
<!DOCTYPE html>
<html lang="en">

<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $title; ?> | <?= $site_title_from_db ?></title>
            <link rel="icon" href="<?= $favicon_path ?>" type="image/x-icon" onerror="this.onerror=null;this.href='<?= $default_favicon_path ?>'">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
            <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
</head>

<body>
            <div class="wrapper">
                        <div class="overlay"></div> <?php include __DIR__ . '/sidebar.php'; ?>
                        <div class="main">
                                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                                                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                                            <i class="bi bi-list"></i>
                                                </button>
                                                <ul class="navbar-nav ms-auto">
                                                            <li class="nav-item dropdown no-arrow">
                                                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <div class="d-flex flex-column align-items-end">
                                                                                                <span class="mr-2 d-none d-lg-inline text-dark small text-right"><?php echo htmlspecialchars($user['name']); ?></span>
                                                                                                <small class="text-secondary"><?php echo $current_role_name; ?></small>
                                                                                    </div>
                                                                                    <?php
                                                                                    $header_photo_path = base_url('assets/images/profile_photos/' . htmlspecialchars($user['photo'] ?? 'default.svg'));
                                                                                    $fallback_image = base_url('assets/images/default.svg');
                                                                                    ?>
                                                                                    <img class="img-profile rounded-circle"
                                                                                                src="<?= $header_photo_path ?>"
                                                                                                width="40"
                                                                                                height="40"
                                                                                                style="object-fit: cover;"
                                                                                                onerror="this.onerror=null;this.src='<?= $fallback_image ?>'">
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                                                                    <a class="dropdown-item" href="<?php echo base_url('modules/settings/user/profile.php'); ?>">
                                                                                                <i class="bi bi-person fa-sm fa-fw mr-2 text-gray-400"></i>
                                                                                                Profile
                                                                                    </a>
                                                                                    <div class="dropdown-divider"></div>
                                                                                    <a class="dropdown-item" href="<?php echo base_url('logout.php'); ?>">
                                                                                                <i class="bi bi-box-arrow-right fa-sm fa-fw mr-2 text-gray-400"></i>
                                                                                                Logout
                                                                                    </a>
                                                                        </div>
                                                            </li>
                                                </ul>
                                    </nav>
                                    <div class="container-fluid">
                                                <?php include 'alert.php'; ?>
</body>

</html>