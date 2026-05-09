<?php
use App\Classes\PemakaianManager;
use App\Classes\BhpManager;
use App\Config\Database;
use App\Classes\Auth;

$auth = new Auth();
$user = $auth->getCurrentUser();
$uid  = (int)($user['id'] ?? 0);

$db = Database::getInstance()->getConnection();

// ── Filter & Search ───────────────────────────────────────────
$tglMulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
$tglAkhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$keyword  = trim($_GET['keyword'] ?? '');

// ── Pagination Constants ──────────────────────────────────────
$limit1 = 10;
$limit2 = 12;
$p1     = max(1, (int)($_GET['p1'] ?? 1));
$p2     = max(1, (int)($_GET['p2'] ?? 1));
$off1   = ($p1 - 1) * $limit1;
$off2   = ($p2 - 1) * $limit2;

// ── Search Logic ──────────────────────────────────────────────
$whereSearch = "";
$searchParams = [$tglMulai, $tglAkhir];
if ($keyword !== '') {
    $whereSearch = " AND (p.nama_pasien LIKE ? OR b.Nama_bhp LIKE ?)";
    $kw = '%' . $keyword . '%';
    $searchParams[] = $kw;
    $searchParams[] = $kw;
}

// ── Section 1: Riwayat detail pemakaian BHP (join detail+header) ──
$countDetailQuery = "
    SELECT COUNT(d.id_detail)
    FROM pemakaian_bhp_detail d
    JOIN pemakaian_bhp  p ON d.id_pemakaian = p.id_pemakaian
    JOIN bhp            b ON d.id_bhp        = b.id_bhp
    WHERE p.tanggal BETWEEN ? AND ? {$whereSearch}
";
$stmtC1 = $db->prepare($countDetailQuery);
$stmtC1->execute($searchParams);
$totalDetail = (int)$stmtC1->fetchColumn();
$totalPage1  = max(1, ceil($totalDetail / $limit1));

