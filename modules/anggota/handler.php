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
        $sqlCek = "SELECT id FROM anggota WHERE nomor_kartu = ? AND id != ?";
        $stmtCek = $conn->prepare($sqlCek);
        $stmtCek->bind_param("si", $data['nomor_kartu'], $id);
        $stmtCek->execute();
        $resultCek = $stmtCek->get_result();
        if ($resultCek->fetch_assoc()) {
            $errors[] = 'Nomor kartu sudah digunakan oleh anggota lain.';
        }
        $stmtCek->close();
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
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssiss", 
                $data['nomor_kartu'], $data['nama'], $data['email'], $data['no_hp'], 
                $data['alamat'], $data['tipe_id'], $data['tanggal_daftar'], $data['status']
            );
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_msg'] = 'Anggota baru berhasil ditambahkan.';
        } else {
            $sql = "UPDATE anggota SET 
                        nomor_kartu = ?,
                        nama = ?,
                        email = ?,
                        no_hp = ?,
                        alamat = ?,
                        tipe_id = ?,
                        tanggal_daftar = ?,
                        status = ?
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssissi", 
                $data['nomor_kartu'], $data['nama'], $data['email'], $data['no_hp'], 
                $data['alamat'], $data['tipe_id'], $data['tanggal_daftar'], $data['status'], $id
            );
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_msg'] = 'Data anggota berhasil diperbarui.';
        }
    } catch (mysqli_sql_exception $e) {
        $_SESSION['error_msg'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
    }

    header('Location: ' . url('modules/anggota/index.php'));
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        try {
            $stmt = $conn->prepare("DELETE FROM anggota WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_msg'] = 'Data anggota berhasil dihapus.';
        } catch (mysqli_sql_exception $e) {
            // Jika ada foreign key constraint
            if ($e->getCode() == 1451) {
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
