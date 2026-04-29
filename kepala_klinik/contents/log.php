<?php
/**
 * Log Aktivitas - Kepala Klinik
 * Data dinamis dari tabel log_aktivitas via class ActivityLog
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\ActivityLog;

$logObj     = new ActivityLog();
$logFilter  = [
    'keyword'        => $_GET['log_keyword']        ?? '',
    'kategori'       => $_GET['log_kategori']       ?? '',
    'role'           => $_GET['log_role']           ?? '',
    'tanggal_dari'   => $_GET['tanggal_dari']       ?? '',
    'tanggal_sampai' => $_GET['tanggal_sampai']     ?? '',
];
$logPage    = max(1, (int)($_GET['log_page'] ?? 1));
$logLimit   = 10;
$logOffset  = ($logPage - 1) * $logLimit;
$logs       = $logObj->getLogs($logFilter, $logLimit, $logOffset);
$logTotal   = $logObj->countLogs($logFilter);
$logHariIni = $logObj->countToday();
$logKategori = [
    'auth'     => $logObj->countByKategori('auth'),
    'bhp'      => $logObj->countByKategori('bhp'),
    'stok'     => $logObj->countByKategori('stok'),
    'laporan'  => $logObj->countByKategori('laporan'),
];
$totalPages  = (int) ceil($logTotal / $logLimit);

// Helper: URL dengan filter param
function logUrl(array $override = []): string {
    $base = ['page' => 'log', 'log_page' => 1];
    $params = array_merge([
        'log_keyword'    => $_GET['log_keyword']    ?? '',
        'log_kategori'   => $_GET['log_kategori']   ?? '',
        'log_role'       => $_GET['log_role']       ?? '',
        'tanggal_dari'   => $_GET['tanggal_dari']   ?? '',
        'tanggal_sampai' => $_GET['tanggal_sampai'] ?? '',
    ], $override);
    return 'index.php?' . http_build_query(array_merge($base, $params));
}
?>

      <div class="w-full p-4 sm:p-6 lg:p-8">
        <div class="max-w-[1400px] mx-auto space-y-6 w-full">

          <!-- ── Header Banner ── -->
          <div
            class="relative w-full rounded-2xl overflow-hidden"
            style="background: radial-gradient(ellipse at 0% 0%, #006B47 0%, #1A9F70 60%, #1DB879 100%);"
          >
            <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
              <div class="absolute -top-[150px] -right-[50px] md:-top-[250px] md:-right-[100px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-5"></div>
              <div class="absolute -bottom-[150px] -right-[50px] md:-bottom-[300px] md:-right-[150px] w-[300px] h-[300px] md:w-[500px] md:h-[500px] rounded-full bg-white opacity-10"></div>
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 py-6 sm:px-8 sm:py-7">
              <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex-shrink-0"
                  style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
                  <i class="fas fa-heart-pulse text-white text-xl sm:text-2xl"></i>
                </div>
                <div class="flex flex-col gap-1 min-w-0">
                  <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Log Aktivitas Pengguna</h1>
                  <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block">
                    Pantau seluruh riwayat aksi yang dilakukan oleh semua pengguna sistem secara real-time.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- ── Stats Cards ── -->
          <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#F3E8FF">
                <i class="fas fa-heart-pulse text-purple-600 text-lg sm:text-xl"></i>
              </div>
              <div class="min-w-0">
                <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Log</p>
                <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $logTotal; ?></p>
              </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EFF6FF">
                <i class="fas fa-clock text-blue-600 text-lg sm:text-xl"></i>
              </div>
              <div class="min-w-0">
                <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Aktivitas Hari Ini</p>
                <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $logHariIni; ?></p>
              </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#ECFDF5">
                <i class="fas fa-right-to-bracket text-emerald-600 text-lg sm:text-xl"></i>
              </div>
              <div class="min-w-0">
                <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Login / Logout</p>
                <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $logKategori['auth']; ?></p>
              </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#ECFEFF">
                <i class="fas fa-boxes-stacked text-cyan-500 text-lg sm:text-xl"></i>
              </div>
              <div class="min-w-0">
                <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">BHP &amp; Stok</p>
                <p class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $logKategori['bhp'] + $logKategori['stok']; ?></p>
              </div>
            </div>

          </div>

          <!-- ── Filter & Search ── -->
          <form method="GET" action="" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
            <input type="hidden" name="page" value="log">
            <input type="hidden" name="log_page" value="1">
            <div class="flex flex-wrap items-center gap-3">

              <!-- Keyword -->
              <div class="relative flex-1 min-w-[200px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="log_keyword"
                  value="<?php echo htmlspecialchars($logFilter['keyword']); ?>"
                  placeholder="Cari nama, aksi, atau detail..."
                  class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
              </div>

              <!-- Tanggal dari -->
              <input type="date" name="tanggal_dari"
                value="<?php echo htmlspecialchars($logFilter['tanggal_dari']); ?>"
                class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">

              <!-- Tanggal sampai -->
              <input type="date" name="tanggal_sampai"
                value="<?php echo htmlspecialchars($logFilter['tanggal_sampai']); ?>"
                class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">

              <!-- Kategori -->
              <select name="log_kategori"
                class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua Kategori</option>
                <option value="auth"     <?php echo $logFilter['kategori']==='auth'     ? 'selected':''; ?>>Auth (Login/Logout)</option>
                <option value="pengguna" <?php echo $logFilter['kategori']==='pengguna' ? 'selected':''; ?>>Pengguna</option>
                <option value="bhp"      <?php echo $logFilter['kategori']==='bhp'      ? 'selected':''; ?>>BHP</option>
                <option value="stok"     <?php echo $logFilter['kategori']==='stok'     ? 'selected':''; ?>>Stok</option>
                <option value="laporan"  <?php echo $logFilter['kategori']==='laporan'  ? 'selected':''; ?>>Laporan</option>
                <option value="sistem"   <?php echo $logFilter['kategori']==='sistem'   ? 'selected':''; ?>>Sistem</option>
              </select>

              <!-- Role -->
              <select name="log_role"
                class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua Role</option>
                <option value="admin"         <?php echo $logFilter['role']==='admin'         ? 'selected':''; ?>>Admin</option>
                <option value="dokter"        <?php echo $logFilter['role']==='dokter'        ? 'selected':''; ?>>Dokter</option>
                <option value="kepala_klinik" <?php echo $logFilter['role']==='kepala_klinik' ? 'selected':''; ?>>Kepala Klinik</option>
              </select>

              <!-- Submit -->
              <button type="submit"
                class="h-10 px-5 rounded-xl text-sm font-plex font-semibold text-white transition-all"
                style="background:linear-gradient(135deg,#047857 0%,#34D399 100%)">
                <i class="fas fa-filter mr-1"></i> Terapkan
              </button>

              <!-- Reset -->
              <?php if ($logFilter['keyword'] || $logFilter['kategori'] || $logFilter['role'] || $logFilter['tanggal_dari'] || $logFilter['tanggal_sampai']): ?>
              <a href="index.php?page=log"
                class="h-10 px-4 rounded-xl text-sm font-plex font-semibold text-slate-500 border border-slate-200 flex items-center hover:bg-slate-50 transition-colors">
                <i class="fas fa-times mr-1"></i> Reset
              </a>
              <?php endif; ?>

            </div>
          </form>

          <!-- ── Log Table ── -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead>
                  <tr class="border-b border-slate-100">
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Detail</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                  <?php if (empty($logs)): ?>
                  <tr>
                    <td colspan="4" class="px-5 py-14 text-center font-plex text-slate-400">
                      <i class="fas fa-heart-pulse text-4xl mb-3 opacity-30 block"></i>
                      <?php echo ($logFilter['keyword'] || $logFilter['kategori'] || $logFilter['role'] || $logFilter['tanggal_dari'] || $logFilter['tanggal_sampai'])
                        ? 'Tidak ada log yang cocok dengan filter.'
                        : 'Belum ada aktivitas tercatat.'; ?>
                    </td>
                  </tr>

                  <?php else: ?>
                  <?php foreach ($logs as $log): ?>
                  <?php
                    // Avatar style berdasarkan role
                    $avatarStyle = match($log['role_user']) {
                      'admin'         => 'background:linear-gradient(135deg,#c7d2fe 0%,#6366f1 100%);color:#1e1b4b',
                      'dokter'        => 'background:linear-gradient(135deg,#a8edea 0%,#5b9bd5 100%);color:#1e4a7a',
                      'kepala_klinik' => 'background:linear-gradient(135deg,#fde68a 0%,#f59e0b 100%);color:#78350f',
                      default         => 'background:#e2e8f0;color:#475569',
                    };
                    $avatarInit = strtoupper(substr($log['nama_user'], 0, 1));

                    // Badge aksi
                    $aksiBadge = match($log['kategori']) {
                      'auth'     => 'bg-blue-50 text-blue-600',
                      'pengguna' => 'bg-purple-50 text-purple-600',
                      'bhp'      => 'bg-emerald-50 text-emerald-600',
                      'stok'     => 'bg-cyan-50 text-cyan-600',
                      'laporan'  => 'bg-amber-50 text-amber-600',
                      default    => 'bg-slate-100 text-slate-500',
                    };
                    $aksiIcon = match($log['aksi']) {
                      'login'                => 'fa-right-to-bracket',
                      'logout'               => 'fa-right-from-bracket',
                      'tambah_pengguna'      => 'fa-user-plus',
                      'hapus_pengguna'       => 'fa-user-minus',
                      'ubah_status_pengguna' => 'fa-user-pen',
                      'catat_pemakaian'      => 'fa-syringe',
                      'stok_masuk'           => 'fa-boxes-stacked',
                      default                => 'fa-circle-dot',
                    };
                    $aksiLabel = ucwords(str_replace('_', ' ', $log['aksi']));

                    // Format waktu
                    $waktuTmp   = strtotime($log['waktu']);
                    $hari       = date('Y-m-d', $waktuTmp);
                    $today      = date('Y-m-d');
                    $yesterday  = date('Y-m-d', strtotime('-1 day'));
                    $waktuLabel = ($hari === $today)     ? 'Hari ini, '  . date('H:i', $waktuTmp)
                                : (($hari === $yesterday) ? 'Kemarin, '  . date('H:i', $waktuTmp)
                                :                           date('d/m/Y H:i', $waktuTmp));
                  ?>
                  <tr class="hover:bg-slate-50/50 transition-colors">

                    <!-- Waktu -->
                    <td class="px-5 py-4 whitespace-nowrap">
                      <div class="flex items-center gap-2">
                        <i class="far fa-clock text-slate-300 text-sm"></i>
                        <span class="font-plex text-sm text-slate-600"><?php echo $waktuLabel; ?></span>
                      </div>
                    </td>

                    <!-- Pengguna -->
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
                          style="<?php echo $avatarStyle; ?>"><?php echo $avatarInit; ?></div>
                        <div>
                          <p class="font-plex text-sm font-medium text-slate-700"><?php echo htmlspecialchars($log['nama_user']); ?></p>
                          <p class="font-plex text-xs text-slate-400"><?php echo htmlspecialchars($log['role_user']); ?></p>
                        </div>
                      </div>
                    </td>

                    <!-- Aksi -->
                    <td class="px-5 py-4">
                      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex <?php echo $aksiBadge; ?>">
                        <i class="fas <?php echo $aksiIcon; ?> text-[10px]"></i>
                        <?php echo htmlspecialchars($aksiLabel); ?>
                      </span>
                    </td>

                    <!-- Detail -->
                    <td class="px-5 py-4 font-plex text-sm text-slate-600 max-w-[340px] truncate"
                      title="<?php echo htmlspecialchars($log['detail']); ?>">
                      <?php echo htmlspecialchars($log['detail'] ?: '-'); ?>
                    </td>

                  </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>

                </tbody>
              </table>
            </div>

            <!-- ── Pagination ── -->
            <?php if ($totalPages > 1): ?>
            <div class="flex flex-col sm:flex-row items-center justify-between px-5 py-4 border-t border-slate-100 gap-3">
              <p class="font-plex text-sm text-slate-500">
                Menampilkan <?php echo min($logOffset + 1, $logTotal); ?>–<?php echo min($logOffset + $logLimit, $logTotal); ?> dari <?php echo $logTotal; ?> log
              </p>
              <div class="flex items-center gap-1.5">
                <?php if ($logPage > 1): ?>
                <a href="<?php echo logUrl(['log_page' => $logPage - 1]); ?>"
                  class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
                  <i class="fas fa-chevron-left text-xs"></i>
                </a>
                <?php endif; ?>

                <?php for ($p = max(1, $logPage - 2); $p <= min($totalPages, $logPage + 2); $p++): ?>
                <a href="<?php echo logUrl(['log_page' => $p]); ?>"
                  class="w-9 h-9 rounded-lg flex items-center justify-center font-plex text-sm transition-colors <?php echo $p === $logPage ? 'bg-brand-600 text-white font-semibold' : 'border border-slate-200 text-slate-600 hover:bg-slate-50'; ?>">
                  <?php echo $p; ?>
                </a>
                <?php endfor; ?>

                <?php if ($logPage < $totalPages): ?>
                <a href="<?php echo logUrl(['log_page' => $logPage + 1]); ?>"
                  class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
                  <i class="fas fa-chevron-right text-xs"></i>
                </a>
                <?php endif; ?>
              </div>
            </div>
            <?php else: ?>
            <!-- Info total kecil jika hanya 1 halaman -->
            <div class="px-5 py-4 border-t border-slate-100">
              <p class="font-plex text-sm text-slate-400">Total <?php echo $logTotal; ?> log aktivitas</p>
            </div>
            <?php endif; ?>

          </div>

        </div>
      </div>