$stmtDetail = $db->prepare("
    SELECT
        p.tanggal, p.created_at, p.nama_pasien, p.unit_tindakan, p.catatan AS catatan_header,
        d.jumlah, d.kondisi,
        b.Nama_bhp, s.Nama_satuan, u.Nama_lengkap AS nama_dokter
    FROM pemakaian_bhp_detail d
    JOIN pemakaian_bhp  p ON d.id_pemakaian = p.id_pemakaian
    JOIN bhp            b ON d.id_bhp        = b.id_bhp
    LEFT JOIN satuan_bhp s ON b.id_satuan    = s.id_satuan
    LEFT JOIN user       u ON p.id_user      = u.id_user
    WHERE p.tanggal BETWEEN ? AND ? {$whereSearch}
    ORDER BY p.created_at DESC, d.id_detail ASC
    LIMIT ? OFFSET ?
");
$params1 = array_merge($searchParams, [$limit1, $off1]);
$stmtDetail->execute($params1);
$riwayatDetail = $stmtDetail->fetchAll();

// ── Section 2: Laporan per pasien ──────────────────────────────
// Count distinct patients for pagination
$countPasienQuery = "
    SELECT COUNT(DISTINCT p.nama_pasien)
    FROM pemakaian_bhp p
    JOIN pemakaian_bhp_detail d ON d.id_pemakaian = p.id_pemakaian
    JOIN bhp b                  ON d.id_bhp = b.id_bhp
    WHERE p.tanggal BETWEEN ? AND ?
      AND p.nama_pasien IS NOT NULL AND p.nama_pasien != '' {$whereSearch}
";
$stmtC2 = $db->prepare($countPasienQuery);
$stmtC2->execute($searchParams);
$totalPasien = (int)$stmtC2->fetchColumn();
$totalPage2  = max(1, ceil($totalPasien / $limit2));

// Fetch paginated distinct patients
$stmtListPasien = $db->prepare("
    SELECT p.nama_pasien
    FROM pemakaian_bhp p
    JOIN pemakaian_bhp_detail d ON d.id_pemakaian = p.id_pemakaian
    JOIN bhp b                  ON d.id_bhp = b.id_bhp
    WHERE p.tanggal BETWEEN ? AND ?
      AND p.nama_pasien IS NOT NULL AND p.nama_pasien != '' {$whereSearch}
    GROUP BY p.nama_pasien
    ORDER BY p.nama_pasien ASC
    LIMIT ? OFFSET ?
");
$params2 = array_merge($searchParams, [$limit2, $off2]);
$stmtListPasien->execute($params2);
$pasienNames = $stmtListPasien->fetchAll(PDO::FETCH_COLUMN);

$pasienMap = [];
if (!empty($pasienNames)) {
    // Generate IN clause placeholders
    $inPlaceholders = implode(',', array_fill(0, count($pasienNames), '?'));
    
    // Fetch items only for those paginated patients
    $stmtPasien = $db->prepare("
        SELECT
            p.nama_pasien, MAX(p.tanggal) AS tanggal,
            b.Nama_bhp, s.Nama_satuan, SUM(d.jumlah) AS total_pakai
        FROM pemakaian_bhp p
        JOIN pemakaian_bhp_detail d ON d.id_pemakaian = p.id_pemakaian
        JOIN bhp b                  ON d.id_bhp = b.id_bhp
        LEFT JOIN satuan_bhp s      ON b.id_satuan = s.id_satuan
        WHERE p.tanggal BETWEEN ? AND ? {$whereSearch}
          AND p.nama_pasien IN ($inPlaceholders)
        GROUP BY p.nama_pasien, b.id_bhp, b.Nama_bhp, s.Nama_satuan
        ORDER BY p.nama_pasien ASC, total_pakai DESC
    ");
    $params3 = array_merge($searchParams, $pasienNames);
    $stmtPasien->execute($params3);
    $rowsPasien = $stmtPasien->fetchAll();

    foreach ($rowsPasien as $r) {
        $np = $r['nama_pasien'];
        if (!isset($pasienMap[$np])) $pasienMap[$np] = [];
        $pasienMap[$np][] = $r;
    }
}

// Helper: inisial
function getInitial(string $name): string {
    $w = explode(' ', trim($name));
    return strtoupper(substr($w[0],0,1) . (isset($w[1]) ? substr($w[1],0,1) : ''));
}
?>

<?php
// Stats ringkas
$totalBhpJenis = count(array_unique(array_column($riwayatDetail, 'Nama_bhp')));
$totalPasienCount = count(array_unique(array_filter(array_column($riwayatDetail, 'nama_pasien'))));
?>
<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto flex flex-col gap-8 w-full">

  <!-- ══ HEADER BANNER ══════════════════════════════════════════ -->
  <div class="relative w-full rounded-2xl overflow-hidden"
    style="background:radial-gradient(ellipse at 0% 0%,#006B47 0%,#1A9F70 60%,#1DB879 100%);">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
      <div class="absolute -top-[150px] -right-[50px] w-[400px] h-[400px] rounded-full bg-white opacity-5"></div>
      <div class="absolute -bottom-[200px] -right-[100px] w-[500px] h-[500px] rounded-full bg-white opacity-10"></div>
    </div>
    <div class="relative z-10 px-6 py-6 sm:px-8 sm:py-7 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
          style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);">
          <i class="fas fa-clipboard-list text-white text-xl"></i>
        </div>
        <div>
          <h1 class="font-bold text-white text-xl sm:text-2xl leading-tight">Laporan Pemakaian BHP</h1>
          <p class="text-white/80 text-[13px] mt-0.5">
            Periode <?= date('d M Y', strtotime($tglMulai)) ?> – <?= date('d M Y', strtotime($tglAkhir)) ?>
          </p>
        </div>
      </div>
      <div class="flex gap-3 flex-shrink-0">
        <button onclick="exportLaporan('pdf')"
          class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-plex font-bold text-[13px] bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-all active:scale-95">
          <i class="far fa-file-pdf"></i> PDF
        </button>
        <button onclick="exportLaporan('excel')"
          class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-plex font-bold text-[13px] bg-white text-brand-700 hover:bg-white/90 transition-all active:scale-95 shadow-sm">
          <i class="far fa-file-excel"></i> Excel
        </button>
      </div>
    </div>
  </div>

  <!-- ══ STATS CARDS ════════════════════════════════════════════ -->
  <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center gap-4">
      <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-list-check text-blue-500"></i>
      </div>
      <div>
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Record</p>
        <p class="text-2xl font-bold text-slate-800 mt-0.5"><?= number_format($totalDetail) ?></p>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center gap-4">
      <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-user-injured text-emerald-500"></i>
      </div>
      <div>
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Pasien</p>
        <p class="text-2xl font-bold text-slate-800 mt-0.5"><?= number_format($totalPasien) ?></p>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center gap-4 col-span-2 sm:col-span-1">
      <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-boxes-stacked text-amber-500"></i>
      </div>
      <div>
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Jenis BHP</p>
        <p class="text-2xl font-bold text-slate-800 mt-0.5"><?= $totalBhpJenis ?></p>
      </div>
    </div>
  </div>

  <!-- ══ SECTION 1: TABEL RIWAYAT ══════════════════════════════ -->
  <section>
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center">
          <i class="fas fa-table-list text-brand-500 text-sm"></i>
        </div>
        <div>
          <h2 class="text-base font-bold text-slate-800">Riwayat Pemakaian Detail</h2>
          <p class="text-[12px] text-slate-400 font-medium">Per item BHP yang tercatat</p>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col sm:flex-row items-start sm:items-end gap-3 mb-4">
      <input type="hidden" name="page" value="laporan">
      <div class="flex items-center gap-2 flex-1">
        <input type="date" name="tgl_mulai" value="<?= htmlspecialchars($tglMulai) ?>"
          class="h-10 px-3 border border-slate-200 bg-slate-50 rounded-xl text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
        <span class="text-slate-300 font-bold">—</span>
        <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tglAkhir) ?>"
          class="h-10 px-3 border border-slate-200 bg-slate-50 rounded-xl text-sm font-medium text-slate-600 outline-none focus:border-brand-500 transition-colors">
      </div>
      <div class="relative flex-1 sm:max-w-[240px]">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
          placeholder="Cari pasien atau BHP..."
          class="w-full h-10 pl-9 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all placeholder:text-slate-400 font-medium text-slate-700">
      </div>
      <button type="submit" class="h-10 px-5 rounded-xl text-sm font-semibold text-white whitespace-nowrap"
        style="background:linear-gradient(135deg,#008D5B 0%,#00B47A 100%);">
        <i class="fas fa-filter text-xs mr-1"></i> Filter
      </button>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden font-plex">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse table-fixed">
          <colgroup>
            <col style="width:130px">  <!-- Tanggal -->
            <col style="width:auto">   <!-- Nama BHP -->
            <col style="width:90px">   <!-- Jml Pakai -->
            <col style="width:90px">   <!-- Satuan -->
            <col style="width:90px">   <!-- Kondisi -->
            <col style="width:auto">   <!-- Pasien / Unit -->
            <col style="width:130px">  <!-- Dokter -->
          </colgroup>
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50">
              <th class="px-4 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
              <th class="px-4 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama BHP</th>
              <th class="px-4 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Jml Pakai</th>
              <th class="px-4 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Satuan</th>
              <th class="px-4 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Kondisi</th>
              <th class="px-4 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pasien / Unit Tindakan</th>
              <th class="px-4 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dokter</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-sm">
            <?php if (empty($riwayatDetail)): ?>
            <tr>
              <td colspan="7" class="px-6 py-14 text-center">
                <div class="flex flex-col items-center gap-3 text-slate-400">
                  <i class="fas fa-clipboard-list text-3xl opacity-40"></i>
                  <p class="font-medium">Belum ada data pemakaian BHP pada periode ini</p>
                </div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($riwayatDetail as $row): ?>
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="px-4 py-4 align-top">
                <div class="font-semibold text-slate-700 text-[13px]"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                <div class="text-[11px] font-medium text-slate-400 mt-0.5"><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
              </td>
              <td class="px-4 py-4 align-top">
                <div class="font-bold text-slate-800 text-[14px] leading-snug"><?= htmlspecialchars($row['Nama_bhp']) ?></div>
              </td>
              <td class="px-4 py-4 align-top text-center">
                <span class="inline-flex items-center justify-center min-w-[32px] h-7 px-2 rounded-lg bg-blue-50 text-blue-600 font-bold text-sm">
                  <?= $row['jumlah'] ?>
                </span>
              </td>
              <td class="px-4 py-4 align-top text-slate-500 font-medium text-[13px]">
                <?= htmlspecialchars($row['Nama_satuan'] ?? '-') ?>
              </td>
              <td class="px-4 py-4 align-top text-center">
                <?php if ($row['kondisi'] === 'habis'): ?>
                  <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-[11px] font-bold">Habis</span>
                <?php else: ?>
                  <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[11px] font-bold">Sisa</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-4 align-top">
                <?php if ($row['nama_pasien']): ?>
                  <div class="font-semibold text-slate-700 text-[13px]"><?= htmlspecialchars($row['nama_pasien']) ?></div>
                <?php endif; ?>
                <?php if ($row['unit_tindakan']): ?>
                  <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                    <i class="fas fa-stethoscope text-[9px]"></i>
                    <?= htmlspecialchars($row['unit_tindakan']) ?>
                  </div>
                <?php endif; ?>
              </td>
              <td class="px-4 py-4 align-top">
                <?php if (!empty($row['nama_dokter'])): ?>
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-brand-50 border border-brand-100 flex items-center justify-center flex-shrink-0">
                      <i class="fas fa-user-doctor text-brand-500 text-[9px]"></i>
                    </div>
                    <span class="text-[12px] font-semibold text-slate-600 leading-tight"><?= htmlspecialchars($row['nama_dokter']) ?></span>
                  </div>
                <?php else: ?>
                  <span class="text-slate-300 text-[12px]">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/30 flex items-center justify-between">
        <span class="text-[13px] font-medium text-slate-400">
          Halaman <span class="font-bold text-slate-700"><?= $p1 ?></span> dari <?= $totalPage1 ?> (Total <?= $totalDetail ?> record)
        </span>
        
        <?php if ($totalPage1 > 1): ?>
        <div class="flex items-center gap-1.5">
          <?php
          $qParam = $_GET; unset($qParam['p1']); $baseQS = http_build_query($qParam); $baseQS = $baseQS ? '&' . $baseQS : '';
          if ($p1 > 1): ?>
            <a href="?p1=<?= $p1 - 1 ?><?= $baseQS ?>" class="h-9 px-3 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium">
              <i class="fas fa-chevron-left text-[11px]"></i>
            </a>
          <?php endif; ?>
          
          <?php
          $sPage = max(1, $p1 - 2); $ePage = min($totalPage1, $p1 + 2);
          for ($i = $sPage; $i <= $ePage; $i++): $isActive = ($i === $p1); ?>
            <a href="?p1=<?= $i ?><?= $baseQS ?>" class="h-9 w-9 flex items-center justify-center rounded-lg border <?= $isActive ? 'bg-brand-50 border-brand-200 text-brand-600 font-bold' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-medium' ?> transition-colors text-sm">
              <?= $i ?>
            </a>
          <?php endfor; ?>

          <?php if ($p1 < $totalPage1): ?>
            <a href="?p1=<?= $p1 + 1 ?><?= $baseQS ?>" class="h-9 px-3 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium">
              <i class="fas fa-chevron-right text-[11px]"></i>
            </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ══ SECTION 2: LAPORAN PER PASIEN ═════════════════════════ -->
  <section class="mt-4">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-[22px] font-display font-medium text-slate-800 tracking-[-0.02em]">Laporan Penggunaan BHP per Pasien</h2>
        <p class="text-[13px] text-slate-400 font-medium mt-1">Setiap kartu menampilkan nama pasien beserta BHP yang digunakan</p>
      </div>
    </div>

    <?php if (empty($pasienMap)): ?>
    <div class="bg-white rounded-[20px] p-12 shadow-sm border border-slate-100 text-center">
      <i class="fas fa-user-injured text-4xl text-slate-300 mb-4 block"></i>
      <p class="font-medium text-slate-500">Belum ada data pasien pada periode ini</p>
      <p class="text-[13px] text-slate-400 mt-1">Pastikan nama pasien diisi saat mencatat pemakaian BHP</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8 font-plex">
      <?php foreach ($pasienMap as $namaPasien => $bhpItems): ?>
      <?php
        $initial = getInitial($namaPasien);
        $totalItem = count($bhpItems);
        // Warna avatar pasien — hash nama untuk konsistensi warna
        $colorSets = [
          ['bg-blue-100 text-blue-700','#EFF6FF','#1D4ED8'],
          ['bg-purple-100 text-purple-700','#F5F3FF','#7C3AED'],
          ['bg-rose-100 text-rose-700','#FFF1F2','#BE123C'],
          ['bg-amber-100 text-amber-700','#FFFBEB','#B45309'],
          ['bg-teal-100 text-teal-700','#F0FDFA','#0F766E'],
        ];
        $ci = abs(crc32($namaPasien)) % count($colorSets);
        [$avatarClass] = $colorSets[$ci];
      ?>
      <div class="bg-white rounded-[24px] border border-slate-100/80 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col">

        <!-- Pasien Header -->
        <div class="flex items-center gap-3 mb-5">
          <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-bold text-[13px] shrink-0 <?= $avatarClass ?>">
            <?= htmlspecialchars($initial) ?>
          </div>
          <div class="min-w-0">
            <h3 class="font-bold text-slate-800 text-[15px] leading-tight truncate"><?= htmlspecialchars($namaPasien) ?></h3>
            <p class="text-[11px] text-slate-400 font-medium mt-0.5"><?= $totalItem ?> jenis BHP digunakan</p>
          </div>
        </div>

        <!-- Daftar BHP -->
        <div class="flex flex-col gap-3 flex-1">
          <?php foreach (array_slice($bhpItems, 0, 5) as $bhp): ?>
          <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
              <div class="w-1.5 h-1.5 rounded-full bg-brand-400 flex-shrink-0"></div>
              <span class="text-[13px] font-semibold text-slate-600 truncate"><?= htmlspecialchars($bhp['Nama_bhp']) ?></span>
            </div>
            <span class="text-[12px] font-bold text-slate-800 whitespace-nowrap flex-shrink-0">
              <?= $bhp['total_pakai'] ?> <span class="text-[10px] font-medium text-slate-400"><?= htmlspecialchars($bhp['Nama_satuan'] ?? '') ?></span>
            </span>
          </div>
          <?php endforeach; ?>
          <?php if ($totalItem > 5): ?>
          <p class="text-[11px] text-slate-400 font-medium pt-1">+<?= $totalItem - 5 ?> lainnya...</p>
          <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-100 pt-4 mt-4">
          <p class="text-[11px] text-slate-400 font-medium">
            <i class="far fa-calendar-alt mr-1"></i>
            <?= date('d M Y', strtotime($bhpItems[0]['tanggal'])) ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="flex items-center justify-between mt-2">
      <!-- Export Buttons -->
      <div class="flex items-center gap-2">
        <button onclick="exportLaporan('pdf')"
          class="h-11 px-5 rounded-xl text-sm font-semibold text-red-500 border border-red-100 bg-red-50/50 flex items-center justify-center gap-2 hover:bg-red-50 transition-colors">
          <i class="far fa-file-pdf"></i> Export PDF
        </button>
        <button onclick="exportLaporan('excel')"
          class="h-11 px-5 rounded-xl text-sm font-semibold text-emerald-600 border border-emerald-100 bg-emerald-50/50 flex items-center justify-center gap-2 hover:bg-emerald-50 transition-colors">
          <i class="far fa-file-excel"></i> Export Excel
        </button>
      </div>

      <!-- Pagination Section 2 -->
      <?php if ($totalPage2 > 1): ?>
      <div class="flex items-center gap-4">
        <span class="text-[13px] font-medium text-slate-400">Halaman <span class="font-bold text-slate-700"><?= $p2 ?></span> dari <?= $totalPage2 ?> (Total <?= $totalPasien ?> pasien)</span>
        <div class="flex items-center gap-1.5">
          <?php
          $qParam = $_GET; unset($qParam['p2']); $baseQS = http_build_query($qParam); $baseQS = $baseQS ? '&' . $baseQS : '';
          if ($p2 > 1): ?>
            <a href="?p2=<?= $p2 - 1 ?><?= $baseQS ?>" class="h-9 px-3 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium"><i class="fas fa-chevron-left text-[11px]"></i></a>
          <?php endif; ?>
          <?php
          $sPage2 = max(1, $p2 - 2); $ePage2 = min($totalPage2, $p2 + 2);
          for ($i = $sPage2; $i <= $ePage2; $i++): $isActive = ($i === $p2); ?>
            <a href="?p2=<?= $i ?><?= $baseQS ?>" class="h-9 w-9 flex items-center justify-center rounded-lg border <?= $isActive ? 'bg-brand-50 border-brand-200 text-brand-600 font-bold' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-medium' ?> transition-colors text-sm"><?= $i ?></a>
          <?php endfor; ?>
          <?php if ($p2 < $totalPage2): ?>
            <a href="?p2=<?= $p2 + 1 ?><?= $baseQS ?>" class="h-9 px-3 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium"><i class="fas fa-chevron-right text-[11px]"></i></a>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </section>

  </div>
</div>

<script>
function exportLaporan(type) {
  const params = new URLSearchParams(window.location.search);
  const tglMulai = document.querySelector('[name="tgl_mulai"]')?.value || params.get('tgl_mulai') || '';
  const tglAkhir = document.querySelector('[name="tgl_akhir"]')?.value || params.get('tgl_akhir') || '';
  const keyword  = document.querySelector('[name="keyword"]')?.value  || params.get('keyword')  || '';

  const url = new URL('/BHP-Poli-Gigi/api/export.php', window.location.origin);
  url.searchParams.set('type', type);
  url.searchParams.set('page', 'laporan');
  if (tglMulai) url.searchParams.set('tgl_mulai', tglMulai);
  if (tglAkhir) url.searchParams.set('tgl_akhir', tglAkhir);
  if (keyword)  url.searchParams.set('keyword', keyword);

  window.location.href = url.toString();
}
</script>
