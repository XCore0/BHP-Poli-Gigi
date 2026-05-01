<?php
/**
 * Dashboard Dokter – Data real dari database
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\BhpManager;
use App\Classes\PemakaianManager;

$bhpMgr  = new BhpManager();
$pemMgr  = new PemakaianManager();

$userId = (int)($currentUser['id'] ?? 0);

// Data real: pemakaian dokter ini hari ini
$pemakaianHariIni = $pemMgr->getAllPemakaian([
    'id_user' => $userId,
    'limit'   => 100,
]);
$pemakaianHariIni = array_filter($pemakaianHariIni, fn($p) => str_starts_with($p['tanggal'], date('Y-m-d')));
$totalPemakaianHariIni = 0;
$pasienSelesai = count($pemakaianHariIni);
foreach ($pemakaianHariIni as $p) {
    $totalPemakaianHariIni += (int)($p['jumlah_item'] ?? 0);
}

// Riwayat terbaru (4 entri terakhir dokter ini)
$riwayatTerbaru = $pemMgr->getAllPemakaian(['id_user' => $userId, 'limit' => 4]);

// Stok menipis (untuk dokter)
$allBhp = $bhpMgr->getAllBhp();
$stokMenipis = count(array_filter($allBhp, fn($b) => (int)($b['Jumlah'] ?? 0) <= 10));

// Top BHP pemakaian
usort($allBhp, fn($a, $b) => (int)($b['Pemakaian'] ?? 0) - (int)($a['Pemakaian'] ?? 0));
$topBhp = array_filter(array_slice($allBhp, 0, 5), fn($b) => (int)($b['Pemakaian'] ?? 0) > 0);
$maxPem = (int)(($allBhp[0]['Pemakaian'] ?? 0) ?: 1);

// Total pemakaian dokter ini (semua waktu)
$totalSemuaPemakaian = $pemMgr->countPemakaian(['id_user' => $userId]);

// Greeting
$jam = (int)date('H');
$greeting = $jam < 12 ? 'Selamat Pagi' : ($jam < 15 ? 'Selamat Siang' : ($jam < 18 ? 'Selamat Sore' : 'Selamat Malam'));
$hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][(int)date('w')];
$tglLabel = $hari . ', ' . date('d') . ' ' . ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][(int)date('n')-1] . ' ' . date('Y');
?>

<div class="w-full p-4 sm:p-6 lg:p-8">
  <div class="max-w-[1400px] mx-auto flex flex-col gap-6 lg:gap-8 w-full font-plex">

    <!-- 1. Hero Welcome Banner -->
    <div class="relative w-full rounded-[24px] p-6 lg:p-10 overflow-hidden shadow-lg border border-brand-50"
      style="background: linear-gradient(135deg, #006B47 0%, #1A9F70 100%);">
      <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none w-1/3 flex items-center justify-end pr-10">
        <i class="fas fa-tooth text-[160px] text-white rotate-12"></i>
      </div>
      <div class="absolute left-1/4 top-0 w-64 h-64 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>

      <div class="relative z-10">
        <span class="inline-block px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-bold tracking-[0.15em] uppercase mb-4 shadow-sm border border-white/10">
          <?php echo $tglLabel; ?>
        </span>
        <h2 class="text-2xl lg:text-3xl font-display font-bold text-white mb-2 tracking-tight">
          <?php echo $greeting; ?>, <?php echo htmlspecialchars($currentUser['nama'] ?? 'Dokter'); ?>
        </h2>
        <p class="text-emerald-50 text-[13px] lg:text-[14px] max-w-xl leading-relaxed font-medium opacity-90">
          Hari ini Anda telah menangani <span class="font-bold text-white"><?php echo $pasienSelesai; ?> pasien</span>
          dan memakai <span class="font-bold text-white"><?php echo $totalPemakaianHariIni; ?> item</span> BHP.
          <?php if ($stokMenipis > 0): ?>
          Terdapat <span class="font-bold text-yellow-300"><?php echo $stokMenipis; ?> BHP</span> dengan stok menipis.
          <?php endif; ?>
        </p>

        <div class="flex flex-wrap gap-3 mt-7">
          <a href="index.php?page=catat"
            class="bg-white text-[#006B47] px-6 py-3 rounded-xl font-bold text-[13px] shadow-[0_8px_16px_rgba(0,107,71,0.2)] hover:shadow-[0_12px_24px_rgba(0,107,71,0.3)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <i class="fas fa-pen-nib text-[12px]"></i> Catat Pemakaian Baru
          </a>
          <a href="index.php?page=data_bhp"
            class="bg-white/10 text-white border border-white/20 hover:bg-white/20 backdrop-blur-sm px-6 py-3 rounded-xl font-bold text-[13px] transition-all flex items-center gap-2">
            <i class="fas fa-boxes text-[12px]"></i> Cek Ketersediaan Stok
          </a>
        </div>
      </div>
    </div>

    <!-- 2. Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
      <!-- Card 1: Pemakaian Hari Ini -->
      <div class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-blue-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm border border-blue-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-box-open"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pemakaian Hari Ini</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $totalPemakaianHariIni; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Item</span>
          </div>
        </div>
      </div>

      <!-- Card 2: Pasien Ditangani Hari Ini -->
      <div class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shadow-sm border border-emerald-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-user-check"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pasien Hari Ini</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $pasienSelesai; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Orang</span>
          </div>
        </div>
      </div>

      <!-- Card 3: Stok Menipis -->
      <div class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-red-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center font-bold shadow-sm border border-red-100/50 group-hover:-translate-y-1 transition-transform <?php echo $stokMenipis > 0 ? 'animate-[pulse_3s_ease-in-out_infinite]' : ''; ?>">
          <i class="text-lg fas fa-exclamation-triangle"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Stok Menipis</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold <?php echo $stokMenipis > 0 ? 'text-red-600' : 'text-slate-800'; ?> leading-none"><?php echo $stokMenipis; ?></h4>
            <span class="text-[12px] font-semibold <?php echo $stokMenipis > 0 ? 'text-red-500' : 'text-slate-500'; ?> mb-1 flex items-center gap-1">
              <?php if ($stokMenipis > 0): ?><i class="fas fa-arrow-down text-[8px]"></i><?php endif; ?>
              Jenis
            </span>
          </div>
        </div>
      </div>

      <!-- Card 4: Total Catatan (semua waktu) -->
      <div class="bg-white rounded-[20px] p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold shadow-sm border border-amber-100/50 group-hover:-translate-y-1 transition-transform">
          <i class="text-lg fas fa-clipboard-list"></i>
        </div>
        <div class="z-10">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Catatan Saya</p>
          <div class="flex items-end gap-1.5">
            <h4 class="text-[26px] font-display font-bold text-slate-800 leading-none"><?php echo $totalSemuaPemakaian; ?></h4>
            <span class="text-[12px] font-semibold text-slate-500 mb-1">Sesi</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Bottom Two Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

      <!-- Left side (Table): Riwayat Pemakaian -->
      <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="flex items-center justify-between px-1">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-500"><i class="fas fa-history text-[13px]"></i></div>
            <h3 class="text-[16px] font-bold text-slate-800 font-display">Riwayat Pemakaian Terbaru</h3>
          </div>
          <a href="index.php?page=laporan" class="text-[12px] font-bold text-[#006B47] hover:text-[#10B981] transition-colors py-1.5 px-3 rounded-lg hover:bg-emerald-50">Lihat Semua &rarr;</a>
        </div>

        <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden relative">
          <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap">
              <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pasien</th>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tindakan</th>
                  <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Item BHP</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50 text-[13px]">
                <?php if (empty($riwayatTerbaru)): ?>
                <tr>
                  <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">
                    <i class="fas fa-clipboard text-4xl opacity-30 block mb-2"></i>
                    Belum ada catatan pemakaian. Klik "Catat Pemakaian Baru" untuk memulai.
                  </td>
                </tr>
                <?php else: foreach ($riwayatTerbaru as $p):
                  $initials = $p['nama_pasien']
                    ? strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $p['nama_pasien']), 0, 2))))
                    : '-';
                ?>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="px-6 py-4 font-bold text-slate-600 text-[12px]"><?php echo date('d/m/Y', strtotime($p['tanggal'])); ?></td>
                  <td class="px-6 py-4 font-bold text-slate-800">
                    <div class="flex items-center gap-3">
                      <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold"><?php echo $initials; ?></div>
                      <span class="text-[13px]"><?php echo htmlspecialchars($p['nama_pasien'] ?? 'Tanpa Pasien'); ?></span>
                    </div>
                  </td>
                  <td class="px-6 py-4 font-semibold text-slate-600"><?php echo htmlspecialchars($p['unit_tindakan'] ?? '-'); ?></td>
                  <td class="px-6 py-4 text-center">
                    <span class="inline-block px-3 py-1.5 bg-[#F0FDF4] text-[#059669] rounded-md font-bold text-[11px] border border-[#A7F3D0]/40">
                      <?php echo (int)($p['jumlah_item'] ?? 0); ?> Item
                    </span>
                  </td>
                </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
          <div class="p-4 border-t border-slate-50 bg-slate-50/30 text-center">
            <span class="text-[12px] font-medium text-slate-400">Menampilkan <?php echo min(4, count($riwayatTerbaru)); ?> catatan terbaru</span>
          </div>
        </div>
      </div>

      <!-- Right side: Top BHP -->
      <div class="lg:col-span-1 flex flex-col gap-4">
        <div class="flex items-center gap-2 px-1">
          <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-500"><i class="fas fa-chart-pie text-[13px]"></i></div>
          <h3 class="text-[16px] font-bold text-slate-800 font-display">Top BHP Terbanyak Dipakai</h3>
        </div>
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 p-6 flex flex-col gap-5">
          <?php if (empty($topBhp)): ?>
          <p class="text-sm text-slate-400 text-center py-4">Belum ada data pemakaian BHP.</p>
          <?php else: ?>
          <?php $colors = ['linear-gradient(90deg,#34D399,#006B47)','linear-gradient(90deg,#60A5FA,#2563EB)','linear-gradient(90deg,#FBBF24,#D97706)','linear-gradient(90deg,#A78BFA,#4F46E5)','background:#94a3b8']; ?>
          <?php foreach (array_values($topBhp) as $i => $b): ?>
          <div>
            <div class="flex items-center justify-between mb-2.5">
              <span class="text-[13px] font-bold text-slate-800 truncate max-w-[150px]"><?php echo htmlspecialchars($b['Nama_bhp']); ?></span>
              <span class="text-[12px] font-bold text-slate-500 ml-1"><?php echo (int)($b['Pemakaian'] ?? 0); ?> <span class="text-[10px] font-normal"><?php echo htmlspecialchars($b['Nama_satuan'] ?? ''); ?></span></span>
            </div>
            <div class="w-full h-[6px] bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full" style="width:<?php echo round((int)($b['Pemakaian'] ?? 0) / $maxPem * 100); ?>%;background:<?php echo $colors[min($i, 4)]; ?>;transition:width 0.8s ease;"></div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>

  </div>
</div>
