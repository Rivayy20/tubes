<?php
// modules/anggota/index.php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';

// Ambil notifikasi dari session
$successMsg = $_SESSION['success_msg'] ?? null;
$errorMsg   = $_SESSION['error_msg'] ?? null;
unset($_SESSION['success_msg'], $_SESSION['error_msg']);

// Parameter filter & pencarian
$search     = trim($_GET['q'] ?? '');
$filterTipe = (int)($_GET['tipe'] ?? 0);
$page       = max(1, (int)($_GET['page'] ?? 1));
$perPage    = 10;
$offset     = ($page - 1) * $perPage;

$anggotaList = [];
$totalRows   = 0;

// Ambil daftar tipe keanggotaan untuk dropdown filter
$stmtTipe = $pdo->query("SELECT * FROM tipe_keanggotaan ORDER BY nama_tipe ASC");
$tipeList = $stmtTipe->fetchAll(PDO::FETCH_ASSOC);

// Bangun query dengan kondisi filter
$conditions = ['1=1'];
$params     = [];

if ($search !== '') {
    $conditions[] = '(a.nama LIKE :q OR a.nomor_kartu LIKE :q OR a.email LIKE :q)';
    $params[':q'] = '%' . $search . '%';
}

if ($filterTipe > 0) {
    $conditions[] = 'a.tipe_id = :tipe';
    $params[':tipe'] = $filterTipe;
}

$where = implode(' AND ', $conditions);

// Hitung total untuk paginasi
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM anggota a WHERE $where");
$stmtCount->execute($params);
$totalRows = (int)$stmtCount->fetchColumn();

// Ambil data sesuai halaman
$sql = "SELECT a.*, t.nama_tipe, t.masa_berlaku_bulan 
        FROM anggota a 
        JOIN tipe_keanggotaan t ON a.tipe_id = t.id 
        WHERE $where 
        ORDER BY a.created_at DESC 
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$anggotaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = $totalRows > 0 ? ceil($totalRows / $perPage) : 1;

$statusLabel = [
    'aktif'    => ['label' => 'Aktif',    'color' => 'bg-green-100 text-green-800'],
    'nonaktif' => ['label' => 'Non-Aktif','color' => 'bg-red-100 text-red-800'],
];

$pageTitle = 'Anggota & Kartu';
require_once __DIR__ . '/../../includes/layout_start.php';
?>

<!-- Header -->
<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Anggota & Kartu</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola data anggota, nomor kartu, tipe keanggotaan, dan cetak ID card.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="<?= url('modules/anggota/create.php') ?>"
           class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors shadow-sm">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Anggota
        </a>
    </div>
</div>

<?php if ($successMsg): ?>
<div id="alertSuccess" class="bg-green-50 border-l-4 border-green-500 p-4 mb-5 rounded-r-lg shadow-sm flex items-start gap-3">
    <svg class="h-5 w-5 text-green-500 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <p class="text-sm text-green-700 font-medium"><?= htmlspecialchars($successMsg) ?></p>
</div>
<?php endif; ?>

<?php if ($errorMsg): ?>
<div id="alertError" class="bg-red-50 border-l-4 border-red-500 p-4 mb-5 rounded-r-lg shadow-sm flex items-start gap-3">
    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <p class="text-sm text-red-700 font-medium"><?= htmlspecialchars($errorMsg) ?></p>
</div>
<?php endif; ?>

