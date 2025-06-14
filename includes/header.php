<?php
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $title; ?> | Silmarils Cookies Dessert</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
            <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
</head>

<body>
            <div class="wrapper">
                        <?php include __DIR__ . '/sidebar.php'; ?>
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
                                                                                                <small class="mr-2 d-none d-lg-inline text-muted text-right"><?php echo htmlspecialchars($_SESSION['roles'] ?? 'User'); ?></small>
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