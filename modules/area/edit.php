<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$query = "SELECT * FROM area_baca WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = 'Data tidak ditemukan.';
    header("Location: index.php");
    exit();
}

$area = mysqli_fetch_assoc($result);
$pageTitle = 'Edit Area Baca';
require_once __DIR__ . '/../../includes/layout_start.php';
?>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Area Baca</h1>
        <p class="mt-1 text-sm text-gray-500">Ubah informasi area baca di bawah ini.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="index.php" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors shadow-sm">
            Kembali
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-red-700 font-medium"><?= htmlspecialchars($_SESSION['error']) ?></p>
        </div>
    </div>
</div>
<?php unset($_SESSION['error']); endif; ?>

<div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= $area['id'] ?>">
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                
                <div class="sm:col-span-3">
                    <label for="kode_area" class="block text-sm font-medium text-gray-700">Kode Area</label>
                    <div class="mt-1">
                        <input type="text" name="kode_area" id="kode_area" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" value="<?= htmlspecialchars($area['kode_area']) ?>" required>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="nama_area" class="block text-sm font-medium text-gray-700">Nama Area</label>
                    <div class="mt-1">
                        <input type="text" name="nama_area" id="nama_area" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" value="<?= htmlspecialchars($area['nama_area']) ?>" required>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                    <div class="mt-1">
                        <select id="jenis" name="jenis" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                            <option value="meja" <?= $area['jenis'] == 'meja' ? 'selected' : '' ?>>Meja</option>
                            <option value="bilik" <?= $area['jenis'] == 'bilik' ? 'selected' : '' ?>>Bilik</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <select id="status" name="status" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                            <option value="tersedia" <?= $area['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="terpakai" <?= $area['status'] == 'terpakai' ? 'selected' : '' ?>>Terpakai</option>
                            <option value="maintenance" <?= $area['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="nomor_lantai" class="block text-sm font-medium text-gray-700">Nomor Lantai</label>
                    <div class="mt-1">
                        <input type="number" min="1" name="nomor_lantai" id="nomor_lantai" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" value="<?= $area['nomor_lantai'] ?>" required>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas (Orang)</label>
                    <div class="mt-1">
                        <input type="number" min="1" name="kapasitas" id="kapasitas" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" value="<?= $area['kapasitas'] ?>" required>
                    </div>
                </div>

                <div class="sm:col-span-6 mt-2">
                    <div class="flex items-start gap-4">
                        <div class="flex items-center h-5">
                            <input id="ada_stopkontak" name="ada_stopkontak" type="checkbox" value="1" <?= $area['ada_stopkontak'] ? 'checked' : '' ?> class="focus:ring-brand-500 h-4 w-4 text-brand-600 border-gray-300 rounded">
                            <label for="ada_stopkontak" class="ml-2 block text-sm text-gray-900">
                                Ada Stopkontak
                            </label>
                        </div>
                        <div class="flex items-center h-5">
                            <input id="ada_wifi" name="ada_wifi" type="checkbox" value="1" <?= $area['ada_wifi'] ? 'checked' : '' ?> class="focus:ring-brand-500 h-4 w-4 text-brand-600 border-gray-300 rounded">
                            <label for="ada_wifi" class="ml-2 block text-sm text-gray-900">
                                Ada WiFi
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="index.php" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Batal</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>
