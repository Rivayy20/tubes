<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

// Cek ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

// Ambil data penerbit yang akan diedit
$query_penerbit = "SELECT * FROM penerbit WHERE id = $id";
$result_penerbit = mysqli_query($conn, $query_penerbit);

if (mysqli_num_rows($result_penerbit) === 0) {
    header("Location: index.php");
    exit();
}

$penerbit = mysqli_fetch_assoc($result_penerbit);

// Proses submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penerbit = mysqli_real_escape_string($conn, $_POST['nama_penerbit']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $wilayah_id = (int)$_POST['wilayah_id'];
    $nama_sales = mysqli_real_escape_string($conn, $_POST['nama_sales']);
    $kontak_sales = mysqli_real_escape_string($conn, $_POST['kontak_sales']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query_update = "UPDATE penerbit SET 
                     nama_penerbit = '$nama_penerbit', 
                     alamat = '$alamat', 
                     wilayah_id = $wilayah_id, 
                     nama_sales = '$nama_sales', 
                     kontak_sales = '$kontak_sales', 
                     email = '$email'
                     WHERE id = $id";
    
    if (mysqli_query($conn, $query_update)) {
        $_SESSION['success'] = "Data penerbit berhasil diupdate!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal mengupdate data: " . mysqli_error($conn);
    }
}

$pageTitle = 'Edit Penerbit';
require_once __DIR__ . '/../../includes/layout_start.php';

// Ambil data wilayah untuk dropdown
$query_wilayah = "SELECT * FROM wilayah ORDER BY nama_wilayah ASC";
$result_wilayah = mysqli_query($conn, $query_wilayah);
?>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Penerbit</h1>
        <p class="mt-1 text-sm text-gray-500">Ubah informasi penerbit atau supplier.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="index.php" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors shadow-sm">
            Kembali
        </a>
    </div>
</div>

<?php if (isset($error)): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="ml-3">
            <p class="text-sm text-red-700 font-medium"><?= $error; ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <form action="" method="POST">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                
                <div class="sm:col-span-4">
                    <label for="nama_penerbit" class="block text-sm font-medium text-gray-700">Nama Penerbit / Supplier <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="nama_penerbit" id="nama_penerbit" value="<?= htmlspecialchars($penerbit['nama_penerbit']); ?>" required class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                    </div>
                </div>
                
                <div class="sm:col-span-2">
                    <label for="wilayah_id" class="block text-sm font-medium text-gray-700">Wilayah <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <select id="wilayah_id" name="wilayah_id" required class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                            <option value="">Pilih Wilayah</option>
                            <?php while($row = mysqli_fetch_assoc($result_wilayah)): ?>
                                <option value="<?= $row['id']; ?>" <?= ($row['id'] == $penerbit['wilayah_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($row['nama_wilayah']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <div class="mt-1">
                        <textarea id="alamat" name="alamat" rows="3" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border"><?= htmlspecialchars($penerbit['alamat']); ?></textarea>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="nama_sales" class="block text-sm font-medium text-gray-700">Nama Sales (PIC)</label>
                    <div class="mt-1">
                        <input type="text" name="nama_sales" id="nama_sales" value="<?= htmlspecialchars($penerbit['nama_sales']); ?>" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="kontak_sales" class="block text-sm font-medium text-gray-700">No. Telepon / WhatsApp</label>
                    <div class="mt-1">
                        <input type="text" name="kontak_sales" id="kontak_sales" value="<?= htmlspecialchars($penerbit['kontak_sales']); ?>" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($penerbit['email']); ?>" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="index.php" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Batal</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Update Data</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>

