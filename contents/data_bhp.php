      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full">

          <!-- Header Banner -->
          <div
            class="hero-gradient rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div
              class="absolute right-0 top-0 opacity-10 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/overlapping-circles.png')] pointer-events-none">
            </div>

            <div class="relative z-10 flex gap-5 md:gap-6 items-center w-full">
              <div
                class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-sm flex items-center justify-center shrink-0 shadow-inner">
                <i class="fa-solid fa-box-open text-2xl sm:text-3xl text-white drop-shadow-md"></i>
              </div>
              <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-display font-bold tracking-tight mb-1">Data Bahan Habis Pakai</h1>
                <p class="text-brand-100 font-medium text-sm sm:text-base max-w-2xl opacity-90 leading-relaxed">
                  Pantau stok, penggunaan, dan ketersediaan bahan habis pakai secara real-time untuk memastikan
                  operasional tetap berjalan lancar.
                </p>
              </div>
            </div>
            <button
              class="relative z-10 w-full md:w-auto bg-white text-brand-700 px-5 py-3 rounded-xl font-bold shadow-[0_4px_14px_0_rgba(255,255,255,0.39)] hover:shadow-[0_6px_20px_rgba(255,255,255,0.23)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
              <i class="fa-solid fa-plus text-sm"></i> Tambah BHP Baru
            </button>
          </div>

          <!-- Filters and Table Container -->
          <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col">

            <!-- Filters -->
            <div
              class="p-5 sm:p-6 border-b border-slate-100 flex flex-col xl:flex-row gap-4 items-end bg-white rounded-t-2xl relative z-10">
              <!-- Search -->
              <div class="w-full xl:w-[40%] flex flex-col gap-1.5">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Cari</label>
                <div class="relative">
                  <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                  <input type="text" placeholder="Cari nama BHP, kode..."
                    class="w-full h-11 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400 font-medium text-slate-700 shadow-sm hover:border-slate-300">
                </div>
              </div>

              <!-- Category -->
              <div class="w-full sm:w-1/2 xl:w-[20%] flex flex-col gap-1.5">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Kategori</label>
                <div class="relative">
                  <select
                    class="w-full h-11 pl-4 pr-10 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all font-medium text-slate-700 appearance-none cursor-pointer shadow-sm hover:border-slate-300">
                    <option>Semua Kategori</option>
                    <option>Alat Pelindung</option>
                    <option>Bahan Tambal</option>
                    <option>Alat Medis</option>
                  </select>
                  <i
                    class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
              </div>

              <!-- Date Range -->
              <div class="w-full sm:w-1/2 xl:w-auto flex flex-col gap-1.5 flex-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</label>
                <div class="flex items-center">
                  <div class="relative flex-1">
                    <input type="text" value="03/01/2024" placeholder="MM/DD/YYYY"
                      class="w-full h-11 px-4 bg-white border border-slate-200 rounded-l-xl text-[13px] outline-none focus:border-brand-500 font-semibold text-slate-600 shadow-sm hover:border-slate-300">
                    <i class="fa-regular fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-slate-600 text-sm pointer-events-none"></i>
                  </div>
                  <div
                    class="h-11 px-3 bg-slate-50/50 border-y border-slate-200 flex items-center justify-center text-slate-400 shadow-sm">
                    <i class="fa-solid fa-minus text-[10px]"></i>
                  </div>
                  <div class="relative flex-1">
                    <input type="text" value="03/31/2024" placeholder="MM/DD/YYYY"
                      class="w-full h-11 px-4 bg-white border border-slate-200 rounded-r-xl text-[13px] outline-none focus:border-brand-500 font-semibold text-slate-600 shadow-sm hover:border-slate-300">
                    <i class="fa-regular fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-slate-600 text-sm pointer-events-none"></i>
                  </div>
                </div>
              </div>

              <!-- Action -->
              <button
                class="w-full xl:w-auto h-11 px-6 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-colors text-sm shrink-0">
                Terapkan
              </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto w-full relative z-0">
              <table class="w-full text-left whitespace-nowrap">
                <thead>
                  <tr class="bg-white text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-200 font-bold">
                    <th class="py-4 px-6">Kode BHP</th>
                    <th class="py-4 px-6">Nama BHP</th>
                    <th class="py-4 px-6">Kategori</th>
                    <th class="py-4 px-6">Satuan</th>
                    <th class="py-4 px-6">Stok</th>
                    <th class="py-4 px-6">Stok Min</th>
                    <th class="py-4 px-6">Tanggal Kadaluarsa</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody class="text-[13px] font-plex divide-y divide-slate-100 text-slate-600">
                  <!-- Item 1 -->
                  <tr class="hover:bg-slate-50/50 transition-colors group bg-white">
                    <td class="py-5 px-6 font-semibold text-slate-700">MD - 321</td>
                    <td class="py-5 px-6 font-semibold text-slate-800">Masker Medis 3-Ply</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-[11px] font-bold bg-emerald-100/50 text-emerald-600 border border-emerald-100/50">
                        Alat Pelindung
                      </span>
                    </td>
                    <td class="py-5 px-6 font-medium text-slate-600">Box</td>
                    <td class="py-5 px-6 font-medium text-slate-600">120</td>
                    <td class="py-5 px-6 font-medium text-slate-500">30</td>
                    <td class="py-5 px-6 font-medium text-slate-600">12-05-2027</td>
                    <td class="py-5 px-6">
                      <span class="text-emerald-600 font-bold text-[11px] tracking-widest uppercase">
                        <span class="text-emerald-500 mr-1.5 text-[14px] leading-none tracking-normal">&bull;</span>Aman
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
                  <tr class="hover:bg-slate-50/50 transition-colors group bg-white">
                    <td class="py-5 px-6 font-semibold text-slate-700">A - 70</td>
                    <td class="py-5 px-6 font-semibold text-slate-800">Alkohol 70%</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-[11px] font-bold bg-amber-100/50 text-amber-600 border border-amber-100/50">
                        Cairan Disinfektan
                      </span>
                    </td>
                    <td class="py-5 px-6 font-medium text-slate-600">Botol</td>
                    <td class="py-5 px-6 font-medium text-slate-600">15</td>
                    <td class="py-5 px-6 font-medium text-slate-500">20</td>
                    <td class="py-5 px-6 font-medium text-slate-600">15-08-2026</td>
                    <td class="py-5 px-6">
                      <span class="text-amber-600 font-bold text-[11px] tracking-widest uppercase">
                        <span class="text-amber-500 mr-1.5 text-[14px] leading-none tracking-normal">&bull;</span>Menipis
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
                  <tr class="hover:bg-slate-50/50 transition-colors group bg-white">
                    <td class="py-5 px-6 font-semibold text-slate-700">CR - 21</td>
                    <td class="py-5 px-6 font-semibold text-slate-800">Cotton Roll</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-[11px] font-bold bg-amber-100/50 text-amber-600 border border-amber-100/50">
                        Bahan Tindakan
                      </span>
                    </td>
                    <td class="py-5 px-6 font-medium text-slate-600">Pack</td>
                    <td class="py-5 px-6 font-medium text-slate-600">8</td>
                    <td class="py-5 px-6 font-medium text-slate-500">15</td>
                    <td class="py-5 px-6 font-medium text-slate-600">10-01-2027</td>
                    <td class="py-5 px-6">
                      <span class="text-amber-600 font-bold text-[11px] tracking-widest uppercase">
                        <span class="text-amber-500 mr-1.5 text-[14px] leading-none tracking-normal">&bull;</span>Menipis
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
                  <tr class="hover:bg-slate-50/50 transition-colors group bg-white">
                    <td class="py-5 px-6 font-semibold text-slate-700">Db - 42</td>
                    <td class="py-5 px-6 font-semibold text-slate-800">Dental Bib</td>
                    <td class="py-5 px-6">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-[11px] font-bold bg-emerald-100/50 text-emerald-600 border border-emerald-100/50">
                        Perlengkapan Pasien
                      </span>
                    </td>
                    <td class="py-5 px-6 font-medium text-slate-600">Pcs</td>
                    <td class="py-5 px-6 font-medium text-slate-600">200</td>
                    <td class="py-5 px-6 font-medium text-slate-500">50</td>
                    <td class="py-5 px-6 font-medium text-slate-600">01-03-2028</td>
                    <td class="py-5 px-6">
                      <span class="text-emerald-600 font-bold text-[11px] tracking-widest uppercase">
                        <span class="text-emerald-500 mr-1.5 text-[14px] leading-none tracking-normal">&bull;</span>Aman
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
      </div>
