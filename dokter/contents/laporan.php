<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto flex flex-col gap-10 w-full">

  <!-- ==================== SECTION 1: LAPORAN PEMAKAIAN BHP ==================== -->
  <section>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-[22px] font-display font-medium text-slate-800 tracking-[-0.02em]">Laporan Pemakaian BHP</h2>
    </div>

    <!-- Filter & Export Card -->
    <div class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex flex-col xl:flex-row xl:items-end justify-between gap-6 mb-6">
      
      <!-- Filter Inputs -->
      <div class="flex flex-col md:flex-row items-start md:items-end gap-4 w-full xl:w-auto">
        <!-- Filter Tanggal -->
        <div class="w-full md:w-auto">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-plex">Filter Tanggal</label>
          <div class="flex items-center gap-2">
            <div class="relative flex-1 md:flex-none">
              <input type="date" value="2024-03-01" class="w-full md:w-[150px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
            </div>
            <div class="w-4 h-[1px] bg-slate-300"></div>
            <div class="relative flex-1 md:flex-none">
              <input type="date" value="2024-03-31" class="w-full md:w-[150px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
            </div>
          </div>
        </div>

        <!-- Filter Kategori -->
        <div class="w-full md:w-auto">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-plex">Filter Kategori</label>
          <div class="relative">
            <select class="w-full md:w-[200px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm text-slate-600 outline-none focus:border-brand-500 appearance-none font-medium transition-colors cursor-pointer">
              <option>Semua Kategori</option>
              <option>BHP Medis</option>
              <option>BHP Non-Medis</option>
            </select>
            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
          </div>
        </div>

        <!-- Apply Button -->
        <button class="w-full md:w-auto h-11 px-6 rounded-xl text-sm font-semibold text-white transition-opacity hover:opacity-90 active:scale-[0.98]" style="background: linear-gradient(135deg, #008D5B 0%, #00B47A 100%); box-shadow: 0 4px 6px -2px rgba(0, 180, 122, 0.2);">
          Terapkan
        </button>
      </div>

      <!-- Export Buttons -->
      <div class="flex items-center gap-3 w-full xl:w-auto mt-4 xl:mt-0 pt-4 xl:pt-0 border-t xl:border-t-0 border-slate-100">
        <button class="flex-1 xl:flex-none h-11 px-5 rounded-xl text-sm font-semibold text-red-500 border border-red-100 bg-red-50/50 flex items-center justify-center gap-2 hover:bg-red-50 transition-colors">
          <i class="far fa-file-pdf"></i> Export PDF
        </button>
        <button class="flex-1 xl:flex-none h-11 px-5 rounded-xl text-sm font-semibold text-emerald-600 border border-emerald-100 bg-emerald-50/50 flex items-center justify-center gap-2 hover:bg-emerald-50 transition-colors">
          <i class="far fa-file-excel"></i> Export Excel
        </button>
      </div>
      
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden font-plex">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest w-40">Tanggal</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Nama BHP</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center w-40">Total Pemakaian</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest w-32">Satuan</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Keterangan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50 text-sm">
            <!-- Row 1 -->
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4.5 align-top">
                <div class="font-medium text-slate-700">12 Mar 2024</div>
                <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">14:30 WIB</div>
              </td>
              <td class="px-6 py-4.5 align-top font-bold text-slate-800 text-[15px]">Sarung Tangan Karet (L)</td>
              <td class="px-6 py-4.5 align-top text-center">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#EFF6FF] text-[#3B82F6] font-bold text-sm">2</span>
              </td>
              <td class="px-6 py-4.5 align-top text-slate-600 font-medium">Box</td>
              <td class="px-6 py-4.5 align-top text-slate-500 leading-relaxed max-w-[300px]">Permintaan untuk ruang tindakan operasional bedah gigi (Dr. Andi).</td>
            </tr>
            <!-- Row 2 -->
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4.5 align-top">
                <div class="font-medium text-slate-700">12 Mar 2024</div>
                <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">10:15 WIB</div>
              </td>
              <td class="px-6 py-4.5 align-top font-bold text-slate-800 text-[15px]">Komposit Resin A3</td>
              <td class="px-6 py-4.5 align-top text-center">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#EFF6FF] text-[#3B82F6] font-bold text-sm">1</span>
              </td>
              <td class="px-6 py-4.5 align-top text-slate-600 font-medium">Tube</td>
              <td class="px-6 py-4.5 align-top text-slate-500 leading-relaxed max-w-[300px]">Penambalan pasien BPJS No. Antrian 14.</td>
            </tr>
            <!-- Row 3 -->
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4.5 align-top">
                <div class="font-medium text-slate-700">11 Mar 2024</div>
                <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">09:00 WIB</div>
              </td>
              <td class="px-6 py-4.5 align-top font-bold text-slate-800 text-[15px]">Masker Bedah 3-Ply</td>
              <td class="px-6 py-4.5 align-top text-center">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#EFF6FF] text-[#3B82F6] font-bold text-sm">5</span>
              </td>
              <td class="px-6 py-4.5 align-top text-slate-600 font-medium">Box</td>
              <td class="px-6 py-4.5 align-top text-slate-500 leading-relaxed max-w-[300px]">Penyebaran stok mingguan ke semua ruang rawat poli.</td>
            </tr>
            <!-- Row 4 -->
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4.5 align-top">
                <div class="font-medium text-slate-700">10 Mar 2024</div>
                <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">16:45 WIB</div>
              </td>
              <td class="px-6 py-4.5 align-top font-bold text-slate-800 text-[15px]">Amoxicillin 500mg</td>
              <td class="px-6 py-4.5 align-top text-center">
                <span class="inline-flex items-center justify-center w-9 h-8 rounded-lg bg-[#EFF6FF] text-[#3B82F6] font-bold text-sm">10</span>
              </td>
              <td class="px-6 py-4.5 align-top text-slate-600 font-medium">Strip</td>
              <td class="px-6 py-4.5 align-top text-slate-500 leading-relaxed max-w-[300px]">Penebusan resep kolektif pasien harian.</td>
            </tr>
            <!-- Row 5 -->
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4.5 align-top">
                <div class="font-medium text-slate-700">09 Mar 2024</div>
                <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">08:20 WIB</div>
              </td>
              <td class="px-6 py-4.5 align-top font-bold text-slate-800 text-[15px]">Pehacain Injection 2ml</td>
              <td class="px-6 py-4.5 align-top text-center">
                <span class="inline-flex items-center justify-center w-9 h-8 rounded-lg bg-[#EFF6FF] text-[#3B82F6] font-bold text-sm">15</span>
              </td>
              <td class="px-6 py-4.5 align-top text-slate-600 font-medium">Ampul</td>
              <td class="px-6 py-4.5 align-top text-slate-500 leading-relaxed max-w-[300px]">Operasi Odontektomi massal.</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="border-t border-slate-100 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/30">
        <span class="text-[13px] font-medium text-slate-400">Menampilkan 1-5 dari 324 Riwayat</span>
        <div class="flex items-center gap-2">
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-white hover:text-slate-600 transition-colors bg-transparent"><i class="fas fa-chevron-left text-[10px]"></i></button>
          
          <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-brand-500 text-white font-bold text-sm shadow-sm">1</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-transparent text-slate-500 hover:bg-slate-100 font-semibold text-sm transition-colors">2</button>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-transparent text-slate-500 hover:bg-slate-100 font-semibold text-sm transition-colors">3</button>
          
          <span class="w-8 flex justify-center text-slate-400 tracking-widest text-xs font-semibold">...</span>
          
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-transparent text-slate-500 hover:bg-slate-100 font-semibold text-sm transition-colors">12</button>
          
          <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-white hover:text-slate-600 transition-colors bg-transparent"><i class="fas fa-chevron-right text-[10px]"></i></button>
        </div>
      </div>
    </div>
  </section>

  <!-- ==================== SECTION 2: LAPORAN PENGGUNAAN OBAT PER PASIEN ==================== -->
  <section class="mt-4">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-[22px] font-display font-medium text-slate-800 tracking-[-0.02em]">Laporan Penggunaan Obat per Pasien</h2>
    </div>
    
    <!-- Filter Card 2 -->
    <div class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
      <div class="flex flex-col md:flex-row items-start md:items-end gap-4 flex-1">
        <!-- Tanggal -->
        <div class="w-full md:w-auto">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-plex">Filter Tanggal</label>
          <div class="flex items-center gap-2">
            <div class="relative flex-1 md:flex-none">
              <input type="date" value="2024-03-01" class="w-full md:w-[150px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
            </div>
            <div class="w-4 h-[1px] bg-slate-300"></div>
            <div class="relative flex-1 md:flex-none">
              <input type="date" value="2024-03-31" class="w-full md:w-[150px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
            </div>
          </div>
        </div>

        <!-- Kategori -->
        <div class="w-full md:w-auto">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-plex">Filter Kategori</label>
          <div class="relative">
            <select class="w-full md:w-[240px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm font-medium text-slate-600 outline-none focus:border-brand-500 appearance-none transition-colors cursor-pointer">
              <option>Semua Kategori</option>
            </select>
            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
          </div>
        </div>
      </div>

      <!-- Apply Button -->
      <button class="w-full md:w-auto h-11 px-6 rounded-xl text-sm font-semibold text-white transition-opacity hover:opacity-90 active:scale-[0.98]" style="background: linear-gradient(135deg, #008D5B 0%, #00B47A 100%); box-shadow: 0 4px 6px -2px rgba(0, 180, 122, 0.2);">
        Terapkan
      </button>
    </div>
    
    <!-- Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8 font-plex">
      
      <!-- Card 1 -->
      <div class="bg-white rounded-[24px] border border-slate-100/80 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <!-- info -->
        <h3 class="font-bold text-slate-800 text-[15px] leading-tight mb-1.5">NaCl 0.9% - Otsuka</h3>
        <p class="text-[12px] text-slate-400 font-medium mb-5 tracking-wide">500ml &nbsp;<span class="text-slate-300">•</span>&nbsp; stock: 10</p>
        
        <div class="bg-slate-50 rounded-2xl p-4 mb-6 inline-block w-full border border-slate-100/50">
          <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pemakaian</span>
          <span class="text-[22px] font-bold text-slate-800">20 Botol</span>
        </div>
        
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3.5">Recent Patients</p>
          <div class="flex flex-col gap-3.5 mb-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">BS</div>
                <span class="text-[13px] font-semibold text-slate-600">Budi santoso</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">FI</div>
                <span class="text-[13px] font-semibold text-slate-600">Fatkul Iman</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">AW</div>
                <span class="text-[13px] font-semibold text-slate-600">Ani Wijaya</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
          </div>
        </div>
        
        <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-brand-600 tracking-wide transition-colors flex items-center gap-1.5 border-t border-slate-100/70 pt-4 mt-2 inline-flex">
          View Detailed Log <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
      </div>
      
      <!-- Card 2 -->
      <div class="bg-white rounded-[24px] border border-slate-100/80 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <h3 class="font-bold text-slate-800 text-[15px] leading-tight mb-1.5">NaCl 0.9% - Otsuka</h3>
        <p class="text-[12px] text-slate-400 font-medium mb-5 tracking-wide">500ml &nbsp;<span class="text-slate-300">•</span>&nbsp; stock: 10</p>
        <div class="bg-slate-50 rounded-2xl p-4 mb-6 inline-block w-full border border-slate-100/50">
          <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pemakaian</span>
          <span class="text-[22px] font-bold text-slate-800">20 Botol</span>
        </div>
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3.5">Recent Patients</p>
          <div class="flex flex-col gap-3.5 mb-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">BS</div>
                <span class="text-[13px] font-semibold text-slate-600">Budi santoso</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">FI</div>
                <span class="text-[13px] font-semibold text-slate-600">Fatkul Iman</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">AW</div>
                <span class="text-[13px] font-semibold text-slate-600">Ani Wijaya</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
          </div>
        </div>
        <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-brand-600 tracking-wide transition-colors flex items-center gap-1.5 border-t border-slate-100/70 pt-4 mt-2 inline-flex">
          View Detailed Log <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
      </div>

      <!-- Card 3 -->
      <div class="bg-white rounded-[24px] border border-slate-100/80 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <h3 class="font-bold text-slate-800 text-[15px] leading-tight mb-1.5">NaCl 0.9% - Otsuka</h3>
        <p class="text-[12px] text-slate-400 font-medium mb-5 tracking-wide">500ml &nbsp;<span class="text-slate-300">•</span>&nbsp; stock: 10</p>
        <div class="bg-slate-50 rounded-2xl p-4 mb-6 inline-block w-full border border-slate-100/50">
          <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pemakaian</span>
          <span class="text-[22px] font-bold text-slate-800">20 Botol</span>
        </div>
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3.5">Recent Patients</p>
          <div class="flex flex-col gap-3.5 mb-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">BS</div>
                <span class="text-[13px] font-semibold text-slate-600">Budi santoso</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">2x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">FI</div>
                <span class="text-[13px] font-semibold text-slate-600">Fatkul Iman</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">2x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">AW</div>
                <span class="text-[13px] font-semibold text-slate-600">Ani Wijaya</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">1x</span>
            </div>
          </div>
        </div>
        <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-brand-600 tracking-wide transition-colors flex items-center gap-1.5 border-t border-slate-100/70 pt-4 mt-2 inline-flex">
          View Detailed Log <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
      </div>

      <!-- Card 4 -->
      <div class="bg-white rounded-[24px] border border-slate-100/80 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <h3 class="font-bold text-slate-800 text-[15px] leading-tight mb-1.5">NaCl 0.9% - Otsuka</h3>
        <p class="text-[12px] text-slate-400 font-medium mb-5 tracking-wide">500ml &nbsp;<span class="text-slate-300">•</span>&nbsp; stock: 10</p>
        <div class="bg-slate-50 rounded-2xl p-4 mb-6 inline-block w-full border border-slate-100/50">
          <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pemakaian</span>
          <span class="text-[22px] font-bold text-slate-800">20 Botol</span>
        </div>
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3.5">Recent Patients</p>
          <div class="flex flex-col gap-3.5 mb-5">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">BS</div>
                <span class="text-[13px] font-semibold text-slate-600">Budi santoso</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">PI</div>
                <span class="text-[13px] font-semibold text-slate-600">Paimin Iwan</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-[10px] flex items-center justify-center">AW</div>
                <span class="text-[13px] font-semibold text-slate-600">Ani Wijaya</span>
              </div>
              <span class="text-[13px] font-bold text-slate-800">3x</span>
            </div>
          </div>
        </div>
        <a href="#" class="text-[11px] font-bold text-slate-400 hover:text-brand-600 tracking-wide transition-colors flex items-center gap-1.5 border-t border-slate-100/70 pt-4 mt-2 inline-flex">
          View Detailed Log <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
      </div>
      
    </div>
    
    <!-- Export Bottom Buttons -->
    <div class="flex items-center gap-3 mt-2">
      <button class="h-11 px-5 rounded-xl text-sm font-semibold text-red-500 border border-red-100 bg-red-50/50 flex items-center justify-center gap-2 hover:bg-red-50 transition-colors">
        <i class="far fa-file-pdf"></i> Export PDF
      </button>
      <button class="h-11 px-5 rounded-xl text-sm font-semibold text-emerald-600 border border-emerald-100 bg-emerald-50/50 flex items-center justify-center gap-2 hover:bg-emerald-50 transition-colors">
        <i class="far fa-file-excel"></i> Export Excel
      </button>
    </div>
  </section>

  </div>
</div>
