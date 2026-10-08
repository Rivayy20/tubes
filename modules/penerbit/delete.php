<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Cek apakah penerbit masih digunakan di tabel buku
    $query_check = "SELECT id FROM buku WHERE penerbit_id = $id LIMIT 1";
    $result_check = mysqli_query($conn, $query_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['error'] = "Penerbit tidak dapat dihapus karena sedang digunakan pada data buku.";
    } else {
        $query_delete = "DELETE FROM penerbit WHERE id = $id";
        
        if (mysqli_query($conn, $query_delete)) {
            $_SESSION['success'] = "Data penerbit berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
        }
    }
}

header("Location: index.php");
exit();
?>

