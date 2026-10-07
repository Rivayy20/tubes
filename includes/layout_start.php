<?php
// includes/layout_start.php
// Pastikan variabel $pageTitle diset sebelum menginclude file ini (opsional)
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <?php require __DIR__ . '/head.php'; ?>
</head>
<body class="h-full flex flex-col font-sans antialiased text-gray-900 selection:bg-brand-100 selection:text-brand-900">
    
    <?php require __DIR__ . '/navbar.php'; ?>
    <?php require __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="p-4 lg:ml-64 flex-1 flex flex-col min-h-0 pt-6">
        <main class="flex-1 max-w-7xl w-full mx-auto pb-8">
