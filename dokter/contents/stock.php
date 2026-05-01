<?php
// â”€â”€ Inisialisasi Manager (autoload sudah di-load dari index.php) â”€â”€
use App\Classes\StokMasukManager;
use App\Classes\BhpManager;
use App\Classes\Auth;

$auth    = new Auth();
$user    = $auth->getCurrentUser();
$mgr     = new StokMasukManager();
$bhpMgr  = new BhpManager();

$bhpList = $bhpMgr->getAllBhp();
$p      = max(1, (int)($_GET['p'] ?? 1));
$limit  = 10;
$offset = ($p - 1) * $limit;

$filter = [
  'keyword' => $_GET['keyword'] ?? '',
  'limit'   => $limit,
  'offset'  => $offset
];

// Ambil riwayat stok masuk
$stokList    = $mgr->getAllStokMasuk($filter);
$totalRecord = $mgr->countStokMasuk($filter);
$totalPages  = max(1, ceil($totalRecord / $limit));
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto space-y-6 w-full">

    <!-- â”€â”€ NOTIFICATION TOAST â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div id="toastStok" class="fixed top-6 right-6 z-[200] hidden">
      <div class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs" id="toastStokInner">
        <i id="toastStokIcon" class="text-[15px]"></i>
        <span id="toastStokMsg"></span>
      </div>
    </div>

    <!-- â”€â”€ HEADER BANNER â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div
      class="relative w-full rounded-2xl overflow-hidden mb-2"
      style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);"
    >
      <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
        <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
        <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
      </div>

      <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 px-6 py-6 sm:px-8 sm:py-7">
        <div class="flex items-center gap-4 sm:gap-5 min-w-0">
          <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
            style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
            <i class="fas fa-arrow-circle-down text-white text-xl sm:text-2xl"></i>
          </div>
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Stok Masuk</h1>
            <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block">
              Catat penerimaan barang baru dari supplier atau hasil pengadaan
            </p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row flex-shrink-0 w-full sm:w-auto gap-3">
          <button
            id="btnOpenModalStok"
            onclick="openModalStok()"
            class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-plex font-bold text-[13px] transition-all duration-200 hover:bg-white/90 active:scale-95 whitespace-nowrap shadow-sm text-brand-700 bg-white"
          >
            <span class="text-base font-bold leading-none">+</span> Input Stok
          </button>
        </div>
      </div>
    </div>

    <!-- â”€â”€ RIWAYAT STOK MASUK â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col p-6 sm:p-8">

      <!-- Card Header -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
            <i class="fas fa-history text-emerald-500"></i>
          </div>
          <div>
            <h2 class="font-display font-bold text-lg text-slate-800">Riwayat Stok Masuk</h2>
            <p class="text-[13px] font-medium text-slate-400 mt-0.5">Semua transaksi penerimaan barang</p>
          </div>
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
          <form method="GET" action="" class="flex items-center gap-2 flex-1 md:flex-none">
            <input type="hidden" name="page" value="stock">
            <div class="relative min-w-[200px] flex-1 md:flex-none">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
              <input type="text" name="keyword" value="<?= htmlspecialchars($filter['keyword']) ?>" placeholder="Cari BHP..." 
                class="w-full h-9 pl-9 pr-4 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all">
            </div>
            <button type="submit" class="h-9 px-4 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-700 transition-colors">
              Cari
            </button>
            <?php if($filter['keyword']): ?>
              <a href="?page=stock" class="h-9 w-9 flex items-center justify-center border border-slate-200 text-slate-400 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </form>
          <span id="badgeTotal" class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[11px] shrink-0">
            <?= $totalRecord ?> record
          </span>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto w-full">
        <table class="w-full text-left whitespace-nowrap">
          <thead>
            <tr class="text-[11px] uppercase tracking-widest text-slate-400 border-b border-slate-100 font-bold">
              <th class="py-4 px-3">TANGGAL</th>
              <th class="py-4 px-3">BARANG</th>
              <th class="py-4 px-3">JUMLAH</th>
              <th class="py-4 px-3">OLEH</th>
              <th class="py-4 px-3"></th>
            </tr>
          </thead>
          <tbody id="tbodyStok" class="text-[13px] font-plex divide-y divide-slate-50/50">
            <?php if (empty($stokList)): ?>
            <tr id="emptyRowStok">
              <td colspan="5" class="py-16 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <i class="fas fa-box-open text-3xl opacity-50"></i>
                  <p class="font-medium text-[14px]">Belum ada riwayat stok masuk</p>
                  <p class="text-[12px]">Klik "Input Stok" untuk mencatat penerimaan barang</p>
                </div>
              </td>
            </tr>
            <?php else: ?>
              <?php foreach ($stokList as $row): ?>
              <tr class="hover:bg-slate-50/50 transition-colors group stok-row" data-id="<?= $row['id_stok_masuk'] ?>">
                <td class="py-5 px-3">
                  <div class="font-medium text-slate-500"><?= date('d M Y', strtotime($row['tanggal_terima'])) ?></div>
                  <div class="text-[11px] text-slate-400 mt-0.5"><?= date('H:i', strtotime($row['created_at'])) ?></div>
                </td>
                <td class="py-5 px-3">
                  <div class="font-semibold text-slate-700"><?= htmlspecialchars($row['Nama_bhp']) ?></div>
                  <?php if (!empty($row['Kode_bhp'])): ?>
                  <div class="text-[11px] text-slate-400 mt-0.5"><?= htmlspecialchars($row['Kode_bhp']) ?></div>
                  <?php endif; ?>
                </td>
                <td class="py-5 px-3">
                  <span class="font-bold text-brand-600">
                    +<?= $row['jumlah'] ?> <?= htmlspecialchars($row['Nama_satuan'] ?? '') ?>
                  </span>
                </td>
                <td class="py-5 px-3 font-medium text-slate-400">
                  <?= htmlspecialchars($row['nama_user'] ?? 'Sistem') ?>
                </td>
                <td class="py-5 px-3 text-right">
                  <button
                    onclick="deleteStok(<?= $row['id_stok_masuk'] ?>, this)"
                    class="text-red-400 hover:text-red-600 transition-colors"
                    title="Hapus"
                  ><i class="far fa-trash-alt text-[14px]"></i></button>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
      <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
        <span class="text-[12px] font-medium text-slate-400">Halaman <span class="font-bold text-slate-700"><?= $p ?></span> dari <?= $totalPages ?></span>
        <div class="flex items-center gap-1.5">
          <?php
            $q = $_GET; unset($q['p']);
            $qs = http_build_query($q);
            $qs = $qs ? '&'.$qs : '';
            if ($p > 1): 
          ?>
            <a href="?p=<?= $p - 1 ?><?= $qs ?>" class="h-9 px-3 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-all font-medium">
              <i class="fas fa-chevron-left text-[10px]"></i>
            </a>
          <?php endif; ?>
          <?php 
          $start = max(1, $p - 2); $end = min($totalPages, $p + 2);
          for ($i = $start; $i <= $end; $i++): $isActive = ($i === $p); ?>
            <a href="?p=<?= $i ?><?= $qs ?>" class="h-9 w-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all <?= $isActive ? 'bg-brand-600 text-white shadow-md shadow-brand-100' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>"><?= $i ?></a>
          <?php endfor; ?>
          <?php if ($p < $totalPages): ?>
            <a href="?p=<?= $p + 1 ?><?= $qs ?>" class="h-9 px-3 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-all font-medium">
              <i class="fas fa-chevron-right text-[10px]"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
  <!-- MODAL: Input Stok Masuk                               -->
  <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
  <div id="modalStokMasuk" class="fixed inset-0 z-[99] hidden flex items-center justify-center font-plex">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModalStok()"></div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl m-4 overflow-hidden flex flex-col">

      <!-- Banner Header -->
      <div class="p-6" style="background: linear-gradient(135deg, #006B47 0%, #10B981 100%);">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full border border-white/20 bg-white/10 flex items-center justify-center text-white">
            <i class="fas fa-arrow-down shadow-sm"></i>
          </div>
          <div>
            <h2 class="text-lg font-bold text-white mb-0.5">Input Stok Masuk</h2>
            <p class="text-[13px] text-emerald-50">Catat penerimaan barang baru (Restock)</p>
          </div>
        </div>
      </div>

      <!-- Form -->
      <form id="formStokMasuk" onsubmit="submitStokMasuk(event)">
        <div class="bg-white px-6 py-6">
          <div class="flex flex-col gap-5">

            <!-- Pilih Barang -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Pilih Barang BHP <span class="text-red-500">*</span></label>
              <div class="relative">
                <i class="fa-solid fa-box absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <select name="id_bhp" id="selectBhpStok" required
                  class="w-full h-11 pl-10 pr-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors appearance-none cursor-pointer">
                  <option value="" disabled selected>Pilih item BHP...</option>
                  <?php foreach ($bhpList as $bhp): ?>
                  <option value="<?= $bhp['id_bhp'] ?>">
                    <?= htmlspecialchars($bhp['Nama_bhp']) ?>
                    <?= !empty($bhp['Nama_satuan']) ? '(' . htmlspecialchars($bhp['Nama_satuan']) . ')' : '' ?>
                    â€” Stok: <?= $bhp['Jumlah'] ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
              </div>
            </div>

            <!-- Jumlah & Tanggal Terima -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="block text-[12px] font-bold text-slate-600 mb-2">Jumlah Masuk <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" id="inputJumlahStok" min="1" value="1" required
                  class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
              </div>
              <div>
                <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Terima <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_terima" id="inputTglTerima" required
                  class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
              </div>
            </div>

            <!-- Tanggal Kedaluarsa -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Kedaluarsa <span class="text-slate-400 font-normal">(Opsional)</span></label>
              <input type="date" name="tgl_kadaluarsa"
                class="w-full h-11 px-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
            </div>

            <!-- Catatan -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Catatan Tambahan (Opsional)</label>
              <textarea name="catatan" placeholder="Nomor faktur, kondisi barang, dll..."
                class="w-full min-h-[5rem] px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors resize-y placeholder:text-slate-400"></textarea>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-5 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50">
          <button type="button" onclick="closeModalStok()"
            class="h-10 px-6 rounded-lg font-bold text-[13px] text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
            Batal
          </button>
          <button type="submit" id="btnSubmitStok"
            class="h-10 px-6 rounded-lg font-bold text-[13px] text-white bg-brand-500 shadow-sm shadow-brand-500/30 hover:bg-brand-600 transition-colors flex items-center gap-2">
            <i class="fas fa-save text-[11px]"></i> Simpan Stok Masuk
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<!-- JAVASCRIPT                                                 -->
<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<script>
// â”€â”€ Set default tanggal hari ini â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
(function () {
  const today = new Date().toISOString().split('T')[0];
  const el = document.getElementById('inputTglTerima');
  if (el) el.value = today;
})();

