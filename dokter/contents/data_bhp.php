      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full">

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
                  <i class="fas fa-box-open text-white text-xl sm:text-2xl"></i>
                </div>

                <!-- Texts -->
                <div class="flex flex-col gap-1 min-w-0">
                  <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">
                    Data Bahan Habis Pakai
                  </h1>
                  <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
                    Pantau stok, penggunaan, dan ketersediaan bahan habis pakai secara real-time
                    untuk memastikan operasional tetap berjalan lancar.
                  </p>
                </div>
              </div>

              <!-- Right: CTA Button -->
              <div class="flex-shrink-0 w-full sm:w-auto">
                <button
                  onclick="document.getElementById('modalTambahBHP').classList.remove('hidden')"
                  class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-plex font-bold text-[14px] transition-all duration-200 hover:bg-slate-50 active:scale-95 whitespace-nowrap shadow-sm"
                  style="background: #fff; color: #006B47; border: none;"
                >
                  <span class="text-base font-bold leading-none">+</span> Tambah BHP Baru
                </button>
              </div>
            </div>
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

      <!-- Modal Tambah BHP Baru -->
      <div id="modalTambahBHP" class="fixed inset-0 z-[99] hidden flex items-center justify-center font-plex">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalTambahBHP').classList.add('hidden')"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl m-4 overflow-hidden flex flex-col">
          
          <!-- Banner Header in Modal -->
          <div class="p-6" style="background: linear-gradient(135deg, #006B47 0%, #10B981 100%);">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full border border-white/20 bg-white/10 flex items-center justify-center text-white">
                  <i class="fas fa-box-open shadow-sm"></i>
              </div>
              <div>
                <h2 class="text-lg font-bold text-white mb-0.5">Tambah Barang Baru</h2>
                <p class="text-[13px] text-emerald-50">Isi formulir di bawah ini untuk menambahkan data barang ke inventaris.</p>
              </div>
            </div>
          </div>

          <div class="bg-white relative px-6 py-6">
            <!-- Form Grid -->
            <div class="flex flex-col gap-5">
              <!-- Row 1 -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-[12px] font-bold text-slate-600 mb-2">Kode Barang</label>
                  <input type="text" placeholder="BHP-0001" class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                </div>
                <div>
                  <label class="block text-[12px] font-bold text-slate-600 mb-2">Kategori</label>
                  <div class="relative">
                    <select class="w-full h-11 pl-4 pr-10 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 appearance-none transition-colors">
                      <option value="" disabled selected>Pilih kategori</option>
                      <option value="1">Alat Pelindung</option>
                      <option value="2">Cairan Disinfektan</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                  </div>
                </div>
              </div>

              <!-- Row 2 -->
              <div>
                <label class="block text-[12px] font-bold text-slate-600 mb-2">Nama BHP</label>
                <div class="relative">
                  <i class="fa-solid fa-box absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                  <input type="text" placeholder="Masukkan nama barang lengkap" class="w-full h-11 pl-10 pr-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                </div>
              </div>

              <!-- Row 3 -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                  <label class="block text-[12px] font-bold text-slate-600 mb-2">Stok Awal</label>
                  <input type="number" value="0" class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                </div>
                <div>
                  <label class="block text-[12px] font-bold text-slate-600 mb-2">Satuan</label>
                  <div class="relative">
                    <select class="w-full h-11 pl-4 pr-10 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 appearance-none transition-colors">
                      <option value="Pc">Pc</option>
                      <option value="Box">Box</option>
                      <option value="Botol">Botol</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                  </div>
                </div>
                <div>
                  <label class="block text-[12px] font-bold text-slate-600 mb-2">Peringatan Stok</label>
                  <input type="text" value="5" placeholder="Batas minimum" class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                </div>
              </div>
            </div>
          </div>

          <!-- Footer Modal -->
          <div class="px-6 py-5 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
            <button onclick="document.getElementById('modalTambahBHP').classList.add('hidden')" class="h-10 px-6 rounded-lg font-bold text-[13px] text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
              Batal
            </button>
            <button class="h-10 px-6 rounded-lg font-bold text-[13px] text-white bg-brand-500 shadow-sm shadow-brand-500/30 hover:bg-brand-600 transition-colors flex items-center gap-2">
              <i class="fas fa-save text-[11px]"></i> Simpan Barang
            </button>
          </div>
          
        </div>
      </div>
