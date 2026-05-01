  <!-- ======================== HEADER ======================== -->
  <header class="w-full h-20 flex items-stretch shadow-sm z-30 shrink-0"
    style="background: rgba(255,255,255,0.80); backdrop-filter: blur(8px);">

    <!-- LEFT: Logo area (matches sidebar width) -->
    <div class="hidden lg:flex items-center gap-4 px-8 shrink-0 sidebar-gradient" style="width: 279px;">

      <!-- Icon badge -->
      <div class="flex items-center justify-center w-10 h-10 rounded-xl shadow-lg shrink-0"
        style="background: linear-gradient(135deg, #34D399 0%, #0D9488 100%);">
        <i class="fas fa-tooth text-white text-xl"></i>
      </div>

      <!-- Clinic name -->
      <div class="flex flex-col gap-0">
        <span class="text-white font-display font-bold text-xl leading-[25px] tracking-[-0.2px]">POLI GIGI</span>
        <span class="font-display font-bold text-[10px] leading-5 tracking-[0.5px]" style="color: #6EE7B7;">KLINIK PRATAMA</span>
      </div>
    </div>

    <!-- CENTER: Page title + description -->
    <div class="flex items-center flex-1 px-6 lg:px-8 bg-white/80 min-w-0 gap-4">
      <button onclick="toggleMobileMenu()"
        class="lg:hidden text-slate-500 hover:text-brand-600 transition-colors p-2 -ml-2 rounded-lg hover:bg-slate-100 text-xl">
        <i class="fa-solid fa-bars"></i>
      </button>

      <!-- Gradient left border -->
      <div class="w-[5px] h-12 rounded-full shrink-0 mr-4 hidden sm:block"
        style="background: linear-gradient(180deg, #006B47 0%, #07FFA7 100%);"></div>

      <!-- Title & description -->
      <div class="flex flex-col gap-0.5 min-w-0">
        <h1 class="font-plex font-bold text-[18px] leading-[22.5px] text-[#101828] truncate"><?php echo htmlspecialchars($page_title ?? 'Poli Gigi'); ?></h1>
        <p class="font-plex font-medium text-sm leading-5 text-[#99A1AF] truncate hidden sm:block"><?php echo htmlspecialchars($page_desc ?? ''); ?></p>
      </div>
    </div>

    <!-- RIGHT: Notifications + User dropdown -->
    <div class="flex items-center gap-6 px-4 sm:px-8 bg-white/80 shrink-0">

      <!-- Bell notification -->
      <div class="relative flex items-center justify-center cursor-pointer hover:opacity-80 transition-opacity">
        <i class="far fa-bell text-[#9CA3AF] text-xl"></i>
        <!-- Red dot -->
        <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 w-2.5 h-2.5 rounded-full bg-red-500"></span>
      </div>

      <!-- Divider -->
      <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>

      <!-- User dropdown -->
      <button class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity"
        onclick="toggleDropdown(event)">
        <!-- Avatar -->
        <div
          class="w-9 h-9 rounded-full flex items-center justify-center font-plex font-semibold text-sm text-white shrink-0"
          style="background: linear-gradient(135deg, #fde68a 0%, #f59e0b 100%); color: #78350f;">
          K
        </div>

        <!-- Name & role -->
        <div class="flex flex-col items-start gap-0 text-left hidden sm:flex">
          <span class="font-plex font-semibold text-sm text-[#101828] leading-5 whitespace-nowrap">Kepala Klinik</span>
          <span class="font-plex font-medium text-xs leading-4 text-amber-600 whitespace-nowrap">Kepala Klinik</span>
        </div>

        <!-- Chevron -->
        <i id="user-chevron"
          class="fas fa-chevron-down text-[#9CA3AF] text-xs transition-transform duration-200 hidden sm:block"></i>
      </button>

      <!-- Dropdown menu -->
      <div id="user-dropdown"
        class="hidden absolute right-6 top-[72px] bg-white rounded-xl shadow-xl border border-gray-100 py-2 min-w-[180px] z-50">
        <a href="index.php?page=profil"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-plex"><i
            class="fas fa-user text-slate-400 w-4"></i> Profil Saya</a>
        <a href="index.php?page=pengaturan"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-plex"><i
            class="fas fa-cog text-slate-400 w-4"></i> Pengaturan</a>
        <div class="my-1 border-t border-gray-100"></div>
        <a href="/BHP-Poli-Gigi/logout.php"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors font-plex"><i
            class="fas fa-sign-out-alt text-red-400 w-4"></i> Keluar</a>
    </div>
  </header>
  <!-- ======================== END HEADER ======================== -->
