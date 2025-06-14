<?php
require_once '../../../config/database.php';
require_once '../../../includes/header.php';

if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'ID jenis barang tidak ditemukan';
            header('Location: index.php');
            exit;
}

$id = $_GET['id'];

$check_sql = "SELECT COUNT(*) as total FROM barang WHERE jenis_barang_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param('i', $id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_row = $check_result->fetch_assoc();

if ($check_row['total'] > 0) {
            $_SESSION['error'] = 'Jenis barang tidak dapat dihapus karena sudah digunakan di data barang';
            header('Location: index.php');
            exit;
}

$sql = "DELETE FROM jenis_barang WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
            $_SESSION['success'] = 'Jenis barang berhasil dihapus';
} else {
            $_SESSION['error'] = 'Jenis barang gagal dihapus';
}

header('Location: index.php');
exit;
