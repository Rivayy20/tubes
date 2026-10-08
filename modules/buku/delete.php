<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    $query = "DELETE FROM buku WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Buku berhasil dihapus!";
    } else {
        $_SESSION['success'] = "Gagal menghapus buku: " . mysqli_error($conn);
    }
}

header("Location: index.php");
exit;
