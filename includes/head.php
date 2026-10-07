<?php
// includes/head.php
require_once __DIR__ . '/../config/app.php';
$pageTitle = isset($pageTitle) ? $pageTitle . ' - Perpustakaan Digital' : 'Perpustakaan Digital';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>

<!-- Tailwind CSS via CDN -->
<!-- Catatan: Untuk tahap development agar mudah bagi tim. Untuk production sebaiknya di-build. -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          sans: ['Inter', 'sans-serif'],
        },
        colors: {
          brand: {
            50: '#eff6ff',
            100: '#dbeafe',
            500: '#3b82f6',
            600: '#2563eb',
            900: '#1e3a8a',
          }
        }
      }
    }
  }
</script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
