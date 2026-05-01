<?php

/**
 * Halaman Kategori BHP - Admin (Dinamis)
 */
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\BhpManager;

$mgr        = new BhpManager();
$kategoriList = $mgr->getAllKategori();

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!-- ===== MODAL TAMBAH / EDIT KATEGORI ===== -->
<div id="modalKategori"
  class="hidden fixed inset-0 z-50 items-center justify-center p-4"
  style="background:rgba(15,23,42,0.45);backdrop-filter:blur(4px);"
  onclick="if(event.target===this)closeKategoriModal()">
  <div class="relative w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl"
    style="background:#fff;animation:katModalIn2 .25s cubic-bezier(.34,1.56,.64,1) both;">
    <!-- Header -->
    <div class="relative px-7 pt-6 pb-5"
      style="background:radial-gradient(ellipse at 0% 0%,#006B47 0%,#1A9F70 60%,#1DB879 100%);">
      <button onclick="closeKategoriModal()"
        class="absolute top-4 right-5 text-white/70 hover:text-white text-xl leading-none">&times;</button>
      <h2 class="font-bold text-white text-xl leading-tight" id="kategoriModalTitle">Tambah Kategori Baru</h2>
      <p class="text-white/80 text-sm mt-1">Isi formulir di bawah ini untuk menambahkan data kategori ke inventaris.</p>
    </div>
    <!-- Body -->
    <form id="formKategori" class="px-7 py-6 space-y-4">
      <input type="hidden" id="kategoriId" name="id" value="">
      <input type="hidden" id="kategoriAction" name="action" value="add_kategori">
      <!-- Row: Nama Kategori (full width) -->
      <div class="flex flex-col gap-1.5">
        <label for="namaKategori" class="text-sm font-semibold text-slate-700">Nama Kategori <span class="text-red-500">*</span></label>
        <input id="namaKategori" name="nama_kategori" type="text" placeholder="cth: Alat Pelindung"
          class="h-11 px-4 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 shadow-sm" required />
      </div>
      <!-- Kode Kategori (readonly saat tambah, editable saat edit) -->
      <div class="flex flex-col gap-1.5">
        <label for="kodeKategori" class="text-sm font-semibold text-slate-700">Kode Kategori</label>
        <input id="kodeKategori" name="kode_kategori" type="text" placeholder="Otomatis dari nama kategori"
          class="h-11 px-4 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 shadow-sm bg-slate-50 cursor-not-allowed" readonly />
        <p id="kodeKategoriHint" class="text-xs text-slate-400 mt-0.5"><i class="fas fa-info-circle mr-1"></i>Kode akan di-generate otomatis berdasarkan nama, contoh: <strong>AP-001</strong></p>
      </div>
      <div class="flex justify-end gap-3 pt-1">
        <button type="button" onclick="closeKategoriModal()"
          class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
        <button type="submit" id="btnSimpanKategori"
          class="px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-95 shadow-sm"
          style="background:linear-gradient(135deg,#006B47 0%,#1DB879 100%);">Simpan Barang</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto space-y-6 w-full mb-8">

    <!-- Header Banner -->
    <div class="relative w-full rounded-2xl overflow-hidden mb-2"
      style="background:radial-gradient(ellipse at 0% 0%,#006B47 0%,#1A9F70 60%,#1DB879 100%);">
      <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
        <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
        <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
        <div class="absolute -bottom-[400px] left-[50px] md:-bottom-[850px] md:left-[100px] w-[600px] h-[600px] md:w-[1000px] md:h-[1000px] rounded-full bg-white opacity-5"></div>
      </div>
      <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 px-6 py-6 sm:px-8 sm:py-7">
        <div class="flex items-center gap-4 sm:gap-5 min-w-0">
          <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
            style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);">
            <i class="fas fa-tags text-white text-xl sm:text-2xl"></i>
          </div>
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Kategori Bahan Habis Pakai</h1>
            <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
              Tambahkan dan atur kategori bahan habis pakai agar data lebih rapi, terstruktur, dan mudah dikelola.
            </p>
          </div>
        </div>
        <div class="flex-shrink-0 w-full sm:w-auto">
          <button id="btnTambahKategori" onclick="openKategoriModal()"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-plex font-bold text-[14px] transition-all duration-200 hover:bg-slate-50 active:scale-95 whitespace-nowrap shadow-sm"
            style="background:#fff;color:#006B47;border:none;">
            <span class="text-base font-bold leading-none">+</span> Tambah Kategori BHP
          </button>
        </div>
      </div>
    </div>

    <!-- Filter & Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col">

      <!-- Filter -->
      <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col xl:flex-row justify-between gap-4 items-end bg-white rounded-t-2xl relative z-10">
        <div class="w-full relative flex-1">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Cari</label>
          <div class="relative max-w-3xl">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="searchKategori" placeholder="Cari nama Kategori" oninput="filterKategori(this.value)"
              class="w-full h-11 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400 font-medium text-slate-700 shadow-sm hover:border-slate-300">
          </div>
        </div>
        <button onclick="filterKategori(document.getElementById('searchKategori').value)"
          class="w-full xl:w-auto h-11 px-8 bg-gradient-to-r from-emerald-600 to-teal-500 text-white rounded-xl font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all text-sm shrink-0">
          Terapkan
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto w-full relative z-0">
        <table class="w-full text-left whitespace-nowrap">
          <thead>
            <tr class="bg-slate-50/50 border-b border-slate-200">
              <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold w-[25%]">Kode Kategori</th>
              <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold w-[50%]">Kategori</th>
              <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold w-[25%] text-right">Aksi</th>
            </tr>
          </thead>
          <tbody id="kategoriTableBody" class="text-sm font-plex divide-y divide-slate-100 text-slate-700">
            <?php if (empty($kategoriList)): ?>
              <tr id="kategoriEmptyRow">
                <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                  <i class="fas fa-tags text-4xl mb-3 opacity-30 block"></i>
                  Belum ada kategori. Klik "+ Tambah Kategori BHP" untuk memulai.
                </td>
              </tr>
            <?php else: ?>
              <?php
              $katColors = [
                ['bg' => 'bg-emerald-100/50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100/50'],
                ['bg' => 'bg-amber-100/50',  'text' => 'text-amber-700',  'border' => 'border-amber-100/50'],
                ['bg' => 'bg-blue-100/50',   'text' => 'text-blue-700',   'border' => 'border-blue-100/50'],
                ['bg' => 'bg-purple-100/50', 'text' => 'text-purple-700', 'border' => 'border-purple-100/50'],
                ['bg' => 'bg-rose-100/50',   'text' => 'text-rose-700',   'border' => 'border-rose-100/50'],
              ];
              foreach ($kategoriList as $i => $kat):
                $col = $katColors[$i % count($katColors)];
                $kode = $kat['Kode_kategori'] ?? '-';
              ?>
                <tr class="hover:bg-slate-50 transition-colors group kategori-row" data-nama="<?php echo strtolower(htmlspecialchars($kat['Nama_kategori'])); ?>">
                  <td class="py-5 px-6">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold tracking-wide bg-slate-100 text-slate-600 border border-slate-200">
                      <?php echo htmlspecialchars($kode); ?>
                    </span>
                  </td>
                  <td class="py-5 px-6">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold <?php echo $col['bg'] . ' ' . $col['text'] . ' border ' . $col['border']; ?>">
                      <?php echo htmlspecialchars($kat['Nama_kategori']); ?>
                    </span>
                  </td>
                  <td class="py-5 px-6 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button onclick="editKategori(<?php echo $kat['id_kategori']; ?>, '<?php echo htmlspecialchars($kat['Nama_kategori'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($kat['Kode_kategori'] ?? '', ENT_QUOTES); ?>')"
                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-blue-100/50 bg-white shadow-sm" title="Edit">
                        <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                      </button>
                      <button onclick="deleteKategori(<?php echo $kat['id_kategori']; ?>, '<?php echo htmlspecialchars($kat['Nama_kategori'], ENT_QUOTES); ?>')"
                        class="text-red-500 hover:text-red-700 hover:bg-red-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-red-100/50 bg-white shadow-sm" title="Hapus">
                        <i class="fa-solid fa-trash-can text-[13px]"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Toast -->
