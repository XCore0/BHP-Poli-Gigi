<?php
$active_page = $active_page ?? '';
$active_submenu = $active_submenu ?? '';
?>
    <!-- ======================== SIDEBAR ======================== -->
    <div id="sidebarOverlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[40] transition-opacity duration-300 opacity-0 pointer-events-none lg:hidden"></div>
    <aside id="sidebarMenu"
      class="fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 z-[50] lg:static lg:translate-x-0 w-[279px] h-full flex flex-col overflow-hidden shadow-2xl flex-shrink-0 sidebar-gradient lg:z-20">
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
            <span class="font-plex text-base leading-6 transition-colors <?php echo $is_dashboard ? 'text-white font-medium' : 'text-white/70 font-normal group-hover:text-white/90'; ?>">Dashboard</span>
          </a>
        </div>

        <!-- ---- Laporan ---- -->
        <?php $is_laporan = ($active_page == 'laporan'); ?>
        <div class="mb-1">
          <a href="index.php?page=laporan"
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group <?php echo $is_laporan ? 'bg-white/10 border border-white/20 shadow-sm' : 'border border-transparent hover:bg-white/5'; ?>">
            <div class="w-5 flex justify-center flex-shrink-0">
              <i class="fas fa-chart-bar text-[17.6px] transition-colors <?php echo $is_laporan ? 'text-white' : 'text-white/70 group-hover:text-white/90'; ?>"></i>
            </div>
            <span class="font-plex text-base leading-6 transition-colors <?php echo $is_laporan ? 'text-white font-medium' : 'text-white/70 font-normal group-hover:text-white/90'; ?>">Laporan</span>
          </a>
        </div>

        <!-- ---- Log Aktivitas ---- -->
        <?php $is_log = ($active_page == 'log'); ?>
        <div class="mb-1">
          <a href="index.php?page=log"
            class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group <?php echo $is_log ? 'bg-white/10 border border-white/20 shadow-sm' : 'border border-transparent hover:bg-white/5'; ?>">
            <div class="w-5 flex justify-center flex-shrink-0">
              <i class="fas fa-clipboard-list text-[17.6px] transition-colors <?php echo $is_log ? 'text-white' : 'text-white/70 group-hover:text-white/90'; ?>"></i>
            </div>
            <span class="font-plex text-base leading-6 transition-colors <?php echo $is_log ? 'text-white font-medium' : 'text-white/70 font-normal group-hover:text-white/90'; ?>">Log Aktivitas</span>
          </a>
        </div>

      </div>
      <!-- End scrollable content -->

      <!-- ---- Wave decoration (bottom) ---- -->
      <div class="absolute bottom-0 left-0 right-0 pointer-events-none select-none z-0">
        <svg width="279" height="274" viewBox="0 0 278 274" fill="none" xmlns="http://www.w3.org/2000/svg"
          class="w-full" preserveAspectRatio="xMidYMax meet">
          <path d="M0 20.3058C46.3345 -6.76859 92.669 -6.76859 139.004 20.3058C185.338 47.3802 231.673 47.3802 278.007 20.3058V273H0V20.3058Z" fill="white" fill-opacity="0.05" />
          <path d="M0 84.8972C45.6356 57.1771 94.719 59.5854 139.004 84.8972C184.929 111.321 231.24 111.691 278.007 84.8972V272.798H0V84.8972Z" fill="white" fill-opacity="0.07" />
          <path d="M0 147.214C48.1548 121.098 96.806 122.098 139.004 147.214C182.62 174.911 229.303 174.626 278.007 147.214V273.098H0V147.214Z" fill="white" fill-opacity="0.08" />
        </svg>
      </div>
    </aside>
    <!-- ======================== END SIDEBAR ======================== -->
