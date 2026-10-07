<?php
// file: index.php (ROOT)
// Entry point sederhana, langsung alihkan (redirect) berdasarkan sesi

session_start();

// Jika sudah login, lempar ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/index.php");
    exit;
}

// Jika belum login, lempar ke login page
header("Location: auth/login.php");
exit;