<div id="toastKat" class="fixed bottom-6 right-6 z-[60] hidden items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-plex font-semibold text-white min-w-[260px]">
  <i id="toastKatIcon" class="fas text-base"></i>
  <span id="toastKatMsg"></span>
</div>

<style>
  @keyframes katModalIn2 {
    from {
      opacity: 0;
      transform: scale(0.92) translateY(16px);
    }

    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }
</style>

<script>
  const KAT_URL = '/BHP-Poli-Gigi/process/bhp_process.php';

  function openKategoriModal(id = '', nama = '', kode = '') {
    const kodeInput = document.getElementById('kodeKategori');
    const kodeHint = document.getElementById('kodeKategoriHint');
    document.getElementById('kategoriId').value = id;
    document.getElementById('namaKategori').value = nama;
    kodeInput.value = kode;
    document.getElementById('kategoriAction').value = id ? 'edit_kategori' : 'add_kategori';
    document.getElementById('kategoriModalTitle').textContent = id ? 'Edit Kategori' : 'Tambah Kategori Baru';

    // Saat tambah: readonly + hint. Saat edit: bisa diubah manual.
    if (id) {
      kodeInput.readOnly = false;
      kodeInput.classList.remove('bg-slate-50', 'cursor-not-allowed');
      kodeInput.placeholder = 'Masukkan kode kategori';
      kodeHint.classList.add('hidden');
    } else {
      kodeInput.readOnly = true;
      kodeInput.classList.add('bg-slate-50', 'cursor-not-allowed');
      kodeInput.placeholder = 'Otomatis dari nama kategori';
      kodeHint.classList.remove('hidden');
    }

    const m = document.getElementById('modalKategori');
    m.classList.remove('hidden');
    m.classList.add('flex');
  }

  function closeKategoriModal() {
    const m = document.getElementById('modalKategori');
    m.classList.add('hidden');
    m.classList.remove('flex');
    document.getElementById('formKategori').reset();
  }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeKategoriModal();
  });

  function editKategori(id, nama, kode = '') {
    openKategoriModal(id, nama, kode);
  }

  // ── Auto-generate kode preview saat mengetik nama kategori ──
  function generateKodePreview(nama) {
    nama = nama.trim();
    if (!nama) return '';
    const words = nama.split(/\s+/).filter(w => w.length > 0);
    let prefix = '';
    if (words.length >= 2) {
      words.forEach(w => prefix += w.charAt(0).toUpperCase());
    } else {
      prefix = nama.substring(0, Math.min(3, nama.length)).toUpperCase();
    }
    const num = String(Math.floor(Math.random() * 999) + 1).padStart(3, '0');
    return prefix + '-' + num;
  }

  document.getElementById('namaKategori').addEventListener('input', function() {
    const isAdd = document.getElementById('kategoriAction').value === 'add_kategori';
    if (!isAdd) return;
    document.getElementById('kodeKategori').value = generateKodePreview(this.value);
  });

  async function deleteKategori(id, nama) {
    if (!confirm(`Hapus kategori "${nama}"?\nBHP yang menggunakan kategori ini akan menjadi tanpa kategori.`)) return;
    const fd = new FormData();
    fd.append('action', 'delete_kategori');
    fd.append('id', id);
    try {
      const res = await fetch(KAT_URL, {
        method: 'POST',
        body: fd
      });
      const json = await res.json();
      if (json.success) {
        showToastKat(json.message, 'success');
        setTimeout(() => location.reload(), 900);
      } else showToastKat(json.message, 'error');
    } catch {
      showToastKat('Gagal menghapus kategori.', 'error');
    }
  }

  document.getElementById('formKategori').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSimpanKategori');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    try {
      const res = await fetch(KAT_URL, {
        method: 'POST',
        body: new FormData(this)
      });
      const json = await res.json();
      if (json.success) {
        showToastKat(json.message, 'success');
        closeKategoriModal();
        setTimeout(() => location.reload(), 900);
      } else showToastKat(json.message, 'error');
    } catch {
      showToastKat('Terjadi kesalahan jaringan.', 'error');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Simpan Barang';
    }
  });

  function filterKategori(val) {
    const v = val.toLowerCase().trim();
    let vis = 0;
    document.querySelectorAll('.kategori-row').forEach(row => {
      const m = row.dataset.nama.includes(v);
      row.style.display = m ? '' : 'none';
      if (m) vis++;
    });
    const empty = document.getElementById('kategoriEmptyRow');
    if (empty) empty.style.display = vis === 0 ? '' : 'none';
  }

  let _ktimer;

  function showToastKat(msg, type = 'success') {
    const t = document.getElementById('toastKat'),
      ic = document.getElementById('toastKatIcon'),
      me = document.getElementById('toastKatMsg');
    clearTimeout(_ktimer);
    t.className = 'fixed bottom-6 right-6 z-[60] flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-plex font-semibold text-white min-w-[260px]';
    t.style.animation = 'toastIn2 .3s ease forwards';
    if (type === 'success') {
      t.style.background = 'linear-gradient(135deg,#047857 0%,#059669 100%)';
      ic.className = 'fas fa-circle-check text-base';
    } else {
      t.style.background = 'linear-gradient(135deg,#DC2626 0%,#EF4444 100%)';
      ic.className = 'fas fa-circle-exclamation text-base';
    }
    me.textContent = msg;
    _ktimer = setTimeout(() => {
      t.style.animation = 'toastOut2 .3s ease forwards';
      setTimeout(() => t.classList.add('hidden'), 300);
    }, 3000);
  }
</script>