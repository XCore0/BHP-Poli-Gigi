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

    <!-- ── NOTIFICATION TOAST ────────────────────────────── -->
    <div id="toastStok" class="fixed top-6 right-6 z-[10001] hidden">
      <div class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs" id="toastStokInner">
        <i id="toastStokIcon" class="text-[15px]"></i>
        <span id="toastStokMsg"></span>
      </div>
    </div>

    <!-- ── HEADER BANNER ────────────────────────────────── -->
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

    <!-- ── RIWAYAT STOK MASUK ────────────────────────────── -->
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
              <th class="py-4 px-3 text-center">ISI</th>
              <th class="py-4 px-3 text-center">JUMLAH (KOTAK)</th>
              <th class="py-4 px-3 text-center">TOTAL UNIT</th>
              <th class="py-4 px-3">KEDALUARSA</th>
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
                  <?php if (!empty($row['catatan'])): ?>
                    <div class="text-[10px] text-slate-400 mt-1 flex items-start gap-1 max-w-[200px] whitespace-normal">
                      <i class="fas fa-info-circle mt-0.5"></i>
                      <span><?= htmlspecialchars($row['catatan']) ?></span>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="py-5 px-3 text-center font-medium text-slate-500">
                  <?= $row['isi_per_stok'] ?? 1 ?>
                </td>
                <td class="py-5 px-3 text-center">
                  <span class="font-bold text-slate-700">
                    <?= $row['jumlah'] ?> <?= htmlspecialchars($row['Nama_satuan'] ?? '') ?>
                  </span>
                </td>
                <td class="py-5 px-3 text-center">
                  <span class="font-bold text-brand-600">
                    +<?= $row['jumlah'] * ($row['isi_per_stok'] ?? 1) ?> <span class="text-[10px] uppercase">Unit</span>
                  </span>
                </td>
                <td class="py-5 px-3">
                  <?php if ($row['tgl_kadaluarsa']): ?>
                    <div class="flex items-center gap-1.5">
                      <i class="far fa-calendar-times text-amber-500 text-[11px]"></i>
                      <span class="font-medium text-slate-600"><?= date('d M Y', strtotime($row['tgl_kadaluarsa'])) ?></span>
                    </div>
                  <?php else: ?>
                    <span class="text-slate-300">—</span>
                  <?php endif; ?>
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

  <!-- ────────────────────────────────────────── -->
  <!-- MODAL: Input Stok Masuk                               -->
  <!-- ────────────────────────────────────────── -->
  <div id="modalStokMasuk" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 font-plex"
    style="background:rgba(15,23,42,0.45);backdrop-filter:blur(4px);"
    onclick="if(event.target===this)closeModalStok()">
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
      style="animation: modalIn .25s cubic-bezier(.34,1.56,.64,1) both;">

      <!-- Banner Header -->
      <div class="relative px-7 pt-6 pb-5 flex-shrink-0" style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);">
        <button type="button" onclick="closeModalStok()" class="absolute top-4 right-5 text-white/70 hover:text-white text-xl leading-none transition-colors">&times;</button>
        <h2 class="font-bold text-white text-xl leading-tight">Input Stok Masuk</h2>
        <p class="text-white/80 text-sm mt-1">Catat penerimaan barang baru (Restock).</p>
      </div>

      <!-- Form -->
      <form id="formStokMasuk" onsubmit="submitStokMasuk(event)">
        <div class="bg-white px-6 py-6">
          <!-- Error Area -->
          <div id="errorStokMasuk" class="hidden mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
            <div class="flex-1">
              <p class="text-[13px] font-bold text-red-700">Terjadi Kesalahan</p>
              <p id="errorStokMsg" class="text-[12px] text-red-600 mt-0.5"></p>
            </div>
            <button type="button" onclick="document.getElementById('errorStokMasuk').classList.add('hidden')" class="text-red-400 hover:text-red-600">
              <i class="fas fa-times text-[12px]"></i>
            </button>
          </div>

          <div class="flex flex-col gap-5">

            <!-- Pilih Barang -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Pilih Barang BHP <span class="text-red-500">*</span></label>
              <div class="relative">
                <i class="fa-solid fa-box absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <select name="id_bhp" id="selectBhpStok" required
                  onchange="updateIsiPerStok(this)"
                  class="w-full h-11 pl-10 pr-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors appearance-none cursor-pointer">
                  <option value="" disabled selected>Pilih item BHP...</option>
                  <?php foreach ($bhpList as $bhp): ?>
                  <option value="<?= $bhp['id_bhp'] ?>" 
                    data-isi="<?= (int)($bhp['isi_per_stok'] ?? 1) ?>"
                    data-stok="<?= (int)($bhp['Jumlah'] ?? 0) ?>"
                    data-satuan="<?= htmlspecialchars($bhp['Nama_satuan'] ?? 'Unit') ?>">
                    <?= htmlspecialchars($bhp['Nama_bhp']) ?>
                    <?= !empty($bhp['Nama_satuan']) ? '(' . htmlspecialchars($bhp['Nama_satuan']) . ')' : '' ?>
                    — Stok: <?= $bhp['Jumlah'] ?>
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

            <!-- Isi Per Stok -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2 flex items-center gap-1.5">
                Isi Per Stok
                <span class="text-slate-400 font-normal" title="Jumlah unit kecil dalam 1 kemasan. Contoh: 1 box = 50 pcs, maka isi = 50">
                  <i class="fa-solid fa-circle-info text-[11px] cursor-help"></i>
                </span>
              </label>
              <div class="relative">
                <i class="fa-solid fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input type="number" name="isi_per_stok" id="inputIsiPerStok" min="1" value="1" required
                  class="w-full h-11 pl-10 pr-4 bg-slate-50/50 border border-slate-200 rounded-xl text-[13px] text-slate-700 outline-none focus:border-brand-500 transition-colors">
              </div>
              <!-- Info dinamis saat BHP dipilih -->
              <div id="infoIsiPerStok" class="hidden mt-2 px-3 py-2 rounded-lg text-[11px] font-medium"
                style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;">
                <i class="fas fa-info-circle mr-1"></i>
                <span id="txtIsiPerStok"></span>
              </div>
            </div>

            <!-- Tanggal Kedaluarsa -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Kedaluarsa <span class="text-red-500">*</span></label>
              <input type="date" name="tgl_kadaluarsa" required
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

