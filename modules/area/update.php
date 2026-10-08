<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $kode_area = mysqli_real_escape_string($conn, $_POST['kode_area']);
    $nama_area = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $nomor_lantai = (int)$_POST['nomor_lantai'];
    $kapasitas = (int)$_POST['kapasitas'];
    $ada_stopkontak = isset($_POST['ada_stopkontak']) ? 1 : 0;
    $ada_wifi = isset($_POST['ada_wifi']) ? 1 : 0;

    $check_query = "SELECT id FROM area_baca WHERE kode_area = '$kode_area' AND id != $id";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = 'Kode Area sudah digunakan oleh area lain.';
        header("Location: edit.php?id=$id");
        exit();
    }

    $query = "UPDATE area_baca SET 
                kode_area = '$kode_area',
                nama_area = '$nama_area',
                jenis = '$jenis',
                nomor_lantai = $nomor_lantai,
                kapasitas = $kapasitas,
                ada_stopkontak = $ada_stopkontak,
                ada_wifi = $ada_wifi,
                status = '$status'
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Data area baca berhasil diperbarui.';
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = 'Gagal memperbarui data: ' . mysqli_error($conn);
        header("Location: edit.php?id=$id");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
