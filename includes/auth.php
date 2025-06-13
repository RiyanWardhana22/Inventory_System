<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
            header('Location: ./login.php');
            exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT users.*, roles.title as role_title FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
            session_destroy();
            header('Location: ./login.php');
            exit;
}

// Check access for current page
$current_page = basename($_SERVER['PHP_SELF']);
$allowed_pages = ['index.php', 'profile.php', 'logout.php'];

if (!in_array($current_page, $allowed_pages)) {
            $menu_slug = str_replace(['tambah.php', 'edit.php', 'hapus.php'], 'index.php', $current_page);

            if (!checkAccess($menu_slug)) {
                        $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini";
                        header('Location: ./index.php');
                        exit;
            }
}
