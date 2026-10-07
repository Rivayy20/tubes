<?php
// modules/anggota/edit.php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . url('modules/anggota/index.php'));
    exit;
}

// Ambil data anggota
$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute([':id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    $_SESSION['error_msg'] = 'Data anggota tidak ditemukan.';
    header('Location: ' . url('modules/anggota/index.php'));
    exit;
}

// Ambil daftar tipe keanggotaan
$stmtTipe = $pdo->query("SELECT * FROM tipe_keanggotaan ORDER BY nama_tipe ASC");
$tipeList = $stmtTipe->fetchAll(PDO::FETCH_ASSOC);

$errors = $_SESSION['form_errors'] ?? [];
$old    = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);

// Merge old data dengan data dari database
$data = array_merge($anggota, $old);

$pageTitle = 'Edit Anggota';
require_once __DIR__ . '/../../includes/layout_start.php';
?>

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
    <a href="<?= url('modules/anggota/index.php') ?>" class="hover:text-brand-600 transition-colors">Anggota & Kartu</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-gray-800 font-medium">Edit Anggota</span>
</nav>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Anggota</h1>
        <p class="mt-1 text-sm text-gray-500">Perbarui data anggota perpustakaan.</p>
    </div>
</div>

<?php if (!empty($errors)): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-5 rounded-r-lg">
    <p class="text-sm font-semibold text-red-700 mb-1">Terdapat kesalahan pada form:</p>
    <ul class="list-disc list-inside space-y-0.5">
        <?php foreach ($errors as $err): ?>
        <li class="text-sm text-red-600"><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= url('modules/anggota/handler.php') ?>" method="POST">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="id" value="<?= $id ?>">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Data Utama -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Pribadi -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                <div class="px-5 py-4 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900">Informasi Pribadi</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Data diri anggota perpustakaan</p>
                </div>
                <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama"
                               value="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                               placeholder="Contoh: Budi Santoso"
                               class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                               placeholder="contoh@email.com"
                               class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <input type="tel" id="no_hp" name="no_hp"
                               value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>"
                               placeholder="08xxxxxxxxxx"
                               class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"
                                  placeholder="Jl. Contoh No. 1, Kota, Provinsi"
                                  class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 resize-none"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Data Keanggotaan -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                <div class="px-5 py-4 border-b border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900">Data Keanggotaan</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi kartu dan status keanggotaan</p>
                </div>
                <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nomor_kartu" class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Kartu <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nomor_kartu" name="nomor_kartu"
                               value="<?= htmlspecialchars($data['nomor_kartu'] ?? '') ?>"
                               class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm font-mono focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                    <div>
                        <label for="tipe_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipe Keanggotaan <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_id" name="tipe_id"
                                class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 bg-white" required>
                            <option value="">-- Pilih Tipe --</option>
                            <?php foreach ($tipeList as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= ((int)($data['tipe_id'] ?? 0) === (int)$t['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['nama_tipe']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status"
                                class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 bg-white" required>
                            <option value="aktif"    <?= ($data['status'] ?? 'aktif') === 'aktif'    ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= ($data['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Non-Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_daftar" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Daftar <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_daftar" name="tanggal_daftar"
                               value="<?= htmlspecialchars($data['tanggal_daftar'] ?? '') ?>"
                               class="block w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Info Tipe & Tombol -->
        <div class="space-y-5">
            <!-- Info Tipe -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-xs font-semibold text-blue-700 mb-2 uppercase tracking-wide">Keterangan Tipe</p>
                <ul class="space-y-2 text-xs text-blue-700">
                    <?php foreach ($tipeList as $t): ?>
                    <li>
                        <span class="font-semibold"><?= htmlspecialchars($t['nama_tipe']) ?></span><br>
                        Maks Pinjam: <?= $t['maks_pinjam'] ?> Buku<br>
                        Masa Berlaku: <?= $t['masa_berlaku_bulan'] ?> Bulan
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Tombol Aksi -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-5 space-y-3">
                <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 py-2.5 px-4 bg-brand-600 text-white rounded-lg text-sm font-semibold hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="<?= url('modules/anggota/index.php') ?>"
                   class="w-full inline-flex justify-center items-center gap-2 py-2.5 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>
