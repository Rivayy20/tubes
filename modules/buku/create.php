<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $pengarang = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $kategori_id = (int)$_POST['kategori_id'];
    $penerbit_id = !empty($_POST['penerbit_id']) ? (int)$_POST['penerbit_id'] : 'NULL';
    $stok = (int)$_POST['stok'];

    // Check if ISBN already exists
    $check_isbn = mysqli_query($conn, "SELECT id FROM buku WHERE isbn = '$isbn'");
    if (mysqli_num_rows($check_isbn) > 0) {
        $error = "ISBN sudah terdaftar!";
    } else {
        $query = "INSERT INTO buku (isbn, judul, pengarang, tahun_terbit, kategori_id, penerbit_id, stok) 
                  VALUES ('$isbn', '$judul', '$pengarang', $tahun_terbit, $kategori_id, $penerbit_id, $stok)";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Buku berhasil ditambahkan!";
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal menambahkan buku: " . mysqli_error($conn);
        }
    }
}

// Fetch categories for select
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Fetch publishers for select
$penerbit_result = mysqli_query($conn, "SELECT * FROM penerbit ORDER BY nama_penerbit ASC");

$pageTitle = 'Tambah Buku';
require_once __DIR__ . '/../../includes/layout_start.php';
?>

<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Buku</h1>
        <p class="mt-1 text-sm text-gray-500">Masukkan detail buku baru.</p>
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

<div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <form action="" method="POST">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                
                <div class="sm:col-span-3">
                    <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                    <div class="mt-1">
                        <input type="text" name="isbn" id="isbn" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" placeholder="Contoh: 9786020000000" required>
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul Buku</label>
                    <div class="mt-1">
                        <input type="text" name="judul" id="judul" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" required>
                    </div>
                </div>
                
                <div class="sm:col-span-3">
                    <label for="pengarang" class="block text-sm font-medium text-gray-700">Pengarang</label>
                    <div class="mt-1">
                        <input type="text" name="pengarang" id="pengarang" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" required>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                    <div class="mt-1">
                        <input type="number" name="tahun_terbit" id="tahun_terbit" min="1900" max="<?= date('Y') ?>" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" required>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <div class="mt-1">
                        <select id="kategori_id" name="kategori_id" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" required>
                            <option value="">Pilih Kategori...</option>
                            <?php while ($kat = mysqli_fetch_assoc($kategori_result)): ?>
                                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="penerbit_id" class="block text-sm font-medium text-gray-700">Penerbit (Opsional)</label>
                    <div class="mt-1">
                        <select id="penerbit_id" name="penerbit_id" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                            <option value="">Pilih Penerbit...</option>
                            <?php while ($pen = mysqli_fetch_assoc($penerbit_result)): ?>
                                <option value="<?= $pen['id'] ?>"><?= htmlspecialchars($pen['nama_penerbit']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="stok" class="block text-sm font-medium text-gray-700">Stok Awal</label>
                    <div class="mt-1">
                        <input type="number" name="stok" id="stok" min="0" value="0" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" required>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="index.php" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Batal</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Simpan Buku</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layout_end.php'; ?>
