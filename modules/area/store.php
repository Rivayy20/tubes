<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_area = mysqli_real_escape_string($conn, $_POST['kode_area']);
    $nama_area = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $nomor_lantai = (int)$_POST['nomor_lantai'];
    $kapasitas = (int)$_POST['kapasitas'];
    $ada_stopkontak = isset($_POST['ada_stopkontak']) ? 1 : 0;
    $ada_wifi = isset($_POST['ada_wifi']) ? 1 : 0;

    $check_query = "SELECT id FROM area_baca WHERE kode_area = '$kode_area'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = 'Kode Area sudah digunakan.';
        header("Location: create.php");
        exit();
    }

    $query = "INSERT INTO area_baca (kode_area, nama_area, jenis, nomor_lantai, kapasitas, ada_stopkontak, ada_wifi, status) 
              VALUES ('$kode_area', '$nama_area', '$jenis', $nomor_lantai, $kapasitas, $ada_stopkontak, $ada_wifi, '$status')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Data area baca berhasil ditambahkan.';
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = 'Gagal menambahkan data: ' . mysqli_error($conn);
        header("Location: create.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