<!-- ────────────────────────────────────────── -->
<!-- JAVASCRIPT                                                 -->
<!-- ────────────────────────────────────────── -->
<script>
// ── Set default tanggal hari ini ──
(function () {
  const today = new Date().toISOString().split('T')[0];
  const el = document.getElementById('inputTglTerima');
  if (el) el.value = today;
})();

// ── Modal helpers ──
function openModalStok() {
  const m = document.getElementById('modalStokMasuk');
  if (document.getElementById('errorStokMasuk')) {
    document.getElementById('errorStokMasuk').classList.add('hidden');
  }
  if (m.parentNode !== document.body) {
    document.body.appendChild(m);
  }
  m.classList.remove('hidden');
  m.classList.add('flex');
}
function updateIsiPerStok(select) {
  const infoEl = document.getElementById('infoIsiPerStok');
  const textEl = document.getElementById('txtIsiPerStok');
  const inputIsi = document.getElementById('inputIsiPerStok');

  if (!select.value) {
    infoEl.classList.add('hidden');
    inputIsi.readOnly = false;
    inputIsi.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-400');
    return;
  }

  const opt = select.options[select.selectedIndex];
  const isi = parseInt(opt.dataset.isi) || 1;
  const stok = parseInt(opt.dataset.stok) || 0;
  const satuan = opt.dataset.satuan || '';

  // Aturan: Jika stok sudah ada (>0) ATAU isi sudah pernah diset (>1), maka LOCK
  // Mengapa >1? Karena default database adalah 1. Jika masih 1 dan stok 0, dianggap belum diset.
  if (stok > 0 || isi > 1) {
    inputIsi.value = isi;
    inputIsi.readOnly = true;
    inputIsi.classList.add('bg-slate-100', 'cursor-not-allowed', 'text-slate-400');
    inputIsi.title = "Kapasitas sudah ditentukan pada input stok pertama kali.";
    
    textEl.textContent = `Item ini sudah memiliki kapasitas tetap: 1 ${satuan} = ${isi} unit kecil.`;
    infoEl.classList.remove('hidden');
    infoEl.style.background = '#f8fafc';
    infoEl.style.color = '#64748b';
    infoEl.style.borderColor = '#e2e8f0';
  } else {
    inputIsi.value = 1;
    inputIsi.readOnly = false;
    inputIsi.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-400');
    inputIsi.title = "";
    
    infoEl.classList.add('hidden');
  }
}
function closeModalStok() {
  const m = document.getElementById('modalStokMasuk');
  m.classList.add('hidden');
  m.classList.remove('flex');
  document.getElementById('formStokMasuk').reset();
  document.getElementById('errorStokMasuk').classList.add('hidden');
  document.getElementById('inputIsiPerStok').value = 1;
  document.getElementById('infoIsiPerStok').classList.add('hidden');
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('inputTglTerima').value = today;
}

