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
<div class="sidebar">
            <div class="sidebar-brand text-center py-3">
                        <h4><?= $site_title ?></h4>
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
                                                            <a class="nav-link <?php echo $active_menu == 'master' ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#masterCollapse">
                                                                        <i class="bi bi-box-seam"></i>
                                                                        <span>Kelola Produk</span>
                                                                        <i class="bi bi-chevron-down float-end"></i>
                                                            </a>
                                                            <div class="collapse <?php echo $active_menu == 'master' ? 'show' : ''; ?>" id="masterCollapse">
                                                                        <ul class="nav flex-column sub-menu">
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'barang' ? 'active' : ''; ?>" href="<?= base_url('modules/kelola/produk') ?>">Produk</a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'jenis_barang' ? 'active' : ''; ?>" href="<?= base_url('modules/kelola/opname_produk/') ?>">Opname Produk</a>
                                                                                    </li>
                                                                        </ul>
                                                            </div>
                                                </li>
                                    <?php endif; ?>

                                    <?php if (checkAccess('transaksi')): ?>
                                                <li class="nav-item">
                                                            <a class="nav-link <?php echo $active_menu == 'transaksi' ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#transaksiCollapse">
                                                                        <i class="bi bi-arrow-left-right"></i>
                                                                        <span>Transaksi</span>
                                                                        <i class="bi bi-chevron-down float-end"></i>
                                                            </a>
                                                            <div class="collapse <?php echo $active_menu == 'transaksi' ? 'show' : ''; ?>" id="transaksiCollapse">
                                                                        <ul class="nav flex-column sub-menu">
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'barang_masuk' ? 'active' : ''; ?>" href="../modules/transaksi/barang_masuk">Barang Masuk</a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'barang_keluar' ? 'active' : ''; ?>" href="../modules/transaksi/barang_keluar">Barang Keluar</a>
                                                                                    </li>
                                                                        </ul>
                                                            </div>
                                                </li>
                                    <?php endif; ?>

                                    <?php if (checkAccess('laporan')): ?>
                                                <li class="nav-item">
                                                            <a class="nav-link <?php echo $active_menu == 'laporan' ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#laporanCollapse">
                                                                        <i class="bi bi-file-earmark-text"></i>
                                                                        <span>Laporan</span>
                                                                        <i class="bi bi-chevron-down float-end"></i>
                                                            </a>
                                                            <div class="collapse <?php echo $active_menu == 'laporan' ? 'show' : ''; ?>" id="laporanCollapse">
                                                                        <ul class="nav flex-column sub-menu">
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'lap_barang_masuk' ? 'active' : ''; ?>" href="../modules/laporan/barang_masuk">Lap. Barang Masuk</a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'lap_barang_keluar' ? 'active' : ''; ?>" href="../modules/laporan/barang_keluar">Lap. Barang Keluar</a>
                                                                                    </li>
                                                                                    <li class="nav-item">
                                                                                                <a class="nav-link <?php echo $active_submenu == 'lap_stok_barang' ? 'active' : ''; ?>" href="../modules/laporan/stok_barang">Lap. Stok Barang</a>
                                                                                    </li>
                                                                        </ul>
                                                            </div>
                                                </li>
                                    <?php endif; ?>

                                    <?php
                                    if (isset($user['role_title']) && $user['role_title'] === 'Admin'): ?>
                                                <li class="nav-item">
                                                            <a class="nav-link <?php echo $active_menu == 'settings' ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#settingsCollapse">
                                                                        <i class="bi bi-gear"></i>
                                                                        <span>Settings</span>
                                                                        <i class="bi bi-chevron-down float-end"></i>
                                                            </a>
                                                            <div class="collapse <?php echo $active_menu == 'settings' ? 'show' : ''; ?>" id="settingsCollapse">
                                                                        <ul class="nav flex-column sub-menu">
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