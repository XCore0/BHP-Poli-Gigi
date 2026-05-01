<?php
/**
 * Dashboard Kepala Klinik – Data real dari database
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\BhpManager;
use App\Classes\PemakaianManager;
use App\Classes\ActivityLog;

$bhpMgr    = new BhpManager();
$pemMgr    = new PemakaianManager();
$logObj    = new ActivityLog();

// Stats real
$allBhp      = $bhpMgr->getAllBhp();
$totalBhp    = count($allBhp);
$stokMenipis = array_filter($allBhp, fn($b) => (int)($b['Jumlah'] ?? 0) <= 10);
$jmlMenipis  = count($stokMenipis);

// Pemakaian bulan ini
$bulanIni = date('Y-m');
$totalPemakaianBulanIni = 0;
$allPemakaian = $pemMgr->getAllPemakaian(['limit' => 1000]);
foreach ($allPemakaian as $p) {
    if (str_starts_with($p['created_at'], $bulanIni)) {
        $totalPemakaianBulanIni += (int)($p['jumlah_item'] ?? 0);
    }
}

// Log aktivitas terbaru (semua user)
$logTerbaru = $logObj->getLogs([], 5, 0);
$logHariIni = $logObj->countToday();

// Pemakaian terbaru
$pemakaianTerbaru = $pemMgr->getAllPemakaian(['limit' => 5]);

// Top 5 BHP paling banyak dipakai
usort($allBhp, fn($a, $b) => (int)($b['Pemakaian'] ?? 0) - (int)($a['Pemakaian'] ?? 0));
$topBhp = array_slice($allBhp, 0, 5);
$maxPemakaian = (int)(($topBhp[0]['Pemakaian'] ?? 0) ?: 1);

// Stok menipis teratas
$stokMenipisArr = array_slice(array_values($stokMenipis), 0, 5);

// Greeting berdasarkan jam
$jam = (int)date('H');
$greeting = $jam < 12 ? 'Selamat Pagi' : ($jam < 15 ? 'Selamat Siang' : ($jam < 18 ? 'Selamat Sore' : 'Selamat Malam'));
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto space-y-6 w-full font-plex">

    <!-- Hero Welcome Banner -->
    <div class="relative w-full rounded-[24px] p-6 lg:p-10 overflow-hidden shadow-lg"
      style="background: linear-gradient(135deg, #006B47 0%, #1A9F70 50%, #1DB879 100%);">
      <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none w-1/3 flex items-center justify-end pr-10">
        <i class="fas fa-hospital text-[160px] text-white rotate-6"></i>
      </div>
      <div class="absolute left-1/4 top-0 w-64 h-64 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>
      <div class="relative z-10">
        <span class="inline-block px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-bold tracking-[0.15em] uppercase mb-4 shadow-sm border border-white/10">
          <?php echo date('l, d F Y'); ?>
        </span>
        <h2 class="text-2xl lg:text-3xl font-display font-bold text-white mb-2 tracking-tight">
          <?php echo $greeting; ?>, <?php echo htmlspecialchars($currentUser['nama'] ?? 'Kepala Klinik'); ?>
        </h2>
        <p class="text-emerald-50 text-[13px] lg:text-[14px] max-w-xl leading-relaxed font-medium opacity-90">
          Pantau seluruh aktivitas klinik, stok BHP, dan laporan pemakaian secara real-time. Ada
          <span class="font-bold text-white"><?php echo $jmlMenipis; ?> item</span> yang perlu perhatian stok.
        </p>
        <div class="flex flex-wrap gap-3 mt-7">
          <a href="index.php?page=log"
            class="bg-white text-[#006B47] px-6 py-3 rounded-xl font-bold text-[13px] shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <i class="fas fa-heart-pulse text-[12px]"></i> Lihat Log Aktivitas
          </a>
          <a href="index.php?page=laporan"
            class="bg-white/10 text-white border border-white/20 hover:bg-white/20 backdrop-blur-sm px-6 py-3 rounded-xl font-bold text-[13px] transition-all flex items-center gap-2">
            <i class="fas fa-chart-bar text-[12px]"></i> Laporan Pemakaian
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
      <!-- Total BHP -->
      <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm border border-emerald-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-xl fas fa-boxes"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total BHP</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $totalBhp; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Jenis</span>
          </div>
        </div>
      </div>

      <!-- Stok Menipis -->
      <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shadow-sm border border-amber-100/50 group-hover:-translate-y-1 transition-transform <?php echo $jmlMenipis > 0 ? 'animate-[pulse_3s_ease-in-out_infinite]' : ''; ?>">
          <i class="text-xl fas fa-exclamation-triangle"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Stok Menipis</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold <?php echo $jmlMenipis > 0 ? 'text-amber-600' : 'text-slate-800'; ?> leading-none"><?php echo $jmlMenipis; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Jenis</span>
          </div>
        </div>
      </div>

      <!-- Pemakaian Bulan Ini -->
      <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-blue-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm border border-blue-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-xl fas fa-chart-line"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pemakaian Bulan Ini</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $totalPemakaianBulanIni; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Item</span>
          </div>
        </div>
      </div>

      <!-- Log Hari Ini -->
      <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-purple-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-sm border border-purple-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-xl fas fa-clipboard-list"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Log Hari Ini</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $logHariIni; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Aktivitas</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Two Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Left: Pemakaian Terbaru + Log -->
      <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Log Aktivitas Terbaru -->
        <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                <i class="fas fa-heart-pulse text-purple-500 text-sm"></i>
              </div>
              <h3 class="font-display font-bold text-slate-800 text-[15px]">Log Aktivitas Terbaru</h3>
            </div>
            <a href="index.php?page=log" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
              Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
          </div>
          <div class="divide-y divide-slate-50">
            <?php if (empty($logTerbaru)): ?>
            <div class="px-6 py-10 text-center text-slate-400">
              <p class="text-sm">Belum ada aktivitas tercatat.</p>
            </div>
            <?php else: ?>
            <?php foreach ($logTerbaru as $log):
              $aksiBadge = match($log['kategori']) {
                'auth'     => 'bg-blue-50 text-blue-600',
                'pengguna' => 'bg-purple-50 text-purple-600',
                'bhp'      => 'bg-emerald-50 text-emerald-600',
                'stok'     => 'bg-cyan-50 text-cyan-600',
                default    => 'bg-slate-100 text-slate-500',
              };
              $avatarStyle = match($log['role_user']) {
                'admin'         => 'background:linear-gradient(135deg,#c7d2fe,#6366f1);color:#1e1b4b',
                'dokter'        => 'background:linear-gradient(135deg,#a7f3d0,#059669);color:#065f46',
                'kepala_klinik' => 'background:linear-gradient(135deg,#fde68a,#f59e0b);color:#78350f',
                default         => 'background:#e2e8f0;color:#475569',
              };
              $ts = strtotime($log['waktu']);
              $waktuLabel = date('Y-m-d',$ts)===date('Y-m-d') ? 'Hari ini, '.date('H:i',$ts) : date('d/m H:i',$ts);
            ?>
            <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-slate-50/50 transition-colors">
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="<?php echo $avatarStyle; ?>">
                <?php echo strtoupper(substr($log['nama_user'],0,1)); ?>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-700 truncate"><?php echo htmlspecialchars($log['nama_user']); ?></p>
                <p class="text-xs text-slate-400"><?php echo $waktuLabel; ?></p>
              </div>
              <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold <?php echo $aksiBadge; ?> flex-shrink-0 whitespace-nowrap">
                <?php echo ucwords(str_replace('_',' ',$log['aksi'])); ?>
              </span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Pemakaian Terbaru -->
        <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-syringe text-emerald-500 text-sm"></i>
              </div>
              <h3 class="font-display font-bold text-slate-800 text-[15px]">Pemakaian BHP Terbaru</h3>
            </div>
            <a href="index.php?page=laporan" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition-colors flex items-center gap-1">
              Laporan <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
              <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                  <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                  <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pasien</th>
                  <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dokter</th>
                  <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Item</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50 text-[13px]">
                <?php if (empty($pemakaianTerbaru)): ?>
                <tr>
                  <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada catatan pemakaian.</td>
                </tr>
                <?php else: foreach ($pemakaianTerbaru as $p): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-4 text-slate-600 text-[12px] font-semibold"><?php echo date('d/m/Y', strtotime($p['tanggal'])); ?></td>
                  <td class="px-6 py-4 text-slate-800 font-medium"><?php echo htmlspecialchars($p['nama_pasien'] ?? '-'); ?></td>
                  <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($p['nama_dokter'] ?? '-'); ?></td>
                  <td class="px-6 py-4 text-center">
                    <span class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-bold text-[11px] border border-emerald-100">
                      <?php echo (int)($p['jumlah_item'] ?? 0); ?> Item
                    </span>
                  </td>
                </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- Right: Top BHP & Stok Menipis -->
      <div class="lg:col-span-1 flex flex-col gap-6">

        <!-- Top BHP Terbanyak Digunakan -->
        <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
          <div class="flex items-center gap-2 px-6 py-4 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
              <i class="fas fa-chart-pie text-blue-500 text-sm"></i>
            </div>
            <h3 class="font-display font-bold text-slate-800 text-[15px]">Top BHP Terbanyak Dipakai</h3>
          </div>
          <div class="px-6 py-5 flex flex-col gap-4">
            <?php if (empty($topBhp) || ($topBhp[0]['Pemakaian'] ?? 0) == 0): ?>
            <p class="text-sm text-slate-400 text-center py-4">Belum ada data pemakaian.</p>
            <?php else: ?>
            <?php $colors = ['linear-gradient(90deg,#34D399,#059669)','linear-gradient(90deg,#60A5FA,#2563EB)','linear-gradient(90deg,#FBBF24,#D97706)','linear-gradient(90deg,#A78BFA,#7C3AED)','linear-gradient(90deg,#FB7185,#E11D48)']; ?>
            <?php foreach ($topBhp as $i => $b): if ((int)($b['Pemakaian'] ?? 0) === 0) continue; ?>
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-[13px] font-bold text-slate-800 truncate max-w-[160px]"><?php echo htmlspecialchars($b['Nama_bhp']); ?></span>
                <span class="text-[12px] font-bold text-slate-500 ml-1"><?php echo (int)($b['Pemakaian'] ?? 0); ?> <span class="text-[10px] font-normal"><?php echo htmlspecialchars($b['Nama_satuan'] ?? ''); ?></span></span>
              </div>
              <div class="w-full h-[6px] bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width:<?php echo round((int)($b['Pemakaian'] ?? 0) / $maxPemakaian * 100); ?>%;background:<?php echo $colors[$i % 5]; ?>;transition:width 1s ease;"></div>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Stok Menipis -->
        <?php if (!empty($stokMenipisArr)): ?>
        <div class="bg-white rounded-[20px] border border-amber-100 shadow-sm overflow-hidden">
          <div class="flex items-center gap-2 px-6 py-4 border-b border-amber-100" style="background:#FFFBEB;">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
              <i class="fas fa-triangle-exclamation text-amber-600 text-sm"></i>
            </div>
            <h3 class="font-display font-bold text-amber-800 text-[15px]">Perlu Pengadaan Segera</h3>
          </div>
          <div class="divide-y divide-amber-50">
            <?php foreach ($stokMenipisArr as $bhp): ?>
            <div class="px-6 py-3.5 flex items-center justify-between hover:bg-amber-50/30 transition-colors">
              <span class="text-[13px] font-semibold text-slate-700 truncate max-w-[160px]"><?php echo htmlspecialchars($bhp['Nama_bhp']); ?></span>
              <span class="text-[12px] font-bold px-2.5 py-1 rounded-lg <?php echo (int)($bhp['Jumlah'] ?? 0) <= 3 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600'; ?>">
                <?php echo (int)($bhp['Jumlah'] ?? 0); ?> tersisa
              </span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>

  </div>
</div>
