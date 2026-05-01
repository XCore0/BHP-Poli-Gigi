<?php
use App\Classes\UserManager;
use App\Classes\Auth;

$auth   = new Auth();
$user   = $auth->getCurrentUser();
$uid    = (int)($user['id'] ?? 0);
$mgr    = new UserManager();
$profil = $mgr->getUserById($uid) ?? [];

$nama    = $profil['Nama_lengkap'] ?? $user['nama'] ?? 'User';
$words   = explode(' ', trim($nama));
$initial = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
$email   = $profil['Email']          ?? $user['email'] ?? '';
$role    = $profil['Role']           ?? $user['role']  ?? 'dokter';
$roleLabel = ['admin'=>'Administrator','dokter'=>'Dokter Gigi','kepala_klinik'=>'Kepala Klinik'][$role] ?? ucfirst($role);
$telp    = $profil['No_telp']        ?? '';
$gender  = $profil['Jenis_kelamin']  ?? '';
$tglGabung = $profil['Tanggal_bergabung'] ? date('d M Y', strtotime($profil['Tanggal_bergabung'])) : '-';
$fotoUrl   = $profil['Foto']         ?? '';
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1000px] mx-auto w-full font-plex space-y-6">

    <!-- Toast -->
    <div id="toastProfil" class="fixed top-6 right-6 z-[200] hidden">
      <div id="toastProfilInner" class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs">
        <i id="toastProfilIcon" class="text-[15px]"></i>
        <span id="toastProfilMsg"></span>
      </div>
    </div>

    <!-- â”€â”€ Header Banner â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div class="relative bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
      <div class="w-full h-32 sm:h-40 relative" style="background: linear-gradient(135deg, #008D5B 0%, #00B47A 100%);">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
      </div>

      <div class="px-6 sm:px-10 pb-8 flex flex-col sm:flex-row items-center sm:items-end gap-6 sm:-mt-14 -mt-16 relative z-10 text-center sm:text-left">

        <!-- Avatar / Foto -->
        <div class="relative shrink-0 group">
          <!-- Foto atau Inisial -->
          <div id="avatarWrapper"
            class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center text-3xl sm:text-4xl font-display font-bold"
            style="background: linear-gradient(135deg, #a8edea 0%, #5b9bd5 100%); color: #1e4a7a;">
            <?php if ($fotoUrl): ?>
              <img id="previewFoto" src="<?= htmlspecialchars($fotoUrl) ?>" alt="Foto Profil"
                class="w-full h-full object-cover object-center">
            <?php else: ?>
              <span id="avatarInitial"><?= htmlspecialchars($initial) ?></span>
              <img id="previewFoto" src="" alt="" class="w-full h-full object-cover object-center hidden">
            <?php endif; ?>
          </div>

          <!-- Overlay upload -->
          <label for="inputFoto"
            class="absolute inset-0 rounded-full bg-black/40 flex flex-col items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
            <i class="fas fa-camera text-white text-xl"></i>
            <span class="text-white text-[10px] font-bold">Ubah</span>
          </label>
          <input type="file" id="inputFoto" accept="image/*" class="hidden" onchange="uploadFoto(this)">

          <!-- Loading spinner overlay -->
          <div id="fotoLoading" class="absolute inset-0 rounded-full bg-black/50 hidden items-center justify-center">
            <i class="fas fa-spinner fa-spin text-white text-xl"></i>
          </div>
        </div>

        <!-- Info Teks -->
        <div class="flex-1 pb-1">
          <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-2">
            <h2 id="displayNama" class="text-2xl sm:text-3xl font-display font-bold text-slate-800"><?= htmlspecialchars($nama) ?></h2>
            <span class="inline-flex items-center justify-center px-3 py-1 bg-brand-50 text-brand-600 rounded-full text-xs font-bold border border-brand-100">
              <?= htmlspecialchars($roleLabel) ?>
            </span>
          </div>
          <p class="text-sm font-medium text-slate-500 flex items-center justify-center sm:justify-start gap-2 flex-wrap">
            <span><i class="far fa-envelope mr-1"></i><?= htmlspecialchars($email) ?></span>
            <?= $telp ? '<span><i class="fas fa-phone-alt mr-1"></i>' . htmlspecialchars($telp) . '</span>' : '' ?>
          </p>
          <p class="text-[11px] text-slate-400 mt-1.5 font-medium">
            <i class="fas fa-camera mr-1"></i> Arahkan kursor ke foto untuk menggantinya Â· Maks 2MB (JPG/PNG/WebP)
          </p>
        </div>
      </div>
    </div>

    <!-- â”€â”€ Form Informasi Pribadi â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 p-6 sm:p-8">
      <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
        <h3 class="text-lg font-bold text-slate-800"><i class="far fa-user-circle text-brand-500 mr-2"></i> Informasi Pribadi</h3>
      </div>

      <form id="formProfil" onsubmit="simpanProfil(event)">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

          <!-- Nama -->
          <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required
              class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 text-[14px] text-slate-800 font-semibold outline-none focus:border-brand-500 transition-colors">
          </div>

          <!-- Role -->
          <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Hak Akses Sistem</label>
            <input type="text" value="<?= htmlspecialchars($roleLabel) ?>" disabled
              class="w-full bg-slate-50 border border-slate-100 rounded-xl h-11 px-4 text-[14px] text-slate-600 font-semibold focus:outline-none opacity-80 cursor-not-allowed">
          </div>

          <!-- Email -->
          <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Alamat Email</label>
            <div class="relative">
              <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required
                class="w-full border border-slate-200 bg-white rounded-xl h-11 pl-10 pr-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
            </div>
          </div>

          <!-- No. Telepon -->
          <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon / WhatsApp</label>
            <div class="relative">
              <i class="fas fa-phone-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="text" name="no_telp" value="<?= htmlspecialchars($telp) ?>" placeholder="+62 8xx-xxxx-xxxx"
                class="w-full border border-slate-200 bg-white rounded-xl h-11 pl-10 pr-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
            </div>
          </div>

          <!-- Jenis Kelamin -->
          <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Jenis Kelamin</label>
            <div class="relative">
              <select name="jenis_kelamin"
                class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                <option value="">â€” Pilih â€”</option>
                <option value="Laki-laki"  <?= $gender==='Laki-laki'  ? 'selected':'' ?>>Laki-laki</option>
                <option value="Perempuan"  <?= $gender==='Perempuan'  ? 'selected':'' ?>>Perempuan</option>
              </select>
              <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
            </div>
          </div>

          <!-- Bergabung -->
          <div class="flex flex-col gap-1.5">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Bergabung Sejak</label>
            <input type="text" value="<?= htmlspecialchars($tglGabung) ?>" disabled
              class="w-full bg-slate-50 border border-slate-100 rounded-xl h-11 px-4 text-[14px] text-slate-600 font-semibold focus:outline-none opacity-80 cursor-not-allowed">
          </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end">
          <button type="submit" id="btnSimpanProfil"
            class="bg-brand-600 text-white px-8 py-3 rounded-xl font-bold text-[14px] hover:bg-brand-700 transition shadow-sm border border-brand-700 flex items-center gap-2">
            <i class="fas fa-save"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>

  </div>
