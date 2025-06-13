<?php
require_once __DIR__ . '/../../../includes/header.php';

if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit;
}

$id = $_GET['id'];

// Cek apakah satuan digunakan di tabel barang
$check = $conn->prepare("SELECT COUNT(*) as total FROM barang WHERE satuan_barang_id = ?");
$check->bind_param('i', $id);
$check->execute();
$result = $check->get_result();
$row = $result->fetch_assoc();

if ($row['total'] > 0) {
            $_SESSION['error'] = 'Satuan tidak dapat dihapus karena masih digunakan di data barang';
            header('Location: ./index.php');
            exit;
}

$stmt = $conn->prepare("DELETE FROM satuan_barang WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
            $_SESSION['success'] = 'Satuan barang berhasil dihapus';
} else {
            $_SESSION['error'] = 'Satuan barang gagal dihapus';
}

header('Location: ./index.php');
exit;
