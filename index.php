<?php
$page = $_GET['page'] ?? 'dashboard';
$page_title = 'Poli Gigi';
$page_desc = '';
$active_page = $page;
$active_submenu = '';

if ($page == 'data_bhp') {
    $active_submenu = 'bhp';
    $page_title = 'Data Bahan Habis Pakai';
    $page_desc = 'Kelola informasi bahan habis pakai secara terstruktur';
} elseif ($page == 'kategori_bhp') {
    $active_submenu = 'bhp';
    $page_title = 'Kategori Bahan Habis Pakai';
    $page_desc = 'Pengaturan kategori bahan habis pakai untuk mempermudah pencarian data';
} elseif ($page == 'catat') {
    $page_title = 'Catat Pemakaian';
    $page_desc = 'Catat penggunaan Bahan Habis Pakai (BHP) untuk setiap tindakan medis';
} elseif ($page == 'laporan') {
    $page_title = 'Laporan Pemakaian';
    $page_desc = 'Menyajikan Laporan pemakaian bahan secara lengkap';
} elseif ($page == 'dashboard') {
    $page_title = 'Dashboard';
    $page_desc = 'Ringkasan informasi dan aktivitas Poliklinik Gigi';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BHP Poli Gigi - <?php echo $page_title; ?></title>

  <!-- Preconnect untuk mempercepat loading -->
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
      body {
        @apply bg-slate-50 text-slate-800 antialiased m-0 p-0;
      }
    }

    @layer utilities {
      .sidebar-gradient {
        background: #006B47;
        border-bottom: 1px solid #ffffff;
      }
      .hero-gradient {
        background: linear-gradient(135deg, #006B47 0%, #059669 50%, #10b981 100%);
      }
      .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }
      .hide-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
      }
      .hide-scrollbar::-webkit-scrollbar-track {
        background: transparent; 
      }
      .hide-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 10px;
      }
      .hide-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
      }
    }

    .nav-link {
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .nav-link::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
      background: #34D399;
      transform: scaleY(0);
      transform-origin: left;
      transition: transform 0.3s ease;
      border-top-right-radius: 4px;
      border-bottom-right-radius: 4px;
    }
    .nav-link:hover, .nav-link.active {
      background: rgba(255, 255, 255, 0.1);
      color: white;
    }
    .nav-link:hover::before, .nav-link.active::before {
      transform: scaleY(1);
    }
    /* Custom scrollbar for sidebar */
    .sidebar-scroll::-webkit-scrollbar {
      width: 4px;
    }
    .sidebar-scroll::-webkit-scrollbar-track {
      background: transparent;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 4px;
    }

    .chevron-icon {
      transition: transform 0.25s ease;
    }
    .chevron-icon.open {
      transform: rotate(180deg);
    }

    .submenu {
      overflow: hidden;
      max-height: 0;
      transition: max-height 0.3s ease;
    }
    .submenu.open {
      max-height: 200px;
    }

    /* View Transitions for SPA */
    ::view-transition-old(main-content),
    ::view-transition-new(main-content) {
      animation-duration: 0.5s;
      animation-timing-function: cubic-bezier(0.4, 0.0, 0.2, 1);
      animation-fill-mode: both;
    }

    ::view-transition-old(main-content) {
      animation-name: fadeOutUp;
    }

    ::view-transition-new(main-content) {
      animation-name: fadeInUp;
    }

    @keyframes fadeOutUp {
      from { opacity: 1; transform: translateY(0) scale(1); }
      to { opacity: 0; transform: translateY(-10px) scale(0.98); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(10px) scale(0.98); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    main {
      view-transition-name: main-content;
    }

    /* Premium Loader Custom Styling */
    .premium-loader { perspective: 1000px; }
    .loader-tooth-wrapper {
      position: relative;
      animation: float-tooth 4s ease-in-out infinite;
      transform-style: preserve-3d;
    }
    .scanner-line {
      position: absolute;
      top: 0; left: -10%; right: -10%; height: 2px;
      background: linear-gradient(90deg, transparent, #10B981, transparent);
      box-shadow: 0 0 10px #34D399, 0 4px 20px #10B981;
      animation: scan-tooth 2.5s ease-in-out infinite alternate;
      z-index: 10;
    }
    .tooth-glow { filter: drop-shadow(0 0 12px rgba(16, 185, 129, 0.4)); }
    @keyframes float-tooth {
      0%, 100% { transform: translateY(0) rotateX(5deg); }
      50% { transform: translateY(-12px) rotateX(-5deg); }
    }
    @keyframes scan-tooth {
      0% { top: 10%; opacity: 0; }
      15% { opacity: 1; }
      85% { opacity: 1; }
      100% { top: 90%; opacity: 0; }
    }
    .pulse-ring {
      position: absolute; inset: -20px;
      border: 1.5px solid rgba(16, 185, 129, 0.3);
      border-radius: 50%;
      animation: ripple-ring 2.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    .pulse-ring:nth-child(2) { animation-delay: 1.25s; }
    @keyframes ripple-ring {
      0% { transform: scale(0.6); opacity: 1; }
      100% { transform: scale(1.6); opacity: 0; }
    }
    .text-gradient {
      background: linear-gradient(to right, #006B47, #10B981);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .bg-noise {
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.03'/%3E%3C/svg%3E");
    }
    @keyframes fill-progress {
      0% { width: 0%; opacity: 0.8; }
      100% { width: 100%; opacity: 1; }
    }
  </style>
</head>

<body class="flex flex-col h-screen overflow-hidden bg-slate-50 font-sans text-slate-800">

  <!-- INITIAL LOADER -->
  <div id="initial-loader" class="fixed inset-0 z-[9999] premium-loader flex flex-col items-center justify-center transition-all duration-700 ease-out">
    <!-- Blurred backdrop with ambient glow and noise -->
    <div class="absolute inset-0 bg-slate-50/70 backdrop-blur-xl"></div>
    <div class="absolute inset-0 bg-noise pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-emerald-400/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="relative z-10 flex flex-col items-center">
      <!-- 3D Floating Tooth with Diamond Glassmorphism -->
      <div class="loader-tooth-wrapper w-32 h-32 flex items-center justify-center mb-10 relative">
        <div class="pulse-ring"></div>
        <div class="pulse-ring"></div>
        
        <!-- Elegant Glass Container (Tilted) -->
        <div class="absolute inset-0 rounded-[2rem] bg-white/40 border border-white/60 shadow-[0_8px_32px_rgba(0,107,71,0.08)] backdrop-blur-md overflow-hidden rotate-45 flex items-center justify-center transition-transform"></div>
        
        <!-- The Tooth with Scanner -->
        <div class="relative z-20 tooth-glow flex flex-col items-center justify-center h-full w-full">
           <i class="fa-solid fa-tooth text-[3.5rem] text-[#006B47] drop-shadow-sm"></i>
           <div class="scanner-line"></div>
        </div>
      </div>

      <!-- Sophisticated Typography & Progress -->
      <div class="flex flex-col items-center text-center">
         <!-- <h3 class="font-display font-extrabold text-3xl tracking-tight text-gradient mb-1">Poli Gigi</h3>
         <h5 class="font-plex font-bold text-[11px] text-brand-700/60 uppercase tracking-[0.25em] mb-7">Klinik Pratama</h5> -->
         
         <!-- Progress Bar -->
         <div class="w-48 h-[4px] mt-4 bg-slate-200/80 rounded-full overflow-hidden relative shadow-inner">
            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-[#34D399] to-[#006B47] rounded-full w-0" style="animation: fill-progress 0.8s ease-out forwards;"></div>
         </div>
      </div>
    </div>
  </div>
  <!-- END INITIAL LOADER -->

  <!-- ======================== HEADER ======================== -->
  <?php include 'components/header.php'; ?>
  <!-- ======================== END HEADER ======================== -->

  <!-- Container for Sidebar + Main Content -->
  <div class="flex flex-1 overflow-hidden relative w-full h-full">

    <!-- ======================== SIDEBAR ======================== -->
    <?php include 'components/sidebar.php'; ?>
    <!-- ======================== END SIDEBAR ======================== -->

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto w-full relative flex flex-col justify-between">

      <div class="flex-1 flex flex-col">
        <?php
        $content_file = "contents/$page.php";
        if (file_exists($content_file)) {
            include $content_file;
        } else {
            echo "<div class='flex-1 flex items-center justify-center flex-col text-slate-400'><i class='fas fa-person-digging text-5xl mb-4 opacity-50'></i><h2 class='text-xl font-bold font-display text-slate-600'>Halaman Sedang Dibangun</h2><p class='text-sm mt-1'>Konten untuk halaman ini belum tersedia.</p></div>";
        }
        ?>
      </div>
      
      <!-- FOOTER -->
      <?php include 'components/footer.php'; ?>
    </main>
  </div> <!-- Close Container for Sidebar + Main Content -->

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
         if(overlay) {
           overlay.classList.remove("opacity-100");
           overlay.classList.add("opacity-0", "pointer-events-none");
         }
      }
    }

    function toggleSubmenu(id) {
      const submenu = document.getElementById(id + "-submenu");
      const chevron = document.getElementById(id + "-chevron");

      if (submenu && chevron) {
        submenu.classList.toggle("open");
        chevron.classList.toggle("open");
      }
    }

    function toggleDropdown(e) {
      if (e) e.stopPropagation();
      const dropdown = document.getElementById("user-dropdown");
      const chevron = document.getElementById("user-chevron");

      if (dropdown) {
        dropdown.classList.toggle("hidden");
        if (chevron) {
          chevron.style.transform = dropdown.classList.contains("hidden")
            ? "rotate(0deg)"
            : "rotate(180deg)";
        }
      }
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
      const dropdown = document.getElementById("user-dropdown");
      const btn = e.target.closest("button[onclick='toggleDropdown(event)']");

      if (!btn && dropdown && !dropdown.classList.contains("hidden")) {
        dropdown.classList.add("hidden");
        const chevron = document.getElementById("user-chevron");
        if (chevron) chevron.style.transform = "rotate(0deg)";
      }
    });

    // --- Single Page Application (SPA) Logic ---
    let loaderHidden = false;
    const hideLoader = () => {
      if (loaderHidden) return;
      loaderHidden = true;
      const loader = document.getElementById('initial-loader');
      if (loader) {
        loader.style.opacity = '0';
        setTimeout(() => {
          loader.style.display = 'none';
        }, 300); // Matches duration-300
      }
    };

    // Hide loader when Window is fully loaded (Tailwind ready)
    window.addEventListener('load', hideLoader);
    
    // Fallback: forcefully hide loader after 800ms so user isn't kept waiting
    setTimeout(hideLoader, 800);

    document.addEventListener('click', async (e) => {
      const link = e.target.closest('a');
      // Only intercept internal links
      if (!link || !link.href || !link.href.includes(window.location.origin) || link.target === '_blank' || link.hasAttribute('download')) return;
      
      if (typeof closeMobileMenu === 'function') closeMobileMenu();
      
      e.preventDefault();
      const url = link.href;
      
      // Update history
      history.pushState(null, '', url);
      
      await fetchAndRenderPage(url);
    });

    window.addEventListener('popstate', () => {
      fetchAndRenderPage(location.href);
    });

    async function fetchAndRenderPage(url) {
      try {
        const resp = await fetch(url);
        const text = await resp.text();
        const parser = new DOMParser();
        const newDoc = parser.parseFromString(text, 'text/html');
        
        // Use View Transitions API if supported for "Smart Animate" effect
        if (document.startViewTransition) {
          document.startViewTransition(() => updatePageContent(newDoc));
        } else {
          updatePageContent(newDoc); // Fallback
        }
      } catch(err) {
        window.location = url; // Fallback to normal navigation on error
      }
    }

    function updatePageContent(newDoc) {
      // 1. Update Title
      document.title = newDoc.title;
      
      // 2. Update Header
      const currentHeaderWrapper = document.querySelector('header');
      const newHeaderWrapper = newDoc.querySelector('header');
      if (currentHeaderWrapper && newHeaderWrapper) {
          currentHeaderWrapper.innerHTML = newHeaderWrapper.innerHTML;
      }
      
      // 3. Update Sidebar Active States
      const currentSidebar = document.querySelector('aside');
      const newSidebar = newDoc.querySelector('aside');
      if (currentSidebar && newSidebar) {
          currentSidebar.innerHTML = newSidebar.innerHTML;
      }
      
      // 4. Update Main Content (This triggers the View Transition)
      const currentMain = document.querySelector('main');
      const newMain = newDoc.querySelector('main');
      if (currentMain && newMain) {
         currentMain.innerHTML = newMain.innerHTML;
         
         // Re-run any scripts in the new main content if needed.
         // (Not strictly necessary if contents are simply static HTML PHP includes
         // but good practice if inline scripts ever get added later).
         Array.from(currentMain.querySelectorAll("script")).forEach(oldScript => {
            const newScript = document.createElement("script");
            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
         });
      }
    }
    // --- End SPA Logic ---
  </script>
</body>

</html>
