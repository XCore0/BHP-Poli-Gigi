<?php

/**
 * Dashboard Admin – Data real dari database
 */
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\UserManager;
use App\Classes\BhpManager;
use App\Classes\ActivityLog;

$userMgr  = new UserManager();
$bhpMgr   = new BhpManager();
$logObj   = new ActivityLog();

// Stats real
$totalPengguna = $userMgr->countAll();
$totalBhp      = count($bhpMgr->getAllBhp());

// BHP dengan stok menipis (khusus yang berlabel MENIPIS)
$allBhp        = $bhpMgr->getAllBhp();
$stokMenipis   = array_filter($allBhp, function($b) {
    $status = BhpManager::getStatusStok((int)($b['Jumlah'] ?? 0), (int)($b['Pemakaian'] ?? 0));
    return $status['level'] == 1; // Level 1 = Menipis
});
$jumlahMenipis = count($stokMenipis);

// Log hari ini
$logHariIni  = $logObj->countToday();
$logTerbaru  = $logObj->getLogs([], 5, 0);

// BHP stok menipis (5 teratas)
$stokMenipisArr = array_slice(array_values($stokMenipis), 0, 5);

// Top 5 BHP paling banyak dipakai
usort($allBhp, fn($a, $b) => (int)($b['Pemakaian'] ?? 0) - (int)($a['Pemakaian'] ?? 0));
$topBhp = array_slice($allBhp, 0, 5);
$maxPemakaian = (int)(($topBhp[0]['Pemakaian'] ?? 0) ?: 1);
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto space-y-6 w-full">

    <!-- Hero Welcome Banner -->
    <div class="relative w-full rounded-[24px] p-6 lg:p-10 overflow-hidden shadow-lg border border-brand-50"
      style="background: linear-gradient(135deg, #006B47 0%, #1A9F70 100%);">
      <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none w-1/3 flex items-center justify-end pr-10">
        <i class="fas fa-shield-halved text-[160px] text-white rotate-12"></i>
      </div>
      <div class="absolute left-1/4 top-0 w-64 h-64 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>

      <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex flex-col gap-1">
          <span class="inline-block w-fit px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-bold tracking-[0.15em] uppercase mb-4 shadow-sm border border-white/10">
            <?php echo date('l, d F Y'); ?>
          </span>
          <h2 class="text-2xl lg:text-3xl font-display font-bold text-white mb-2 tracking-tight">
            Selamat Datang, <?php echo htmlspecialchars($currentUser['nama'] ?? 'Admin'); ?>
          </h2>
          <p class="text-emerald-50 text-[13px] lg:text-[14px] max-w-xl leading-relaxed font-medium opacity-90">
            Kelola seluruh data, pengguna, dan konfigurasi sistem secara real-time. 
            <?php if ($jumlahMenipis > 0): ?>
            Terdapat <span class="font-bold text-yellow-300"><?php echo $jumlahMenipis; ?> item</span> dengan stok menipis.
            <?php endif; ?>
          </p>
        </div>

        <div class="flex flex-wrap gap-3 mt-2 sm:mt-0">
          <a href="?page=pengguna"
            class="bg-white/10 text-white border border-white/20 hover:bg-white/20 backdrop-blur-sm px-6 py-3 rounded-xl font-bold text-[13px] transition-all flex items-center gap-2">
            <i class="fas fa-users text-[12px]"></i> Kelola Pengguna
          </a>
          <a href="?page=data_bhp"
            class="bg-white text-[#006B47] px-6 py-3 rounded-xl font-bold text-[13px] shadow-[0_8px_16px_rgba(0,107,71,0.2)] hover:shadow-[0_12px_24px_rgba(0,107,71,0.3)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <i class="fas fa-boxes-stacked text-[12px]"></i> Data BHP
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
      <!-- Card 1: Total Pengguna -->
      <a href="?page=pengguna" class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-blue-200 transition-all group relative overflow-hidden cursor-pointer">
        <div class="absolute top-0 right-0 w-20 h-20 bg-blue-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm border border-blue-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-users"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Pengguna</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $totalPengguna; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Akun</span>
          </div>
        </div>
      </a>

      <!-- Card 2: Total BHP -->
      <a href="?page=data_bhp" class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-emerald-200 transition-all group relative overflow-hidden cursor-pointer">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shadow-sm border border-emerald-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-boxes"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total BHP</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $totalBhp; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Jenis</span>
          </div>
        </div>
      </a>

      <!-- Card 3: Stok Menipis -->
      <a href="?page=data_bhp" class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-red-200 transition-all group relative overflow-hidden cursor-pointer">
        <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center font-bold shadow-sm border border-red-100/50 group-hover:-translate-y-1 transition-transform <?php echo $jumlahMenipis > 0 ? 'animate-[pulse_3s_ease-in-out_infinite]' : ''; ?>">
          <i class="text-lg fas fa-exclamation-triangle"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Stok Menipis</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold <?php echo $jumlahMenipis > 0 ? 'text-red-600' : 'text-slate-800'; ?> leading-none"><?php echo $jumlahMenipis; ?></h4>
            <span class="text-[12px] font-semibold <?php echo $jumlahMenipis > 0 ? 'text-red-500' : 'text-slate-500'; ?> mb-1">Jenis</span>
          </div>
        </div>
      </a>

      <!-- Card 4: Log Aktivitas -->
      <a href="?page=pengguna&tab=log" class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-amber-200 transition-all group relative overflow-hidden cursor-pointer">
        <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold shadow-sm border border-amber-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-clipboard-list"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Log Hari Ini</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $logHariIni; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Aktivitas</span>
          </div>
        </div>
      </a>
    </div>

    <!-- Bottom 2-column layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Left: Log Aktivitas Terbaru -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
              <i class="fas fa-heart-pulse text-purple-500 text-sm"></i>
            </div>
            <h3 class="font-display font-bold text-slate-800 text-[15px]">Log Aktivitas Terbaru</h3>
          </div>
          <a href="?page=pengguna&tab=log" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
            Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
          </a>
        </div>
        <div class="divide-y divide-slate-50">
          <?php if (empty($logTerbaru)): ?>
            <div class="px-6 py-10 text-center text-slate-400">
              <i class="fas fa-heart-pulse text-3xl opacity-30 block mb-2"></i>
              <p class="text-sm">Belum ada aktivitas tercatat.</p>
            </div>
          <?php else: ?>
            <?php foreach ($logTerbaru as $log):
              $avatarStyle = match ($log['role_user']) {
                'admin'         => 'background:linear-gradient(135deg,#c7d2fe,#6366f1);color:#1e1b4b',
                'dokter'        => 'background:linear-gradient(135deg,#a7f3d0,#059669);color:#065f46',
                'kepala_klinik' => 'background:linear-gradient(135deg,#fde68a,#f59e0b);color:#78350f',
                default         => 'background:#e2e8f0;color:#475569',
              };
              $aksiBadge = match ($log['kategori']) {
                'auth'     => 'bg-blue-50 text-blue-600',
                'pengguna' => 'bg-purple-50 text-purple-600',
                'bhp'      => 'bg-emerald-50 text-emerald-600',
                'stok'     => 'bg-cyan-50 text-cyan-600',
                'laporan'  => 'bg-amber-50 text-amber-600',
                default    => 'bg-slate-100 text-slate-500',
              };
              $aksiLabel = ucwords(str_replace('_', ' ', $log['aksi']));
              $ts = strtotime($log['waktu']);
              $waktuLabel = (date('Y-m-d', $ts) === date('Y-m-d'))
                ? 'Hari ini, ' . date('H:i', $ts)
                : date('d/m/Y H:i', $ts);
            ?>
              <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-slate-50/50 transition-colors">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="<?php echo $avatarStyle; ?>">
                  <?php echo strtoupper(substr($log['nama_user'], 0, 1)); ?>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-plex text-sm font-medium text-slate-700 truncate"><?php echo htmlspecialchars($log['nama_user']); ?></p>
                  <p class="font-plex text-xs text-slate-400"><?php echo $waktuLabel; ?></p>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold font-plex <?php echo $aksiBadge; ?> flex-shrink-0">
                  <?php echo htmlspecialchars($aksiLabel); ?>
                </span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Right Side: Top BHP & Stok Menipis -->
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
                <span class="text-[13px] font-bold text-slate-800 truncate max-w-[140px]"><?php echo htmlspecialchars($b['Nama_bhp']); ?></span>
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
        <div class="bg-white rounded-[20px] border border-slate-200 shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                <i class="fas fa-triangle-exclamation text-amber-500 text-sm"></i>
              </div>
              <h3 class="font-display font-bold text-slate-800 text-[15px]">Stok Menipis</h3>
            </div>
            <a href="?page=data_bhp" class="text-xs font-bold text-amber-600 hover:text-amber-800 transition-colors">Kelola</a>
          </div>
          <div class="divide-y divide-slate-50">
            <?php if (empty($stokMenipisArr)): ?>
              <div class="px-6 py-10 text-center text-slate-400">
                <i class="fas fa-circle-check text-3xl text-emerald-300 block mb-2"></i>
                <p class="text-sm font-plex font-medium text-emerald-600">Semua stok aman!</p>
              </div>
            <?php else: ?>
              <?php foreach ($stokMenipisArr as $bhp):
                $pct = min(100, max(0, (int)($bhp['Jumlah'] ?? 0) * 10));
                $color = ($bhp['Jumlah'] ?? 0) <= 3 ? '#EF4444' : '#F59E0B';
              ?>
                <div class="px-6 py-3.5 hover:bg-slate-50/50 transition-colors">
                  <div class="flex items-center justify-between mb-1.5">
                    <span class="font-plex text-sm font-semibold text-slate-700 truncate max-w-[140px]"><?php echo htmlspecialchars($bhp['Nama_bhp']); ?></span>
                    <span class="font-plex text-xs font-bold ml-2 <?php echo ($bhp['Jumlah'] ?? 0) <= 3 ? 'text-red-600' : 'text-amber-600'; ?>">
                      <?php echo (int)($bhp['Jumlah'] ?? 0); ?> <?php echo htmlspecialchars($bhp['Nama_satuan'] ?? 'Unit'); ?>
                    </span>
                  </div>
                  <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500" style="width:<?php echo $pct; ?>%;background:<?php echo $color; ?>;"></div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>