      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full mb-8">

          <!-- Header Banner -->
          <div
            class="relative w-full rounded-2xl overflow-hidden mb-2"
            style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);"
          >
            <!-- Decorative circle shapes (background) -->
            <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
              <!-- Shape A (Top Right) -->
              <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
              <!-- Shape B (Bottom Right) -->
              <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
              <!-- Shape C (Bottom Curve) -->
              <div class="absolute -bottom-[400px] left-[50px] md:-bottom-[850px] md:left-[100px] w-[600px] h-[600px] md:w-[1000px] md:h-[1000px] rounded-full bg-white opacity-5"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 px-6 py-6 sm:px-8 sm:py-7">
              <!-- Left: Icon + Text -->
              <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <!-- Icon badge -->
                <div
                  class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
                  style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);"
                >
                  <i class="fas fa-tags text-white text-xl sm:text-2xl"></i>
                </div>

                <!-- Texts -->
                <div class="flex flex-col gap-1 min-w-0">
                  <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">
                    Kategori Bahan Habis Pakai
                  </h1>
                  <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
                    Tambahkan dan atur kategori bahan habis pakai agar data lebih rapi, terstruktur, dan mudah dikelola.
                  </p>
                </div>
              </div>

              <!-- Right: CTA Button -->
              <div class="flex-shrink-0 w-full sm:w-auto">
                <button
                  class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-plex font-bold text-[14px] transition-all duration-200 hover:bg-slate-50 active:scale-95 whitespace-nowrap shadow-sm"
                  style="background: #fff; color: #006B47; border: none;"
                >
                  <span class="text-base font-bold leading-none">+</span> Tambah Kategori BHP
                </button>
              </div>
            </div>
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
