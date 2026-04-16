<?php
$active_page = $active_page ?? '';
$active_submenu = $active_submenu ?? '';
?>
    <!-- ======================== SIDEBAR ======================== -->
    <aside
      class="hidden lg:flex relative w-[279px] h-full flex-col overflow-hidden shadow-2xl flex-shrink-0 sidebar-gradient z-20">
      <!-- Scrollable content area -->
      <div class="flex flex-col px-4 py-6 gap-0 flex-1 overflow-y-auto sidebar-scroll relative z-10">

        <!-- ---- Dashboard ---- -->
        <?php $is_dashboard = ($active_page == 'dashboard'); ?>
        <div class="mb-1">
          <a href="index.php?page=dashboard"
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group <?php echo $is_dashboard ? 'bg-white/10 border border-white/20 shadow-sm' : 'border border-transparent hover:bg-white/5'; ?>">
            <div class="w-5 flex justify-center flex-shrink-0">
              <i class="fas fa-home text-[17.6px] transition-colors <?php echo $is_dashboard ? 'text-white' : 'text-white/70 group-hover:text-white/90'; ?>"></i>
            </div>
            <span
              class="font-plex text-base leading-6 transition-colors <?php echo $is_dashboard ? 'text-white font-medium' : 'text-white/70 font-normal group-hover:text-white/90'; ?>">Dashboard</span>
          </a>
        </div>

        <!-- ---- Section: Manajemen ---- -->
        <div class="py-4">
          <div class="px-4">
            <span class="text-white/70 font-plex text-[11px] font-bold tracking-[1.2px] uppercase">Manajemen</span>
          </div>
        </div>

        <!-- ---- Manajemen BHP (expandable) ---- -->
        <?php $is_bhp_open = ($active_submenu == 'bhp'); ?>
        <div class="mb-1">
          <button onclick="toggleSubmenu('bhp')"
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl w-full text-left transition-all duration-200 group <?php echo ($is_bhp_open && $active_page == '') ? 'bg-white/10 border border-white/20 shadow-sm' : 'border border-transparent hover:bg-white/5'; ?>">
            <div class="w-5 flex justify-center flex-shrink-0">
              <i class="fa-solid fa-boxes-stacked text-[18px] transition-colors <?php echo $is_bhp_open ? 'text-white' : 'text-white/80 group-hover:text-white'; ?>"></i>
            </div>
            <span
              class="flex-1 font-plex text-base font-medium leading-6 transition-colors <?php echo $is_bhp_open ? 'text-white' : 'text-white/90 group-hover:text-white'; ?>">Manajemen
              BHP</span>
            <i id="bhp-chevron" class="fa-solid fa-chevron-down text-[13px] chevron-icon transition-transform <?php echo $is_bhp_open ? 'open text-white' : 'text-white/80'; ?>"></i>
          </button>

          <!-- Submenu -->
          <div id="bhp-submenu" class="submenu <?php echo $is_bhp_open ? 'open' : ''; ?>">
            <div class="ml-[25px] pl-4 py-1 mt-1 mb-2 flex flex-col gap-1.5 border-l-[1.5px] border-white/20">
              <a href="index.php?page=data_bhp"
                class="block px-3 py-2.5 rounded-xl font-plex text-[15px] leading-5 transition-all duration-200 <?php echo ($active_page == 'data_bhp') ? 'bg-white/10 border border-white/20 shadow-sm text-white font-medium' : 'border border-transparent hover:bg-white/5 text-white/70 hover:text-white font-normal'; ?>">
                Data BHP
              </a>
              <a href="index.php?page=kategori_bhp"
                class="block px-3 py-2.5 rounded-xl font-plex text-[15px] leading-5 transition-all duration-200 <?php echo ($active_page == 'kategori_bhp') ? 'bg-white/10 border border-white/20 shadow-sm text-white font-medium' : 'border border-transparent hover:bg-white/5 text-white/70 hover:text-white font-normal'; ?>">
                Kategori BHP
              </a>
            </div>
          </div>
        </div>

        <!-- ---- Stock Masuk ---- -->
        <?php $is_stock = ($active_page == 'stock'); ?>
        <div class="mb-1">
          <a href="index.php?page=stock"
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group <?php echo $is_stock ? 'bg-white/10 border border-white/20 shadow-sm' : 'border border-transparent hover:bg-white/5'; ?>">
            <div class="w-[30px] h-8 -ml-1 flex justify-center items-center rounded-xl flex-shrink-0">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="transition-opacity <?php echo $is_stock ? 'opacity-100' : 'opacity-70 group-hover:opacity-90'; ?>">
                <g clip-path="url(#clip_stock)">
                  <path
                    d="M7.99855 14.6639C11.6798 14.6639 14.664 11.6797 14.664 7.99847C14.664 4.31723 11.6798 1.33301 7.99855 1.33301C4.31732 1.33301 1.33309 4.31723 1.33309 7.99847C1.33309 11.6797 4.31732 14.6639 7.99855 14.6639Z"
                    stroke="white" stroke-width="1.33309" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M7.99855 5.33228V10.6646" stroke="white" stroke-width="1.33309" stroke-linecap="round"
                    stroke-linejoin="round" />
                  <path d="M5.33237 7.99854L7.99855 10.6647L10.6647 7.99854" stroke="white" stroke-width="1.33309"
                    stroke-linecap="round" stroke-linejoin="round" />
                </g>
              </svg>
            </div>
            <span
              class="font-plex text-base leading-6 transition-colors <?php echo $is_stock ? 'text-white font-medium' : 'text-white/70 font-normal group-hover:text-white/90'; ?>">Stock
              Masuk</span>
          </a>
        </div>

        <!-- ---- Catat Pemakaian ---- -->
        <?php $is_catat = ($active_page == 'catat'); ?>
        <div class="mb-1">
          <a href="index.php?page=catat"
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group <?php echo $is_catat ? 'bg-white/10 border border-white/20 shadow-sm' : 'border border-transparent hover:bg-white/5'; ?>">
            <div class="w-5 flex justify-center flex-shrink-0">
              <i class="fas fa-pencil-alt text-[17.6px] transition-colors <?php echo $is_catat ? 'text-white' : 'text-white/70 group-hover:text-white/90'; ?>"></i>
            </div>
            <span
              class="font-plex text-base leading-6 transition-colors <?php echo $is_catat ? 'text-white font-medium' : 'text-white/70 font-normal group-hover:text-white/90'; ?>">Catat
              Pemakaian</span>
          </a>
        </div>

        <!-- ---- Laporan Pemakaian ---- -->
        <?php $is_laporan = ($active_page == 'laporan'); ?>
        <div class="mb-1">
          <a href="index.php?page=laporan"
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group <?php echo $is_laporan ? 'bg-white/10 border border-white/20 shadow-sm' : 'border border-transparent hover:bg-white/5'; ?>">
            <div class="w-5 flex justify-center flex-shrink-0">
              <i class="fas fa-chart-bar text-[17.6px] transition-colors <?php echo $is_laporan ? 'text-white' : 'text-white/70 group-hover:text-white/90'; ?>"></i>
            </div>
            <span
              class="font-plex text-base leading-6 transition-colors <?php echo $is_laporan ? 'text-white font-medium' : 'text-white/70 font-normal group-hover:text-white/90'; ?>">Laporan
              Pemakaian</span>
          </a>
        </div>

      </div>
      <!-- End scrollable content -->

      <!-- ---- Wave decoration (bottom) ---- -->
      <div class="absolute bottom-0 left-0 right-0 pointer-events-none select-none z-0">
        <svg width="279" height="274" viewBox="0 0 278 274" fill="none" xmlns="http://www.w3.org/2000/svg"
          class="w-full" preserveAspectRatio="xMidYMax meet">
          <path
            d="M0 20.3058C46.3345 -6.76859 92.669 -6.76859 139.004 20.3058C185.338 47.3802 231.673 47.3802 278.007 20.3058V273H0V20.3058Z"
            fill="white" fill-opacity="0.05" />
          <path
            d="M0 84.8972C45.6356 57.1771 94.719 59.5854 139.004 84.8972C184.929 111.321 231.24 111.691 278.007 84.8972V272.798H0V84.8972Z"
            fill="white" fill-opacity="0.07" />
          <path
            d="M0 147.214C48.1548 121.098 96.806 122.098 139.004 147.214C182.62 174.911 229.303 174.626 278.007 147.214V273.098H0V147.214Z"
            fill="white" fill-opacity="0.08" />
        </svg>
      </div>
    </aside>
    <!-- ======================== END SIDEBAR ======================== -->
