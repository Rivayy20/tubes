<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = mysqli_real_escape_string($conn, trim($_POST['nama_kategori']));
    
    if (!empty($nama_kategori)) {
        // Check for duplicate
        $check = mysqli_query($conn, "SELECT id FROM kategori WHERE nama_kategori = '$nama_kategori'");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['error'] = "Kategori '$nama_kategori' sudah ada!";
        } else {
            $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Kategori berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan kategori: " . mysqli_error($conn);
            }
        }
    } else {
        $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
    }
}

header("Location: index.php");
exit;
