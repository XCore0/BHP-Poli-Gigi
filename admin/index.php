<?php
$page = $_GET['page'] ?? 'dashboard';
$page_title = 'Poli Gigi';
$page_desc = '';
$active_page = $page;
$active_submenu = '';

// Role config for Admin
$role_name = 'Administrator';
$role_label = 'Admin';
$role_avatar_initial = 'A';
$role_avatar_bg = 'linear-gradient(135deg, #c7d2fe 0%, #6366f1 100%)';
$role_avatar_color = '#1e1b4b';
$role_label_color = 'text-indigo-600';

if ($page == 'dashboard') {
    $page_title = 'Dashboard Admin';
    $page_desc = 'Kelola seluruh data dan pengguna sistem';
} elseif ($page == 'data_bhp') {
    $active_submenu = 'bhp';
    $page_title = 'Data Bahan Habis Pakai';
    $page_desc = 'Kelola informasi bahan habis pakai secara terstruktur';
} elseif ($page == 'kategori_bhp') {
    $active_submenu = 'bhp';
    $page_title = 'Kategori Bahan Habis Pakai';
    $page_desc = 'Pengaturan kategori bahan habis pakai untuk mempermudah pencarian data';
} elseif ($page == 'pengguna') {
    $page_title = 'Manajemen Pengguna';
    $page_desc = 'Kelola akun dan hak akses pengguna sistem';
} elseif ($page == 'stock') {
    $page_title = 'Stock Masuk';
    $page_desc = 'Catat dan kelola penerimaan stok bahan habis pakai';
} elseif ($page == 'laporan') {
    $page_title = 'Laporan';
    $page_desc = 'Menyajikan laporan pemakaian bahan secara lengkap';
} elseif ($page == 'log') {
    $page_title = 'Log Aktivitas';
    $page_desc = 'Rekam jejak aktivitas seluruh pengguna sistem';
} elseif ($page == 'profil') {
    $page_title = 'Profil Saya';
    $page_desc = 'Kelola informasi pribadi dan credential akun Anda';
} elseif ($page == 'pengaturan') {
    $page_title = 'Pengaturan Akun';
    $page_desc = 'Konfigurasi preferensi akses dan keamanan sistem';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BHP Poli Gigi - Admin - <?php echo $page_title; ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">

  <script src="https://cdn.tailwindcss.com"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700&family=Bricolage+Grotesque:wght@600;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            display: ['Bricolage Grotesque', 'sans-serif'],
            plex: ['IBM Plex Sans', 'sans-serif'],
          },
          colors: {
            brand: { 50: '#ECFDF5', 100: '#D1FAE5', 200: '#A7F3D0', 300: '#6EE7B7', 400: '#34D399', 500: '#10B981', 600: '#059669', 700: '#047857', 800: '#064E3B', 900: '#006B47' },
          },
          boxShadow: {
            card: '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05)',
            glass: '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
          }
        }
      }
    }
  </script>

  <style type="text/tailwindcss">
    @layer base {
      body { @apply bg-slate-50 text-slate-800 antialiased m-0 p-0; }
    }
    @layer utilities {
      .sidebar-gradient { background: #006B47; border-bottom: 1px solid #ffffff; }
      .hide-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
      .hide-scrollbar::-webkit-scrollbar-track { background: transparent; }
      .hide-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    }
    .nav-link { transition: all 0.3s ease; position: relative; overflow: hidden; }
    .sidebar-scroll::-webkit-scrollbar { width: 4px; }
    .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
    .chevron-icon { transition: transform 0.25s ease; }
    .chevron-icon.open { transform: rotate(180deg); }
    .submenu { overflow: hidden; max-height: 0; transition: max-height 0.3s ease; }
    .submenu.open { max-height: 200px; }
    ::view-transition-old(main-content), ::view-transition-new(main-content) {
      animation-duration: 0.5s;
      animation-timing-function: cubic-bezier(0.4, 0.0, 0.2, 1);
      animation-fill-mode: both;
    }
    ::view-transition-old(main-content) { animation-name: fadeOutUp; }
    ::view-transition-new(main-content) { animation-name: fadeInUp; }
    @keyframes fadeOutUp { from { opacity: 1; transform: translateY(0) scale(1); } to { opacity: 0; transform: translateY(-10px) scale(0.98); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    main { view-transition-name: main-content; }
    @keyframes fill-progress { 0% { width: 0%; opacity: 0.8; } 100% { width: 100%; opacity: 1; } }
  </style>
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

  <script>
    function toggleMobileMenu() {
      const sidebar = document.getElementById("sidebarMenu");
      const overlay = document.getElementById("sidebarOverlay");
      if(sidebar && overlay) {
        if(sidebar.classList.contains("-translate-x-full")) {
          sidebar.classList.remove("-translate-x-full");
          overlay.classList.remove("opacity-0", "pointer-events-none");
          overlay.classList.add("opacity-100");
        } else {
          sidebar.classList.add("-translate-x-full");
          overlay.classList.remove("opacity-100");
          overlay.classList.add("opacity-0", "pointer-events-none");
        }
      }
    }
    function closeMobileMenu() {
      const sidebar = document.getElementById("sidebarMenu");
      const overlay = document.getElementById("sidebarOverlay");
      if (sidebar && !sidebar.classList.contains("-translate-x-full")) {
        sidebar.classList.add("-translate-x-full");
        if(overlay) { overlay.classList.remove("opacity-100"); overlay.classList.add("opacity-0", "pointer-events-none"); }
      }
    }
    function toggleSubmenu(id) {
      const submenu = document.getElementById(id + "-submenu");
      const chevron = document.getElementById(id + "-chevron");
      if (submenu && chevron) { submenu.classList.toggle("open"); chevron.classList.toggle("open"); }
    }
    function toggleDropdown(e) {
      if (e) e.stopPropagation();
      const dropdown = document.getElementById("user-dropdown");
      const chevron = document.getElementById("user-chevron");
      if (dropdown) {
        dropdown.classList.toggle("hidden");
        if (chevron) { chevron.style.transform = dropdown.classList.contains("hidden") ? "rotate(0deg)" : "rotate(180deg)"; }
      }
    }
    document.addEventListener("click", function (e) {
      const dropdown = document.getElementById("user-dropdown");
      const btn = e.target.closest("button[onclick='toggleDropdown(event)']");
      if (!btn && dropdown && !dropdown.classList.contains("hidden")) {
        dropdown.classList.add("hidden");
        const chevron = document.getElementById("user-chevron");
        if (chevron) chevron.style.transform = "rotate(0deg)";
      }
    });
    document.addEventListener('click', async (e) => {
      const link = e.target.closest('a');
      if (!link || !link.href || !link.href.includes(window.location.origin) || link.target === '_blank' || link.hasAttribute('download')) return;
      if (typeof closeMobileMenu === 'function') closeMobileMenu();
      e.preventDefault();
      const url = link.href;
      history.pushState(null, '', url);
      await fetchAndRenderPage(url);
    });
    window.addEventListener('popstate', () => { fetchAndRenderPage(location.href); });
    async function fetchAndRenderPage(url) {
      try {
        const resp = await fetch(url);
        const text = await resp.text();
        const parser = new DOMParser();
        const newDoc = parser.parseFromString(text, 'text/html');
        if (document.startViewTransition) { document.startViewTransition(() => updatePageContent(newDoc)); }
        else { updatePageContent(newDoc); }
      } catch(err) { window.location = url; }
    }
    function updatePageContent(newDoc) {
      document.title = newDoc.title;
      const currentH = document.querySelector('header'); const newH = newDoc.querySelector('header');
      if (currentH && newH) currentH.innerHTML = newH.innerHTML;
      const currentS = document.querySelector('aside'); const newS = newDoc.querySelector('aside');
      if (currentS && newS) currentS.innerHTML = newS.innerHTML;
      const currentM = document.querySelector('main'); const newM = newDoc.querySelector('main');
      if (currentM && newM) {
        currentM.innerHTML = newM.innerHTML;
        Array.from(currentM.querySelectorAll("script")).forEach(oldScript => {
          const newScript = document.createElement("script");
          Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
          newScript.appendChild(document.createTextNode(oldScript.innerHTML));
          oldScript.parentNode.replaceChild(newScript, oldScript);
        });
      }
    }
  </script>
</body>

</html>
