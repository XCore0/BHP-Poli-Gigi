<?php
$page = $_GET['page'] ?? 'dashboard';
$page_title = 'Poli Gigi';
$page_desc = '';
$active_page = $page;
$active_submenu = '';

// Role config for Kepala Klinik
$role_name = 'Kepala Klinik';
$role_label = 'Kepala Klinik';
$role_avatar_initial = 'K';
$role_avatar_bg = 'linear-gradient(135deg, #fde68a 0%, #f59e0b 100%)';
$role_avatar_color = '#78350f';
$role_label_color = 'text-amber-600';

if ($page == 'dashboard') {
    $page_title = 'Dashboard';
    $page_desc = 'Ringkasan informasi dan monitoring klinik';
} elseif ($page == 'laporan') {
    $page_title = 'Laporan Pemakaian';
    $page_desc = 'Menyajikan laporan pemakaian bahan secara lengkap';
} elseif ($page == 'stok') {
    $page_title = 'Stok BHP';
    $page_desc = 'Pantau ketersediaan bahan habis pakai';
} elseif ($page == 'pengadaan') {
    $page_title = 'Permintaan Pengadaan';
    $page_desc = 'Kelola dan setujui permintaan pengadaan bahan';
} elseif ($page == 'profil') {
    $page_title = 'Profil Saya';
    $page_desc = 'Kelola informasi pribadi dan credential akun Anda';
} elseif ($page == 'pengaturan') {
    $page_title = 'Pengaturan Akun';
    $page_desc = 'Konfigurasi preferensi akses dan keamanan sistem';
} elseif ($page == 'log') {
    $page_title = 'Log Aktivitas';
    $page_desc = 'Pantau seluruh aktivitas pengguna di sistem';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BHP Poli Gigi - Kepala Klinik - <?php echo $page_title; ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">

  <script src="https://cdn.tailwindcss.com"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700&family=Bricolage+Grotesque:wght@600;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script src="/BHP-Poli-Gigi/assets/js/tailwind-config.js"></script>
  <style type="text/tailwindcss">
    @layer base {
      body { @apply bg-slate-50 text-slate-800 antialiased m-0 p-0; }
    }
  </style>
  <link rel="stylesheet" href="/BHP-Poli-Gigi/assets/css/style.css">
</head>

<body class="flex flex-col h-screen overflow-hidden bg-slate-50 font-sans text-slate-800">

  <!-- LOADER -->
  <?php include __DIR__ . '/../components/loader.php'; ?>

  <!-- ======================== HEADER ======================== -->
  <?php include __DIR__ . '/../components/header.php'; ?>
  <!-- ======================== END HEADER ======================== -->

  <!-- Container for Sidebar + Main Content -->
  <div class="flex flex-1 overflow-hidden relative w-full h-full">

    <!-- ======================== SIDEBAR ======================== -->
    <?php include __DIR__ . '/components/sidebar.php'; ?>
    <!-- ======================== END SIDEBAR ======================== -->

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto w-full relative flex flex-col justify-between">

      <div class="flex-1 flex flex-col">
        <?php
        $content_file = "contents/$page.php";
        if (file_exists($content_file)) {
            include $content_file;
        } else {
            echo "<div class='flex-1 flex items-center justify-center flex-col text-slate-400 py-24'><i class='fas fa-person-digging text-5xl mb-4 opacity-50'></i><h2 class='text-xl font-bold font-display text-slate-600'>Halaman Sedang Dibangun</h2><p class='text-sm mt-1'>Konten untuk halaman ini belum tersedia.</p></div>";
        }
        ?>
      </div>

      <!-- FOOTER -->
      <?php include __DIR__ . '/../components/footer.php'; ?>
    </main>
  </div>

  <script src="/BHP-Poli-Gigi/assets/js/main.js"></script>
</body>

</html>
