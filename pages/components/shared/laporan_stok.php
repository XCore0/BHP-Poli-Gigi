<?php
use App\Config\Database;

$db = Database::getInstance()->getConnection();

// ── Filter ─────────────────────────────────────────────────────
$tglMulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
$tglAkhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$keyword  = trim($_GET['keyword'] ?? '');

// ── Pagination ─────────────────────────────────────────────────
$limit  = 10;
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
  <div class="max-w-[1400px] mx-auto flex flex-col gap-10 w-full">

  <section>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-[22px] font-display font-medium text-slate-800 tracking-[-0.02em]">Laporan Stok Masuk BHP</h2>
    </div>

    <!-- Filter -->
    <form method="GET" action="" class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex flex-col xl:flex-row xl:items-end justify-between gap-6 mb-6">
      <input type="hidden" name="page" value="<?= htmlspecialchars($_GET['page'] ?? 'stock') ?>">
      <div class="flex flex-col md:flex-row items-start md:items-end gap-4 w-full xl:w-auto">
        <div class="w-full md:w-auto">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-plex">Filter Tanggal</label>
          <div class="flex items-center gap-2">
            <input type="date" name="tgl_mulai" value="<?= htmlspecialchars($tglMulai) ?>"
              class="w-full md:w-[150px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
            <div class="w-4 h-[1px] bg-slate-300"></div>
            <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tglAkhir) ?>"
              class="w-full md:w-[150px] border border-slate-200 bg-slate-50 rounded-xl h-11 px-3.5 text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
          </div>
        </div>
        <div class="w-full md:w-[250px]">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 font-plex">Pencarian</label>
          <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
              placeholder="Cari nama BHP atau catatan..."
              class="w-full h-11 pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all placeholder:text-slate-400 font-medium text-slate-700 shadow-sm hover:border-slate-300">
          </div>
        </div>
        <button type="submit"
          class="w-full md:w-auto h-11 px-6 rounded-xl text-sm font-semibold text-white transition-opacity hover:opacity-90 active:scale-[0.98]"
          style="background: linear-gradient(135deg, #008D5B 0%, #00B47A 100%);">
          Terapkan
        </button>
      </div>
      <div class="flex items-center gap-3 w-full xl:w-auto mt-4 xl:mt-0 pt-4 xl:pt-0 border-t xl:border-t-0 border-slate-100">
        <button type="button" onclick="exportStokLaporan('pdf')"
          class="flex-1 xl:flex-none h-11 px-5 rounded-xl text-sm font-semibold text-red-500 border border-red-100 bg-red-50/50 flex items-center justify-center gap-2 hover:bg-red-50 transition-colors">
          <i class="far fa-file-pdf"></i> Export PDF
        </button>
        <button type="button" onclick="exportStokLaporan('excel')"
          class="flex-1 xl:flex-none h-11 px-5 rounded-xl text-sm font-semibold text-emerald-600 border border-emerald-100 bg-emerald-50/50 flex items-center justify-center gap-2 hover:bg-emerald-50 transition-colors">
          <i class="far fa-file-excel"></i> Export Excel
        </button>
      </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden font-plex">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" style="min-width:700px">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Tgl Terima</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Nama BHP</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Jumlah</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Satuan</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Tgl Kadaluarsa</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Catatan</th>
              <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Dicatat Oleh</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50 text-sm">
            <?php if (empty($rows)): ?>
            <tr>
              <td colspan="7" class="px-6 py-14 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <i class="fas fa-boxes-stacked text-3xl opacity-40"></i>
                  <p class="font-medium">Tidak ada data stok masuk pada periode ini</p>
                </div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($rows as $row): ?>
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4 align-top">
                <div class="font-medium text-slate-700"><?= date('d M Y', strtotime($row['tanggal_terima'])) ?></div>
                <div class="text-[11px] font-medium text-slate-400 mt-0.5 uppercase tracking-wider">
                  <?= date('H:i', strtotime($row['created_at'])) ?> WIB
                </div>
              </td>
              <td class="px-6 py-4 align-top">
                <div class="font-bold text-slate-800 text-[15px]"><?= htmlspecialchars($row['Nama_bhp'] ?? '-') ?></div>
                <?php if ($row['Kode_bhp']): ?>
                <div class="font-medium text-slate-400 text-[12px] mt-0.5"><?= htmlspecialchars($row['Kode_bhp']) ?></div>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 align-top text-center">
                <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-sm">
                  <?= $row['jumlah'] ?>
                </span>
              </td>
              <td class="px-6 py-4 align-top text-slate-600 font-medium">
                <?= htmlspecialchars($row['Nama_satuan'] ?? '-') ?>
              </td>
              <td class="px-6 py-4 align-top">
                <?php if ($row['tgl_kadaluarsa']): ?>
                  <?php
                    $kadaluarsa = strtotime($row['tgl_kadaluarsa']);
                    $isExpired  = $kadaluarsa < time();
                    $isNear     = $kadaluarsa < strtotime('+30 days') && !$isExpired;
                  ?>
                  <div class="font-medium <?= $isExpired ? 'text-red-500' : ($isNear ? 'text-amber-500' : 'text-slate-600') ?>">
                    <?= date('d M Y', $kadaluarsa) ?>
                  </div>
                  <?php if ($isExpired): ?><div class="mt-1"><span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-[10px] font-bold">Expired</span></div><?php endif; ?>
                  <?php if ($isNear): ?><div class="mt-1"><span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full text-[10px] font-bold">Mendekati</span></div><?php endif; ?>
                <?php else: ?>
                  <span class="text-slate-400">—</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 align-top text-slate-500 text-[13px] max-w-[200px] truncate" title="<?= htmlspecialchars($row['catatan'] ?? '') ?>">
                <?= htmlspecialchars($row['catatan'] ?: '—') ?>
              </td>
              <td class="px-6 py-4 align-top text-slate-500 font-medium text-[13px]">
                <?= htmlspecialchars($row['nama_user'] ?? '-') ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/30 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="font-plex text-sm text-slate-500 font-medium">
          Menampilkan <span class="font-bold text-slate-700"><?= min($offset + 1, $totalRows ?: 0) ?></span> hingga <span class="font-bold text-slate-700"><?= min($offset + $limit, $totalRows) ?></span> dari <span class="font-bold text-slate-700"><?= $totalRows ?></span> data
        </p>
        <?php if ($totalPages > 1): ?>
        <div class="flex items-center gap-1.5">
          <?php
          $qp = $_GET; unset($qp['p']);
          $qs = http_build_query($qp); $qs = $qs ? '&' . $qs : '';
          if ($page_n > 1): ?>
          <a href="?p=<?= $page_n - 1 ?><?= $qs ?>" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-white hover:border-brand-500 hover:text-brand-500 transition-colors bg-white shadow-sm" title="Sebelumnya">
            <i class="fas fa-chevron-left text-[10px]"></i>
          </a>
          <?php endif; ?>
          <?php for ($i = max(1, $page_n - 2); $i <= min($totalPages, $page_n + 2); $i++): ?>
          <a href="?p=<?= $i ?><?= $qs ?>"
            class="w-9 h-9 rounded-xl flex items-center justify-center font-plex text-sm transition-colors shadow-sm <?= $i === $page_n ? 'bg-brand-600 border-brand-600 text-white font-bold' : 'border border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-500 font-medium' ?>">
            <?= $i ?>
          </a>
          <?php endfor; ?>
          <?php if ($page_n < $totalPages): ?>
          <a href="?p=<?= $page_n + 1 ?><?= $qs ?>" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-white hover:border-brand-500 hover:text-brand-500 transition-colors bg-white shadow-sm" title="Selanjutnya">
            <i class="fas fa-chevron-right text-[10px]"></i>
          </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  </div>
</div>

<script>
function exportStokLaporan(type) {
  const params = new URLSearchParams(window.location.search);
  const url = new URL('/api/export.php', window.location.origin);
  url.searchParams.set('type', type);
  url.searchParams.set('page', 'stok');
  const tglMulai = document.querySelector('[name="tgl_mulai"]')?.value || '';
  const tglAkhir = document.querySelector('[name="tgl_akhir"]')?.value || '';
  const keyword  = document.querySelector('[name="keyword"]')?.value  || '';
  if (tglMulai) url.searchParams.set('tgl_mulai', tglMulai);
  if (tglAkhir) url.searchParams.set('tgl_akhir', tglAkhir);
  if (keyword)  url.searchParams.set('keyword', keyword);
  window.location.href = url.toString();
}
</script>
