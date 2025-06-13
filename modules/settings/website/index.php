<?php
$title = 'Pengaturan Website';
$active_menu = 'settings';
$active_submenu = 'website';
require_once '../../includes/header.php';

$sql = "SELECT * FROM website_settings LIMIT 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();

if (!$settings) {
            // Insert default settings if not exists
            $conn->query("INSERT INTO website_settings (judul_website, deskripsi) VALUES ('INVENTORYWEB', 'Sistem Manajemen Inventori')");
            $settings = [
                        'judul_website' => 'INVENTORYWEB',
                        'deskripsi' => 'Sistem Manajemen Inventori',
                        'logo' => '',
                        'favicon' => ''
            ];
}
?>

<div class="card shadow mb-4">
            <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pengaturan Website</h6>
            </div>
            <div class="card-body">
                        <form method="POST" action="update.php" enctype="multipart/form-data">
                                    <div class="row">
                                                <div class="col-md-6">
                                                            <div class="mb-3">
                                                                        <label for="judul_website" class="form-label">Judul Website</label>
                                                                        <input type="text" class="form-control" id="judul_website" name="judul_website" value="<?php echo $settings['judul_website']; ?>" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                        <label for="deskripsi" class="form-label">Deskripsi Website</label>
                                                                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo $settings['deskripsi']; ?></textarea>
                                                            </div>
                                                </div>

                                                <div class="col-md-6">
                                                            <div class="mb-3">
                                                                        <label for="logo" class="form-label">Logo</label>
                                                                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                                                        <?php if ($settings['logo']): ?>
                                                                                    <div class="mt-2">
                                                                                                <img src="../../assets/images/<?php echo $settings['logo']; ?>" width="100">
                                                                                                <input type="hidden" name="logo_old" value="<?php echo $settings['logo']; ?>">
                                                                                    </div>
                                                                        <?php endif; ?>
                                                            </div>

                                                            <div class="mb-3">
                                                                        <label for="favicon" class="form-label">Favicon</label>
                                                                        <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                                                                        <?php if ($settings['favicon']): ?>
                                                                                    <div class="mt-2">
                                                                                                <img src="../../assets/images/<?php echo $settings['favicon']; ?>" width="32">
                                                                                                <input type="hidden" name="favicon_old" value="<?php echo $settings['favicon']; ?>">
                                                                                    </div>
                                                                        <?php endif; ?>
                                                            </div>
                                                </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
            </div>
</div>

<?php
require_once '../../includes/footer.php';
?>