<!-- Filter & Pencarian -->
<div class="bg-white shadow-sm border border-gray-200 rounded-lg p-4 mb-5">
    <form method="GET" action="" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
            </div>
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
                   placeholder="Cari nama, nomor kartu, atau email..."
                   class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500">
        </div>
        <select name="tipe" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 bg-white">
            <option value="0">Semua Tipe</option>
            <?php foreach ($tipeList as $t): ?>
                <option value="<?= $t['id'] ?>" <?= $filterTipe === (int)$t['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['nama_tipe']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
            Cari
        </button>
        <?php if ($search || $filterTipe > 0): ?>
        <a href="<?= url('modules/anggota/index.php') ?>"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            Reset
        </a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel Data Anggota -->
<div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-700">
            Total: <span class="text-brand-600"><?= number_format($totalRows) ?></span> anggota terdaftar
        </h2>
        <?php if ($totalRows > 0): ?>
        <span class="text-xs text-gray-400">Halaman <?= $page ?> dari <?= $totalPages ?></span>
        <?php endif; ?>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Kartu</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Berlaku s.d.</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if (empty($anggotaList)): ?>
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm text-gray-500">
                            <?= ($search || $filterTipe > 0) ? 'Tidak ada anggota yang cocok dengan filter.' : 'Belum ada anggota terdaftar.' ?>
                        </p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($anggotaList as $row): 
                    $status = $statusLabel[$row['status']] ?? ['label' => $row['status'], 'color' => 'bg-gray-100 text-gray-600'];
                    // Hitung tanggal expired (tanggal_daftar + masa_berlaku_bulan)
                    $tglDaftar = new DateTime($row['tanggal_daftar']);
                    $tglDaftar->modify('+' . $row['masa_berlaku_bulan'] . ' months');
                    $isExpired = $tglDaftar->getTimestamp() < time();
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="font-mono text-sm font-semibold text-gray-700 bg-gray-100 px-2 py-0.5 rounded">
                            <?= htmlspecialchars($row['nomor_kartu']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 font-bold text-sm flex-shrink-0">
                                <?= strtoupper(mb_substr($row['nama'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($row['nama']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($row['email'] ?? '-') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            <?= htmlspecialchars($row['nama_tipe']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $status['color'] ?>">
                            <?= $status['label'] ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-sm <?= $isExpired ? 'text-red-500 font-medium' : 'text-gray-500' ?>">
                        <?= $tglDaftar->format('d M Y') ?>
                        <?php if ($isExpired): ?><span class="text-xs">(Kedaluwarsa)</span><?php endif; ?>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= url('modules/anggota/cetak_id.php') ?>?id=<?= $row['id'] ?>"
                               title="Cetak ID Card"
                               target="_blank"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                ID Card
                            </a>
                            <a href="<?= url('modules/anggota/edit.php') ?>?id=<?= $row['id'] ?>"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <button type="button"
                                    onclick="konfirmasiHapus(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['nama'])) ?>')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginasi -->
    <?php if ($totalPages > 1): ?>
    <div class="px-5 py-4 border-t border-gray-200 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Menampilkan <?= number_format($offset + 1) ?>–<?= number_format(min($offset + $perPage, $totalRows)) ?> dari <?= number_format($totalRows) ?> data
        </p>
        <div class="flex gap-1">
            <?php
            $baseUrl = url('modules/anggota/index.php') . '?' . http_build_query(array_filter(['q' => $search, 'tipe' => $filterTipe]));
            $sep = ($search || $filterTipe > 0) ? '&' : '?';
            ?>
            <?php if ($page > 1): ?>
            <a href="<?= $baseUrl . $sep ?>page=<?= $page - 1 ?>"
               class="px-3 py-1.5 text-sm border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50 transition-colors">
                &laquo; Prev
            </a>
            <?php endif; ?>
            <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
            <a href="<?= $baseUrl . $sep ?>page=<?= $p ?>"
               class="px-3 py-1.5 text-sm border rounded-md transition-colors <?= $p === $page ? 'bg-brand-600 text-white border-brand-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50' ?>">
                <?= $p ?>
            </a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
            <a href="<?= $baseUrl . $sep ?>page=<?= $page + 1 ?>"
               class="px-3 py-1.5 text-sm border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50 transition-colors">
                Next &raquo;
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modalHapus" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="tutupModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 relative z-10">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Hapus Anggota</h3>
            <p class="text-sm text-center text-gray-500 mb-6">
                Yakin ingin menghapus data anggota <strong id="namaHapus" class="text-gray-800"></strong>?
                Tindakan ini tidak bisa dibatalkan.
            </p>
            <form id="formHapus" method="POST" action="<?= url('modules/anggota/handler.php') ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="idHapus" value="">
                <div class="flex gap-3">
                    <button type="button" onclick="tutupModal()"
                            class="flex-1 py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2 px-4 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function konfirmasiHapus(id, nama) {
    document.getElementById('idHapus').value = id;
    document.getElementById('namaHapus').textContent = nama;
    document.getElementById('modalHapus').classList.remove('hidden');
}

function tutupModal() {
    document.getElementById('modalHapus').classList.add('hidden');
}

// Auto-dismiss alert setelah 4 detik
['alertSuccess', 'alertError'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) setTimeout(function() { el.style.opacity='0'; el.style.transition='opacity 0.5s'; setTimeout(function(){ el.remove(); }, 500); }, 4000);
});
</script>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>
