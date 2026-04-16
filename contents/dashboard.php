<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto flex flex-col gap-6 lg:gap-8 w-full font-plex">
    
    <!-- 1. Hero Welcome Banner -->
    <div class="relative w-full rounded-[24px] p-6 lg:p-10 overflow-hidden shadow-lg border border-brand-50" style="background: linear-gradient(135deg, #006B47 0%, #1A9F70 100%);">
      <!-- Decorative Elements -->
      <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none w-1/3 flex items-center justify-end pr-10">
        <i class="fas fa-tooth text-[160px] text-white rotate-12"></i>
      </div>
      <div class="absolute left-1/4 top-0 w-64 h-64 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>

      <div class="relative z-10">
        <span class="inline-block px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-bold tracking-[0.15em] uppercase mb-4 shadow-sm border border-white/10">Kamis, 17 April 2026</span>
        <h2 class="text-2xl lg:text-3xl font-display font-bold text-white mb-2 tracking-tight">Selamat Pagi, drg. Andi Pratama</h2>
        <p class="text-emerald-50 text-[13px] lg:text-[14px] max-w-xl leading-relaxed font-medium opacity-90">
          Ini adalah ringkasan aktivitas pemakaian Bahan Habis Pakai (BHP) Anda hari ini. Terdapat 3 jadwal pasien terkonfirmasi di Ruang Poli Utama yang menunggu tindakan.
        </p>
        
        <div class="flex flex-wrap gap-3 mt-7">
          <a href="index.php?page=catat" class="bg-white text-[#006B47] px-6 py-3 rounded-xl font-bold text-[13px] shadow-[0_8px_16px_rgba(0,107,71,0.2)] hover:shadow-[0_12px_24px_rgba(0,107,71,0.3)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <i class="fas fa-pen-nib text-[12px]"></i> Catat Pemakaian Baru
          </a>
          <a href="index.php?page=data_bhp" class="bg-white/10 text-white border border-white/20 hover:bg-white/20 backdrop-blur-sm px-6 py-3 rounded-xl font-bold text-[13px] transition-all flex items-center gap-2">
            <i class="fas fa-boxes text-[12px]"></i> Cek Ketersediaan Stok
          </a>
        </div>
      </div>
    </div>
    
    <!-- 2. Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
      <!-- Card 1: Total Pemakaian Anda (Blue) -->
      <div class="bg-white rounded-[20px] p-5.5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-blue-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm border border-blue-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-box-open"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pemakaian Anda</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none">24</h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Item</span>
          </div>
        </div>
      </div>
      
      <!-- Card 2: Pasien Ditangani (Emerald) -->
      <div class="bg-white rounded-[20px] p-5.5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shadow-sm border border-emerald-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-user-check"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pasien Selesai</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none">6</h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Orang</span>
          </div>
        </div>
      </div>

      <!-- Card 3: Peringatan Stok Rendah (Red) -->
      <div class="bg-white rounded-[20px] p-5.5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center font-bold shadow-sm border border-red-100/50 group-hover:-translate-y-1 transition-transform animate-[pulse_3s_ease-in-out_infinite]">
          <i class="text-lg fas fa-exclamation-triangle"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Stok Menipis</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-red-600 leading-none">3</h4>
            <span class="text-[12px] font-semibold text-red-500 mb-1 flex items-center gap-1"><i class="fas fa-arrow-down text-[8px]"></i> Jenis</span>
          </div>
        </div>
      </div>

      <!-- Card 4: Antrean Belum Selesai (Amber) -->
      <div class="bg-white rounded-[20px] p-5.5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold shadow-sm border border-amber-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-hourglass-half"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Sisa Antrean</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none">5</h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Pasien</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Bottom Two Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
      
      <!-- Left side (Table): Riwayat Pemakaian -->
      <div class="lg:col-span-2 flex flex-col gap-4">
        <!-- Title -->
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-500"><i class="fas fa-history text-[13px]"></i></div>
              <h3 class="text-[16px] font-bold text-slate-800 font-display">Riwayat Pemakaian Anda Hari Ini</h3>
            </div>
            <a href="index.php?page=laporan" class="text-[12px] font-bold text-[#006B47] hover:text-[#10B981] transition-colors py-1.5 px-3 rounded-lg hover:bg-emerald-50">Lebih Detail &rarr;</a>
        </div>
        
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden relative">
          <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap">
              <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu</th>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pasien</th>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama BHP (Dipakai)</th>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Jumlah</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50 text-[13px]">
                <!-- Row 1 -->
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="px-6 py-4.5 font-bold text-slate-600 text-[12px]">09:45 WIB</td>
                  <td class="px-6 py-4.5 font-bold text-slate-800">
                    <div class="flex items-center gap-3">
                      <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">BS</div>
                      <span class="text-[13px]">Budi Santoso</span>
                    </div>
                  </td>
                  <td class="px-6 py-4.5 font-semibold text-slate-600">Spuit Kas Taruna</td>
                  <td class="px-6 py-4.5 text-center">
                    <span class="inline-block px-3 py-1.5 bg-[#F0FDF4] text-[#059669] rounded-md font-bold text-[11px] border border-[#A7F3D0]/40">2 Pcs</span>
                  </td>
                </tr>
                <!-- Row 2 -->
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="px-6 py-4.5 font-bold text-slate-600 text-[12px]">09:12 WIB</td>
                  <td class="px-6 py-4.5 font-bold text-slate-800">
                    <div class="flex items-center gap-3">
                      <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">AL</div>
                      <span class="text-[13px]">Anita Lestari</span>
                    </div>
                  </td>
                  <td class="px-6 py-4.5 font-semibold text-slate-600">Komposit Resin A3</td>
                  <td class="px-6 py-4.5 text-center">
                    <span class="inline-block px-3 py-1.5 bg-[#F0FDF4] text-[#059669] rounded-md font-bold text-[11px] border border-[#A7F3D0]/40">1 Tube</span>
                  </td>
                </tr>
                <!-- Row 3 -->
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="px-6 py-4.5 font-bold text-slate-600 text-[12px]">08:30 WIB</td>
                  <td class="px-6 py-4.5 font-bold text-slate-800">
                    <div class="flex items-center gap-3">
                      <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">FI</div>
                      <span class="text-[13px]">Fatkul Iman</span>
                    </div>
                  </td>
                  <td class="px-6 py-4.5 font-semibold text-slate-600">Cotton Roll</td>
                  <td class="px-6 py-4.5 text-center">
                    <span class="inline-block px-3 py-1.5 bg-[#F0FDF4] text-[#059669] rounded-md font-bold text-[11px] border border-[#A7F3D0]/40">5 Pcs</span>
                  </td>
                </tr>
                <!-- Row 4 -->
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="px-6 py-4.5 font-bold text-slate-600 text-[12px]">08:15 WIB</td>
                  <td class="px-6 py-4.5 font-bold text-slate-800">
                    <div class="flex items-center gap-3">
                      <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">-</div>
                      <span class="text-[13px] text-slate-400 italic">Tanpa Pasien</span>
                    </div>
                  </td>
                  <td class="px-6 py-4.5 font-semibold text-slate-600">Masker Bedah 3-Ply</td>
                  <td class="px-6 py-4.5 text-center">
                    <span class="inline-block px-3 py-1.5 bg-[#F0FDF4] text-[#059669] rounded-md font-bold text-[11px] border border-[#A7F3D0]/40">1 Box</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
            
            <div class="p-4 border-t border-slate-50 bg-slate-50/30 text-center">
              <span class="text-[12px] font-medium text-slate-400">Hanya menampilkan 4 data terakhir</span>
            </div>
        </div>
      </div>

      <!-- Right side: BHP Terbanyak Digunakan -->
      <div class="lg:col-span-1 flex flex-col gap-4">
        <div class="flex items-center gap-2 px-1">
          <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-500"><i class="fas fa-chart-pie text-[13px]"></i></div>
          <h3 class="text-[16px] font-bold text-slate-800 font-display">Top BHP Minggu Ini</h3>
        </div>
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 p-6 flex flex-col gap-6">
          
          <!-- item 1 -->
          <div>
            <div class="flex items-center justify-between mb-2.5">
              <span class="text-[13px] font-bold text-slate-800">Masker Bedah 3-Ply</span>
              <span class="text-[12px] font-bold text-slate-500">45 <span class="text-[10px] font-normal">Box</span></span>
            </div>
            <div class="w-full h-[6px] bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full" style="width: 85%; background: linear-gradient(90deg, #34D399, #006B47);"></div>
            </div>
          </div>

          <!-- item 2 -->
          <div>
            <div class="flex items-center justify-between mb-2.5">
              <span class="text-[13px] font-bold text-slate-800">Spuit Kas Taruna</span>
              <span class="text-[12px] font-bold text-slate-500">38 <span class="text-[10px] font-normal">Pcs</span></span>
            </div>
            <div class="w-full h-[6px] bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full" style="width: 70%; background: linear-gradient(90deg, #60A5FA, #2563EB);"></div>
            </div>
          </div>

          <!-- item 3 -->
          <div>
            <div class="flex items-center justify-between mb-2.5">
              <span class="text-[13px] font-bold text-slate-800">Pehacain Injection</span>
              <span class="text-[12px] font-bold text-slate-500">22 <span class="text-[10px] font-normal">Ampul</span></span>
            </div>
            <div class="w-full h-[6px] bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full" style="width: 45%; background: linear-gradient(90deg, #FBBF24, #D97706);"></div>
            </div>
          </div>
          
          <!-- item 4 -->
          <div>
            <div class="flex items-center justify-between mb-2.5">
              <span class="text-[13px] font-bold text-slate-800">Cotton Roll</span>
              <span class="text-[12px] font-bold text-slate-500">12 <span class="text-[10px] font-normal">Pack</span></span>
            </div>
            <div class="w-full h-[6px] bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full" style="width: 25%; background: linear-gradient(90deg, #A78BFA, #4F46E5);"></div>
            </div>
          </div>

          <!-- item 5 -->
          <div class="pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between mb-2.5">
              <span class="text-[13px] font-bold text-slate-800">Glove Latex (M)</span>
              <span class="text-[12px] font-bold text-slate-500">8 <span class="text-[10px] font-normal">Box</span></span>
            </div>
            <div class="w-full h-[6px] bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full bg-slate-400" style="width: 15%;"></div>
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>
</div>
