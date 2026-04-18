<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1000px] mx-auto w-full font-plex space-y-6">

    <!-- Header / Banner Area -->
    <div class="relative bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
      <!-- Gradient Cover -->
      <div class="w-full h-32 sm:h-40 bg-brand-600 relative" style="background: linear-gradient(135deg, #008D5B 0%, #00B47A 100%);">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
      </div>
      
      <!-- Avatar & Basic Info -->
      <div class="px-6 sm:px-10 pb-8 flex flex-col sm:flex-row items-center sm:items-end gap-6 sm:-mt-12 -mt-16 relative z-10 text-center sm:text-left">
        <!-- Avatar Wrapper -->
        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white bg-slate-100 shadow-md flex items-center justify-center text-4xl sm:text-5xl font-display font-bold text-white relative shrink-0" style="background: linear-gradient(135deg, #a8edea 0%, #5b9bd5 100%); color: #1e4a7a;">
          TT
          <div class="absolute bottom-1 right-1 w-6 h-6 rounded-full bg-emerald-500 border-2 border-white flex justify-center items-center">
             <i class="fas fa-check text-white text-[10px]"></i>
          </div>
        </div>
        
        <!-- Info Text -->
        <div class="flex-1 pb-1">
          <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-2">
            <h2 class="text-2xl sm:text-3xl font-display font-bold text-slate-800">Team Terserahhh</h2>
            <span class="inline-flex items-center justify-center px-3 py-1 bg-brand-50 text-brand-600 rounded-full text-xs font-bold border border-brand-100">
              Dokter Gigi
            </span>
          </div>
          <p class="text-sm font-medium text-slate-500 flex items-center justify-center sm:justify-start gap-2">
            <i class="fas fa-id-badge"></i> NIP: 198504232010121004 &nbsp;|&nbsp; <i class="fas fa-clinic-medical"></i> Poli Utama
          </p>
        </div>
        
        <!-- Action Button -->
        <div class="pb-2">
          <button class="bg-slate-50 text-slate-700 border border-slate-200 px-5 py-2.5 rounded-xl font-bold text-[13px] hover:bg-slate-100 transition-colors shadow-sm flex items-center gap-2">
            <i class="fas fa-camera"></i> Ubah Foto
          </button>
        </div>
      </div>
    </div>

    <!-- Main Profil Form -->
    <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 p-6 sm:p-8">
      <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
        <h3 class="text-lg font-bold text-slate-800"><i class="far fa-user-circle text-brand-500 mr-2"></i> Informasi Pribadi</h3>
        <button class="text-brand-600 text-sm font-bold hover:text-brand-800 transition-colors flex items-center gap-1.5">
          <i class="fas fa-edit text-xs"></i> Edit Data
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
        <!-- Nama Lengkap -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap & Gelar</label>
          <input type="text" value="drg. Andi Pratama, Sp.KG" disabled class="w-full bg-slate-50 border border-slate-100 rounded-xl h-11 px-4 text-[14px] text-slate-800 font-semibold focus:outline-none disabled:opacity-80">
        </div>

        <!-- Role -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Hak Akses Sistem</label>
          <input type="text" value="Dokter" disabled class="w-full bg-slate-50 border border-slate-100 rounded-xl h-11 px-4 text-[14px] text-slate-800 font-semibold focus:outline-none disabled:opacity-80">
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Alamat Email</label>
          <div class="relative">
            <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="email" value="andi.pratama@klinikpratama.com" class="w-full border border-slate-200 bg-white rounded-xl h-11 pl-10 pr-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
          </div>
        </div>

        <!-- Phone -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon / WhatsApp</label>
          <div class="relative">
            <i class="fas fa-phone-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" value="+62 812-3456-7890" class="w-full border border-slate-200 bg-white rounded-xl h-11 pl-10 pr-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
          </div>
        </div>
        
        <!-- Jenis Kelamin -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Jenis Kelamin</label>
          <select class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
            <option>Laki-laki</option>
            <option>Perempuan</option>
          </select>
        </div>

        <!-- Tanggal Bergabung -->
        <div class="flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tahun Bergabung</label>
          <input type="text" value="Maret 2021 (3 Tahun)" disabled class="w-full bg-slate-50 border border-slate-100 rounded-xl h-11 px-4 text-[14px] text-slate-800 font-semibold focus:outline-none disabled:opacity-80">
        </div>
      </div>

      <!-- Action Footer -->
      <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end">
        <button class="bg-brand-600 text-white px-8 py-3 rounded-xl font-bold text-[14px] hover:bg-brand-700 transition shadow-sm border border-brand-700 flex items-center gap-2">
          <i class="fas fa-save"></i> Simpan Perubahan
        </button>
      </div>

    </div>
  </div>
</div>
