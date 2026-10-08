<?php
// modules/anggota/cetak_id.php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    die('ID Anggota tidak valid.');
}

$sql = "SELECT a.*, t.nama_tipe, t.masa_berlaku_bulan 
        FROM anggota a 
        JOIN tipe_keanggotaan t ON a.tipe_id = t.id 
        WHERE a.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$anggota = $result->fetch_assoc();
$stmt->close();

if (!$anggota) {
    die('Data anggota tidak ditemukan.');
}

$tglDaftar = new DateTime($anggota['tanggal_daftar']);
$tglBerlaku = clone $tglDaftar;
$tglBerlaku->modify('+' . $anggota['masa_berlaku_bulan'] . ' months');

$namaPendek = mb_strimwidth($anggota['nama'], 0, 20, '...');
$inisial = strtoupper(mb_substr($anggota['nama'], 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak ID Card - <?= htmlspecialchars($anggota['nama']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .no-print { display: none; }
            .card-container { box-shadow: none !important; border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 flex flex-col items-center justify-center min-h-screen">
    
    <div class="no-print mb-6 space-x-2">
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm font-medium">
            🖨️ Cetak ID Card
        </button>
        <a href="<?= url('modules/anggota/index.php') ?>" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 shadow-sm font-medium">
            Kembali
        </a>
    </div>

    <!-- ID Card Format (Ukuran KTP: 85.60 mm × 53.98 mm) -->
    <div class="card-container bg-white rounded-xl shadow-xl overflow-hidden relative" style="width: 324px; height: 204px;">
        
        <!-- Background Pattern / Header -->
        <div class="absolute top-0 left-0 w-full h-16 bg-blue-700 flex items-center px-4">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center font-bold text-blue-700 mr-2">
                P
            </div>
            <div>
                <h1 class="text-white font-bold text-sm leading-tight">PERPUSTAKAAN DIGITAL</h1>
                <p class="text-blue-200 text-[10px] leading-tight tracking-wider uppercase"><?= htmlspecialchars($anggota['nama_tipe']) ?> MEMBER</p>
            </div>
        </div>

        <!-- Content -->
        <div class="absolute top-16 left-0 w-full p-4 flex gap-3">
            <!-- Foto Profil -->
            <div class="w-16 h-20 rounded border border-gray-300 bg-gray-100 overflow-hidden flex-shrink-0 flex items-center justify-center flex-col">
                <span class="text-2xl text-gray-400 font-bold"><?= $inisial ?></span>
            </div>
            
            <!-- Data -->
            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 leading-tight uppercase">
                        <?= htmlspecialchars($namaPendek) ?>
                    </h2>
                    <p class="text-blue-600 font-mono font-semibold text-xs mt-0.5 tracking-wider">
                        <?= htmlspecialchars($anggota['nomor_kartu']) ?>
                    </p>
                </div>
                
                <div class="text-[10px] text-gray-600 mt-2">
                    <p>Berlaku s.d: <span class="font-semibold text-gray-900"><?= $tglBerlaku->format('d M Y') ?></span></p>
                    <p class="mt-0.5 truncate">Alamat: <?= htmlspecialchars($anggota['alamat'] ?: '-') ?></p>
                </div>
            </div>
        </div>

        <!-- Footer / Barcode Area -->
        <div class="absolute bottom-0 left-0 w-full h-8 bg-gray-50 border-t border-gray-200 flex items-center justify-between px-4">
            <span class="text-[8px] text-gray-400">Harap bawa kartu ini saat berkunjung.</span>
            <div class="w-24 h-4 bg-gray-800" style="background-image: repeating-linear-gradient(90deg, #1f2937, #1f2937 2px, transparent 2px, transparent 4px);"></div>
        </div>

        <!-- Watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L1 7l11 5 9-4.09V17h2V7L12 2z"/>
                <path d="M1 12l11 5 11-5v2l-11 5-11-5v-2z"/>
            </svg>
        </div>
    </div>

</body>
</html>
