<?php
require_once '../../../config/database.php';
require_once '../../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul_website = $_POST['judul_website'];
            $deskripsi = $_POST['deskripsi'];

            // Handle logo upload
            $logo = $_POST['logo_old'] ?? '';
            if ($_FILES['logo']['name']) {
                        $logo_name = 'logo_' . time() . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                        $logo_tmp = $_FILES['logo']['tmp_name'];
                        $logo_path = '../../../assets/images/' . $logo_name;

                        if (move_uploaded_file($logo_tmp, $logo_path)) {
                                    $logo = $logo_name;
                                    // Delete old logo if exists
                                    if (isset($_POST['logo_old']) && $_POST['logo_old'] && file_exists('../../../assets/images/' . $_POST['logo_old'])) {
                                                unlink('../../../assets/images/' . $_POST['logo_old']);
                                    }
                        }
            }

            // Handle favicon upload
            $favicon = $_POST['favicon_old'] ?? '';
            if ($_FILES['favicon']['name']) {
                        $favicon_name = 'favicon_' . time() . '.' . pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
                        $favicon_tmp = $_FILES['favicon']['tmp_name'];
                        $favicon_path = '../../../assets/images/' . $favicon_name;

                        if (move_uploaded_file($favicon_tmp, $favicon_path)) {
                                    $favicon = $favicon_name;
                                    // Delete old favicon if exists
                                    if (isset($_POST['favicon_old']) && $_POST['favicon_old'] && file_exists('../../../assets/images/' . $_POST['favicon_old'])) {
                                                unlink('../../../assets/images/' . $_POST['favicon_old']);
                                    }
                        }
            }

            $sql = "UPDATE website_settings SET judul_website = ?, deskripsi = ?, logo = ?, favicon = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $judul_website, $deskripsi, $logo, $favicon);

            if ($stmt->execute()) {
                        $_SESSION['success'] = 'Pengaturan website berhasil diperbarui';
            } else {
                        $_SESSION['error'] = 'Pengaturan website gagal diperbarui';
            }

            header('Location: index.php');
            exit;
}