// â”€â”€ Modal helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function openModalStok() {
  document.getElementById('modalStokMasuk').classList.remove('hidden');
}
function closeModalStok() {
  document.getElementById('modalStokMasuk').classList.add('hidden');
  document.getElementById('formStokMasuk').reset();
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('inputTglTerima').value = today;
}

// â”€â”€ Toast notification â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function showToastStok(msg, success = true) {
  const toast = document.getElementById('toastStok');
  const inner = document.getElementById('toastStokInner');
  const icon  = document.getElementById('toastStokIcon');
  const msgEl = document.getElementById('toastStokMsg');

  msgEl.textContent = msg;
  if (success) {
    inner.className = 'flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-emerald-500 text-white';
    icon.className  = 'fas fa-check-circle text-[15px]';
  } else {
    inner.className = 'flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-red-500 text-white';
    icon.className  = 'fas fa-exclamation-circle text-[15px]';
  }
  toast.classList.remove('hidden');
  setTimeout(() => toast.classList.add('hidden'), 3500);
}

// â”€â”€ Tambah baris ke tabel â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function appendStokRow(item) {
  const tbody = document.getElementById('tbodyStok');

  // Hapus "empty row" jika ada
  const emptyRow = document.getElementById('emptyRowStok');
  if (emptyRow) emptyRow.remove();

  // Format tanggal
  const tglTerima = item.tanggal_terima
    ? new Date(item.tanggal_terima + 'T00:00:00').toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
    : '-';
  const jamInput = item.created_at
    ? item.created_at.substring(11, 16)
    : new Date().toTimeString().substring(0, 5);

  const tr = document.createElement('tr');
  tr.className = 'hover:bg-slate-50/50 transition-colors group stok-row';
  tr.dataset.id = item.id_stok_masuk;
  tr.innerHTML = `
    <td class="py-5 px-3">
      <div class="font-medium text-slate-500">${tglTerima}</div>
      <div class="text-[11px] text-slate-400 mt-0.5">${jamInput}</div>
    </td>
    <td class="py-5 px-3">
      <div class="font-semibold text-slate-700">${escapeHtml(item.Nama_bhp || '')}</div>
      ${item.Kode_bhp ? `<div class="text-[11px] text-slate-400 mt-0.5">${escapeHtml(item.Kode_bhp)}</div>` : ''}
    </td>
    <td class="py-5 px-3">
      <span class="font-bold text-brand-600">+${item.jumlah} ${escapeHtml(item.Nama_satuan || '')}</span>
    </td>
    <td class="py-5 px-3 font-medium text-slate-400">${escapeHtml(item.nama_user || 'Sistem')}</td>
    <td class="py-5 px-3 text-right">
      <button onclick="deleteStok(${item.id_stok_masuk}, this)" class="text-red-400 hover:text-red-600 transition-colors" title="Hapus">
        <i class="far fa-trash-alt text-[14px]"></i>
      </button>
    </td>
  `;
  tbody.insertBefore(tr, tbody.firstChild);

  // Update badge
  updateBadge(1);
}

