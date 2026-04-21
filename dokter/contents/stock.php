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
            <i class="fas fa-arrow-circle-down text-white text-xl sm:text-2xl"></i>
          </div>

          <!-- Texts -->
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">
              Stok Masuk
            </h1>
            <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
              Catat penerimaan barang baru dari supplier atau hasil pengadaan
            </p>
          </div>
        </div>

        <!-- Right: CTA Button -->
        <div class="flex flex-col sm:flex-row flex-shrink-0 w-full sm:w-auto gap-3">
          <button
            onclick="document.getElementById('modalStokMasuk').classList.remove('hidden')"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-plex font-bold text-[13px] transition-all duration-200 hover:bg-white active:scale-95 whitespace-nowrap shadow-sm text-brand-700 bg-white"
          >
            <span class="text-base font-bold leading-none">+</span> Input Stok
          </button>
          <button
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-plex font-bold text-[13px] transition-all duration-200 hover:bg-white/20 active:scale-95 whitespace-nowrap shadow-sm border border-white/20 text-white"
            style="background: rgba(255,255,255,0.15);"
          >
            <i class="fas fa-download"></i> Export
          </button>
        </div>
      </div>
    </div>

    <!-- Riwayat Stok Masuk Card -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col p-6 sm:p-8">
      
      <!-- Card Header -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center pb-0.5">
              <i class="fas fa-history text-emerald-500"></i>
            </div>
            <div>
              <h2 class="font-display font-bold text-lg text-slate-800">Riwayat Stok Masuk</h2>
              <p class="text-[13px] font-medium text-slate-400 mt-0.5">Semua transaksi penerimaan barang</p>
            </div>
        </div>
        <div>
            <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[11px]">8 record</span>
        </div>
      </div>

      <!-- Table Section -->
      <div class="overflow-x-auto w-full">
        <table class="w-full text-left whitespace-nowrap">
          <thead>
            <tr class="text-[11px] uppercase tracking-widest text-slate-400 border-b border-slate-100 font-bold">
              <th class="py-4 px-3 w-[20%]">TANGGAL</th>
              <th class="py-4 px-3 w-[35%]">BARANG</th>
              <th class="py-4 px-3 w-[25%]">JUMLAH</th>
              <th class="py-4 px-3 w-[15%]">OLEH</th>
              <th class="py-4 px-3 w-[5%]"></th>
            </tr>
          </thead>
          <tbody class="text-[13px] font-plex divide-y divide-slate-50/50">
            
            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">05 Feb 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">17:30</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Gutta Percha Points 25mm</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+10 Box</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>
            
            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">04 Feb 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">16:10</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Paracetamol 500mg Tab</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+20 Strip</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>
            
            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">03 Feb 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">15:10</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Masker Medis 3-Ply</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+10 Box</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>

            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">03 Feb 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">15:00</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Sarung Tangan Latex (S)</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+10 Box</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>

            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">08 Jan 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">10:00</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Glass Ionomer Cement (GIC)</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+5 Pot</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>

            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">07 Jan 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">17:30</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Lidocaine HCl 2% Carpule</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+10 Ampul</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>

            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">06 Jan 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">10:00</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Alkohol 70%</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+5 Liter</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>

            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">05 Jan 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">15:45</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Cotton Roll</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+20 Pack</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>

            <tr class="hover:bg-slate-50/50 transition-colors group">
              <td class="py-5 px-3">
                  <div class="font-medium text-slate-500">05 Jan 2025</div>
                  <div class="text-[11px] text-slate-400 mt-0.5">15:30</div>
              </td>
              <td class="py-5 px-3 font-semibold text-slate-700">Sarung Tangan Latex (M)</td>
              <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">+10 Box</span>
              </td>
              <td class="py-5 px-3 font-medium text-slate-400">Admin</td>
              <td class="py-5 px-3 text-right">
                  <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt text-[14px]"></i></button>
              </td>
            </tr>
            
          </tbody>
        </table>
      </div>

      <!-- Pagination Container -->
      <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-100 pt-6">
        <span class="text-[12px] font-medium text-slate-500">Menampilkan 1-5 dari 824 Riwayat</span>
        <div class="flex items-center gap-1.5">
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors"><i class="fas fa-chevron-left text-[10px]"></i></button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-brand-500 text-white font-bold text-[12px] shadow-sm shadow-brand-500/20">1</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 font-medium text-[12px] hover:bg-slate-50 transition-colors">2</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 font-medium text-[12px] hover:bg-slate-50 transition-colors">3</button>
          <span class="w-8 h-8 flex items-center justify-center text-slate-400 text-[12px]">...</span>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 font-medium text-[12px] hover:bg-slate-50 transition-colors">12</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors"><i class="fas fa-chevron-right text-[10px]"></i></button>
        </div>
      </div>

    </div>

  </div>

  <!-- Modal Input Stok Masuk -->
  <div id="modalStokMasuk" class="fixed inset-0 z-[99] hidden flex items-center justify-center font-plex">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalStokMasuk').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl m-4 overflow-hidden flex flex-col">
      
      <!-- Banner Header in Modal -->
      <div class="p-6" style="background: linear-gradient(135deg, #006B47 0%, #10B981 100%);">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full border border-white/20 bg-white/10 flex items-center justify-center text-white">
                <i class="fas fa-arrow-down shadow-sm"></i>
            </div>
            <div>
              <h2 class="text-lg font-bold text-white mb-0.5">Input Stok Masuk</h2>
              <p class="text-[13px] text-emerald-50">Catat penerimaan barang baru (Restock)</p>
            </div>
          </div>
      </div>
      
      <div class="bg-white relative px-6 py-6">
        <!-- Form Grid -->
        <div class="flex flex-col gap-5">
            <!-- Pilih Barang -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Pilih Barang</label>
              <div class="relative">
                <i class="fa-solid fa-box absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <select class="w-full h-11 pl-10 pr-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors appearance-none cursor-pointer">
                    <option value="" disabled selected>Pilih item BHP...</option>
                    <option value="1">Gutta Percha Points 25mm</option>
                    <option value="2">Paracetamol 500mg Tab</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
              </div>
            </div>

            <!-- Jumlah Masuk & Tanggal Terima -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="block text-[12px] font-bold text-slate-600 mb-2">Jumlah Masuk</label>
                <input type="number" value="1" class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
              </div>
              <div>
                <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Terima</label>
                <div class="relative">
                  <input type="date" class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                </div>
              </div>
            </div>

            <!-- Supplier & Tanggal Kedaluarsa -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="block text-[12px] font-bold text-slate-600 mb-2">Supplier</label>
                <input type="text" placeholder="Nama Toko / Supplier" class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
              </div>
              <div>
                <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Kedaluarsa</label>
                <div class="relative">
                  <input type="date" class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                </div>
              </div>
            </div>

            <!-- Catatan -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Catatan Tambahan (Opsional)</label>
              <textarea placeholder="Nomor faktur, kondisi barang, dll..." class="w-full min-h-[5rem] px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors resize-y placeholder:text-slate-400"></textarea>
            </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-5 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
        <button onclick="document.getElementById('modalStokMasuk').classList.add('hidden')" class="h-10 px-6 rounded-lg font-bold text-[13px] text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
          Batal
        </button>
        <button class="h-10 px-6 rounded-lg font-bold text-[13px] text-white bg-brand-500 shadow-sm shadow-brand-500/30 hover:bg-brand-600 transition-colors flex items-center gap-2">
          <i class="fas fa-save text-[11px]"></i> Simpan Stok Masuk
        </button>
      </div>
      
    </div>
  </div>

</div>
