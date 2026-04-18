      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full">

          <!-- Page Title + Export -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-display font-bold text-2xl text-slate-800">Data Stok Bahan Habis Pakai</h2>
            <div class="flex items-center gap-2 flex-shrink-0">
              <button class="px-4 py-2.5 rounded-xl border border-red-200 text-red-500 text-sm font-plex font-semibold hover:bg-red-50 transition-colors flex items-center gap-2"><i class="fas fa-file-pdf"></i> Export PDF</button>
              <button class="px-4 py-2.5 rounded-xl border border-brand-200 text-brand-600 text-sm font-plex font-semibold hover:bg-brand-50 transition-colors flex items-center gap-2"><i class="fas fa-file-excel"></i> Export Excel</button>
            </div>
          </div>

          <!-- Filter Bar -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
              <!-- Search -->
              <div class="flex-1">
                <label class="font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 block">Cari</label>
                <div class="relative">
                  <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                  <input type="text" placeholder="Cari nama BHP, kode..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent placeholder:text-slate-400">
                </div>
              </div>
              <!-- Category -->
              <div class="sm:w-48">
                <label class="font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 block">Kategori</label>
                <select class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 bg-white">
                  <option>Semua Kategori</option>
                  <option>Alat Pelindung Diri</option>
                  <option>Material Medis</option>
                  <option>Obat-obatan</option>
                </select>
              </div>
              <!-- Date Range -->
              <div class="flex-1">
                <label class="font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 block">Tanggal</label>
                <div class="flex items-center gap-2">
                  <input type="date" value="2024-03-01" class="flex-1 min-w-0 px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent">
                  <span class="text-slate-400 font-bold flex-shrink-0">—</span>
                  <input type="date" value="2024-03-31" class="flex-1 min-w-0 px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent">
                </div>
              </div>
              <!-- Apply -->
              <button class="px-5 py-2.5 rounded-xl bg-brand-600 text-white text-sm font-plex font-semibold hover:bg-brand-700 transition-colors flex-shrink-0">Terapkan</button>
            </div>
          </div>

          <!-- Data Table -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead>
                  <tr class="border-b border-slate-100">
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama BHP</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Stok Tersedia</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Satuan</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-mask-face text-blue-400 text-sm"></i></div>
                        <span class="font-plex text-sm font-medium text-slate-700">Masker Bedah 3-Ply</span>
                      </div>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Alat Pelindung Diri</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-700 font-semibold text-center">150</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Box</td>
                    <td class="px-5 py-4 text-center">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold font-plex bg-emerald-50 text-emerald-600"><i class="fas fa-check-circle text-[10px]"></i> AMAN</span>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">24 Okt 2026</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-prescription-bottle text-purple-400 text-sm"></i></div>
                        <span class="font-plex text-sm font-medium text-slate-700">Komposit Resin A2</span>
                      </div>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Material Medis</td>
                    <td class="px-5 py-4 font-plex text-sm text-red-500 font-bold text-center">2</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Tube</td>
                    <td class="px-5 py-4 text-center">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold font-plex bg-red-50 text-red-500"><i class="fas fa-exclamation-circle text-[10px]"></i> MENIPIS</span>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">12 Jan 2025</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-syringe text-amber-400 text-sm"></i></div>
                        <span class="font-plex text-sm font-medium text-slate-700">Jarum Suntik (Needle) 30G</span>
                      </div>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Material Medis</td>
                    <td class="px-5 py-4 font-plex text-sm text-amber-600 font-bold text-center">15</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Box</td>
                    <td class="px-5 py-4 text-center">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold font-plex bg-amber-50 text-amber-600"><i class="fas fa-exclamation-triangle text-[10px]"></i> WARNING</span>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">08 Nov 2025</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-pills text-green-400 text-sm"></i></div>
                        <span class="font-plex text-sm font-medium text-slate-700">Amoxicillin 500mg</span>
                      </div>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Obat-obatan</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-700 font-semibold text-center">45</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Strip</td>
                    <td class="px-5 py-4 text-center">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold font-plex bg-emerald-50 text-emerald-600"><i class="fas fa-check-circle text-[10px]"></i> AMAN</span>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">30 Sep 2024</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-vial text-teal-400 text-sm"></i></div>
                        <span class="font-plex text-sm font-medium text-slate-700">Pehacain Injection 2ml</span>
                      </div>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Obat-obatan</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-700 font-semibold text-center">80</td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">Ampul</td>
                    <td class="px-5 py-4 text-center">
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold font-plex bg-emerald-50 text-emerald-600"><i class="fas fa-check-circle text-[10px]"></i> AMAN</span>
                    </td>
                    <td class="px-5 py-4 font-plex text-sm text-slate-500">15 Mar 2026</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between px-5 py-4 border-t border-slate-100 gap-3">
              <p class="font-plex text-sm text-slate-500">Menampilkan 5 dari total 142 BHP</p>
              <div class="flex items-center gap-1.5">
                <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
                <button class="w-9 h-9 rounded-lg bg-brand-600 text-white flex items-center justify-center font-plex text-sm font-semibold">1</button>
                <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors font-plex text-sm">2</button>
                <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors font-plex text-sm">3</button>
                <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
              </div>
            </div>
          </div>

        </div>
      </div>