function updateBadge(delta) {
  const badge = document.getElementById('badgeTotal');
  const match = badge.textContent.trim().match(/\d+/);
  const cur   = match ? parseInt(match[0]) : 0;
  badge.textContent = (cur + delta) + ' record';
}

function escapeHtml(str) {
  const d = document.createElement('div');
  d.appendChild(document.createTextNode(str));
  return d.innerHTML;
}

// â”€â”€ Submit tambah stok â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function submitStokMasuk(e) {
  e.preventDefault();
  const form = document.getElementById('formStokMasuk');
  const btn  = document.getElementById('btnSubmitStok');
  const fd   = new FormData(form);
  fd.append('action', 'add_stok_masuk');

  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin text-[11px]"></i> Menyimpan...';

  fetch('/BHP-Poli-Gigi/process/stok_masuk_process.php', {
    method : 'POST',
    body   : fd,
    credentials: 'same-origin'
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      showToastStok(res.message, true);
      closeModalStok();
      // Reload halaman agar data refresh (termasuk nama_user dan format dari server)
      setTimeout(() => window.location.reload(), 900);
    } else {
      showToastStok(res.message || 'Terjadi kesalahan.', false);
    }
  })
  .catch(() => showToastStok('Koneksi gagal, coba lagi.', false))
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-save text-[11px]"></i> Simpan Stok Masuk';
  });
}

