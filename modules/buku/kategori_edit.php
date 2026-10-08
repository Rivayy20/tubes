<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';

if (!$id) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = mysqli_real_escape_string($conn, trim($_POST['nama_kategori']));
    
    if (empty($nama_kategori)) {
        $error = "Nama kategori tidak boleh kosong!";
    } else {
        // Check if another category has the same name
        $check = mysqli_query($conn, "SELECT id FROM kategori WHERE nama_kategori = '$nama_kategori' AND id != $id");
        if (mysqli_num_rows($check) > 0) {
            $error = "Kategori '$nama_kategori' sudah terdaftar!";
        } else {
            $query = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id = $id";
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Kategori berhasil diperbarui!";
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal memperbarui kategori: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch existing data
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori WHERE id = $id");
if (mysqli_num_rows($kategori_result) === 0) {
    header("Location: index.php");
    exit;
}
$kategori = mysqli_fetch_assoc($kategori_result);

$pageTitle = 'Edit Kategori Buku';
require_once __DIR__ . '/../../includes/layout_start.php';
?>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
        <p class="mt-1 text-sm text-gray-500">Ubah nama kategori buku.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="index.php" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors shadow-sm">
            Kembali
        </a>
    </div>
</div>

<?php if ($error): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="ml-3">
            <p class="text-sm text-red-700 font-medium"><?= htmlspecialchars($error) ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-8 max-w-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="" method="POST">
            <div>
                <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <div class="mt-1">
                    <input type="text" name="nama_kategori" id="nama_kategori" value="<?= htmlspecialchars($kategori['nama_kategori']) ?>" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" required>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3 pt-5 border-t border-gray-100">
                <a href="index.php" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Batal</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Perbarui Kategori</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>
