<?php
use App\Classes\UserManager;
use App\Classes\Auth;

$auth  = new Auth();
$user  = $auth->getCurrentUser();
$uid   = (int)($user['id'] ?? 0);
$mgr   = new UserManager();
$pref  = $mgr->getPreferensi($uid);
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1000px] mx-auto w-full font-plex">

    <!-- Toast -->
    <div id="toastSet" class="fixed top-6 right-6 z-[200] hidden">
      <div id="toastSetInner" class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs">
        <i id="toastSetIcon" class="text-[15px]"></i>
        <span id="toastSetMsg"></span>
      </div>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl sm:text-3xl font-display font-bold text-slate-800 tracking-tight">Pengaturan</h2>
      <p class="text-slate-500 text-sm font-medium mt-1">Kelola kata sandi, notifikasi, dan preferensi akun Anda.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

      <!-- Sidebar Nav -->
      <div class="w-full lg:w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-6">
          <nav class="flex flex-col">
            <a href="#sec-keamanan" onclick="showSection('keamanan')"
              id="nav-keamanan"
              class="nav-tab px-5 py-4 border-l-4 border-brand-500 bg-brand-50/50 text-brand-700 font-bold text-[14px] flex items-center gap-3">
              <i class="fas fa-shield-alt w-5 text-center"></i> Keamanan Akun
            </a>
            <a href="#sec-notif" onclick="showSection('notif')"
              id="nav-notif"
              class="nav-tab px-5 py-4 border-l-4 border-transparent text-slate-600 font-semibold text-[14px] flex items-center gap-3 hover:bg-slate-50 transition-colors">
              <i class="far fa-bell w-5 text-center"></i> Notifikasi Sistem
            </a>
          </nav>
        </div>
      </div>

      <!-- Content Area -->
      <div class="flex-1 flex flex-col gap-6">

        <!-- SECTION: Keamanan / Ganti Password -->
        <div id="sec-keamanan">
          <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h3 class="text-[16px] font-bold text-slate-800 font-display">Keamanan Akun</h3>
              <p class="text-[12px] text-slate-500 font-medium">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun.</p>
            </div>
            <form id="formPassword" onsubmit="gantiPassword(event)" class="p-6 sm:p-8 flex flex-col gap-5">
              <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Kata Sandi Saat Ini</label>
                <div class="relative">
                  <input type="password" name="password_lama" id="inputPassLama" required placeholder="Masukkan kata sandi lama"
                    class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 pr-12 text-[14px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                  <button type="button" onclick="togglePass('inputPassLama','eyeLama')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i id="eyeLama" class="fas fa-eye-slash text-[14px]"></i>
                  </button>
                </div>
              </div>

              <div class="w-full h-px bg-slate-100"></div>

              <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Kata Sandi Baru</label>
                <div class="relative">
                  <input type="password" name="password_baru" id="inputPassBaru" required placeholder="Minimal 8 karakter"
                    class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 pr-12 text-[14px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                  <button type="button" onclick="togglePass('inputPassBaru','eyeBaru')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i id="eyeBaru" class="fas fa-eye-slash text-[14px]"></i>
                  </button>
                </div>
                <p class="text-[11px] text-slate-400 mt-2 font-medium">Minimal 8 karakter, kombinasi huruf dan angka.</p>
              </div>

              <div>
                <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                  <input type="password" name="konfirmasi_password" id="inputPassKonfirm" required placeholder="Ulangi kata sandi baru"
                    class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 pr-12 text-[14px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
                  <button type="button" onclick="togglePass('inputPassKonfirm','eyeKonfirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i id="eyeKonfirm" class="fas fa-eye-slash text-[14px]"></i>
                  </button>
                </div>
              </div>

              <!-- Strength indicator -->
              <div id="strengthBar" class="hidden">
                <div class="flex gap-1 mt-1">
                  <div id="s1" class="h-1.5 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                  <div id="s2" class="h-1.5 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                  <div id="s3" class="h-1.5 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                  <div id="s4" class="h-1.5 flex-1 rounded-full bg-slate-200 transition-colors"></div>
                </div>
                <p id="strengthLabel" class="text-[11px] font-medium mt-1 text-slate-400"></p>
              </div>

              <div class="pt-4 flex justify-end">
                <button type="submit" id="btnGantiPass"
                  class="bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] hover:bg-slate-900 transition shadow-sm flex items-center gap-2">
                  <i class="fas fa-key text-[11px]"></i> Perbarui Kata Sandi
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- SECTION: Notifikasi -->
        <div id="sec-notif">
          <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
              <h3 class="text-[16px] font-bold text-slate-800 font-display">Pengaturan Notifikasi</h3>
              <p class="text-[12px] text-slate-500 font-medium">Pilih aktivitas yang ingin Anda terima sebagai notifikasi.</p>
            </div>
            <form id="formNotif" onsubmit="simpanNotif(event)" class="p-6 sm:p-8 flex flex-col gap-6">

              <!-- Toggle: Stok Kurang -->
              <div class="flex items-center justify-between gap-4">
                <div>
                  <h4 class="text-[14px] font-bold text-slate-800 mb-0.5">Peringatan Stok Kurang</h4>
                  <p class="text-[12px] text-slate-500 font-medium">Tampilkan peringatan ketika stok BHP menyentuh batas minimum.</p>
                </div>
                <label class="relative inline-flex flex-shrink-0 cursor-pointer items-center">
                  <input type="checkbox" name="notif_stok_kurang" class="peer sr-only" <?= $pref['notif_stok_kurang'] ? 'checked' : '' ?>>
                  <div class="h-6 w-11 rounded-full bg-slate-200 peer-checked:bg-brand-500 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full shadow-inner"></div>
                </label>
              </div>

              <!-- Toggle: Laporan Harian -->
              <div class="flex items-center justify-between gap-4">
                <div>
                  <h4 class="text-[14px] font-bold text-slate-800 mb-0.5">Laporan Pemakaian Harian</h4>
                  <p class="text-[12px] text-slate-500 font-medium">Tampilkan ringkasan pemakaian BHP setiap hari di dashboard.</p>
                </div>
                <label class="relative inline-flex flex-shrink-0 cursor-pointer items-center">
                  <input type="checkbox" name="notif_laporan_harian" class="peer sr-only" <?= $pref['notif_laporan_harian'] ? 'checked' : '' ?>>
                  <div class="h-6 w-11 rounded-full bg-slate-200 peer-checked:bg-brand-500 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full shadow-inner"></div>
                </label>
              </div>

              <div class="pt-2 flex justify-end">
                <button type="submit" id="btnSimpanNotif"
                  class="bg-brand-600 text-white px-6 py-2.5 rounded-xl font-bold text-[13px] hover:bg-brand-700 transition shadow-sm flex items-center gap-2">
                  <i class="fas fa-save text-[11px]"></i> Simpan Preferensi
                </button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
function showToastSet(msg, ok=true) {
  const t=document.getElementById('toastSet');
  const i=document.getElementById('toastSetInner');
  const ic=document.getElementById('toastSetIcon');
  document.getElementById('toastSetMsg').textContent=msg;
  if(ok){i.className='flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-emerald-500 text-white';ic.className='fas fa-check-circle text-[15px]';}
  else{i.className='flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-red-500 text-white';ic.className='fas fa-exclamation-circle text-[15px]';}
  t.classList.remove('hidden');
  setTimeout(()=>t.classList.add('hidden'),3500);
}

// Tab navigation
function showSection(sec) {
  ['keamanan','notif'].forEach(s => {
    document.getElementById('nav-'+s).className = 'nav-tab px-5 py-4 border-l-4 border-transparent text-slate-600 font-semibold text-[14px] flex items-center gap-3 hover:bg-slate-50 transition-colors';
  });
  document.getElementById('nav-'+sec).className = 'nav-tab px-5 py-4 border-l-4 border-brand-500 bg-brand-50/50 text-brand-700 font-bold text-[14px] flex items-center gap-3';
}

// Toggle password visibility
function togglePass(inputId, iconId) {
  const inp = document.getElementById(inputId);
  const ic  = document.getElementById(iconId);
  if (inp.type === 'password') { inp.type = 'text'; ic.className = 'fas fa-eye text-[14px]'; }
  else { inp.type = 'password'; ic.className = 'fas fa-eye-slash text-[14px]'; }
}

// Password strength indicator
document.getElementById('inputPassBaru').addEventListener('input', function() {
  const v = this.value;
  const bar = document.getElementById('strengthBar');
  if (v.length === 0) { bar.classList.add('hidden'); return; }
  bar.classList.remove('hidden');
  let score = 0;
  if (v.length >= 8)  score++;
  if (/[A-Z]/.test(v)) score++;
  if (/[0-9]/.test(v)) score++;
  if (/[^A-Za-z0-9]/.test(v)) score++;
  const colors = ['bg-red-400','bg-orange-400','bg-yellow-400','bg-emerald-500'];
  const labels = ['Lemah','Cukup','Baik','Kuat'];
  for (let i=1; i<=4; i++) {
    const el = document.getElementById('s'+i);
    el.className = 'h-1.5 flex-1 rounded-full transition-colors ' + (i <= score ? colors[score-1] : 'bg-slate-200');
  }
  document.getElementById('strengthLabel').textContent = labels[score-1] || '';
});

// Ganti Password
function gantiPassword(e) {
  e.preventDefault();
  const btn = document.getElementById('btnGantiPass');
  const fd  = new FormData(document.getElementById('formPassword'));
  fd.append('action', 'ganti_password');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin text-[11px]"></i> Memperbarui...';
  fetch('/BHP-Poli-Gigi/process/profil_process.php', {method:'POST', body:fd, credentials:'same-origin'})
  .then(r=>r.json()).then(res=>{
    showToastSet(res.message, res.success);
    if (res.success) document.getElementById('formPassword').reset();
  }).catch(()=>showToastSet('Koneksi gagal.', false))
  .finally(()=>{btn.disabled=false; btn.innerHTML='<i class="fas fa-key text-[11px]"></i> Perbarui Kata Sandi';});
}

// Simpan Notifikasi
function simpanNotif(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSimpanNotif');
  const fd  = new FormData(document.getElementById('formNotif'));
  fd.append('action', 'save_preferensi');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin text-[11px]"></i> Menyimpan...';
  fetch('/BHP-Poli-Gigi/process/profil_process.php', {method:'POST', body:fd, credentials:'same-origin'})
  .then(r=>r.json()).then(res=>{
    showToastSet(res.message, res.success);
  }).catch(()=>showToastSet('Koneksi gagal.', false))
  .finally(()=>{btn.disabled=false; btn.innerHTML='<i class="fas fa-save text-[11px]"></i> Simpan Preferensi';});
}
</script>
