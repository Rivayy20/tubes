# 📚 Panduan Kerja Tim: Sistem Perpustakaan Digital

File ini menjelaskan struktur layout baru yang sudah mengadopsi Tailwind CSS secara dinamis (via CDN) tanpa merusak/mengubah logika sistem auth yang lama. Tujuannya agar pengerjaan bagian UI bisa dipisah secara mandiri dan memperkecil kemungkinan *merge conflict*.

## 📂 Struktur Modul Final
```text
tubes/
├── auth/            # Logika keamanan (Dilarang ubah sembarangan)
├── config/          # menu.php (atur isi sidebar di sini), app.php, db.php
├── includes/        # Template master (head, navbar, sidebar, layout start/end)
└── modules/         # TUGAS MASING-MASING TIM DI SINI!
    ├── _template/   # 👉 File contoh/boilerplate UI (Bisa kamu copas)
    ├── anggota/     # (Modul B)
    ├── area/        # (Modul D)
    ├── buku/        # (Modul A)
    └── penerbit/    # (Modul C)
```

---

## 🛠️ Cara Membuat Halaman Baru (Step-by-step)

Masing-masing penanggung jawab modul hanya **BEBAS** untuk mengubah file di dalam folder modulnya saja (misal `modules/buku/`).

1. **Buat File/Folder**  
   Di dalam folder modul kalian, buat file `index.php` untuk daftar data, `create.php` untuk tambah data, dsb. Atau cara paling gampang, **copy paste dari `modules/_template/index.php`**.

2. **Gunakan Template Layout**  
   Setiap halaman **wajib** strukturnya seperti ini:
   ```php
   <?php
   // 1. Panggil check_auth untuk keamanan
   require_once __DIR__ . '/../../auth/check_auth.php';
   
   // 2. Tentukan judul halaman
   $pageTitle = 'Judul Halaman Bebas';
   
   // 3. Muat desain atas (navbar, sidebar)
   require_once __DIR__ . '/../../includes/layout_start.php';
   ?>
   
   <!-- ================================ -->
   <!-- TULIS KODE HTML / LOGIKA DI SINI -->
   <!-- ================================ -->
   
   <?php 
   // 4. Muat penutup layout
   require_once __DIR__ . '/../../includes/layout_end.php'; 
   ?>
   ```

3. **Gunakan Base URL untuk Link & Gambar**  
   Kalau kamu bikin `<form action="...">` atau `<a>` ke halaman yang beda folder, **gunakan `url(...)` helper**.
   - Contoh Benar: `<a href="<?= url('modules/buku/create.php') ?>">`
   - Contoh Salah: `<a href="../buku/create.php">` *(rawan pecah jika foldernya dimodifikasi)*

4. **Koneksi Database**  
   Kalau butuh insert/select data, *include* file `db.php`:
   ```php
   require_once __DIR__ . '/../../config/db.php';
   // lalu pakai variabel $conn seperti biasa
   ```

---

## 🤝 Aturan Kerja Tim
1. **Jangan mengubah `auth/*`** atau layout di dalam `includes/` sendirian tanpa bilang ke grup.
2. Jika butuh nambah menu baru di kiri (sidebar), tambahkan array baru di `config/menu.php`. Jangan edit manual file HTML-nya!
3. Pakai class-class Tailwind CSS dari contoh `_template` biar desainnya kompak dan seragam se-proyek. 
4. Akses proyek ini di browser seperti biasa `http://localhost/tubes/`. Sistem root akan langsung melempar kamu ke halaman login/dashboard secara otomatis.
