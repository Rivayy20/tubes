<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    // Check if category is used by any book
    $check = mysqli_query($conn, "SELECT id FROM buku WHERE kategori_id = $id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Gagal menghapus! Kategori sedang digunakan oleh data buku.";
    } else {
        $query = "DELETE FROM kategori WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Kategori berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus kategori: " . mysqli_error($conn);
        }
    }
}

header("Location: index.php");
exit;
