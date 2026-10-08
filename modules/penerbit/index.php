<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

$pageTitle = 'Penerbit & Supplier';
require_once __DIR__ . '/../../includes/layout_start.php';

// Ambil data penerbit beserta wilayahnya
$query = "SELECT p.*, w.nama_wilayah 
          FROM penerbit p 
          LEFT JOIN wilayah w ON p.wilayah_id = w.id 
          ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Penerbit & Supplier</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar semua penerbit dan supplier buku.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="create.php" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors shadow-sm">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Penerbit
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700 font-medium"><?= $_SESSION['success']; ?></p>
        </div>
    </div>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-red-700 font-medium"><?= $_SESSION['error']; ?></p>
        </div>
    </div>
</div>
<?php unset($_SESSION['error']); endif; ?>

<div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Penerbit</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sales</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++; ?></td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            <?= htmlspecialchars($row['nama_penerbit']); ?>
                            <?php if(!empty($row['alamat'])): ?>
                                <div class="text-xs text-gray-500 font-normal mt-1"><?= htmlspecialchars($row['alamat']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $row['nama_wilayah'] ? htmlspecialchars($row['nama_wilayah']) : '<span class="text-gray-400 italic">Tidak ada</span>'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($row['nama_sales']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php if(!empty($row['kontak_sales'])): ?>
                                <div><?= htmlspecialchars($row['kontak_sales']); ?></div>
                            <?php endif; ?>
                            <?php if(!empty($row['email'])): ?>
                                <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($row['email']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="text-brand-600 hover:text-brand-900 mr-3">Edit</a>
                            <a href="delete.php?id=<?= $row['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus penerbit ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                            Belum ada data penerbit atau supplier.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>
