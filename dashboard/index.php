<?php
require_once __DIR__ . '/../auth/check_auth.php';
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna'); ?>!</h1>
    <p class="mt-1 text-sm text-gray-500">Anda saat ini masuk sebagai <span class="font-semibold text-brand-600"><?= htmlspecialchars($_SESSION['user_role'] ?? 'Guest'); ?></span>. Berikut ringkasan aktivitas perpustakaan digital hari ini.</p>
</div>

<!-- Dashboard Stats Widgets -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg transition-all hover:shadow-md">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-brand-50 border border-brand-100 rounded-md p-3 text-brand-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Anggota</dt>
                        <dd class="text-xl font-bold text-gray-900">120</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg transition-all hover:shadow-md">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-50 border border-green-100 rounded-md p-3 text-green-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Buku</dt>
                        <dd class="text-xl font-bold text-gray-900">4,500</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity / Quick Actions -->
<div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Aktivitas Terbaru</h2>
    <div class="bg-gray-50 rounded-lg p-8 border-2 border-dashed border-gray-300 text-center">
        <p class="text-sm text-gray-500">Belum ada aktivitas terekam hari ini. Pilih menu di sidebar untuk mulai mengelola perpustakaan.</p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>
