<?php

/**
 * Halaman Data BHP - Admin (Dinamis)
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\BhpManager;
$mgr       = new BhpManager();
$filter    = [
  'keyword'     => $_GET['keyword']     ?? '',
  'id_kategori' => $_GET['id_kategori'] ?? '',
];
$bhpList      = $mgr->getAllBhp($filter);
$kategoriList = $mgr->getAllKategori();
$satuanList   = $mgr->getAllSatuan();
$kodeBaru     = $mgr->generateKodeBhp();

// Warna status stok
function stokStatus(int $jumlah, int $Pemakaian): array
{
  if ($jumlah <= 0)            return ['label' => 'Habis',   'cls' => 'text-red-600',   'dot' => 'text-red-500'];
  if ($jumlah <= $Pemakaian)    return ['label' => 'Menipis', 'cls' => 'text-amber-600', 'dot' => 'text-amber-500'];
  return                               ['label' => 'Aman',   'cls' => 'text-emerald-600', 'dot' => 'text-emerald-500'];
}
?>

<!-- ===== MODAL TAMBAH / EDIT BHP ===== -->
<div id="modalBhp"
  class="hidden fixed inset-0 z-50 items-center justify-center p-4"
  style="background:rgba(15,23,42,0.45);backdrop-filter:blur(4px);"
  onclick="if(event.target===this)closeBhpModal()">
  <div class="relative w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl"
    style="background:#fff;animation:bhpModalIn2 .25s cubic-bezier(.34,1.56,.64,1) both;">
    <!-- Header -->
    <div class="relative px-7 pt-6 pb-5"
      style="background:radial-gradient(ellipse at 0% 0%,#006B47 0%,#1A9F70 60%,#1DB879 100%);">
      <button onclick="closeBhpModal()"
        class="absolute top-4 right-5 text-white/70 hover:text-white text-xl leading-none">&times;</button>
      <h2 class="font-bold text-white text-xl leading-tight" id="bhpModalTitle">Tambah Barang Baru</h2>
      <p class="text-white/80 text-sm mt-1">Isi formulir di bawah ini untuk menambahkan data barang ke inventaris.</p>
    </div>
    <!-- Body -->
    <form id="formBhp" class="p-7 space-y-5">
      <input type="hidden" id="bhpId" name="id" value="">
      <input type="hidden" id="bhpAction" name="action" value="add_bhp">

      <!-- Row 1: Kode + Kategori -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="flex flex-col gap-1.5">
          <label for="bhpKode" class="text-sm font-semibold text-slate-700">Kode Barang</label>
          <input id="bhpKode" name="kode_bhp" type="text"
            class="h-11 px-4 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 shadow-sm"
            placeholder="Generate otomatis jika kosong" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="bhpKategori" class="text-sm font-semibold text-slate-700">Kategori</label>
          <div class="relative">
            <select id="bhpKategori" name="id_kategori"
              class="w-full h-11 pl-4 pr-10 border border-slate-200 rounded-xl text-sm text-slate-500 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all appearance-none cursor-pointer shadow-sm bg-white">
              <option value="">Pilih kategori</option>
              <?php foreach ($kategoriList as $kat): ?>
                <option value="<?php echo $kat['id_kategori']; ?>"><?php echo htmlspecialchars($kat['Nama_kategori']); ?></option>
              <?php endforeach; ?>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
          </div>
        </div>
      </div>

      <!-- Row 2: Nama BHP -->
      <div class="flex flex-col gap-1.5">
        <label for="bhpNama" class="text-sm font-semibold text-slate-700">Nama BHP <span class="text-red-500">*</span></label>
        <input id="bhpNama" name="nama_bhp" type="text" required
          placeholder="Masukkan nama barang lengkap"
          class="h-11 px-4 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 shadow-sm" />
      </div>

      <!-- Row 3: Stok + Stok Min + Satuan -->
      <div class="grid grid-cols-3 gap-5">
        <div class="flex flex-col gap-1.5">
          <label for="bhpJumlah" class="text-sm font-semibold text-slate-700">Stok Awal</label>
          <input id="bhpJumlah" name="jumlah" type="number" value="0" min="0"
            class="h-11 px-4 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-sm" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="bhpPemakaian" class="text-sm font-semibold text-slate-700">Pemakaian</label>
          <input id="bhpPemakaian" name="Pemakaian" type="number" value="5" min="0"
            class="h-11 px-4 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-sm" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="bhpSatuan" class="text-sm font-semibold text-slate-700">Satuan</label>
          <div class="relative">
            <select id="bhpSatuan" name="id_satuan"
              class="w-full h-11 pl-4 pr-10 border border-slate-200 rounded-xl text-sm text-slate-700 font-medium outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all appearance-none cursor-pointer shadow-sm bg-white">
              <option value="">Pilih satuan</option>
              <?php foreach ($satuanList as $sat): ?>
                <option value="<?php echo $sat['id_satuan']; ?>"><?php echo htmlspecialchars($sat['Nama_satuan']); ?></option>
              <?php endforeach; ?>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
          </div>
        </div>
      </div>

      <!-- Pesan jika tidak ada kategori/satuan -->
      <?php if (empty($kategoriList) || empty($satuanList)): ?>
        <div class="rounded-xl px-4 py-3 text-sm font-medium bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-2">
          <i class="fas fa-triangle-exclamation"></i>
          <?php if (empty($kategoriList) && empty($satuanList)): ?>
            Belum ada data <a href="index.php?page=kategori_bhp" class="underline font-bold">Kategori</a> dan <a href="index.php?page=satuan_bhp" class="underline font-bold">Satuan</a>. Tambahkan terlebih dahulu.
          <?php elseif (empty($kategoriList)): ?>
            Belum ada data <a href="index.php?page=kategori_bhp" class="underline font-bold">Kategori</a>. Tambahkan terlebih dahulu.
          <?php else: ?>
            Belum ada data <a href="index.php?page=satuan_bhp" class="underline font-bold">Satuan</a>. Tambahkan terlebih dahulu.
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Footer -->
      <div class="flex justify-end gap-3 pt-2">
        <button type="button" onclick="closeBhpModal()"
          class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
        <button type="submit" id="btnSimpanBhp"
          class="px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-95 shadow-sm"
          style="background:linear-gradient(135deg,#006B47 0%,#1DB879 100%);">Simpan Barang</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto space-y-6 w-full">

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
            <i class="fas fa-box-open text-white text-xl sm:text-2xl"></i>
          </div>
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Data Bahan Habis Pakai</h1>
            <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
              Pantau stok, penggunaan, dan ketersediaan bahan habis pakai secara real-time untuk memastikan operasional tetap berjalan lancar.
            </p>
          </div>
        </div>
        <div class="flex-shrink-0 w-full sm:w-auto">
          <button id="btnTambahBHP" onclick="openBhpModal()"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-plex font-bold text-[14px] transition-all duration-200 hover:bg-slate-50 active:scale-95 whitespace-nowrap shadow-sm"
            style="background:#fff;color:#006B47;border:none;">
            <span class="text-base font-bold leading-none">+</span> Tambah BHP Baru
          </button>
        </div>
      </div>
    </div>

    <!-- Filters and Table Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col">

      <!-- Filters -->
      <form method="GET" action="" class="p-5 sm:p-6 border-b border-slate-100 flex flex-col xl:flex-row gap-4 items-end bg-white rounded-t-2xl relative z-10">
        <input type="hidden" name="page" value="data_bhp">
        <!-- Search -->
        <div class="w-full xl:w-[40%] flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Cari</label>
          <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="keyword" value="<?php echo htmlspecialchars($filter['keyword']); ?>"
              placeholder="Cari nama BHP, kode..."
              class="w-full h-11 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400 font-medium text-slate-700 shadow-sm hover:border-slate-300">
          </div>
        </div>
        <!-- Category dropdown dinamis -->
        <div class="w-full sm:w-1/2 xl:w-[20%] flex flex-col gap-1.5">
          <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Kategori</label>
          <div class="relative">
            <select name="id_kategori"
              class="w-full h-11 pl-4 pr-10 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all font-medium text-slate-700 appearance-none cursor-pointer shadow-sm hover:border-slate-300">
              <option value="">Semua Kategori</option>
              <?php foreach ($kategoriList as $kat): ?>
                <option value="<?php echo $kat['id_kategori']; ?>" <?php echo $filter['id_kategori'] == $kat['id_kategori'] ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($kat['Nama_kategori']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
          </div>
        </div>
        <!-- Apply -->
        <button type="submit"
          class="w-full xl:w-auto h-11 px-6 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-colors text-sm shrink-0">
          Terapkan
        </button>
        <?php if ($filter['keyword'] || $filter['id_kategori']): ?>
          <a href="index.php?page=data_bhp"
            class="w-full xl:w-auto h-11 px-4 rounded-xl text-sm font-semibold text-slate-500 border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors shrink-0">
            <i class="fas fa-times mr-1"></i> Reset
          </a>
        <?php endif; ?>
      </form>

      <!-- Table -->
      <div class="overflow-x-auto w-full relative z-0">
        <table class="w-full text-left whitespace-nowrap">
          <thead>
            <tr class="bg-white text-[11px] uppercase tracking-wider text-slate-400 border-b border-slate-200 font-bold">
              <th class="py-4 px-6">Kode BHP</th>
              <th class="py-4 px-6">Nama BHP</th>
              <th class="py-4 px-6">Kategori</th>
              <th class="py-4 px-6">Satuan</th>
              <th class="py-4 px-6">Stok</th>
              <th class="py-4 px-6">Pemakaian</th>
              <th class="py-4 px-6">Status</th>
              <th class="py-4 px-6 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-[13px] font-plex divide-y divide-slate-100 text-slate-600">
            <?php if (empty($bhpList)): ?>
              <tr>
                <td colspan="8" class="px-6 py-14 text-center text-slate-400">
                  <i class="fas fa-box-open text-4xl mb-3 opacity-30 block"></i>
                  <?php echo ($filter['keyword'] || $filter['id_kategori'])
                    ? 'Tidak ada BHP yang cocok dengan filter.'
                    : 'Belum ada data BHP. Klik "+ Tambah BHP Baru" untuk memulai.'; ?>
                </td>
              </tr>
            <?php else: ?>
              <?php
              $katColors = [
                ['bg' => 'bg-emerald-100/50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100/50'],
                ['bg' => 'bg-amber-100/50',  'text' => 'text-amber-600',  'border' => 'border-amber-100/50'],
                ['bg' => 'bg-blue-100/50',   'text' => 'text-blue-600',   'border' => 'border-blue-100/50'],
                ['bg' => 'bg-purple-100/50', 'text' => 'text-purple-600', 'border' => 'border-purple-100/50'],
                ['bg' => 'bg-rose-100/50',   'text' => 'text-rose-600',   'border' => 'border-rose-100/50'],
              ];
              $katColorMap = [];
              foreach ($kategoriList as $idx => $k) $katColorMap[$k['id_kategori']] = $katColors[$idx % count($katColors)];
              foreach ($bhpList as $bhp):
                $status = stokStatus((int)$bhp['Jumlah'], (int)$bhp['Pemakaian']);
                $col    = $katColorMap[$bhp['id_kategori']] ?? $katColors[0];
              ?>
                <tr class="hover:bg-slate-50/50 transition-colors group bg-white" id="bhp-row-<?php echo $bhp['id_bhp']; ?>">
                  <td class="py-5 px-6 font-semibold text-slate-700"><?php echo htmlspecialchars($bhp['Kode_bhp'] ?? '-'); ?></td>
                  <td class="py-5 px-6 font-semibold text-slate-800"><?php echo htmlspecialchars($bhp['Nama_bhp']); ?></td>
                  <td class="py-5 px-6">
                    <?php if ($bhp['Nama_kategori']): ?>
                      <span class="inline-flex items-center px-3 py-1.5 rounded-md text-[11px] font-bold <?php echo $col['bg'] . ' ' . $col['text'] . ' border ' . $col['border']; ?>">
                        <?php echo htmlspecialchars($bhp['Nama_kategori']); ?>
                      </span>
                    <?php else: ?>
                      <span class="text-slate-300 text-xs">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="py-5 px-6 font-medium text-slate-600"><?php echo $bhp['Nama_satuan'] ? htmlspecialchars($bhp['Nama_satuan']) : '—'; ?></td>
                  <td class="py-5 px-6 font-medium text-slate-600"><?php echo number_format((int)$bhp['Jumlah']); ?></td>
                  <td class="py-5 px-6 font-medium text-slate-500"><?php echo number_format((int)$bhp['Pemakaian']); ?></td>
                  <td class="py-5 px-6">
                    <span class="<?php echo $status['cls']; ?> font-bold text-[11px] tracking-widest uppercase">
                      <span class="<?php echo $status['dot']; ?> mr-1.5 text-[14px] leading-none tracking-normal">&bull;</span><?php echo $status['label']; ?>
                    </span>
                  </td>
                  <td class="py-5 px-6 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <button onclick="editBhp(<?php echo htmlspecialchars(json_encode($bhp), ENT_QUOTES); ?>)"
                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 w-8 h-8 flex items-center justify-center rounded-lg transition-colors border border-blue-100/50 bg-white shadow-sm" title="Edit">
                        <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                      </button>
                      <button onclick="deleteBhp(<?php echo $bhp['id_bhp']; ?>, '<?php echo htmlspecialchars($bhp['Nama_bhp'], ENT_QUOTES); ?>')"
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
<div id="toastBhp" class="fixed bottom-6 right-6 z-[60] hidden items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-plex font-semibold text-white min-w-[260px]">
  <i id="toastBhpIcon" class="fas text-base"></i>
  <span id="toastBhpMsg"></span>
</div>

<style>
  @keyframes bhpModalIn2 {
    from {
      opacity: 0;
      transform: scale(0.92) translateY(16px);
    }

    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  @keyframes toastIn2 {
    from {
      opacity: 0;
      transform: translateY(12px)
    }

    to {
      opacity: 1;
      transform: translateY(0)
    }
  }

  @keyframes toastOut2 {
    from {
      opacity: 1;
      transform: translateY(0)
    }

    to {
      opacity: 0;
      transform: translateY(12px)
    }
  }
</style>

<script>
  const BHP_URL = '/BHP-Poli-Gigi/process/bhp_process.php';

  /* ── Modal ───────────────────────────────────── */
  function openBhpModal(id = '', kode = '', nama = '', jumlah = 0, Pemakaian = 5, id_kat = '', id_sat = '') {
    document.getElementById('bhpId').value = id;
    document.getElementById('bhpKode').value = kode;
    document.getElementById('bhpNama').value = nama;
    document.getElementById('bhpJumlah').value = jumlah;
    document.getElementById('bhpPemakaian').value = Pemakaian;
    document.getElementById('bhpKategori').value = id_kat;
    document.getElementById('bhpSatuan').value = id_sat;
    document.getElementById('bhpAction').value = id ? 'edit_bhp' : 'add_bhp';
    document.getElementById('bhpModalTitle').textContent = id ? 'Edit Data BHP' : 'Tambah Barang Baru';
    const m = document.getElementById('modalBhp');
    m.classList.remove('hidden');
    m.classList.add('flex');
  }

  function closeBhpModal() {
    const m = document.getElementById('modalBhp');
    m.classList.add('hidden');
    m.classList.remove('flex');
    document.getElementById('formBhp').reset();
  }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeBhpModal();
  });

  /* ── Edit ────────────────────────────────────── */
  function editBhp(d) {
    openBhpModal(d.id_bhp, d.Kode_bhp || '', d.Nama_bhp, d.Jumlah, d.Pemakaian, d.id_kategori || '', d.id_satuan || '');
  }

  /* ── Delete ──────────────────────────────────── */
  async function deleteBhp(id, nama) {
    if (!confirm(`Hapus BHP "${nama}"?\nTindakan ini tidak dapat dibatalkan.`)) return;
    const fd = new FormData();
    fd.append('action', 'delete_bhp');
    fd.append('id', id);
    try {
      const res = await fetch(BHP_URL, {
        method: 'POST',
        body: fd
      });
      const json = await res.json();
      if (json.success) {
        showToastBhp(json.message, 'success');
        setTimeout(() => location.reload(), 900);
      } else showToastBhp(json.message, 'error');
    } catch {
      showToastBhp('Gagal menghapus BHP.', 'error');
    }
  }

  /* ── Form Submit ─────────────────────────────── */
  document.getElementById('formBhp').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSimpanBhp');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    try {
      const res = await fetch(BHP_URL, {
        method: 'POST',
        body: new FormData(this)
      });
      const json = await res.json();
      if (json.success) {
        showToastBhp(json.message, 'success');
        closeBhpModal();
        setTimeout(() => location.reload(), 900);
      } else showToastBhp(json.message, 'error');
    } catch {
      showToastBhp('Terjadi kesalahan jaringan.', 'error');
    } finally {
      btn.disabled = false;
      btn.textContent = 'Simpan Barang';
    }
  });

  /* ── Toast ───────────────────────────────────── */
  let _bhpTimer;

  function showToastBhp(msg, type = 'success') {
    const t = document.getElementById('toastBhp'),
      ic = document.getElementById('toastBhpIcon'),
      me = document.getElementById('toastBhpMsg');
    clearTimeout(_bhpTimer);
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
    _bhpTimer = setTimeout(() => {
      t.style.animation = 'toastOut2 .3s ease forwards';
      setTimeout(() => t.classList.add('hidden'), 300);
    }, 3000);
  }
</script>