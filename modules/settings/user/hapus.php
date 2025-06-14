<?php
ob_start();
require_once __DIR__ . '/../../../includes/header.php';

if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
            $_SESSION['success'] = 'Akun berhasil dihapus';
} else {
            $_SESSION['error'] = 'Akun gagal dihapus: ' . $conn->error;
}

header('Location: index.php');
exit;
ob_end_flush();
