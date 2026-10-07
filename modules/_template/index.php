<?php
// modules/_template/index.php
// Contoh Halaman Template untuk ditiru oleh anggota tim

// 1. Memuat auth check (harus paling atas sebelum output apapun)
require_once __DIR__ . '/../../auth/check_auth.php';

// 2. Set title halaman
$pageTitle = 'Contoh Modul Template';

// 3. Muat layout awal
require_once __DIR__ . '/../../includes/layout_start.php';
?>

<!-- Header Section (Judul & Tombol Tambah) -->
<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Data</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar semua data yang ada di modul ini.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="#" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors shadow-sm">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Data
        </a>
    </div>
</div>

<!-- Contoh Alert Success/Error (Gunakan jika ada notifikasi dari session) -->
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700 font-medium">Aksi berhasil dilakukan dengan aman!</p>
        </div>
    </div>
</div>

<!-- Contoh Komponen Tabel Data -->
<div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Looping data di sini -->
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Data Dummy Pertama</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-brand-600 hover:text-brand-900 mr-3">Edit</a>
                        <a href="#" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Contoh Komponen Form Input (Bisa dipisah di file create.php) -->
<div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-8">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Contoh Form Input</h3>
        <p class="mt-1 text-sm text-gray-500">Gunakan class bawaan Tailwind untuk form standar.</p>
    </div>
    <div class="px-4 py-5 sm:p-6">
        <form action="#" method="POST">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Input Text -->
                <div class="sm:col-span-3">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="mt-1">
                        <input type="text" name="nama" id="nama" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" placeholder="Masukkan nama..." required>
                    </div>
                </div>
                
                <!-- Input Select -->
                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <select id="status" name="status" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                
                <!-- Textarea -->
                <div class="sm:col-span-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan / Deskripsi</label>
                    <div class="mt-1">
                        <textarea id="keterangan" name="keterangan" rows="3" class="shadow-sm focus:ring-brand-500 focus:border-brand-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Batal</button>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php 
// 4. Muat layout akhir (wajib di paling bawah)
require_once __DIR__ . '/../../includes/layout_end.php'; 
?>
