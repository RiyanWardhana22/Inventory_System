<?php
if (!isset($user)) {
            die('User data not available');
}

$site_title = 'Silmarils Cookies Dessert';
if (isset($conn)) {
            $settings_stmt = $conn->prepare("SELECT site_title FROM website_settings WHERE id = 1");
            if ($settings_stmt) {
                        $settings_stmt->execute();
                        $settings_result = $settings_stmt->get_result();
                        $settings = $settings_result->fetch_assoc();
                        if ($settings && !empty($settings['site_title'])) {
                                    $site_title = htmlspecialchars($settings['site_title']);
                        }
            }
}
?>
<style>
            .sidebar-nav ul,
            .sidebar-nav li {
                        list-style: none;
                        padding: 0;
                        margin: 0;
            }

            .sidebar {
                        min-width: 250px;
                        max-width: 250px;
                        background: #ffffff;
                        color: #212529;
                        transition: all 0.3s;
                        min-height: 100vh;
                        padding: 15px;
                        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
                        height: 100vh;
                        top: 0;
                        left: 0;
                        display: flex;
                        flex-direction: column;
            }

            .sidebar-brand {
                        padding: 1rem;
                        padding-bottom: 20px;
                        border-bottom: 1px solid #eeeeee;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
            }

            .sidebar-brand .sidebar-logo {
                        height: 30px;
                        width: auto;
                        margin-right: 10px;
            }

            .sidebar-nav {
                        padding: 1rem 0;
                        flex-grow: 1;
                        overflow-y: auto;
            }

            .sidebar-nav .nav-item {
                        margin-bottom: 5px;
            }

            .sidebar-nav .nav-link {
                        color: #666666;
                        padding: 12px 15px;
                        border-radius: 8px;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        transition: all 0.2s ease-in-out;
            }

            .sidebar-nav .nav-link:hover {
                        background-color: #f0f4f8;
                        color: #333333;
            }

            .sidebar-nav .nav-link.active {
                        background-color: #e0f2fe;
                        color: #007bff;
                        font-weight: 600;
            }

            .sidebar-nav .nav-link i {
                        font-size: 1.2rem;
                        margin-right: 10px;
            }

            .sidebar-nav .nav-link .collapse-icon {
                        transition: transform 0.2s ease-in-out;
            }

            .sidebar-nav .nav-link.collapsed .collapse-icon {
                        transform: rotate(0deg);
            }

            .sidebar-nav .nav-link:not(.collapsed) .collapse-icon {
                        transform: rotate(180deg);
            }

            .sub-menu {
                        padding-left: 20px;
                        background-color: transparent;
                        list-style: none;
                        margin-top: 5px;
                        margin-bottom: 5px;
            }

            .sub-menu .nav-item .nav-link {
                        padding-top: 8px;
                        padding-bottom: 8px;
                        font-size: 0.95rem;
                        color: #666666;
            }

            .sub-menu .nav-item .nav-link.active {
                        background-color: #f0f4f8;
                        color: #007bff;
            }

            .sidebar .mt-auto {
                        margin-top: auto;
                        border-top: 1px solid #eeeeee;
                        padding-top: 15px;
            }

            @media (max-width: 768px) {
                        .sidebar {
                                    margin-left: -250px;
                        }

                        .sidebar.active {
                                    margin-left: 0;
                        }
            }
</style>
<div class="sidebar">
            <div class="sidebar-brand d-flex align-items-center justify-content-center py-3">
                        <h4 class="mb-0"><?= $site_title ?></h4>
            </div>
            <div class="sidebar-nav">
                        <ul class="nav flex-column">
                                    <li class="nav-item">
                                                <a class="nav-link <?= $active_menu == 'dashboard' ? 'active' : '' ?>"
                                                            href="<?= base_url('index.php') ?>">
                                                            <i class="bi bi-speedometer2"></i>
                                                            <span>Dashboard</span>
                                                </a>
                                    </li>

                                    <?php if (checkAccess('master')): ?>
                                                <li class="nav-item">
                                                            <a class="nav-link <?= $active_menu == 'master' ? 'active' : ''; ?>"
                                                                        href="<?= base_url('modules/kelola/produk') ?>"> <i class="bi bi-tags"></i>
                                                                        <span>Produk</span>
                                                            </a>
                                                </li>
                                                <li class="nav-item">
                                                            <a class="nav-link <?= $active_menu == 'master' ? 'active' : ''; ?>"
                                                                        href="<?= base_url('modules/kelola/opname_produk') ?>"> <i class="bi bi-box-seam"></i>
                                                                        <span>Opname Produk</span>
                                                            </a>
                                                </li>
                                    <?php endif; ?>

                                    <?php if (checkAccess('laporan')): ?>
                                                <li class="nav-item">
                                                            <a class="nav-link <?= $active_menu == 'laporan' ? 'active' : ''; ?>"
                                                                        href="<?= base_url('modules/laporan/opname_produk/') ?>"> <i class="bi bi-file-earmark-text"></i>
                                                                        <span>Laporan</span>
                                                            </a>
                                                </li>
                                    <?php endif; ?>

                                    <?php
                                    if (isset($user['role_title']) && $user['role_title'] === 'Admin'): ?>
                                                <li class="nav-item">
                                                            <a class="nav-link <?php echo $active_menu == 'settings' ? 'active' : ''; ?> collapsed" data-bs-toggle="collapse" href="#settingsCollapse" aria-expanded="<?php echo $active_menu == 'settings' ? 'true' : 'false'; ?>">
                                                                        <i class="bi bi-gear"></i>
                                                                        <span>Settings</span>
                                                                        <i class="bi bi-chevron-down float-end collapse-icon"></i>
                                                            </a>
                                                            <div class="collapse <?php echo $active_menu == 'settings' ? 'show' : ''; ?>" id="settingsCollapse">
                                                                        <ul class="nav flex-column sub-menu ps-4">
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'user' ? 'active' : ''; ?>" href="<?= base_url('modules/settings/user/') ?>">User</a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'website' ? 'active' : ''; ?>" href="<?= base_url('modules/settings/website/') ?>">Website</a>
                                                                                    </li>
                                                                        </ul>
                                                            </div>
                                                </li>
                                    <?php endif; ?>
                        </ul>
            </div>
</div>