<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

$pageTitle = 'Katalog Buku';
require_once __DIR__ . '/../../includes/layout_start.php';

$success_msg = '';
if (isset($_SESSION['success'])) {
    $success_msg = $_SESSION['success'];
    unset($_SESSION['success']);
}

$query = "SELECT b.*, k.nama_kategori, p.nama_penerbit 
          FROM buku b 
          JOIN kategori k ON b.kategori_id = k.id 
          LEFT JOIN penerbit p ON b.penerbit_id = p.id
          ORDER BY b.id DESC";
$result = mysqli_query($conn, $query);

$kategori_query = "SELECT * FROM kategori ORDER BY id DESC";
$kategori_result = mysqli_query($conn, $kategori_query);
?>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Katalog Buku</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar semua buku yang tersedia di perpustakaan.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="create.php" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Buku
        </a>
    </div>
</div>

<?php if ($success_msg): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700 font-medium"><?= htmlspecialchars($success_msg) ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Error from kategori (if any) -->
<?php if (isset($_SESSION['error'])): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="ml-3">
            <p class="text-sm text-red-700 font-medium"><?= htmlspecialchars($_SESSION['error']) ?></p>
        </div>
    </div>
</div>
<?php unset($_SESSION['error']); endif; ?>

<div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ISBN</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['isbn']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= htmlspecialchars($row['judul']) ?><br>
                        <span class="text-xs text-gray-500 font-normal">Oleh: <?= htmlspecialchars($row['pengarang']) ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($row['nama_kategori']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($row['stok']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="text-brand-600 hover:text-brand-900 mr-3">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($result) === 0): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data buku.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- KATEGORI BUKU SECTION -->
<div class="mt-12 sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Kategori Buku</h2>
        <p class="mt-1 text-sm text-gray-500">Kelola daftar kategori buku.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <form action="kategori_store.php" method="POST" class="flex gap-2">
            <input type="text" name="nama_kategori" placeholder="Nama Kategori Baru" required class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block sm:text-sm border-gray-300 rounded-md py-2 px-3 border" style="min-width: 200px;">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors shadow-sm whitespace-nowrap">
                Tambah Kategori
            </button>
        </form>
    </div>
</div>

<div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($kat = mysqli_fetch_assoc($kategori_result)): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($kat['id']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="kategori_edit.php?id=<?= $kat['id'] ?>" class="text-brand-600 hover:text-brand-900 mr-3">Edit</a>
                        <a href="kategori_delete.php?id=<?= $kat['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada buku yang menggunakan kategori ini.')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($kategori_result) === 0): ?>
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data kategori.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>
