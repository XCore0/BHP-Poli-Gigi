      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full">

          <!-- Header Banner -->
          <div
            class="relative w-full rounded-2xl overflow-hidden"
            style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);"
          >
            <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
              <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
              <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
              <div class="absolute -bottom-[400px] left-[50px] md:-bottom-[850px] md:left-[100px] w-[600px] h-[600px] md:w-[1000px] md:h-[1000px] rounded-full bg-white opacity-5"></div>
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 py-6 sm:px-8 sm:py-7">
              <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
                  style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
                  <i class="fas fa-users-gear text-white text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col gap-1 min-w-0">
                  <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Pengguna & Log Aktivitas</h1>
                  <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block">
                    Kelola akun pengguna dan pantau seluruh aktivitas sistem
                  </p>
                </div>
              </div>
              <!-- Action button (changes per tab) -->
              <div id="banner-action">
                <button class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors flex-shrink-0">
                  <i class="fas fa-plus"></i> Tambah Pengguna
                </button>
              </div>
            </div>
          </div>

          <!-- Tabs -->
          <div class="flex items-center gap-2">
            <button onclick="switchTab('kelola')" id="tab-kelola"
              class="px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-brand-600 text-white shadow-sm">
              <i class="fas fa-users text-xs"></i> Kelola Pengguna <span class="bg-white/20 text-white text-[11px] px-2 py-0.5 rounded-full font-bold">3</span>
            </button>
            <button onclick="switchTab('log')" id="tab-log"
              class="px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-slate-100 text-slate-600 hover:bg-slate-200">
              <i class="fas fa-heart-pulse text-xs"></i> Log Aktivitas <span class="bg-slate-200 text-slate-500 text-[11px] px-2 py-0.5 rounded-full font-bold">20</span>
            </button>
          </div>

          <!-- ========== TAB 1: KELOLA PENGGUNA ========== -->
          <div id="content-kelola">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #EFF6FF;">
                  <i class="fas fa-users text-blue-600 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Pengguna</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">3</p>
                </div>
              </div>
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #F3E8FF;">
                  <i class="fas fa-shield-halved text-purple-600 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Admin</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">1</p>
                </div>
              </div>
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #ECFDF5;">
                  <i class="fas fa-user-doctor text-brand-600 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Dokter</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">1</p>
                </div>
              </div>
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #FFF1F2;">
                  <i class="fas fa-hospital-user text-rose-500 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Kepala Klinik</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">1</p>
                </div>
              </div>
            </div>

            <!-- User Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full text-left">
                  <thead>
                    <tr class="border-b border-slate-100">
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Email Login</th>
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Role / Hak Akses</th>
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Akun</th>
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-5 py-4 font-plex text-sm font-medium text-slate-700">Admin Utama</td>
                      <td class="px-5 py-4 font-plex text-sm text-slate-500">admin@poligigi.com</td>
                      <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-indigo-50 text-indigo-600">
                          <i class="fas fa-shield-halved text-[10px]"></i> Admin
                        </span>
                      </td>
                      <td class="px-5 py-4">
                        <span class="font-plex text-sm font-semibold text-emerald-500">Aktif</span>
                      </td>
                      <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-2">
                          <button class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 hover:bg-blue-100 transition-colors"><i class="fas fa-pen-to-square text-sm"></i></button>
                          <button class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-red-100 transition-colors"><i class="fas fa-ban text-sm"></i></button>
                        </div>
                      </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-5 py-4 font-plex text-sm font-medium text-slate-700">Drg. Andi S, Sp.KG</td>
                      <td class="px-5 py-4 font-plex text-sm text-slate-500">andi.dokter@poligigi.com</td>
                      <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-emerald-50 text-emerald-600">
                          <i class="fas fa-user-doctor text-[10px]"></i> Dokter
                        </span>
                      </td>
                      <td class="px-5 py-4">
                        <span class="font-plex text-sm font-semibold text-emerald-500">Aktif</span>
                      </td>
                      <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-2">
                          <button class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 hover:bg-blue-100 transition-colors"><i class="fas fa-pen-to-square text-sm"></i></button>
                          <button class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-red-100 transition-colors"><i class="fas fa-ban text-sm"></i></button>
                        </div>
                      </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-5 py-4 font-plex text-sm font-medium text-slate-700">Drg. Rina P.</td>
                      <td class="px-5 py-4 font-plex text-sm text-slate-500">kepala@poligigi.com</td>
                      <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-amber-50 text-amber-600">
                          <i class="fas fa-hospital-user text-[10px]"></i> Kepala Klinik
                        </span>
                      </td>
                      <td class="px-5 py-4">
                        <span class="font-plex text-sm font-semibold text-emerald-500">Aktif</span>
                      </td>
                      <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-2">
                          <button class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 hover:bg-blue-100 transition-colors"><i class="fas fa-pen-to-square text-sm"></i></button>
                          <button class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:bg-red-100 transition-colors"><i class="fas fa-ban text-sm"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- ========== END TAB 1 ========== -->

          <!-- ========== TAB 2: LOG AKTIVITAS ========== -->
          <div id="content-log" class="hidden">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #F3E8FF;">
                  <i class="fas fa-heart-pulse text-purple-600 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Log</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">10</p>
                </div>
              </div>
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #EFF6FF;">
                  <i class="fas fa-clock text-blue-600 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Aktivitas Hari Ini</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">3</p>
                </div>
              </div>
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #ECFDF5;">
                  <i class="fas fa-boxes-packing text-brand-600 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Stok Masuk</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">3</p>
                </div>
              </div>
              <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #ECFEFF;">
                  <i class="fas fa-stethoscope text-cyan-500 text-lg sm:text-xl"></i>
                </div>
                <div class="min-w-0">
                  <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Pemakaian Dokter</p>
                  <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">5</p>
                </div>
              </div>
            </div>

            <!-- Search + Filter -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 mb-6">
              <div class="flex items-center gap-3">
                <div class="relative flex-1">
                  <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                  <input type="text" placeholder="Cari user, aksi, atau detail..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent placeholder:text-slate-400">
                </div>
                <button class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors flex-shrink-0">
                  <i class="fas fa-filter text-sm"></i>
                </button>
              </div>
            </div>

            <!-- Log Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full text-left">
                  <thead>
                    <tr class="border-b border-slate-100">
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                      <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Detail</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-2"><i class="far fa-clock text-slate-300 text-sm"></i><span class="font-plex text-sm text-slate-600">Hari ini, 22:25</span></div>
                      </td>
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0" style="background: linear-gradient(135deg, #c7d2fe 0%, #6366f1 100%); color: #1e1b4b;">A</div>
                          <span class="font-plex text-sm font-medium text-slate-700">Admin</span>
                        </div>
                      </td>
                      <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-blue-50 text-blue-600"><i class="fas fa-sign-in-alt"></i> Login</span>
                      </td>
                      <td class="px-5 py-4 font-plex text-sm text-slate-600">Admin berhasil masuk ke sistem</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-2"><i class="far fa-clock text-slate-300 text-sm"></i><span class="font-plex text-sm text-slate-600">Hari ini, 22:25</span></div>
                      </td>
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0" style="background: linear-gradient(135deg, #a8edea 0%, #5b9bd5 100%); color: #1e4a7a;">D</div>
                          <span class="font-plex text-sm font-medium text-slate-700">Dr. Iman</span>
                        </div>
                      </td>
                      <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-emerald-50 text-emerald-600"><i class="fas fa-syringe"></i> Doctor Usage</span>
                      </td>
                      <td class="px-5 py-4 font-plex text-sm text-slate-600">Catat pemakaian: Sarung Tangan Latex ×4 — Pasien: Tn. Budi</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-2"><i class="far fa-clock text-slate-300 text-sm"></i><span class="font-plex text-sm text-slate-600">Hari ini, 22:25</span></div>
                      </td>
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0" style="background: linear-gradient(135deg, #a8edea 0%, #5b9bd5 100%); color: #1e4a7a;">D</div>
                          <span class="font-plex text-sm font-medium text-slate-700">Dr. Iman</span>
                        </div>
                      </td>
                      <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-emerald-50 text-emerald-600"><i class="fas fa-syringe"></i> Doctor Usage</span>
                      </td>
                      <td class="px-5 py-4 font-plex text-sm text-slate-600">Catat pemakaian: Semen GIC ×2 — Pasien: Ny. Dewi</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-2"><i class="far fa-clock text-slate-300 text-sm"></i><span class="font-plex text-sm text-slate-600">Kemarin, 22:25</span></div>
                      </td>
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0" style="background: linear-gradient(135deg, #fde68a 0%, #f59e0b 100%); color: #78350f;">K</div>
                          <span class="font-plex text-sm font-medium text-slate-700">Kepala Klinik</span>
                        </div>
                      </td>
                      <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-blue-50 text-blue-600"><i class="fas fa-sign-in-alt"></i> Login</span>
                      </td>
                      <td class="px-5 py-4 font-plex text-sm text-slate-600">Kepala Klinik melihat laporan bulanan</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- Pagination -->
              <div class="flex flex-col sm:flex-row items-center justify-between px-5 py-4 border-t border-slate-100 gap-3">
                <p class="font-plex text-sm text-slate-500">Menampilkan 1-10 dari 20 log</p>
                <div class="flex items-center gap-1.5">
                  <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
                  <button class="w-9 h-9 rounded-lg bg-brand-600 text-white flex items-center justify-center font-plex text-sm font-semibold">1</button>
                  <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors font-plex text-sm">2</button>
                  <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
                </div>
              </div>
            </div>
          </div>
          <!-- ========== END TAB 2 ========== -->

        </div>
      </div>

      <style>
        /* Tab slide animations */
        .tab-exit-left {
          animation: slideOutLeft 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .tab-exit-right {
          animation: slideOutRight 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .tab-enter-left {
          animation: slideInLeft 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .tab-enter-right {
          animation: slideInRight 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideOutLeft {
          from { opacity: 1; transform: translateX(0); }
          to   { opacity: 0; transform: translateX(-30px); }
        }
        @keyframes slideOutRight {
          from { opacity: 1; transform: translateX(0); }
          to   { opacity: 0; transform: translateX(30px); }
        }
        @keyframes slideInLeft {
          from { opacity: 0; transform: translateX(-30px); }
          to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
          from { opacity: 0; transform: translateX(30px); }
          to   { opacity: 1; transform: translateX(0); }
        }
      </style>

      <script>
        let currentTab = 'kelola';

        function switchTab(tab) {
          if (tab === currentTab) return;

          const tabKelola = document.getElementById('tab-kelola');
          const tabLog = document.getElementById('tab-log');
          const contentKelola = document.getElementById('content-kelola');
          const contentLog = document.getElementById('content-log');
          const bannerAction = document.getElementById('banner-action');

          const goingForward = (tab === 'log');
          const outgoing = goingForward ? contentKelola : contentLog;
          const incoming = goingForward ? contentLog : contentKelola;

          // Animate out (slide in the direction of navigation)
          outgoing.classList.add(goingForward ? 'tab-exit-left' : 'tab-exit-right');

          setTimeout(() => {
            outgoing.classList.add('hidden');
            outgoing.classList.remove('tab-exit-left', 'tab-exit-right');

            // Animate in (slide from opposite side)
            incoming.classList.remove('hidden');
            incoming.classList.add(goingForward ? 'tab-enter-right' : 'tab-enter-left');

            setTimeout(() => {
              incoming.classList.remove('tab-enter-left', 'tab-enter-right');
            }, 350);
          }, 250);

          // Update tab buttons
          if (tab === 'kelola') {
            tabKelola.className = 'px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-brand-600 text-white shadow-sm';
            tabLog.className = 'px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-slate-100 text-slate-600 hover:bg-slate-200';
            tabKelola.querySelector('span').className = 'bg-white/20 text-white text-[11px] px-2 py-0.5 rounded-full font-bold';
            tabLog.querySelector('span').className = 'bg-slate-200 text-slate-500 text-[11px] px-2 py-0.5 rounded-full font-bold';
            bannerAction.innerHTML = '<button class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors flex-shrink-0"><i class="fas fa-plus"></i> Tambah Pengguna</button>';
          } else {
            tabLog.className = 'px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-brand-600 text-white shadow-sm';
            tabKelola.className = 'px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-slate-100 text-slate-600 hover:bg-slate-200';
            tabLog.querySelector('span').className = 'bg-white/20 text-white text-[11px] px-2 py-0.5 rounded-full font-bold';
            tabKelola.querySelector('span').className = 'bg-slate-200 text-slate-500 text-[11px] px-2 py-0.5 rounded-full font-bold';
            bannerAction.innerHTML = '<button class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors flex-shrink-0"><i class="fas fa-download"></i> Export</button>';
          }

          currentTab = tab;
        }
      </script>