</div>

<script>
function showToastProfil(msg, ok=true) {
  const t=document.getElementById('toastProfil');
  const i=document.getElementById('toastProfilInner');
  const ic=document.getElementById('toastProfilIcon');
  document.getElementById('toastProfilMsg').textContent=msg;
  if(ok){i.className='flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-emerald-500 text-white';ic.className='fas fa-check-circle text-[15px]';}
  else{i.className='flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-red-500 text-white';ic.className='fas fa-exclamation-circle text-[15px]';}
  t.classList.remove('hidden');
  setTimeout(()=>t.classList.add('hidden'),3500);
}

// â”€â”€ Upload Foto â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function uploadFoto(input) {
  if (!input.files || !input.files[0]) return;
  const file = input.files[0];

  // Preview lokal sebelum upload
  const reader = new FileReader();
  reader.onload = function(e) {
    const img     = document.getElementById('previewFoto');
    const initial = document.getElementById('avatarInitial');
    img.src = e.target.result;
    img.classList.remove('hidden');
    if (initial) initial.classList.add('hidden');
  };
  reader.readAsDataURL(file);

  // Tampilkan spinner
  const loadingEl = document.getElementById('fotoLoading');
  loadingEl.classList.remove('hidden');
  loadingEl.classList.add('flex');

  const fd = new FormData();
  fd.append('action', 'upload_foto');
  fd.append('foto', file);

  fetch('/BHP-Poli-Gigi/process/profil_process.php', {method:'POST', body:fd, credentials:'same-origin'})
  .then(r => r.json())
  .then(res => {
    showToastProfil(res.message, res.success);
    if (res.success && res.foto_url) {
      document.getElementById('previewFoto').src = res.foto_url;
    }
  })
  .catch(() => showToastProfil('Upload gagal, coba lagi.', false))
  .finally(() => {
    loadingEl.classList.add('hidden');
    loadingEl.classList.remove('flex');
    input.value = '';
  });
}

// â”€â”€ Simpan Profil â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function simpanProfil(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSimpanProfil');
  const fd  = new FormData(document.getElementById('formProfil'));
  fd.append('action', 'update_profil');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
  fetch('/BHP-Poli-Gigi/process/profil_process.php', {method:'POST', body:fd, credentials:'same-origin'})
  .then(r => r.json())
  .then(res => {
    showToastProfil(res.message, res.success);
    if (res.success) setTimeout(() => window.location.reload(), 1200);
  })
  .catch(() => showToastProfil('Koneksi gagal.', false))
  .finally(() => { btn.disabled=false; btn.innerHTML='<i class="fas fa-save"></i> Simpan Perubahan'; });
}
</script>
