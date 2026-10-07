<?php
// config/app.php

// Tentukan Base URL aplikasi
// Sesuaikan jika dijalankan di server produksi atau subfolder yang berbeda
define('BASE_URL', '/tubes');

/**
 * Helper function untuk mendapatkan absolute URL
 * @param string $path path relatif (contoh: 'assets/css/style.css')
 * @return string url absolut
 */
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Helper function untuk mendapatkan absolute path file system
 * @param string $path path relatif
 * @return string path absolut di server
 */
function base_path($path = '') {
    return dirname(__DIR__) . '/' . ltrim($path, '/');
}
