<?php
$title = isset($title) ? $title : 'INVENTORYWEB';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';
require_once __DIR__ . '/auth.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>INVENTORYWEB - <?php echo $title; ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
                                                                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user['name']; ?></span>
                                                                                    <img class="img-profile rounded-circle"
                                                                                                src="<?php echo base_url('assets/images/' . (!empty($user['photo']) ? $user['photo'] : 'default.svg')); ?>"
                                                                                                width="40"
                                                                                                onerror="this.onerror=null;this.src='<?php echo base_url('assets/images/default.png'); ?>'">
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