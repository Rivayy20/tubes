<?php
// includes/sidebar.php
$menuItems = require __DIR__ . '/../config/menu.php';

// Mendeteksi halaman yang aktif secara sederhana
$currentUri = $_SERVER['REQUEST_URI'];
?>
<!-- Mobile overlay untuk menutup sidebar jika diklik di luarnya -->
<div id="sidebarOverlay" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 lg:translate-x-0 shadow-sm lg:shadow-none">
    <div class="h-full px-4 py-6 overflow-y-auto bg-white flex flex-col">
        <!-- Profil Mobile Info -->
        <div class="mb-6 lg:hidden px-2 pb-4 border-b border-gray-200 flex flex-col items-center">
            <div class="w-12 h-12 bg-brand-100 rounded-full flex items-center justify-center text-brand-600 font-bold text-xl mb-2">
                <?= substr(htmlspecialchars($_SESSION['user_name'] ?? 'P'), 0, 1) ?>
            </div>
            <span class="text-sm font-bold text-gray-900"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></span>
            <span class="text-xs text-brand-600 uppercase font-semibold mt-1 bg-brand-50 px-2 py-0.5 rounded"><?= htmlspecialchars($_SESSION['user_role'] ?? 'Role') ?></span>
        </div>

        <!-- Menu Navigasi -->
        <ul class="space-y-1.5 font-medium flex-1">
            <li class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</li>
            <?php foreach ($menuItems as $menu): ?>
                <?php 
                    // Cek aktif jika URI saat ini mengandung URL dari menu
                    $isActive = strpos($currentUri, $menu['url']) !== false;
                    $activeClass = $isActive 
                        ? 'bg-brand-50 text-brand-600' 
                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
                    $iconClass = $isActive ? 'text-brand-600' : 'text-gray-400 group-hover:text-gray-600';
                ?>
                <li>
                    <a href="<?= url($menu['url']) ?>" class="flex items-center px-3 py-2.5 rounded-lg group transition-colors <?= $activeClass ?>">
                        <div class="<?= $iconClass ?> transition-colors">
                            <?= $menu['icon'] ?>
                        </div>
                        <span class="ml-3"><?= htmlspecialchars($menu['label']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <div class="mt-auto px-3 pt-6 border-t border-gray-100">
            <div class="bg-brand-50 rounded-lg p-4 text-center">
                <p class="text-xs text-brand-600 font-semibold mb-1">Perpustakaan Digital</p>
                <p class="text-[10px] text-gray-500">Versi 1.0.0</p>
            </div>
        </div>
    </div>
</aside>
