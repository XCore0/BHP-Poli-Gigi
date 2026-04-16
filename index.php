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
  </style>
</head>

<body class="flex flex-col h-screen overflow-hidden bg-slate-50 font-sans text-slate-800">

  <!-- ======================== HEADER ======================== -->
  <?php include 'components/header.php'; ?>
  <!-- ======================== END HEADER ======================== -->

  <!-- Container for Sidebar + Main Content -->
  <div class="flex flex-1 overflow-hidden relative w-full h-full">

    <!-- ======================== SIDEBAR ======================== -->
    <?php include 'components/sidebar.php'; ?>
    <!-- ======================== END SIDEBAR ======================== -->

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto w-full relative">

      <?php
      $content_file = "contents/$page.php";
      if (file_exists($content_file)) {
          include $content_file;
      } else {
          echo "<div class='h-[500px] flex items-center justify-center flex-col text-slate-400'><i class='fas fa-person-digging text-5xl mb-4 opacity-50'></i><h2 class='text-xl font-bold font-display text-slate-600'>Halaman Sedang Dibangun</h2><p class='text-sm mt-1'>Konten untuk halaman ini belum tersedia.</p></div>";
      }
      ?>
    </main>
  </div> <!-- Close Container for Sidebar + Main Content -->

  <script>
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
  </script>
</body>

</html>
