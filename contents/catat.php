<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto flex flex-col lg:flex-row gap-6 lg:gap-8 w-full font-plex">

    <!-- LEFT COLUMN: Main Form -->
    <div class="flex-1 flex flex-col">
      <!-- Title -->
      <h2 class="text-2xl font-display font-semibold text-slate-800 tracking-tight mb-5">Catat Pemakaian</h2>

      <!-- Form Card -->
      <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 p-6 sm:p-8">
        
        <!-- Header text -->
        <h3 class="text-lg font-bold text-slate-800 mb-1">Catat Pemakaian BHP</h3>
        <p class="text-[13px] text-slate-500 mb-8 font-medium">Catat penggunaan Bahan Habis Pakai (BHP) untuk setiap tindakan medis.</p>

        <!-- Form Top Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
          <div>
            <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Pemakaian <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="date" value="2023-10-27" class="w-full border border-slate-200 bg-slate-50/50 rounded-xl h-12 px-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
            </div>
          </div>
          <div>
            <label class="block text-[12px] font-bold text-slate-600 mb-2">Nama Dokter <span class="text-red-500">*</span></label>
            <div class="relative">
              <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <select class="w-full border border-slate-200 bg-slate-50/50 rounded-xl h-12 pl-10 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                <option>Dr. Andi Pratama</option>
              </select>
              <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
          <div>
            <label class="block text-[12px] font-bold text-slate-600 mb-2">Jenis Tindakan <span class="text-red-500">*</span></label>
            <select class="w-full border border-slate-200 bg-slate-50/50 rounded-xl h-12 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
              <option>BHP Gawat Darurat (UGD)</option>
            </select>
            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none mt-3.5"></i>
          </div>
          <div>
            <label class="block text-[12px] font-bold text-slate-600 mb-2">Lokasi / Ruangan <span class="text-red-500">*</span></label>
            <select class="w-full border border-slate-200 bg-slate-50/50 rounded-xl h-12 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
              <option>Bed 1 - UGD</option>
            </select>
            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none mt-3.5"></i>
          </div>
        </div>

        <div class="mb-10">
          <div class="flex items-center justify-between mb-2">
            <label class="text-[12px] font-bold text-slate-600">Nama Pasien</label>
            <span class="text-[11px] text-slate-400 font-medium">(Opsional)</span>
          </div>
          <input type="text" placeholder="Cari ID atau nama pasien..." class="w-full border border-slate-200 bg-slate-50/50 rounded-xl h-12 px-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors placeholder:font-normal placeholder:text-slate-400">
        </div>

        <!-- Section: Tambah BHP -->
        <div class="border-t border-slate-100 pt-8 mb-8">
          <h4 class="text-[15px] font-bold text-slate-800 flex items-center gap-2.5 mb-6">
            <i class="fas fa-pen text-blue-500 text-[14px]"></i> Tambah BHP Digunakan
          </h4>
          
          <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 sm:p-5 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1">
                <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Pilih BHP</label>
                <div class="relative">
                  <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[13px]"></i>
                  <select class="w-full border border-slate-200 bg-white rounded-xl h-11 pl-10 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                    <option>Spuit Kas Taruna</option>
                  </select>
                  <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                </div>
              </div>
              <div class="w-full sm:w-[140px]">
                <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Jumlah</label>
                <div class="relative">
                  <input type="number" value="2" class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 pr-12 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
                  <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[12px] font-medium pointer-events-none">Pcs</span>
                </div>
              </div>
              <div class="w-full sm:w-auto flex items-end">
                <button class="w-full sm:w-auto h-11 px-5 rounded-xl text-[13px] font-bold text-blue-600 bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
                  <i class="fas fa-plus text-[11px]"></i> Tambah
                </button>
              </div>
            </div>
          </div>

          <!-- Added Items Table -->
          <div class="border border-slate-100 rounded-2xl overflow-hidden mb-6">
            <div class="overflow-x-auto w-full flex-1">
            <table class="w-full text-left whitespace-nowrap">
              <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                  <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-widest w-full">Nama BHP</th>
                  <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Jumlah</th>
                  <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Satuan</th>
                  <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-[13px]">
                <tr>
                  <td class="px-5 py-4 font-semibold text-slate-800">NaCl 0.9% 500ml - Otsuka</td>
                  <td class="px-5 py-4 text-center">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-bold mx-auto">1</span>
                  </td>
                  <td class="px-5 py-4 text-center text-slate-500 font-medium">Botol</td>
                  <td class="px-5 py-4 text-center">
                    <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt"></i></button>
                  </td>
                </tr>
                <tr>
                  <td class="px-5 py-4 font-semibold text-slate-800">IV Catheter 20G</td>
                  <td class="px-5 py-4 text-center">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-bold mx-auto">1</span>
                  </td>
                  <td class="px-5 py-4 text-center text-slate-500 font-medium">Pcs</td>
                  <td class="px-5 py-4 text-center">
                    <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt"></i></button>
                  </td>
                </tr>
                <tr>
                  <td class="px-5 py-4 font-semibold text-slate-800">Spuit Kas Taruna</td>
                  <td class="px-5 py-4 text-center">
                    <span class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 font-bold mx-auto">2</span>
                  </td>
                  <td class="px-5 py-4 text-center text-slate-500 font-medium">Pcs</td>
                  <td class="px-5 py-4 text-center">
                    <button class="text-red-400 hover:text-red-600 transition-colors"><i class="far fa-trash-alt"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>

          <div>
            <label class="block text-[12px] font-bold text-slate-600 mb-2">Catatan Tambahan</label>
            <textarea placeholder="Tambahkan keterangan jika diperlukan..." class="w-full border border-slate-200 bg-slate-50/50 rounded-xl max-h-32 min-h-[5rem] px-4 py-3 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors placeholder:font-normal placeholder:text-slate-400"></textarea>
          </div>
        </div>

        <!-- Section: Pemakaian Details (Satuan Unit) -->
        <div class="border border-slate-100 rounded-2xl p-5 sm:p-6 mb-8 relative overflow-hidden">
          <div class="absolute inset-0 bg-slate-50/30"></div>
          <div class="relative z-10">
            <h4 class="text-[14px] font-bold text-slate-800 mb-5">Pemakaian</h4>
            
            <div class="flex flex-col xl:flex-row gap-6 lg:gap-10">
              
              <!-- Left side inputs -->
              <div class="flex-1 flex flex-col gap-5">
                <div>
                  <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Satuan Unit</label>
                  <div class="inline-flex bg-slate-100 rounded-xl p-1">
                    <button class="px-5 py-1.5 rounded-lg text-[12px] font-bold bg-white text-slate-800 shadow-sm">BOX</button>
                    <button class="px-5 py-1.5 rounded-lg text-[12px] font-bold text-slate-500 hover:text-slate-700 transition">PCS</button>
                    <button class="px-5 py-1.5 rounded-lg text-[12px] font-bold text-slate-500 hover:text-slate-700 transition">STRIP</button>
                  </div>
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Nama Barang</label>
                  <div class="relative max-w-sm">
                    <select class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                      <option>NaCl 0.9% 500ml - Otsuka</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                  </div>
                </div>

                <div>
                  <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Jumlah Pemakaian</label>
                  <div class="flex items-center gap-3">
                    <button class="w-10 h-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:text-slate-600 transition"><i class="fas fa-minus text-[10px]"></i></button>
                    <input type="text" value="1" class="w-16 h-10 text-center border font-bold text-[15px] border-slate-200 bg-white rounded-xl text-slate-700 outline-none focus:border-brand-500 transition-colors">
                    <button class="w-10 h-10 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:text-slate-600 transition"><i class="fas fa-plus text-[10px]"></i></button>
                  </div>
                </div>
              </div>

              <!-- Right side Panels -->
              <div class="flex-1 flex flex-col sm:flex-row gap-4">
                
                <!-- Konversi Box -->
                <div class="flex-1 bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                  <div class="flex items-center gap-2 text-[12px] font-bold text-slate-600 mb-3">
                    <i class="fa-solid fa-circle-notch text-slate-400"></i> Konversi unit
                  </div>
                  <div class="flex flex-col gap-2 mb-4">
                    <div class="bg-slate-50 px-3 py-2 rounded-lg text-[13px] font-bold text-slate-600 border border-slate-100 flex justify-between">
                      <span>1 BOX</span> <span class="text-slate-400">= 100 PCS</span>
                    </div>
                    <div class="bg-slate-50 px-3 py-2 rounded-lg text-[13px] font-bold text-slate-600 border border-slate-100 flex justify-between">
                      <span>1 STR</span> <span class="text-slate-400">= 100 PCS</span>
                    </div>
                  </div>
                  <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide leading-relaxed">
                    Pengurangan akan dipotong pada stok <span class="text-blue-500">1.200 PCS</span>
                  </p>
                </div>

                <!-- Warning Box -->
                <div class="flex-1 bg-red-50 border border-red-100 rounded-2xl p-4 relative overflow-hidden">
                  <!-- Red left border accent -->
                  <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                  <div class="flex gap-3">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg mt-0.5"></i>
                    <div>
                      <h5 class="text-[12px] font-bold text-red-800 mb-1 leading-tight tracking-wide">PERINGATAN STOK RENDAH</h5>
                      <p class="text-[11px] text-red-600/90 font-medium leading-relaxed">
                        Stok di bawah batas minimum.<br>
                        Tersisa <span class="font-bold">5 PCS</span>. Disarankan melakukan restock segera.
                      </p>
                    </div>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>

        <!-- Section: Tanggal Kadaluarsa -->
        <div class="border border-slate-200 bg-slate-50/30 rounded-2xl p-6 sm:p-8 mb-8">
          <h4 class="text-[16px] font-bold text-slate-800 mb-6">Form Tanggal Kadaluarsa BHP</h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Kadaluarsa <span class="text-red-500">*</span></label>
              <div class="relative">
                <input type="date" value="2023-12-12" class="w-full border border-slate-200 bg-white rounded-xl h-12 px-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
              </div>
            </div>
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Nomor Batch <span class="text-red-500">*</span></label>
              <div class="relative">
                <select class="w-full border border-slate-200 bg-white rounded-xl h-12 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                  <option>- Pilih Nomor Batch -</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Kategori Risiko <span class="text-red-500">*</span></label>
              <div class="relative">
                <select class="w-full border border-slate-200 bg-white rounded-xl h-12 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                  <option>Pilih Kategori</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
              </div>
            </div>
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Instruksi Penyimpanan <span class="text-red-500">*</span></label>
              <div class="relative">
                <select class="w-full border border-slate-200 bg-white rounded-xl h-12 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                  <option>Suhu Ruang (15-25°C)</option>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
              </div>
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="text-[12px] font-bold text-slate-600">Catatan Tambahan</label>
              <span class="text-[11px] text-slate-400 font-medium">(Opsional)</span>
            </div>
            <textarea placeholder="Tambahkan keterangan jika diperlukan..." class="w-full border border-slate-200 bg-white rounded-xl max-h-32 min-h-[5rem] px-4 py-3 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors placeholder:text-slate-400 placeholder:font-normal"></textarea>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button class="h-12 px-8 rounded-xl text-[14px] font-bold text-white transition-opacity hover:opacity-90 active:scale-[0.98] shadow-md flex items-center gap-2" style="background: linear-gradient(135deg, #00B47A 0%, #008D5B 100%);">
            <i class="fas fa-save text-[12px]"></i> Simpan Catatan Pemakaian
          </button>
        </div>

      </div>
    </div>
    
    <!-- RIGHT COLUMN -->
    <div class="w-full lg:w-[320px] xl:w-[360px] flex flex-col gap-6 shrink-0 mt-2 lg:mt-12">
      
      <!-- Informasi BHP Card -->
      <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-50 flex gap-3 items-center">
          <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center font-bold shadow-sm">
            <i class="fas fa-box-open text-[15px]"></i>
          </div>
          <div>
            <h4 class="font-bold text-[14px] text-slate-800">Informasi BHP</h4>
            <p class="text-[11px] text-slate-400 font-medium">Detail data terpilih</p>
          </div>
        </div>
        
        <div class="p-5 flex flex-col gap-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-slate-500">
              <i class="fas fa-cube text-[12px] w-4 text-center"></i>
              <span class="text-[12px] font-medium">Nama BHP</span>
            </div>
            <span class="text-[13px] font-bold text-slate-800">Spuit Kas Taruna</span>
          </div>
          
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-slate-500">
              <i class="fas fa-tags text-[12px] w-4 text-center"></i>
              <span class="text-[12px] font-medium">Kategori</span>
            </div>
            <div class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-[11px] font-bold">
              Alat Suntik
            </div>
          </div>
          
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-slate-500">
              <i class="fas fa-boxes text-[12px] w-4 text-center"></i>
              <span class="text-[12px] font-medium">Stok Saat ini</span>
            </div>
            <span class="text-[13px] font-bold text-slate-800">150 <span class="text-[11px] font-medium text-slate-400">Pcs</span></span>
          </div>
          
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-slate-500">
              <i class="fas fa-arrow-down text-[12px] w-4 text-center"></i>
              <span class="text-[12px] font-medium">Stok Minimum</span>
            </div>
            <span class="text-[13px] font-semibold text-slate-600">50 <span class="text-[11px] font-medium text-slate-400">Pcs</span></span>
          </div>
          
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-slate-500">
              <i class="fas fa-heartbeat text-[12px] w-4 text-center"></i>
              <span class="text-[12px] font-medium">Status Stok</span>
            </div>
            <div class="px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-600 text-[11px] font-bold flex items-center gap-1.5">
              <i class="fas fa-check-circle text-[10px]"></i> Aman
            </div>
          </div>
          
          <div class="flex items-center justify-between pt-1">
            <div class="flex items-center gap-2 text-slate-500">
              <i class="far fa-calendar-alt text-[12px] w-4 text-center"></i>
              <span class="text-[12px] font-medium">Exp. Terdekat</span>
            </div>
            <span class="text-[13px] font-bold text-amber-600">12 Dec 2026</span>
          </div>
        </div>
      </div>

      <!-- Pemakaian Aktif Card -->
      <div class="rounded-[24px] p-6 shadow-xl relative overflow-hidden" style="background: linear-gradient(145deg, #1A2436 0%, #101622 100%);">
        <!-- Abstract shape decoration -->
        <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none w-32 h-32 bg-white rounded-tl-full"></div>
        <div class="absolute -right-10 -top-10 opacity-5 pointer-events-none w-40 h-40 border-8 border-white rounded-full"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center">
          <p class="text-[12px] font-bold text-slate-300 mb-1 opacity-90">Pemakaian Aktif Hari ini</p>
          <div class="flex items-end justify-center gap-2 mt-2">
            <span class="text-[42px] font-display font-bold text-white leading-none tracking-tight">124</span>
            <span class="text-[13px] font-medium text-slate-400 mb-1.5">Item Total</span>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>
