      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full">

          <!-- Page Title + Export -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-display font-bold text-2xl text-slate-800">Log Aktivitas Pengguna</h2>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Total Log - pulse icon, purple -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #F3E8FF;">
                <i class="fas fa-heart-pulse text-purple-600 text-lg sm:text-xl"></i>
              </div>
              <div class="min-w-0">
                <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Log</p>
                <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">10</p>
              </div>
            </div>
            <!-- Aktivitas Hari Ini - clock icon, blue -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #EFF6FF;">
                <i class="fas fa-clock text-blue-600 text-lg sm:text-xl"></i>
              </div>
              <div class="min-w-0">
                <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Aktivitas Hari Ini</p>
                <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">3</p>
              </div>
            </div>
            <!-- Stok Masuk - box icon, green -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #ECFDF5;">
                <i class="fas fa-boxes-packing text-brand-600 text-lg sm:text-xl"></i>
              </div>
              <div class="min-w-0">
                <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Stok Masuk</p>
                <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight">3</p>
              </div>
            </div>
            <!-- Pemakaian Dokter - stethoscope icon, cyan -->
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

          <!-- Filter Bar -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
              <!-- Date Range -->
              <div class="flex-1">
                <label class="font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 block">Filter Tanggal</label>
                <div class="flex items-center gap-2">
                  <input type="date" value="2024-03-01" class="flex-1 min-w-0 px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent">
                  <span class="text-slate-400 font-bold flex-shrink-0">—</span>
                  <input type="date" value="2024-03-31" class="flex-1 min-w-0 px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent">
                </div>
              </div>
              <!-- Category -->
              <div class="sm:w-48">
                <label class="font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 block">Filter Kategori</label>
                <select class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 bg-white">
                  <option>Semua Kategori</option>
                  <option>Login</option>
                  <option>Stock In</option>
                  <option>Doctor Usage</option>
                </select>
              </div>
              <!-- Apply + Export -->
              <div class="flex items-center gap-2 flex-shrink-0">
                <button class="px-5 py-2.5 rounded-xl bg-brand-600 text-white text-sm font-plex font-semibold hover:bg-brand-700 transition-colors">Terapkan</button>
                <button class="px-4 py-2.5 rounded-xl border border-red-200 text-red-500 text-sm font-plex font-semibold hover:bg-red-50 transition-colors hidden sm:flex items-center gap-2"><i class="fas fa-file-pdf"></i> Export PDF</button>
                <button class="px-4 py-2.5 rounded-xl border border-brand-200 text-brand-600 text-sm font-plex font-semibold hover:bg-brand-50 transition-colors hidden sm:flex items-center gap-2"><i class="fas fa-file-excel"></i> Export Excel</button>
              </div>
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
                      <div class="flex items-center gap-2">
                        <i class="far fa-clock text-slate-300 text-sm"></i>
                        <span class="font-plex text-sm text-slate-600">Hari ini, 22:25</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0" style="background: linear-gradient(135deg, #c7d2fe 0%, #6366f1 100%); color: #1e1b4b;">A</div>
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
                      <div class="flex items-center gap-2">
                        <i class="far fa-clock text-slate-300 text-sm"></i>
                        <span class="font-plex text-sm text-slate-600">Hari ini, 22:25</span>
                      </div>
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
                      <div class="flex items-center gap-2">
                        <i class="far fa-clock text-slate-300 text-sm"></i>
                        <span class="font-plex text-sm text-slate-600">Hari ini, 22:25</span>
                      </div>
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
                      <div class="flex items-center gap-2">
                        <i class="far fa-clock text-slate-300 text-sm"></i>
                        <span class="font-plex text-sm text-slate-600">Kemarin, 22:25</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0" style="background: linear-gradient(135deg, #c7d2fe 0%, #6366f1 100%); color: #1e1b4b;">A</div>
                        <span class="font-plex text-sm font-medium text-slate-700">Admin</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex bg-amber-50 text-amber-600"><i class="fas fa-box"></i> Stock In</span>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-600">Tambah stok masuk: Kapas Dental 50 pcs dari Supplier ABC</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-2">
                        <i class="far fa-clock text-slate-300 text-sm"></i>
                        <span class="font-plex text-sm text-slate-600">Kemarin, 22:25</span>
                      </div>
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
      </div>
