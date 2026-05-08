<?php
/**
 * Laporan Stok Masuk — Kepala Klinik (View Only)
 * Menampilkan riwayat penerimaan stok BHP dari database
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Config\Database;

$db = Database::getInstance()->getConnection();

// ── Filter ─────────────────────────────────────────────────────
$tglMulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
$tglAkhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$keyword  = trim($_GET['keyword'] ?? '');

// ── Pagination ─────────────────────────────────────────────────
$limit  = 15;
$page_n = max(1, (int)($_GET['p'] ?? 1));
$offset = ($page_n - 1) * $limit;

// ── Query ──────────────────────────────────────────────────────
$where  = ['sm.tanggal_terima BETWEEN ? AND ?'];
$params = [$tglMulai, $tglAkhir];
if ($keyword !== '') {
    $kw = '%' . $keyword . '%';
    $where[] = '(b.Nama_bhp LIKE ? OR sm.catatan LIKE ?)';
    $params[] = $kw;
    $params[] = $kw;
}
$whereSQL = ' WHERE ' . implode(' AND ', $where);

// Count
$stmtCnt = $db->prepare("SELECT COUNT(*) FROM stok_masuk sm
    LEFT JOIN bhp b ON sm.id_bhp = b.id_bhp" . $whereSQL);
$stmtCnt->execute($params);
$totalRows  = (int)$stmtCnt->fetchColumn();
$totalPages = max(1, (int)ceil($totalRows / $limit));

// Data
$stmtData = $db->prepare("
    SELECT sm.tanggal_terima, b.Kode_bhp, b.Nama_bhp,
           sm.jumlah, s.Nama_satuan,
           sm.tgl_kadaluarsa, sm.catatan,
           u.Nama_lengkap AS nama_user, sm.created_at
    FROM stok_masuk sm
    LEFT JOIN bhp        b ON sm.id_bhp   = b.id_bhp
    LEFT JOIN satuan_bhp s ON b.id_satuan  = s.id_satuan
    LEFT JOIN user       u ON sm.id_user   = u.id_user" .
    $whereSQL . "
    ORDER BY sm.tanggal_terima DESC
    LIMIT ? OFFSET ?
");
$stmtData->execute(array_merge($params, [$limit, $offset]));
$rows = $stmtData->fetchAll();
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto space-y-6 w-full">

    <!-- Header Banner -->
    <div class="relative w-full rounded-2xl overflow-hidden"
      style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);">
      <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
        <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
        <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
      </div>
      <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 py-6 sm:px-8 sm:py-7">
        <div class="flex items-center gap-4 sm:gap-5 min-w-0">
          <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
            style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
            <i class="fas fa-boxes-stacked text-white text-xl sm:text-2xl"></i>
          </div>
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Laporan Stok Masuk BHP</h1>
            <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block">
              Riwayat penerimaan bahan habis pakai berdasarkan periode
            </p>
          </div>
        </div>
        <!-- Export buttons -->
        <div class="flex gap-2 flex-shrink-0">
          <button onclick="exportStokLaporan('pdf')"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors">
            <i class="fas fa-file-pdf"></i> PDF
          </button>
          <button onclick="exportStokLaporan('excel')"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors">
            <i class="fas fa-file-excel"></i> Excel
          </button>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
      <input type="hidden" name="page" value="laporan_stok">
      <div class="flex flex-wrap items-end gap-4">
        <div>
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-plex">Periode</label>
          <div class="flex items-center gap-2">
            <input type="date" name="tgl_mulai" value="<?= htmlspecialchars($tglMulai) ?>"
              class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <span class="text-slate-300">—</span>
            <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tglAkhir) ?>"
              class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
          </div>
        </div>
        <div class="flex-1 min-w-[200px]">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 font-plex">Pencarian</label>
          <div class="relative">
            <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
              placeholder="Cari nama BHP atau catatan..."
              class="w-full h-10 pl-10 pr-4 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
          </div>
        </div>
        <button type="submit"
          class="h-10 px-5 rounded-xl text-sm font-plex font-semibold text-white transition-all"
          style="background:linear-gradient(135deg,#047857 0%,#34D399 100%)">
          <i class="fas fa-filter mr-1"></i> Terapkan
        </button>
        <?php if ($keyword || $tglMulai !== date('Y-m-01') || $tglAkhir !== date('Y-m-d')): ?>
        <a href="?page=laporan_stok" class="h-10 px-4 rounded-xl text-sm font-plex font-semibold text-slate-500 border border-slate-200 flex items-center hover:bg-slate-50 transition-colors">
          <i class="fas fa-times mr-1"></i> Reset
        </a>
        <?php endif; ?>
      </div>
    </form>

    <!-- Summary -->
    <div class="flex items-center gap-3">
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-3 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
          <i class="fas fa-boxes-stacked text-emerald-600 text-sm"></i>
        </div>
        <div>
          <p class="font-plex text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Total Record</p>
          <p class="font-display font-bold text-xl text-slate-800"><?= $totalRows ?></p>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left" style="min-width:700px">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
              <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Tgl Terima</th>
              <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama BHP</th>
              <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Jumlah</th>
              <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Satuan</th>
              <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Tgl Kadaluarsa</th>
              <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Catatan</th>
              <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Dicatat Oleh</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php if (empty($rows)): ?>
            <tr>
              <td colspan="7" class="px-5 py-14 text-center font-plex text-slate-400">
                <i class="fas fa-boxes-stacked text-4xl mb-3 opacity-30 block"></i>
                Tidak ada data stok masuk pada periode ini.
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($rows as $row): ?>
            <tr class="hover:bg-slate-50/50 transition-colors">
              <td class="px-5 py-4">
                <div class="font-plex text-sm font-medium text-slate-700">
                  <?= date('d M Y', strtotime($row['tanggal_terima'])) ?>
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="font-plex text-sm font-semibold text-slate-800"><?= htmlspecialchars($row['Nama_bhp'] ?? '-') ?></div>
                <?php if ($row['Kode_bhp']): ?>
                <div class="font-plex text-xs text-slate-400"><?= htmlspecialchars($row['Kode_bhp']) ?></div>
                <?php endif; ?>
              </td>
              <td class="px-5 py-4 text-center">
                <span class="inline-flex items-center justify-center min-w-[36px] h-8 px-2 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-sm font-plex">
                  <?= $row['jumlah'] ?>
                </span>
              </td>
              <td class="px-5 py-4 font-plex text-sm text-slate-500">
                <?= htmlspecialchars($row['Nama_satuan'] ?? '-') ?>
              </td>
              <td class="px-5 py-4 font-plex text-sm text-slate-500">
                <?php if ($row['tgl_kadaluarsa']): ?>
                  <?php
                    $kadaluarsa = strtotime($row['tgl_kadaluarsa']);
                    $isExpired  = $kadaluarsa < time();
                    $isNear     = $kadaluarsa < strtotime('+30 days') && !$isExpired;
                  ?>
                  <span class="font-medium <?= $isExpired ? 'text-red-500' : ($isNear ? 'text-amber-500' : 'text-slate-600') ?>">
                    <?= date('d M Y', $kadaluarsa) ?>
                    <?php if ($isExpired): ?><span class="ml-1 text-[10px] bg-red-50 text-red-500 px-1.5 py-0.5 rounded-full">Expired</span><?php endif; ?>
                    <?php if ($isNear): ?><span class="ml-1 text-[10px] bg-amber-50 text-amber-500 px-1.5 py-0.5 rounded-full">Mendekati</span><?php endif; ?>
                  </span>
                <?php else: ?>
                  <span class="text-slate-300">—</span>
                <?php endif; ?>
              </td>
              <td class="px-5 py-4 font-plex text-sm text-slate-500 max-w-[200px] truncate" title="<?= htmlspecialchars($row['catatan'] ?? '') ?>">
                <?= htmlspecialchars($row['catatan'] ?: '—') ?>
              </td>
              <td class="px-5 py-4 font-plex text-sm text-slate-500">
                <?= htmlspecialchars($row['nama_user'] ?? '-') ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1 || $totalRows > 0): ?>
      <div class="flex flex-col sm:flex-row items-center justify-between px-5 py-4 border-t border-slate-100 gap-3">
        <p class="font-plex text-sm text-slate-500">
          Menampilkan <?= min($offset + 1, $totalRows) ?>–<?= min($offset + $limit, $totalRows) ?>
          dari <?= $totalRows ?> record
        </p>
        <?php if ($totalPages > 1): ?>
        <div class="flex items-center gap-1.5">
          <?php
          $qp = $_GET; unset($qp['p']);
          $qs = http_build_query($qp); $qs = $qs ? '&' . $qs : '';
          if ($page_n > 1): ?>
          <a href="?p=<?= $page_n - 1 ?><?= $qs ?>" class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
            <i class="fas fa-chevron-left text-xs"></i>
          </a>
          <?php endif; ?>
          <?php for ($i = max(1, $page_n - 2); $i <= min($totalPages, $page_n + 2); $i++): ?>
          <a href="?p=<?= $i ?><?= $qs ?>"
            class="w-9 h-9 rounded-lg flex items-center justify-center font-plex text-sm transition-colors <?= $i === $page_n ? 'bg-brand-600 text-white font-semibold' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            <?= $i ?>
          </a>
          <?php endfor; ?>
          <?php if ($page_n < $totalPages): ?>
          <a href="?p=<?= $page_n + 1 ?><?= $qs ?>" class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
            <i class="fas fa-chevron-right text-xs"></i>
          </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
function exportStokLaporan(type) {
  const params = new URLSearchParams(window.location.search);
  const url = new URL('/BHP-Poli-Gigi/api/export.php', window.location.origin);
  url.searchParams.set('type', type);
  url.searchParams.set('page', 'stok');
  const tglMulai = params.get('tgl_mulai') || document.querySelector('[name="tgl_mulai"]')?.value || '';
  const tglAkhir = params.get('tgl_akhir') || document.querySelector('[name="tgl_akhir"]')?.value || '';
  const keyword  = params.get('keyword')   || document.querySelector('[name="keyword"]')?.value  || '';
  if (tglMulai) url.searchParams.set('tgl_mulai', tglMulai);
  if (tglAkhir) url.searchParams.set('tgl_akhir', tglAkhir);
  if (keyword)  url.searchParams.set('keyword', keyword);
  window.location.href = url.toString();
}
</script>
