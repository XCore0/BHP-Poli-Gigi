<?php
/**
 * Halaman Manajemen Pengguna (Admin)
 * Data dinamis dari database + form tambah pengguna via modal
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\UserManager;
use App\Classes\ActivityLog;

$manager   = new UserManager();
$users     = $manager->getAllUsers();
$total     = $manager->countAll();
$adminCnt  = $manager->countByRole('admin');
$dokterCnt = $manager->countByRole('dokter');
$kepalaCnt = $manager->countByRole('kepala_klinik');

// Log Aktivitas
$logObj      = new ActivityLog();
$logFilter   = [
    'keyword'   => $_GET['log_keyword']   ?? '',
    'kategori'  => $_GET['log_kategori']  ?? '',
    'role'      => $_GET['log_role']      ?? '',
];
$logPage     = max(1, (int)($_GET['log_page'] ?? 1));
$logLimit    = 10;
$logOffset   = ($logPage - 1) * $logLimit;
$logs        = $logObj->getLogs($logFilter, $logLimit, $logOffset);
$logTotal    = $logObj->countLogs($logFilter);
$logHariIni  = $logObj->countToday();
$logKategori = [
    'auth'      => $logObj->countByKategori('auth'),
    'pengguna'  => $logObj->countByKategori('pengguna'),
    'bhp'       => $logObj->countByKategori('bhp'),
    'stok'      => $logObj->countByKategori('stok'),
];
$totalPages  = (int) ceil($logTotal / $logLimit);
?>

<!-- ======================================================
     MODAL TAMBAH PENGGUNA
     ====================================================== -->
<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
  <!-- Backdrop -->
  <div id="modal-backdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
    onclick="closeModal()"></div>

  <!-- Modal Card -->
  <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden animate-modal">

    <!-- Top accent -->
    <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #006B47 0%, #07FFA7 100%);"></div>

    <!-- Header -->
    <div class="flex items-center justify-between px-7 pt-7 pb-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
          style="background: #ECFDF5; border: 1px solid #A7F3D0;">
          <i class="fas fa-user-plus text-brand-600" style="color: #059669;"></i>
        </div>
        <div>
          <h2 class="font-display font-bold text-slate-800 text-lg">Tambah Pengguna</h2>
          <p class="font-plex text-xs text-slate-400">Buat akun pengguna baru</p>
        </div>
      </div>
      <button onclick="closeModal()"
        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
        <i class="fas fa-times text-sm"></i>
      </button>
    </div>

    <!-- Divider -->
    <div class="border-t border-slate-100 mx-7"></div>

    <!-- Alert (hidden by default) -->
    <div id="modal-alert" class="hidden mx-7 mt-4 px-4 py-3 rounded-xl text-sm font-plex font-medium flex items-center gap-2"></div>

    <!-- Form -->
    <form id="form-tambah-user" class="px-7 py-5 space-y-4">
      <!-- Nama Lengkap -->
      <div>
        <label class="block text-sm font-plex font-semibold text-slate-700 mb-1.5">
          Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nama" id="input-nama"
          placeholder="cth: Drg. Budi Santoso, Sp.KG"
          class="w-full h-11 px-4 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:border-transparent placeholder:text-slate-400"
          style="--tw-ring-color: #059669;" required>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-plex font-semibold text-slate-700 mb-1.5">
          Email Login <span class="text-red-500">*</span>
        </label>
        <input type="email" name="email" id="input-email"
          placeholder="email@poligigi.com"
          class="w-full h-11 px-4 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:border-transparent placeholder:text-slate-400"
          style="--tw-ring-color: #059669;" required>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-plex font-semibold text-slate-700 mb-1.5">
          Password <span class="text-red-500">*</span>
          <span class="text-slate-400 font-normal">(minimal 6 karakter)</span>
        </label>
        <div class="relative">
          <input type="password" name="password" id="input-password"
            placeholder="Buat password yang kuat"
            class="w-full h-11 pl-4 pr-10 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:border-transparent placeholder:text-slate-400"
            style="--tw-ring-color: #059669;" required minlength="6">
          <button type="button" onclick="toggleModalPwd()"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors text-sm">
            <i id="modal-eye" class="fas fa-eye"></i>
          </button>
        </div>
        <!-- Strength bar -->
        <div class="mt-2 h-1.5 bg-slate-100 rounded-full overflow-hidden">
          <div id="pwd-strength-bar" class="h-full rounded-full transition-all duration-300" style="width:0;background:#e5e7eb;"></div>
        </div>
        <p id="pwd-strength-text" class="text-[11px] font-plex text-slate-400 mt-1"></p>
      </div>

      <!-- Role -->
      <div>
        <label class="block text-sm font-plex font-semibold text-slate-700 mb-1.5">
          Role / Hak Akses <span class="text-red-500">*</span>
        </label>
        <select name="role" id="input-role"
          class="w-full h-11 px-4 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 bg-white focus:outline-none focus:ring-2 focus:border-transparent"
          style="--tw-ring-color: #059669;" required>
          <option value="" disabled selected>-- Pilih role --</option>
          <option value="admin">Admin</option>
          <option value="dokter">Dokter</option>
          <option value="kepala_klinik">Kepala Klinik</option>
        </select>
      </div>

      <!-- Footer Buttons -->
      <div class="flex items-center gap-3 pt-2">
        <button type="button" onclick="closeModal()"
          class="flex-1 h-11 border border-slate-200 rounded-xl text-sm font-plex font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
          Batal
        </button>
        <button type="submit" id="btn-simpan"
          class="flex-1 h-11 rounded-xl text-sm font-plex font-bold text-white flex items-center justify-center gap-2 transition-all"
          style="background: linear-gradient(135deg, #047857 0%, #34D399 100%); box-shadow: 0 4px 12px rgba(5,150,105,0.3);">
          <i id="btn-simpan-icon" class="fas fa-user-plus text-sm"></i>
          <span id="btn-simpan-text">Simpan Pengguna</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ======================================================
     MAIN CONTENT
     ====================================================== -->
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
            <i class="fas fa-users-gear text-white text-xl sm:text-2xl"></i>
          </div>
          <div class="flex flex-col gap-1 min-w-0">
            <h1 class="font-display font-bold text-white text-xl sm:text-2xl lg:text-3xl leading-tight">Pengguna &amp; Log Aktivitas</h1>
            <p class="font-plex font-medium text-white/90 text-[13px] sm:text-[14px] leading-relaxed hidden sm:block">
              Kelola akun pengguna dan pantau seluruh aktivitas sistem
            </p>
          </div>
        </div>
        <!-- Action button -->
        <div id="banner-action">
          <button onclick="openModal()"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-plex font-semibold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors flex-shrink-0">
            <i class="fas fa-plus"></i> Tambah Pengguna
          </button>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-2">
      <button onclick="switchTab('kelola')" id="tab-kelola"
        class="px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-brand-600 text-white shadow-sm">
        <i class="fas fa-users text-xs"></i> Kelola Pengguna
        <span id="badge-kelola" class="bg-white/20 text-white text-[11px] px-2 py-0.5 rounded-full font-bold"><?php echo $total; ?></span>
      </button>
      <button onclick="switchTab('log')" id="tab-log"
        class="px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-slate-100 text-slate-600 hover:bg-slate-200">
        <i class="fas fa-heart-pulse text-xs"></i> Log Aktivitas
        <span class="bg-slate-200 text-slate-500 text-[11px] px-2 py-0.5 rounded-full font-bold">-</span>
      </button>
    </div>

    <!-- ========== TAB 1: KELOLA PENGGUNA ========== -->
    <div id="content-kelola">
      <!-- Stats Cards -->
      <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #EFF6FF;">
            <i class="fas fa-users text-blue-600 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Pengguna</p>
            <p id="stat-total" class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $total; ?></p>
          </div>
        </div>
        <!-- Admin -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #F3E8FF;">
            <i class="fas fa-shield-halved text-purple-600 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Admin</p>
            <p id="stat-admin" class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $adminCnt; ?></p>
          </div>
        </div>
        <!-- Dokter -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #ECFDF5;">
            <i class="fas fa-user-doctor text-emerald-600 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Dokter</p>
            <p id="stat-dokter" class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $dokterCnt; ?></p>
          </div>
        </div>
        <!-- Kepala Klinik -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #FFF1F2;">
            <i class="fas fa-hospital-user text-rose-500 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Kepala Klinik</p>
            <p id="stat-kepala" class="font-display font-bold text-xl sm:text-2xl text-slate-800 leading-tight"><?php echo $kepalaCnt; ?></p>
          </div>
        </div>
      </div>

      <!-- User Table -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b border-slate-100">
                <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
                <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Email Login</th>
                <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Role / Hak Akses</th>
                <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Akun</th>
                <th class="px-5 py-4 font-plex text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
              </tr>
            </thead>
            <tbody id="user-table-body" class="divide-y divide-slate-100">
              <?php if (empty($users)): ?>
              <tr>
                <td colspan="5" class="px-5 py-12 text-center font-plex text-slate-400">
                  <i class="fas fa-users text-4xl mb-3 opacity-30 block"></i>
                  Belum ada pengguna. Klik "Tambah Pengguna" untuk memulai.
                </td>
              </tr>
              <?php else: ?>
              <?php foreach ($users as $u): ?>
              <?php
                $roleLabel  = ['admin' => 'Admin', 'dokter' => 'Dokter', 'kepala_klinik' => 'Kepala Klinik'][$u['Role']] ?? $u['Role'];
                $roleIcon   = ['admin' => 'fa-shield-halved', 'dokter' => 'fa-user-doctor', 'kepala_klinik' => 'fa-hospital-user'][$u['Role']] ?? 'fa-user';
                $roleBg     = ['admin' => 'bg-indigo-50 text-indigo-600', 'dokter' => 'bg-emerald-50 text-emerald-600', 'kepala_klinik' => 'bg-amber-50 text-amber-600'][$u['Role']] ?? 'bg-slate-50 text-slate-600';
                $isAktif    = $u['Status_akun'] === 'aktif';
              ?>
              <tr class="hover:bg-slate-50/50 transition-colors" id="row-<?php echo $u['id_user']; ?>">
                <td class="px-5 py-4 font-plex text-sm font-medium text-slate-700"><?php echo htmlspecialchars($u['Nama_lengkap']); ?></td>
                <td class="px-5 py-4 font-plex text-sm text-slate-500"><?php echo htmlspecialchars($u['Email']); ?></td>
                <td class="px-5 py-4">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex <?php echo $roleBg; ?>">
                    <i class="fas <?php echo $roleIcon; ?> text-[10px]"></i> <?php echo $roleLabel; ?>
                  </span>
                </td>
                <td class="px-5 py-4">
                  <span id="status-text-<?php echo $u['id_user']; ?>"
                    class="font-plex text-sm font-semibold <?php echo $isAktif ? 'text-emerald-500' : 'text-slate-400'; ?>">
                    <?php echo $isAktif ? 'Aktif' : 'Nonaktif'; ?>
                  </span>
                </td>
                <td class="px-5 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <!-- Toggle Status -->
                    <button onclick="toggleStatus(<?php echo $u['id_user']; ?>)"
                      id="btn-toggle-<?php echo $u['id_user']; ?>"
                      title="<?php echo $isAktif ? 'Nonaktifkan' : 'Aktifkan'; ?> akun"
                      class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors <?php echo $isAktif ? 'bg-red-50 text-red-400 hover:bg-red-100' : 'bg-emerald-50 text-emerald-500 hover:bg-emerald-100'; ?>">
                      <i class="fas <?php echo $isAktif ? 'fa-ban' : 'fa-check-circle'; ?> text-sm"></i>
                    </button>
                    <!-- Hapus -->
                    <button onclick="hapusUser(<?php echo $u['id_user']; ?>, '<?php echo htmlspecialchars($u['Nama_lengkap'], ENT_QUOTES); ?>')"
                      title="Hapus pengguna"
                      class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-400 transition-colors">
                      <i class="fas fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- ========== END TAB 1 ========== -->

    <!-- ========== TAB 2: LOG AKTIVITAS ========== -->
    <div id="content-log" class="hidden">

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#F3E8FF">
            <i class="fas fa-heart-pulse text-purple-600 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Log</p>
            <p class="font-display font-bold text-xl sm:text-2xl text-slate-800"><?php echo $logTotal; ?></p>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#EFF6FF">
            <i class="fas fa-clock text-blue-600 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Aktivitas Hari Ini</p>
            <p class="font-display font-bold text-xl sm:text-2xl text-slate-800"><?php echo $logHariIni; ?></p>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#ECFDF5">
            <i class="fas fa-right-to-bracket text-emerald-600 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Login / Logout</p>
            <p class="font-display font-bold text-xl sm:text-2xl text-slate-800"><?php echo $logKategori['auth']; ?></p>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#ECFEFF">
            <i class="fas fa-boxes-stacked text-cyan-500 text-lg sm:text-xl"></i>
          </div>
          <div class="min-w-0">
            <p class="font-plex text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">BHP & Stok</p>
            <p class="font-display font-bold text-xl sm:text-2xl text-slate-800"><?php echo $logKategori['bhp'] + $logKategori['stok']; ?></p>
          </div>
        </div>
      </div>

      <!-- Filter & Search -->
      <form method="GET" action="" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 mb-4">
        <input type="hidden" name="page" value="pengguna">
        <input type="hidden" name="log_page" value="1">
        <div class="flex flex-wrap items-center gap-3">
          <div class="relative flex-1 min-w-[200px]">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="log_keyword" value="<?php echo htmlspecialchars($logFilter['keyword']); ?>"
              placeholder="Cari nama, aksi, atau detail..."
              class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
          </div>
          <select name="log_kategori"
            class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <option value="">Semua Kategori</option>
            <option value="auth"      <?php echo $logFilter['kategori']==='auth'      ? 'selected':'' ?>>Auth (Login/Logout)</option>
            <option value="pengguna" <?php echo $logFilter['kategori']==='pengguna'  ? 'selected':'' ?>>Pengguna</option>
            <option value="bhp"      <?php echo $logFilter['kategori']==='bhp'       ? 'selected':'' ?>>BHP</option>
            <option value="stok"     <?php echo $logFilter['kategori']==='stok'      ? 'selected':'' ?>>Stok</option>
            <option value="laporan"  <?php echo $logFilter['kategori']==='laporan'   ? 'selected':'' ?>>Laporan</option>
            <option value="sistem"   <?php echo $logFilter['kategori']==='sistem'    ? 'selected':'' ?>>Sistem</option>
          </select>
          <select name="log_role"
            class="h-10 px-3 border border-slate-200 rounded-xl text-sm font-plex text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <option value="">Semua Role</option>
            <option value="admin"         <?php echo $logFilter['role']==='admin'         ? 'selected':'' ?>>Admin</option>
            <option value="dokter"        <?php echo $logFilter['role']==='dokter'        ? 'selected':'' ?>>Dokter</option>
            <option value="kepala_klinik" <?php echo $logFilter['role']==='kepala_klinik' ? 'selected':'' ?>>Kepala Klinik</option>
          </select>
          <button type="submit"
            class="h-10 px-5 rounded-xl text-sm font-plex font-semibold text-white transition-all"
            style="background:linear-gradient(135deg,#047857 0%,#34D399 100%)">
            <i class="fas fa-filter mr-1"></i> Terapkan
          </button>
          <?php if ($logFilter['keyword'] || $logFilter['kategori'] || $logFilter['role']): ?>
          <a href="?page=pengguna" class="h-10 px-4 rounded-xl text-sm font-plex font-semibold text-slate-500 border border-slate-200 flex items-center hover:bg-slate-50 transition-colors">
            <i class="fas fa-times mr-1"></i> Reset
          </a>
          <?php endif; ?>
        </div>
      </form>

      <!-- Log Table -->
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
                <td colspan="4" class="px-5 py-12 text-center font-plex text-slate-400">
                  <i class="fas fa-heart-pulse text-4xl mb-3 opacity-30 block"></i>
                  <?php echo $logFilter['keyword'] || $logFilter['kategori'] || $logFilter['role']
                    ? 'Tidak ada log yang cocok dengan filter.' : 'Belum ada aktivitas tercatat.'; ?>
                </td>
              </tr>
              <?php else: ?>
              <?php foreach ($logs as $log): ?>
              <?php
                // Warna avatar berdasarkan role
                $avatarStyle = match($log['role_user']) {
                  'admin'         => 'background:linear-gradient(135deg,#c7d2fe 0%,#6366f1 100%);color:#1e1b4b',
                  'dokter'        => 'background:linear-gradient(135deg,#a8edea 0%,#5b9bd5 100%);color:#1e4a7a',
                  'kepala_klinik' => 'background:linear-gradient(135deg,#fde68a 0%,#f59e0b 100%);color:#78350f',
                  default         => 'background:#e2e8f0;color:#475569',
                };
                $avatarInit = strtoupper(substr($log['nama_user'], 0, 1));
                // Badge warna aksi berdasarkan kategori
                $aksiBadge = match($log['kategori']) {
                  'auth'     => 'bg-blue-50 text-blue-600',
                  'pengguna' => 'bg-purple-50 text-purple-600',
                  'bhp'      => 'bg-emerald-50 text-emerald-600',
                  'stok'     => 'bg-cyan-50 text-cyan-600',
                  'laporan'  => 'bg-amber-50 text-amber-600',
                  default    => 'bg-slate-100 text-slate-500',
                };
                $aksiIcon = match($log['aksi']) {
                  'login'             => 'fa-right-to-bracket',
                  'logout'            => 'fa-right-from-bracket',
                  'tambah_pengguna'   => 'fa-user-plus',
                  'hapus_pengguna'    => 'fa-user-minus',
                  'ubah_status_pengguna' => 'fa-user-pen',
                  'catat_pemakaian'   => 'fa-syringe',
                  'stok_masuk'        => 'fa-boxes-stacked',
                  default             => 'fa-circle-dot',
                };
                $aksiLabel = str_replace('_', ' ', $log['aksi']);
                $aksiLabel = ucwords($aksiLabel);
                // Format waktu
                $waktuTmp  = strtotime($log['waktu']);
                $hari      = date('Y-m-d', $waktuTmp);
                $today     = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 day'));
                $waktuLabel = ($hari === $today) ? 'Hari ini, ' . date('H:i', $waktuTmp)
                            : (($hari === $yesterday) ? 'Kemarin, ' . date('H:i', $waktuTmp)
                            : date('d/m/Y H:i', $waktuTmp));
              ?>
              <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-5 py-4">
                  <div class="flex items-center gap-2">
                    <i class="far fa-clock text-slate-300 text-sm"></i>
                    <span class="font-plex text-sm text-slate-600"><?php echo $waktuLabel; ?></span>
                  </div>
                </td>
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
                <td class="px-5 py-4">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-plex <?php echo $aksiBadge; ?>">
                    <i class="fas <?php echo $aksiIcon; ?> text-[10px]"></i>
                    <?php echo htmlspecialchars($aksiLabel); ?>
                  </span>
                </td>
                <td class="px-5 py-4 font-plex text-sm text-slate-600 max-w-[300px] truncate" title="<?php echo htmlspecialchars($log['detail']); ?>">
                  <?php echo htmlspecialchars($log['detail'] ?: '-'); ?>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex flex-col sm:flex-row items-center justify-between px-5 py-4 border-t border-slate-100 gap-3">
          <p class="font-plex text-sm text-slate-500">
            Menampilkan <?php echo min($logOffset + 1, $logTotal); ?>–<?php echo min($logOffset + $logLimit, $logTotal); ?> dari <?php echo $logTotal; ?> log
          </p>
          <div class="flex items-center gap-1.5">
            <?php if ($logPage > 1): ?>
            <a href="?page=pengguna&log_page=<?php echo $logPage-1; ?>&log_keyword=<?php echo urlencode($logFilter['keyword']); ?>&log_kategori=<?php echo urlencode($logFilter['kategori']); ?>&log_role=<?php echo urlencode($logFilter['role']); ?>"
              class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
              <i class="fas fa-chevron-left text-xs"></i>
            </a>
            <?php endif; ?>
            <?php for ($p = max(1, $logPage-2); $p <= min($totalPages, $logPage+2); $p++): ?>
            <a href="?page=pengguna&log_page=<?php echo $p; ?>&log_keyword=<?php echo urlencode($logFilter['keyword']); ?>&log_kategori=<?php echo urlencode($logFilter['kategori']); ?>&log_role=<?php echo urlencode($logFilter['role']); ?>"
              class="w-9 h-9 rounded-lg flex items-center justify-center font-plex text-sm transition-colors <?php echo $p === $logPage ? 'bg-brand-600 text-white font-semibold' : 'border border-slate-200 text-slate-600 hover:bg-slate-50'; ?>">
              <?php echo $p; ?>
            </a>
            <?php endfor; ?>
            <?php if ($logPage < $totalPages): ?>
            <a href="?page=pengguna&log_page=<?php echo $logPage+1; ?>&log_keyword=<?php echo urlencode($logFilter['keyword']); ?>&log_kategori=<?php echo urlencode($logFilter['kategori']); ?>&log_role=<?php echo urlencode($logFilter['role']); ?>"
              class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
              <i class="fas fa-chevron-right text-xs"></i>
            </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <!-- ========== END TAB 2 ========== -->

  </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-6 right-6 z-50 hidden items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-plex font-semibold text-white min-w-[260px]">
  <i id="toast-icon" class="fas text-base"></i>
  <span id="toast-msg"></span>
</div>

<style>
  .animate-modal { animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
  @keyframes modalIn {
    from { opacity: 0; transform: translateY(24px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }
  .tab-exit-left  { animation: slideOutLeft  0.25s cubic-bezier(0.4,0,0.2,1) forwards; }
  .tab-exit-right { animation: slideOutRight 0.25s cubic-bezier(0.4,0,0.2,1) forwards; }
  .tab-enter-left  { animation: slideInLeft  0.35s cubic-bezier(0.16,1,0.3,1) forwards; }
  .tab-enter-right { animation: slideInRight 0.35s cubic-bezier(0.16,1,0.3,1) forwards; }
  @keyframes slideOutLeft  { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(-30px)} }
  @keyframes slideOutRight { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(30px)} }
  @keyframes slideInLeft   { from{opacity:0;transform:translateX(-30px)} to{opacity:1;transform:translateX(0)} }
  @keyframes slideInRight  { from{opacity:0;transform:translateX(30px)} to{opacity:1;transform:translateX(0)} }
  @keyframes toastIn  { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
  @keyframes toastOut { from{opacity:1;transform:translateY(0)} to{opacity:0;transform:translateY(12px)} }
</style>

<script>
  /* ─── Tab Switch ─────────────────────────────────── */
  let currentTab = 'kelola';
  function switchTab(tab) {
    if (tab === currentTab) return;
    const tabKelola = document.getElementById('tab-kelola');
    const tabLog    = document.getElementById('tab-log');
    const cKelola   = document.getElementById('content-kelola');
    const cLog      = document.getElementById('content-log');
    const goFwd     = (tab === 'log');
    const out = goFwd ? cKelola : cLog;
    const into = goFwd ? cLog : cKelola;
    out.classList.add(goFwd ? 'tab-exit-left' : 'tab-exit-right');
    setTimeout(() => {
      out.classList.add('hidden'); out.classList.remove('tab-exit-left','tab-exit-right');
      into.classList.remove('hidden'); into.classList.add(goFwd ? 'tab-enter-right' : 'tab-enter-left');
      setTimeout(() => into.classList.remove('tab-enter-left','tab-enter-right'), 350);
    }, 250);
    const activeClass = 'px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-brand-600 text-white shadow-sm';
    const inactiveClass = 'px-5 py-2.5 rounded-full text-sm font-plex font-semibold transition-all duration-200 flex items-center gap-2 bg-slate-100 text-slate-600 hover:bg-slate-200';
    if (tab === 'kelola') { tabKelola.className = activeClass; tabLog.className = inactiveClass; }
    else { tabLog.className = activeClass; tabKelola.className = inactiveClass; }
    currentTab = tab;
  }

  /* ─── Modal ──────────────────────────────────────── */
  function openModal() {
    const m = document.getElementById('modal-tambah');
    m.classList.remove('hidden');
    m.classList.add('flex');
    document.getElementById('form-tambah-user').reset();
    hideModalAlert();
    resetPwdStrength();
  }
  function closeModal() {
    const m = document.getElementById('modal-tambah');
    m.classList.add('hidden');
    m.classList.remove('flex');
  }

  /* ─── Toggle Modal Password ──────────────────────── */
  function toggleModalPwd() {
    const inp = document.getElementById('input-password');
    const icon = document.getElementById('modal-eye');
    const isText = inp.type === 'text';
    inp.type = isText ? 'password' : 'text';
    icon.className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
  }

  /* ─── Password Strength ──────────────────────────── */
  document.getElementById('input-password').addEventListener('input', function() {
    const val  = this.value;
    const bar  = document.getElementById('pwd-strength-bar');
    const text = document.getElementById('pwd-strength-text');
    let score  = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
      { color: '#e5e7eb', label: '', pct: '0%' },
      { color: '#EF4444', label: 'Sangat Lemah', pct: '20%' },
      { color: '#F97316', label: 'Lemah', pct: '40%' },
      { color: '#EAB308', label: 'Sedang', pct: '60%' },
      { color: '#22C55E', label: 'Kuat', pct: '80%' },
      { color: '#059669', label: 'Sangat Kuat', pct: '100%' },
    ];
    const lvl = levels[score] || levels[0];
    bar.style.width     = lvl.pct;
    bar.style.background = lvl.color;
    text.textContent    = lvl.label;
    text.style.color    = lvl.color;
  });
  function resetPwdStrength() {
    document.getElementById('pwd-strength-bar').style.width = '0';
    document.getElementById('pwd-strength-text').textContent = '';
  }

  /* ─── Modal Alert ────────────────────────────────── */
  function showModalAlert(msg, type = 'error') {
    const el = document.getElementById('modal-alert');
    el.classList.remove('hidden','bg-red-50','text-red-600','bg-emerald-50','text-emerald-600','border','border-red-200','border-emerald-200');
    if (type === 'error') {
      el.className = 'mx-7 mt-4 px-4 py-3 rounded-xl text-sm font-plex font-medium flex items-center gap-2 bg-red-50 text-red-600 border border-red-200';
      el.innerHTML = '<i class="fas fa-circle-exclamation"></i> ' + msg;
    } else {
      el.className = 'mx-7 mt-4 px-4 py-3 rounded-xl text-sm font-plex font-medium flex items-center gap-2 bg-emerald-50 text-emerald-600 border border-emerald-200';
      el.innerHTML = '<i class="fas fa-circle-check"></i> ' + msg;
    }
  }
  function hideModalAlert() {
    document.getElementById('modal-alert').classList.add('hidden');
  }

  /* ─── Toast ──────────────────────────────────────── */
  let toastTimer;
  function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const msgEl = document.getElementById('toast-msg');
    clearTimeout(toastTimer);
    toast.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-plex font-semibold text-white min-w-[260px]';
    toast.style.animation = 'toastIn 0.3s ease forwards';
    if (type === 'success') {
      toast.style.background = 'linear-gradient(135deg, #047857 0%, #059669 100%)';
      icon.className = 'fas fa-circle-check text-base';
    } else {
      toast.style.background = 'linear-gradient(135deg, #DC2626 0%, #EF4444 100%)';
      icon.className = 'fas fa-circle-exclamation text-base';
    }
    msgEl.textContent = msg;
    toastTimer = setTimeout(() => { toast.style.animation = 'toastOut 0.3s ease forwards'; setTimeout(() => toast.classList.add('hidden'), 300); }, 3000);
  }

  /* ─── Submit Form Tambah Pengguna ────────────────── */
  document.getElementById('form-tambah-user').addEventListener('submit', async function(e) {
    e.preventDefault();
    hideModalAlert();
    const btn  = document.getElementById('btn-simpan');
    const icon = document.getElementById('btn-simpan-icon');
    const text = document.getElementById('btn-simpan-text');
    btn.disabled = true;
    icon.className = 'fas fa-spinner fa-spin text-sm';
    text.textContent = 'Menyimpan...';

    const data = new FormData(this);
    data.append('action', 'add');

    try {
      const res  = await fetch('/BHP-Poli-Gigi/process/user_process.php', { method: 'POST', body: data });
      const json = await res.json();
      if (json.success) {
        showToast(json.message, 'success');
        closeModal();
        setTimeout(() => location.reload(), 800);
      } else {
        showModalAlert(json.message, 'error');
      }
    } catch (err) {
      showModalAlert('Terjadi kesalahan jaringan. Coba lagi.', 'error');
    } finally {
      btn.disabled = false;
      icon.className = 'fas fa-user-plus text-sm';
      text.textContent = 'Simpan Pengguna';
    }
  });

  /* ─── Toggle Status Akun ─────────────────────────── */
  async function toggleStatus(id) {
    const data = new FormData();
    data.append('action', 'toggle_status');
    data.append('id', id);
    try {
      const res  = await fetch('/BHP-Poli-Gigi/process/user_process.php', { method: 'POST', body: data });
      const json = await res.json();
      if (json.success) {
        const isAktif   = json.new_status === 'aktif';
        const statusEl  = document.getElementById('status-text-' + id);
        const btnEl     = document.getElementById('btn-toggle-' + id);
        statusEl.textContent = isAktif ? 'Aktif' : 'Nonaktif';
        statusEl.className   = 'font-plex text-sm font-semibold ' + (isAktif ? 'text-emerald-500' : 'text-slate-400');
        btnEl.title          = isAktif ? 'Nonaktifkan akun' : 'Aktifkan akun';
        btnEl.className      = 'w-8 h-8 rounded-lg flex items-center justify-center transition-colors ' + (isAktif ? 'bg-red-50 text-red-400 hover:bg-red-100' : 'bg-emerald-50 text-emerald-500 hover:bg-emerald-100');
        btnEl.querySelector('i').className = 'fas ' + (isAktif ? 'fa-ban' : 'fa-check-circle') + ' text-sm';
        showToast(json.message, 'success');
      } else {
        showToast(json.message, 'error');
      }
    } catch { showToast('Gagal mengubah status.', 'error'); }
  }

  /* ─── Hapus Pengguna ─────────────────────────────── */
  async function hapusUser(id, nama) {
    if (!confirm('Hapus pengguna "' + nama + '"?\nTindakan ini tidak dapat dibatalkan.')) return;
    const data = new FormData();
    data.append('action', 'delete');
    data.append('id', id);
    try {
      const res  = await fetch('/BHP-Poli-Gigi/process/user_process.php', { method: 'POST', body: data });
      const json = await res.json();
      if (json.success) {
        const row = document.getElementById('row-' + id);
        if (row) { row.style.animation = 'slideOutLeft 0.3s ease forwards'; setTimeout(() => row.remove(), 300); }
        showToast(json.message, 'success');
      } else {
        showToast(json.message, 'error');
      }
    } catch { showToast('Gagal menghapus pengguna.', 'error'); }
  }
</script>
