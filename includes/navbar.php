<?php
// includes/navbar.php
?>
<header class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
        <div class="flex items-center gap-4">
            <!-- Mobile sidebar toggle -->
            <button id="sidebarToggle" class="p-2 -ml-2 text-gray-500 hover:text-gray-600 lg:hidden focus:outline-none focus:ring-2 focus:ring-brand-500 rounded-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-xl font-bold text-gray-900 hidden sm:block">Perpustakaan</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="hidden sm:flex flex-col items-end mr-2">
                <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></span>
                <span class="text-xs text-brand-600 uppercase font-bold tracking-wider"><?= htmlspecialchars($_SESSION['user_role'] ?? 'Role') ?></span>
            </div>
            <a href="<?= url('auth/logout.php') ?>" class="text-sm font-medium text-gray-500 hover:text-red-600 transition-colors flex items-center gap-1 bg-gray-50 hover:bg-red-50 px-3 py-2 rounded-md">
                <span class="hidden sm:inline">Logout</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </a>
        </div>
    </div>
</header>
