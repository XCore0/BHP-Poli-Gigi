      <!-- Main Scrollable Content -->
      <div class="flex-1 overflow-y-auto w-full p-4 sm:p-6 lg:p-8 hide-scrollbar flex flex-col justify-between">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full mb-8">

          <!-- Header Banner -->
          <div
            class="hero-gradient rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div
              class="absolute right-0 top-0 opacity-10 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/overlapping-circles.png')] pointer-events-none">
            </div>

            <div class="relative z-10 flex gap-5 md:gap-6 items-center w-full">
              <div
                class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-sm flex items-center justify-center shrink-0 shadow-inner">
                <i class="fa-solid fa-user-group text-2xl sm:text-3xl text-white drop-shadow-md"></i>
              </div>
              <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-display font-bold tracking-tight mb-1">Kategori Bahan Habis Pakai</h1>
                <p class="text-brand-100 font-medium text-sm sm:text-base max-w-2xl opacity-90 leading-relaxed">
                  Tambahkan dan atur kategori bahan habis pakai agar data lebih rapi, terstruktur, dan mudah dikelola.
                </p>
              </div>
            </div>
            <button
              class="relative z-10 w-full md:w-auto bg-white text-brand-700 px-5 py-3 rounded-xl font-bold shadow-[0_4px_14px_0_rgba(255,255,255,0.39)] hover:shadow-[0_6px_20px_rgba(255,255,255,0.23)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
              <i class="fa-solid fa-plus text-sm"></i> Tambah Kategori BHP
            </button>
          </div>

          <!-- Filters and Table Container -->
          <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col">

            <!-- Filters -->
            <div
              class="p-5 sm:p-6 border-b border-slate-100 flex flex-col xl:flex-row justify-between gap-4 items-end bg-white rounded-t-2xl relative z-10">
              <!-- Search -->
              <div class="w-full relative flex-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Cari</label>
                <div class="relative max-w-3xl">
                  <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                  <input type="text" placeholder="Cari nama Kategori"
                    class="w-full h-11 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400 font-medium text-slate-700 shadow-sm hover:border-slate-300">
                </div>
              </div>

              <!-- Action -->
              <button
                class="w-full xl:w-auto h-11 px-8 bg-gradient-to-r from-emerald-600 to-teal-500 text-white rounded-xl font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all text-sm shrink-0">
                Terapkan
              </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto w-full relative z-0">
              <table class="w-full text-left whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-50/50 border-b border-slate-200">
                    <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold w-[25%]">Kode Kategori</th>
                    <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold w-[50%]">Kategori</th>
                    <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold w-[25%] text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody class="text-sm font-plex divide-y divide-slate-100 text-slate-700">
                  <!-- Item 1 -->
                  <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="py-5 px-6 font-bold text-slate-700">AP - 001</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold bg-emerald-100/50 text-emerald-700 border border-emerald-100/50">
                        Alat Pelindung
                      </span>
                    </td>
                    <td class="py-5 px-6 text-right">
                      <div class="flex items-center justify-end gap-2">
                        <button class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-blue-100/50 bg-white shadow-sm" title="Edit">
                          <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700 hover:bg-red-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-red-100/50 bg-white shadow-sm" title="Hapus">
                          <i class="fa-solid fa-trash-can text-[13px]"></i>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Item 2 -->
                  <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="py-5 px-6 font-bold text-slate-700">CD - 001</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold bg-amber-100/50 text-amber-700 border border-amber-100/50">
                        Cairan Disinfektan
                      </span>
                    </td>
                    <td class="py-5 px-6 text-right">
                      <div class="flex items-center justify-end gap-2">
                        <button class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-blue-100/50 bg-white shadow-sm" title="Edit">
                          <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700 hover:bg-red-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-red-100/50 bg-white shadow-sm" title="Hapus">
                          <i class="fa-solid fa-trash-can text-[13px]"></i>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Item 3 -->
                  <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="py-5 px-6 font-bold text-slate-700">BT - 001</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold bg-amber-100/50 text-amber-700 border border-amber-100/50">
                        Bahan Tindakan
                      </span>
                    </td>
                    <td class="py-5 px-6 text-right">
                      <div class="flex items-center justify-end gap-2">
                        <button class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-blue-100/50 bg-white shadow-sm" title="Edit">
                          <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700 hover:bg-red-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-red-100/50 bg-white shadow-sm" title="Hapus">
                          <i class="fa-solid fa-trash-can text-[13px]"></i>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Item 4 -->
                  <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="py-5 px-6 font-bold text-slate-700">PP - 001</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold bg-emerald-100/50 text-emerald-700 border border-emerald-100/50">
                        Perlengkapan Pasien
                      </span>
                    </td>
                    <td class="py-5 px-6 text-right">
                      <div class="flex items-center justify-end gap-2">
                        <button class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-blue-100/50 bg-white shadow-sm" title="Edit">
                          <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700 hover:bg-red-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-red-100/50 bg-white shadow-sm" title="Hapus">
                          <i class="fa-solid fa-trash-can text-[13px]"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>

        </div>

        <!-- Footer inside the reliable scroll container -->
        <div class="w-full py-4 mt-auto text-center opacity-70">
          <p class="font-plex text-xs font-medium text-slate-500 mb-0.5">© 2026 Sistem Informasi Manajemen BHP Poli Gigi</p>
          <p class="font-plex text-[10px] tracking-wide text-slate-400">Versi 1.0 • Developed by Team Terserahhh</p>
        </div>

      </div>
