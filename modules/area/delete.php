<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $query = "DELETE FROM area_baca WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Data area baca berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Gagal menghapus data: ' . mysqli_error($conn);
    }
}

header("Location: index.php");
exit();
