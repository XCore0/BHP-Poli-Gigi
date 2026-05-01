<?php
/**
 * Dashboard Admin – Data real dari database
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\UserManager;
use App\Classes\BhpManager;
use App\Classes\ActivityLog;

$userMgr  = new UserManager();
$bhpMgr   = new BhpManager();
$logObj   = new ActivityLog();

// Stats real
$totalPengguna = $userMgr->countAll();
$totalBhp      = count($bhpMgr->getAllBhp());

// BHP dengan stok menipis (jumlah <= 10)
$allBhp        = $bhpMgr->getAllBhp();
$stokMenipis   = array_filter($allBhp, fn($b) => (int)($b['Jumlah'] ?? 0) <= 10);
$jumlahMenipis = count($stokMenipis);

// Log hari ini
$logHariIni  = $logObj->countToday();
$logTerbaru  = $logObj->getLogs([], 5, 0);

// BHP stok menipis (5 teratas)
$stokMenipisArr = array_slice(array_values($stokMenipis), 0, 5);
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto space-y-6 w-full">

    <!-- Header Banner -->
    <div class="relative w-full rounded-2xl overflow-hidden mb-2"
      style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);">
      <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
        <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
        <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
        <div class="absolute -bottom-[400px] left-[50px] md:-bottom-[850px] md:left-[100px] w-[600px] h-[600px] md:w-[1000px] md:h-[1000px] rounded-full bg-white opacity-5"></div>
      </div>
      <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 px-6 py-6 sm:px-8 sm:py-7">
        <div class="flex items-center gap-4 sm:gap-5 min-w-0">
          <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
            style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
            <i class="fas fa-shield-halved text-white text-xl sm:text-2xl"></i>
          </div>
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">
              Dashboard Administrator
            </h1>
            <p class="font-plex font-medium text-white/80 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block max-w-2xl">
              Selamat datang, <span class="font-bold text-white"><?php echo htmlspecialchars($currentUser['nama'] ?? 'Admin'); ?></span>. Kelola seluruh data, pengguna, dan konfigurasi sistem.
            </p>
          </div>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
          <a href="?page=pengguna"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors">
            <i class="fas fa-users text-xs"></i> Kelola Pengguna
          </a>
          <a href="?page=data_bhp"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white text-[#006B47] hover:bg-white/90 transition-colors shadow-lg">
            <i class="fas fa-boxes-stacked text-xs"></i> Data BHP
          </a>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
      <!-- Card 1: Total Pengguna -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform" style="background: #EFF6FF;">
          <i class="fas fa-users text-blue-500 text-xl"></i>
        </div>
        <div>
          <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Pengguna</p>
          <p class="font-display font-bold text-2xl text-slate-800 leading-tight"><?php echo $totalPengguna; ?></p>
          <p class="font-plex text-xs text-slate-400">Akun terdaftar</p>
        </div>
      </div>
      <!-- Card 2: Total BHP -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform" style="background: #ECFDF5;">
          <i class="fas fa-boxes text-emerald-600 text-xl"></i>
        </div>
        <div>
          <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Total BHP</p>
          <p class="font-display font-bold text-2xl text-slate-800 leading-tight"><?php echo $totalBhp; ?></p>
          <p class="font-plex text-xs text-slate-400">Jenis BHP terdaftar</p>
        </div>
      </div>
      <!-- Card 3: Stok Menipis -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform <?php echo $jumlahMenipis > 0 ? 'animate-[pulse_2s_ease-in-out_infinite]' : ''; ?>" style="background: #FFF7ED;">
          <i class="fas fa-exclamation-triangle text-amber-500 text-xl"></i>
        </div>
        <div>
          <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Stok Menipis</p>
          <p class="font-display font-bold text-2xl <?php echo $jumlahMenipis > 0 ? 'text-amber-600' : 'text-slate-800'; ?> leading-tight"><?php echo $jumlahMenipis; ?></p>
          <p class="font-plex text-xs text-slate-400">Perlu segera dipenuhi</p>
        </div>
      </div>
      <!-- Card 4: Log Hari Ini -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform" style="background: #F3E8FF;">
          <i class="fas fa-clipboard-list text-purple-500 text-xl"></i>
        </div>
        <div>
          <p class="font-plex text-xs text-slate-400 font-semibold uppercase tracking-wider">Log Hari Ini</p>
          <p class="font-display font-bold text-2xl text-slate-800 leading-tight"><?php echo $logHariIni; ?></p>
          <p class="font-plex text-xs text-slate-400">Aktivitas tercatat</p>
        </div>
      </div>
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
            $avatarStyle = match($log['role_user']) {
              'admin'         => 'background:linear-gradient(135deg,#c7d2fe,#6366f1);color:#1e1b4b',
              'dokter'        => 'background:linear-gradient(135deg,#a7f3d0,#059669);color:#065f46',
              'kepala_klinik' => 'background:linear-gradient(135deg,#fde68a,#f59e0b);color:#78350f',
              default         => 'background:#e2e8f0;color:#475569',
            };
            $aksiBadge = match($log['kategori']) {
              'auth'     => 'bg-blue-50 text-blue-600',
              'pengguna' => 'bg-purple-50 text-purple-600',
              'bhp'      => 'bg-emerald-50 text-emerald-600',
              'stok'     => 'bg-cyan-50 text-cyan-600',
              'laporan'  => 'bg-amber-50 text-amber-600',
              default    => 'bg-slate-100 text-slate-500',
            };
            $aksiLabel = ucwords(str_replace('_', ' ', $log['aksi']));
            $ts = strtotime($log['waktu']);
            $waktuLabel = (date('Y-m-d',$ts)===date('Y-m-d'))
              ? 'Hari ini, '.date('H:i',$ts)
              : date('d/m/Y H:i',$ts);
          ?>
          <div class="flex items-center gap-3 px-6 py-3.5 hover:bg-slate-50/50 transition-colors">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="<?php echo $avatarStyle; ?>">
              <?php echo strtoupper(substr($log['nama_user'],0,1)); ?>
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

      <!-- Right: Stok Menipis -->
      <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
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
            <p class="text-xs mt-1">Tidak ada BHP yang perlu segera diisi.</p>
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
