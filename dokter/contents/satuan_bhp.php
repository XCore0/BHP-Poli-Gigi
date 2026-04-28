<?php
/**
 * Halaman Satuan BHP - Dokter (Dinamis)
 */
use App\Classes\BhpManager;
$mgr    = new BhpManager();
$satuanList = $mgr->getAllSatuan();
$keyword    = $_GET['keyword'] ?? '';

if (!function_exists('satBadgeColor')) {
    function satBadgeColor(int $i): array {
        $colors = [
            ['bg' => '#DCFCE7', 'text' => '#166534'],
            ['bg' => '#FEF9C3', 'text' => '#854D0E'],
            ['bg' => '#DBEAFE', 'text' => '#1E40AF'],
            ['bg' => '#FCE7F3', 'text' => '#9D174D'],
            ['bg' => '#E0E7FF', 'text' => '#3730A3'],
            ['bg' => '#CCFBF1', 'text' => '#134E4A'],
        ];
        return $colors[$i % count($colors)];
    }
}
?>

<!-- ===== MODAL TAMBAH SATUAN ===== -->
<div id="modalTambahSatuan"
  class="hidden fixed inset-0 z-50 items-center justify-center p-4"
  style="background:rgba(15,23,42,0.45);backdrop-filter:blur(4px);"
  onclick="if(event.target===this)closeSatuanModal()">
  <div class="relative w-full max-w-md rounded-2xl overflow-hidden shadow-2xl"
    style="background:#fff;animation:satuanModalIn .25s cubic-bezier(.34,1.56,.64,1) both;">
    <!-- Header -->
    <div class="relative px-7 pt-6 pb-5"
      style="background:radial-gradient(ellipse at 0% 0%,#006B47 0%,#1A9F70 60%,#1DB879 100%);">
      <button onclick="closeSatuanModal()"
        class="absolute top-4 right-5 text-white/70 hover:text-white text-xl leading-none">&times;</button>
      <h2 class="font-bold text-white text-xl leading-tight" id="satuanModalTitle">Tambah Satuan Baru</h2>
      <p class="text-white/80 text-sm mt-1">Isi formulir di bawah ini untuk menambahkan data satuan ke inventaris.</p>
    </div>
    <!-- Body -->
    <form id="formSatuan" class="px-7 py-6 space-y-4">
      <input type="hidden" id="satuanId" name="id" value="">
      <input type="hidden" id="satuanAction" name="action" value="add_satuan">
      <div class="flex flex-col gap-1.5">
        <label for="namaSatuan" class="text-sm font-semibold text-slate-700">Nama Satuan</label>
        <input id="namaSatuan" name="nama_satuan" type="text" placeholder="Masukkan nama lengkap"
          class="h-11 px-4 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 shadow-sm" required />
      </div>
      <div class="flex justify-end gap-3 pt-1">
        <button type="button" onclick="closeSatuanModal()"
          class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
        <button type="submit" id="btnSimpanSatuan"
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
            <i class="fas fa-ruler-combined text-white text-xl sm:text-2xl"></i>
          </div>
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Satuan Bahan Habis Pakai</h1>
            <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
              Tambahkan dan atur satuan bahan habis pakai agar data lebih rapi, terstruktur, dan mudah dikelola.
            </p>
          </div>
        </div>
        <div class="flex-shrink-0 w-full sm:w-auto">
          <button id="btnTambahSatuan" onclick="openSatuanModal()"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-plex font-bold text-[14px] transition-all duration-200 hover:bg-slate-50 active:scale-95 whitespace-nowrap shadow-sm"
            style="background:#fff;color:#006B47;border:none;">
            <span class="text-base font-bold leading-none">+</span> Tambah Satuan BHP
          </button>
        </div>
      </div>
    </div>

    <!-- Filter & Table Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col">

      <!-- Filter -->
      <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col xl:flex-row justify-between gap-4 items-end bg-white rounded-t-2xl relative z-10">
        <div class="w-full relative flex-1">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Cari</label>
          <div class="relative max-w-3xl">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="searchSatuan" placeholder="Cari nama satuan"
              oninput="filterSatuan(this.value)"
              class="w-full h-11 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400 font-medium text-slate-700 shadow-sm hover:border-slate-300">
          </div>
        </div>
        <button onclick="filterSatuan(document.getElementById('searchSatuan').value)"
          class="w-full xl:w-auto h-11 px-8 bg-gradient-to-r from-emerald-600 to-teal-500 text-white rounded-xl font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all text-sm shrink-0">
          Terapkan
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto w-full relative z-0">
        <table class="w-full text-left whitespace-nowrap">
          <thead>
            <tr class="bg-slate-50/50 border-b border-slate-200">
              <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold w-16">No</th>
              <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold">Nama Satuan</th>
              <th class="py-4 px-6 text-[11px] uppercase tracking-wider text-slate-500 font-bold text-right">Aksi</th>
            </tr>
          </thead>
          <tbody id="satuanTableBody" class="text-sm font-plex divide-y divide-slate-100 text-slate-700">
            <?php if (empty($satuanList)): ?>
            <tr id="satuanEmptyRow">
              <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                <i class="fas fa-ruler-combined text-4xl mb-3 opacity-30 block"></i>
                Belum ada data satuan. Klik "+ Tambah Satuan BHP" untuk memulai.
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($satuanList as $i => $sat): ?>
            <tr class="hover:bg-slate-50 transition-colors group satuan-row" data-nama="<?php echo strtolower(htmlspecialchars($sat['Nama_satuan'])); ?>">
              <td class="py-5 px-6 font-bold text-slate-500"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></td>
              <td class="py-5 px-6">
                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-bold tracking-wide"
                  style="background:<?php echo satBadgeColor($i)['bg']; ?>;color:<?php echo satBadgeColor($i)['text']; ?>;">
                  <?php echo htmlspecialchars(strtoupper($sat['Nama_satuan'])); ?>
                </span>
              </td>
              <td class="py-5 px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button onclick="editSatuan(<?php echo $sat['id_satuan']; ?>, '<?php echo htmlspecialchars($sat['Nama_satuan'], ENT_QUOTES); ?>')"
                    class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-blue-100/50 bg-white shadow-sm" title="Edit">
                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                  </button>
                  <button onclick="deleteSatuan(<?php echo $sat['id_satuan']; ?>, '<?php echo htmlspecialchars($sat['Nama_satuan'], ENT_QUOTES); ?>')"
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
<div id="toastSatuan" class="fixed bottom-6 right-6 z-[60] hidden items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-plex font-semibold text-white min-w-[260px]">
  <i id="toastSatuanIcon" class="fas text-base"></i>
  <span id="toastSatuanMsg"></span>
