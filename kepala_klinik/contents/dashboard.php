      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full">

          <!-- Header Banner -->
          <div
            class="relative w-full rounded-2xl overflow-hidden mb-2"
            style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);"
          >
            <!-- Decorative circle shapes -->
            <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
              <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
              <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
              <div class="absolute -bottom-[400px] left-[50px] md:-bottom-[850px] md:left-[100px] w-[600px] h-[600px] md:w-[1000px] md:h-[1000px] rounded-full bg-white opacity-5"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 px-6 py-6 sm:px-8 sm:py-7">
              <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
                  style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
                  <i class="fas fa-hospital-user text-white text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col gap-1 min-w-0">
                  <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Dashboard Kepala Klinik</h1>
                  <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
                    Pantau seluruh aktivitas klinik, stok BHP, dan laporan pemakaian secara real-time.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #ECFDF5;">
                <i class="fas fa-boxes text-brand-600 text-xl"></i>
              </div>
              <div>
                <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Total BHP</p>
                <p class="font-display font-bold text-2xl text-slate-800 leading-tight">48</p>
                <p class="font-plex text-xs text-slate-400">Jenis BHP terdaftar</p>
              </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #FFF7ED;">
                <i class="fas fa-exclamation-triangle text-amber-500 text-xl"></i>
              </div>
              <div>
                <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Stok Menipis</p>
                <p class="font-display font-bold text-2xl text-amber-600 leading-tight">7</p>
                <p class="font-plex text-xs text-slate-400">Perlu segera dipenuhi</p>
              </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #EFF6FF;">
                <i class="fas fa-chart-line text-blue-500 text-xl"></i>
              </div>
              <div>
                <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Pemakaian Bulan Ini</p>
                <p class="font-display font-bold text-2xl text-slate-800 leading-tight">312</p>
                <p class="font-plex text-xs text-slate-400">Total item terpakai</p>
              </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #FDF2F8;">
                <i class="fas fa-file-signature text-purple-500 text-xl"></i>
              </div>
              <div>
                <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Pengadaan Pending</p>
                <p class="font-display font-bold text-2xl text-slate-800 leading-tight">3</p>
                <p class="font-plex text-xs text-slate-400">Menunggu persetujuan</p>
              </div>
            </div>
          </div>

          <!-- Placeholder Content -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
            <i class="fas fa-chart-pie text-4xl text-slate-200 mb-3"></i>
            <h3 class="font-display font-bold text-slate-500 text-lg">Grafik & Analitik</h3>
            <p class="font-plex text-sm text-slate-400 mt-1">Konten grafik dan analitik akan ditampilkan di sini.</p>
          </div>

        </div>
      </div>
