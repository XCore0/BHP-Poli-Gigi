<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1000px] mx-auto w-full font-plex">
    
    <div class="mb-8">
      <h2 class="text-2xl sm:text-3xl font-display font-bold text-slate-800 tracking-tight">Pengaturan</h2>
      <p class="text-slate-500 text-sm font-medium mt-1">Kelola kata sandi, notifikasi, dan preferensi akun Anda.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
      
      <!-- Setting Tabs Sidebar (Desktop) / Dropdown (Mobile) -->
      <div class="w-full lg:w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-6">
          <nav class="flex flex-col">
            <a href="#" class="px-5 py-4 border-l-4 border-brand-500 bg-brand-50/50 text-brand-700 font-bold text-[14px] flex items-center gap-3">
              <i class="fas fa-shield-alt w-5 text-center"></i> Keinginan / Keamanan
            </a>
            <a href="#" class="px-5 py-4 border-l-4 border-transparent text-slate-600 font-semibold text-[14px] flex items-center gap-3 hover:bg-slate-50 transition-colors">
              <i class="far fa-bell w-5 text-center"></i> Notifikasi Sistem
            </a>
            <a href="#" class="px-5 py-4 border-l-4 border-transparent text-slate-600 font-semibold text-[14px] flex items-center gap-3 hover:bg-slate-50 transition-colors">
              <i class="fas fa-desktop w-5 text-center"></i> Tampilan (Tema)
            </a>
          </nav>
        </div>
      </div>

      <!-- Settings Content Area -->
      <div class="flex-1 flex flex-col gap-6">
        
        <!-- CARD: Ganti Password -->
        <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
          <div class="p-6 border-b border-slate-100">
            <h3 class="text-[16px] font-bold text-slate-800 font-display">Keamanan Akun</h3>
            <p class="text-[12px] text-slate-500 font-medium">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun.</p>
          </div>
          <div class="p-6 sm:p-8 flex flex-col gap-5">
            <div>
               <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Kata Sandi Saat Ini</label>
               <input type="password" placeholder="Masukkan kata sandi lama" class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 text-[14px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
            </div>
            
            <div class="w-full h-px bg-slate-100 my-1"></div>

            <div>
               <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Kata Sandi Baru</label>
               <input type="password" placeholder="Masukkan kata sandi baru" class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 text-[14px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
               <p class="text-[11px] text-slate-400 mt-2 font-medium">Minimal 8 karakter berupa kombinasi huruf dan angka.</p>
            </div>
            
            <div>
               <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Konfirmasi Kata Sandi Baru</label>
               <input type="password" placeholder="Ulangi kata sandi baru" class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 text-[14px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
            </div>

            <div class="pt-4 flex justify-end">
               <button class="bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] hover:bg-slate-900 transition shadow-sm">
                 Perbarui Kata Sandi
               </button>
            </div>
          </div>
        </div>

        <!-- CARD: Preferensi Notifikasi -->
        <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
          <div class="p-6 border-b border-slate-100">
            <h3 class="text-[16px] font-bold text-slate-800 font-display">Pengaturan Notifikasi</h3>
            <p class="text-[12px] text-slate-500 font-medium">Pilih aktivitas apa saja yang ingin Anda terima sebagai notifikasi.</p>
          </div>
          <div class="p-6 sm:p-8 flex flex-col gap-6">
            
            <!-- Toggle Item 1 -->
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-[14px] font-bold text-slate-800 mb-0.5">Peringatan Stok Kurang</h4>
                <p class="text-[12px] text-slate-500 font-medium">Berikan peringatan "Push" di aplikasi ketika stok BHP menyentuh batas minimum.</p>
              </div>
              <label class="relative inline-flex flex-shrink-0 cursor-pointer items-center">
                <input type="checkbox" class="peer sr-only" checked>
                <div class="h-6 w-11 rounded-full bg-slate-200 peer-checked:bg-brand-500 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white shadow-inner"></div>
              </label>
            </div>

            <!-- Toggle Item 2 -->
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-[14px] font-bold text-slate-800 mb-0.5">Laporan Otomatis</h4>
                <p class="text-[12px] text-slate-500 font-medium">Kirimkan saya ringkasan pemakaian BHP harian via Email saat sore hari.</p>
              </div>
              <label class="relative inline-flex flex-shrink-0 cursor-pointer items-center">
                <input type="checkbox" class="peer sr-only">
                <div class="h-6 w-11 rounded-full bg-slate-200 peer-checked:bg-brand-500 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white shadow-inner"></div>
              </label>
            </div>

          </div>
        </div>

      </div>
    </div>

  </div>
</div>