// â”€â”€ Hapus stok â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function deleteStok(id, btn) {
  if (!confirm('Yakin ingin menghapus data stok masuk ini?\nJumlah stok BHP akan dikurangi kembali.')) return;

  const originalHTML = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin text-[12px]"></i>';

  const fd = new FormData();
  fd.append('action', 'delete_stok_masuk');
  fd.append('id', id);

  fetch('/BHP-Poli-Gigi/process/stok_masuk_process.php', {
    method : 'POST',
    body   : fd,
    credentials: 'same-origin'
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      // Hapus baris dari DOM
      const row = document.querySelector(`.stok-row[data-id="${id}"]`);
      if (row) {
        row.style.transition = 'opacity 0.3s, transform 0.3s';
        row.style.opacity    = '0';
        row.style.transform  = 'translateX(20px)';
        setTimeout(() => {
          row.remove();
          updateBadge(-1);
          // Tampilkan empty jika tidak ada baris
          if (!document.querySelector('.stok-row')) {
            const tbody = document.getElementById('tbodyStok');
            tbody.innerHTML = `
              <tr id="emptyRowStok">
                <td colspan="5" class="py-16 text-center">
                  <div class="flex flex-col items-center gap-3 text-slate-400">
                    <i class="fas fa-box-open text-3xl opacity-50"></i>
                    <p class="font-medium text-[14px]">Belum ada riwayat stok masuk</p>
                    <p class="text-[12px]">Klik "Input Stok" untuk mencatat penerimaan barang</p>
                  </div>
                </td>
              </tr>`;
          }
        }, 300);
      }
      showToastStok(res.message, true);
    } else {
      btn.disabled = false;
      btn.innerHTML = originalHTML;
      showToastStok(res.message || 'Gagal menghapus.', false);
    }
  })
  .catch(() => {
    btn.disabled = false;
    btn.innerHTML = originalHTML;
    showToastStok('Koneksi gagal, coba lagi.', false);
  });
}
</script>