</div>

<style>
@keyframes satuanModalIn {
  from { opacity:0; transform:scale(0.92) translateY(16px); }
  to   { opacity:1; transform:scale(1)   translateY(0); }
}
@keyframes toastIn2  { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
@keyframes toastOut2 { from{opacity:1;transform:translateY(0)} to{opacity:0;transform:translateY(12px)} }
</style>

<script>
const SATUAN_PROCESS_URL = '/be-poli/process/bhp_process.php';

/* ── Modal open / close ──────────────────────── */
function openSatuanModal(id = '', nama = '') {
  document.getElementById('satuanId').value       = id;
  document.getElementById('namaSatuan').value     = nama;
  document.getElementById('satuanAction').value   = id ? 'edit_satuan' : 'add_satuan';
  document.getElementById('satuanModalTitle').textContent = id ? 'Edit Satuan' : 'Tambah Satuan Baru';
  const m = document.getElementById('modalTambahSatuan');
  m.classList.remove('hidden'); m.classList.add('flex');
}
function closeSatuanModal() {
  const m = document.getElementById('modalTambahSatuan');
  m.classList.add('hidden'); m.classList.remove('flex');
  document.getElementById('formSatuan').reset();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSatuanModal(); });

/* ── Edit ────────────────────────────────────── */
function editSatuan(id, nama) { openSatuanModal(id, nama); }

/* ── Delete ──────────────────────────────────── */
async function deleteSatuan(id, nama) {
  if (!confirm(`Hapus satuan "${nama}"?\nTindakan ini tidak dapat dibatalkan.`)) return;
  const fd = new FormData();
  fd.append('action', 'delete_satuan');
  fd.append('id', id);
  try {
    const res  = await fetch(SATUAN_PROCESS_URL, { method:'POST', body:fd });
    const json = await res.json();
    if (json.success) { showToastSatuan(json.message, 'success'); setTimeout(() => location.reload(), 900); }
    else showToastSatuan(json.message, 'error');
  } catch { showToastSatuan('Gagal menghapus satuan.', 'error'); }
}

/* ── Form submit ─────────────────────────────── */
document.getElementById('formSatuan').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSimpanSatuan');
  btn.disabled = true;
  btn.textContent = 'Menyimpan...';
  try {
    const res  = await fetch(SATUAN_PROCESS_URL, { method:'POST', body: new FormData(this) });
    const json = await res.json();
    if (json.success) {
      showToastSatuan(json.message, 'success');
      closeSatuanModal();
      setTimeout(() => location.reload(), 900);
    } else {
      showToastSatuan(json.message, 'error');
    }
  } catch { showToastSatuan('Terjadi kesalahan jaringan.', 'error'); }
  finally { btn.disabled = false; btn.textContent = 'Simpan Barang'; }
});

/* ── Filter tabel ────────────────────────────── */
function filterSatuan(val) {
  const v = val.toLowerCase().trim();
  let visible = 0;
  document.querySelectorAll('.satuan-row').forEach(row => {
    const match = row.dataset.nama.includes(v);
    row.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  const emptyEl = document.getElementById('satuanEmptyRow');
  if (emptyEl) emptyEl.style.display = visible === 0 ? '' : 'none';
}

/* ── Toast ───────────────────────────────────── */
let _toastTimer2;
function showToastSatuan(msg, type='success') {
  const toast = document.getElementById('toastSatuan');
  const icon  = document.getElementById('toastSatuanIcon');
  const msgEl = document.getElementById('toastSatuanMsg');
  clearTimeout(_toastTimer2);
  toast.className = 'fixed bottom-6 right-6 z-[60] flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-plex font-semibold text-white min-w-[260px]';
  toast.style.animation = 'toastIn2 .3s ease forwards';
  if (type === 'success') {
    toast.style.background = 'linear-gradient(135deg,#047857 0%,#059669 100%)';
    icon.className = 'fas fa-circle-check text-base';
  } else {
    toast.style.background = 'linear-gradient(135deg,#DC2626 0%,#EF4444 100%)';
    icon.className = 'fas fa-circle-exclamation text-base';
  }
  msgEl.textContent = msg;
  _toastTimer2 = setTimeout(() => {
    toast.style.animation = 'toastOut2 .3s ease forwards';
    setTimeout(() => toast.classList.add('hidden'), 300);
  }, 3000);
}
</script>
