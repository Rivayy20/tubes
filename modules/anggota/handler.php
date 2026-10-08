<?php
// modules/anggota/handler.php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';

$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('modules/anggota/index.php'));
    exit;
}

if ($action === 'create' || $action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'nama'           => trim($_POST['nama'] ?? ''),
        'email'          => trim($_POST['email'] ?? ''),
        'no_hp'          => trim($_POST['no_hp'] ?? ''),
        'alamat'         => trim($_POST['alamat'] ?? ''),
        'nomor_kartu'    => trim($_POST['nomor_kartu'] ?? ''),
        'tipe_id'        => (int)($_POST['tipe_id'] ?? 0),
        'status'         => trim($_POST['status'] ?? 'aktif'),
        'tanggal_daftar' => trim($_POST['tanggal_daftar'] ?? date('Y-m-d')),
    ];

    $errors = [];

    // Validasi
    if ($data['nama'] === '') $errors[] = 'Nama lengkap wajib diisi.';
    if ($data['nomor_kartu'] === '') $errors[] = 'Nomor kartu wajib diisi.';
    if ($data['tipe_id'] <= 0) $errors[] = 'Tipe keanggotaan wajib dipilih.';
    if ($data['tanggal_daftar'] === '') $errors[] = 'Tanggal daftar wajib diisi.';

    // Cek duplikasi nomor kartu
    if (empty($errors)) {
        $sqlCek = "SELECT id FROM anggota WHERE nomor_kartu = :nomor_kartu AND id != :id";
        $stmtCek = $pdo->prepare($sqlCek);
        $stmtCek->execute([':nomor_kartu' => $data['nomor_kartu'], ':id' => $id]);
        if ($stmtCek->fetch()) {
            $errors[] = 'Nomor kartu sudah digunakan oleh anggota lain.';
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_old'] = $data;
        $redirect = $action === 'create' ? 'create.php' : "edit.php?id=$id";
        header('Location: ' . url("modules/anggota/$redirect"));
        exit;
    }

    try {
        if ($action === 'create') {
            $sql = "INSERT INTO anggota (nomor_kartu, nama, email, no_hp, alamat, tipe_id, tanggal_daftar, status)
                    VALUES (:nomor_kartu, :nama, :email, :no_hp, :alamat, :tipe_id, :tanggal_daftar, :status)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            $_SESSION['success_msg'] = 'Anggota baru berhasil ditambahkan.';
        } else {
            $data['id'] = $id;
            $sql = "UPDATE anggota SET 
                        nomor_kartu = :nomor_kartu,
                        nama = :nama,
                        email = :email,
                        no_hp = :no_hp,
                        alamat = :alamat,
                        tipe_id = :tipe_id,
                        tanggal_daftar = :tanggal_daftar,
                        status = :status
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            $_SESSION['success_msg'] = 'Data anggota berhasil diperbarui.';
        }
    } catch (PDOException $e) {
        $_SESSION['error_msg'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
    }

    header('Location: ' . url('modules/anggota/index.php'));
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $_SESSION['success_msg'] = 'Data anggota berhasil dihapus.';
        } catch (PDOException $e) {
            // Jika ada foreign key constraint
            if ($e->getCode() == 23000) {
                $_SESSION['error_msg'] = 'Gagal menghapus: Anggota ini memiliki riwayat peminjaman.';
            } else {
                $_SESSION['error_msg'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            }
        }
    }
    header('Location: ' . url('modules/anggota/index.php'));
    exit;
}

header('Location: ' . url('modules/anggota/index.php'));