// ── Toast notification ──
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

// ── Tambah baris ke tabel ──
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
      ${item.catatan ? `<div class="text-[10px] text-slate-400 mt-1 flex items-start gap-1 max-w-[200px] whitespace-normal"><i class="fas fa-info-circle mt-0.5"></i><span>${escapeHtml(item.catatan)}</span></div>` : ''}
    </td>
    <td class="py-5 px-3 text-center font-medium text-slate-500">${item.isi_per_stok || 1}</td>
    <td class="py-5 px-3 text-center">
      <span class="font-bold text-slate-700">${item.jumlah} ${escapeHtml(item.Nama_satuan || '')}</span>
    </td>
    <td class="py-5 px-3 text-center">
      <span class="font-bold text-brand-600">+${(item.jumlah * (item.isi_per_stok || 1))} <span class="text-[10px] uppercase">Unit</span></span>
    </td>
    <td class="py-5 px-3">
      ${item.tgl_kadaluarsa ? `<div class="flex items-center gap-1.5"><i class="far fa-calendar-times text-amber-500 text-[11px]"></i><span class="font-medium text-slate-600">${item.tgl_kadaluarsa}</span></div>` : '<span class="text-slate-300">—</span>'}
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

// ── Submit tambah stok ──
function submitStokMasuk(e) {
  e.preventDefault();
  const form = document.getElementById('formStokMasuk');
  const btn  = document.getElementById('btnSubmitStok');
  const fd   = new FormData(form);
  fd.append('action', 'add_stok_masuk');

  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin text-[11px]"></i> Menyimpan...';

  fetch('/BHP-Poli-Gigi/process/stok_masuk_process.php', { method: 'POST', body: fd, credentials: 'same-origin' })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      showToastStok(res.message, true);
      appendStokRow(res.data);
      setTimeout(() => closeModalStok(), 500);
    } else {
      // Show error on top of form as requested
      const errEl = document.getElementById('errorStokMasuk');
      const errMsg = document.getElementById('errorStokMsg');
      errMsg.textContent = res.message || 'Gagal menyimpan data.';
      errEl.classList.remove('hidden');
      errEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  })
  .catch(err => {
    console.error(err);
    const errEl = document.getElementById('errorStokMasuk');
    const errMsg = document.getElementById('errorStokMsg');
    errMsg.textContent = 'Terjadi kesalahan koneksi atau server.';
    errEl.classList.remove('hidden');
  })
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-save text-[11px]"></i> Simpan Stok Masuk';
  });
}

// â”€â”€ Hapus stok â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function deleteStok(id, btn) {
  showDeleteConfirm('Hapus Stok Masuk?', 'Yakin ingin menghapus data stok masuk ini? Jumlah stok BHP akan dikurangi kembali.', () => {
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
  });
}
</script>

<style>
  @keyframes modalIn {
    from { opacity: 0; transform: scale(0.92) translateY(16px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
  }
</style>
