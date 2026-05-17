<?php
// Shared header - uses $role_name, $role_label, $role_avatar_initial, $role_avatar_bg, $role_avatar_color, $role_label_color
$role_name = $role_name ?? 'User';
$role_label = $role_label ?? 'User';
$role_avatar_initial = $role_avatar_initial ?? 'U';
$role_avatar_bg = $role_avatar_bg ?? 'linear-gradient(135deg, #a8edea 0%, #5b9bd5 100%)';
$role_avatar_color = $role_avatar_color ?? '#1e4a7a';
$role_label_color = $role_label_color ?? 'text-brand-600';
$role_photo = $role_photo ?? null;

$notificationData = [
  'count' => 0,
  'items' => [],
  'heading' => 'Notifikasi',
  'empty_message' => 'Belum ada notifikasi baru.',
  'cta_label' => 'Lihat halaman',
  'cta_url' => 'index.php',
];

if (class_exists('\App\Classes\NotificationManager')) {
  try {
    $notificationManager = new \App\Classes\NotificationManager();
    $notificationData = $notificationManager->getHeaderNotifications($currentUser ?? [], 6);
  } catch (Throwable $e) {
    $notificationData = [
      'count' => 0,
      'items' => [],
      'heading' => 'Notifikasi',
      'empty_message' => 'Belum ada notifikasi baru.',
      'cta_label' => 'Lihat halaman',
      'cta_url' => 'index.php',
    ];
  }
}

$notificationCount = (int)($notificationData['count'] ?? 0);
$notificationItems = $notificationData['items'] ?? [];
$notificationHeading = $notificationData['heading'] ?? 'Notifikasi';
$notificationEmptyMessage = $notificationData['empty_message'] ?? 'Belum ada notifikasi baru.';
$notificationCtaLabel = $notificationData['cta_label'] ?? 'Lihat halaman';
$notificationCtaUrl = $notificationData['cta_url'] ?? 'index.php';

if ($notificationCount > 99) {
  $notificationCountLabel = '99+';
} else {
  $notificationCountLabel = (string)$notificationCount;
}
?>

<!-- ======================== LOGOUT MODAL ======================== -->
<div id="logout-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center px-4"
  style="background: rgba(15,23,42,0.55); backdrop-filter: blur(4px);">
  <div id="logout-modal-card"
    class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden"
    style="animation: none;">

    <!-- Accent top bar -->
    <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #DC2626 0%, #F97316 100%);"></div>

    <!-- Content -->
    <div class="px-8 pt-8 pb-7 text-center">

      <!-- Icon -->
      <div class="mx-auto mb-5 w-16 h-16 rounded-2xl flex items-center justify-center"
        style="background: #FEF2F2; border: 1.5px solid #FECACA;">
        <i class="fas fa-right-from-bracket text-2xl" style="color: #DC2626;"></i>
      </div>

      <!-- Text -->
      <h3 class="font-display font-bold text-slate-800 text-xl mb-2">Keluar dari Sistem?</h3>
      <p class="font-plex text-sm text-slate-500 leading-relaxed mb-7">
        Sesi Anda akan diakhiri dan Anda perlu login kembali untuk mengakses sistem.
      </p>

      <!-- Buttons -->
      <div class="flex items-center gap-3">
        <button onclick="closeLogoutModal()"
          class="flex-1 h-11 border border-slate-200 rounded-xl text-sm font-plex font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
          Batal
        </button>
        <button id="btn-logout-confirm" onclick="logoutConfirmed()"
          class="flex-1 h-11 rounded-xl text-sm font-plex font-bold text-white flex items-center justify-center gap-2 transition-all"
          style="background: linear-gradient(135deg, #DC2626 0%, #F97316 100%); box-shadow: 0 4px 12px rgba(220,38,38,0.3);">
          <i id="btn-logout-icon" class="fas fa-right-from-bracket text-sm"></i>
          <span id="btn-logout-text">Ya, Keluar</span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- ======================== END LOGOUT MODAL ======================== -->

  <!-- ======================== HEADER ======================== -->
  <header class="w-full h-20 flex items-stretch shadow-sm z-30 shrink-0"
    style="background: rgba(255,255,255,0.80); backdrop-filter: blur(8px);">

    <!-- LEFT: Logo area (matches sidebar width) -->
    <div class="hidden lg:flex items-center gap-4 px-8 shrink-0 sidebar-gradient" style="width: 279px;">
      <div class="flex items-center justify-center w-10 h-10 rounded-xl shadow-lg shrink-0"
        style="background: linear-gradient(135deg, #34D399 0%, #0D9488 100%);">
        <i class="fas fa-tooth text-white text-xl"></i>
      </div>
      <div class="flex flex-col gap-0">
        <span class="text-white font-display font-bold text-xl leading-[25px] tracking-[-0.2px]">POLI GIGI</span>
        <span class="font-display font-bold text-[10px] leading-5 tracking-[0.5px]" style="color: #6EE7B7;">KLINIK PRATAMA</span>
      </div>
    </div>

    <!-- CENTER: Page title + description -->
    <div class="flex items-center flex-1 px-6 lg:px-8 bg-white/80 min-w-0 gap-4">
      <button onclick="toggleMobileMenu()"
        class="lg:hidden text-slate-500 hover:text-brand-600 transition-colors p-2 -ml-2 rounded-lg hover:bg-slate-100 text-xl">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="w-[5px] h-12 rounded-full shrink-0 mr-4 hidden sm:block"
        style="background: linear-gradient(180deg, #006B47 0%, #07FFA7 100%);"></div>

      <div class="flex flex-col gap-0.5 min-w-0">
        <h1 class="font-plex font-bold text-[18px] leading-[22.5px] text-[#101828] truncate"><?php echo htmlspecialchars($page_title ?? 'Poli Gigi'); ?></h1>
        <p class="font-plex font-medium text-sm leading-5 text-[#99A1AF] truncate hidden sm:block"><?php echo htmlspecialchars($page_desc ?? ''); ?></p>
      </div>
    </div>

    <!-- RIGHT: Notifications + User dropdown -->
    <div class="flex items-center gap-6 px-4 sm:px-8 bg-white/80 shrink-0">
      <div class="relative">
        <button type="button" onclick="toggleNotificationDropdown(event)"
          class="relative flex items-center justify-center w-11 h-11 rounded-xl hover:bg-slate-100 transition-colors text-slate-400 hover:text-slate-600">
          <i class="far fa-bell text-xl"></i>
          <?php if ($notificationCount > 0): ?>
            <span id="notification-count-badge"
              class="absolute top-0.5 right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold leading-none flex items-center justify-center shadow-sm">
              <?php echo htmlspecialchars($notificationCountLabel); ?>
            </span>
          <?php endif; ?>
        </button>

        <div id="notification-dropdown"
          class="hidden fixed right-4 lg:right-6 top-20 bg-white rounded-2xl shadow-2xl border border-slate-100 min-w-[340px] max-w-[calc(100vw-1.5rem)] z-50 overflow-hidden">
          <div class="px-4 py-3 border-b border-slate-100 bg-white/80 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-50 text-slate-600">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <h4 class="font-display font-bold text-slate-800 text-sm">Notifikasi</h4>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold whitespace-nowrap">
              <?php echo htmlspecialchars($notificationCountLabel); ?>
            </span>
          </div>
          <div class="max-h-[220px] overflow-y-auto divide-y divide-slate-50">
            <?php if (empty($notificationItems)): ?>
              <div class="px-4 py-8 text-center text-slate-400">
                <i class="far fa-bell-slash text-3xl mb-2 opacity-30 block"></i>
                <p class="text-xs font-medium"><?php echo htmlspecialchars($notificationEmptyMessage); ?></p>
              </div>
            <?php else: ?>
              <?php foreach ($notificationItems as $item):
                $toneClass = match (($item['tone'] ?? 'neutral')) {
                  'danger'  => 'bg-red-50 text-red-600 border-red-100',
                  'warning' => 'bg-amber-50 text-amber-600 border-amber-100',
                  'success' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                  'info'    => 'bg-blue-50 text-blue-600 border-blue-100',
                  default   => 'bg-slate-100 text-slate-500 border-slate-200',
                };
              ?>
              <a href="<?php echo htmlspecialchars($item['url'] ?? 'index.php'); ?>"
                data-notif-id="<?php echo htmlspecialchars($item['id'] ?? ''); ?>"
                class="flex items-start gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors group">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 border <?php echo $toneClass; ?>">
                  <?php
                    $rawIcon = $item['icon'] ?? '';
                    if (!is_string($rawIcon) || trim($rawIcon) === '') {
                      // choose default icon based on tone to match design
                      $tone = $item['tone'] ?? '';
                      switch ($tone) {
                        case 'danger':
                          $iconClass = 'fas fa-exclamation-triangle';
                          break;
                        case 'warning':
                          $iconClass = 'fas fa-clock';
                          break;
                        case 'success':
                          $iconClass = 'fas fa-check';
                          break;
                        case 'info':
                          $iconClass = 'fas fa-info-circle';
                          break;
                        default:
                          $iconClass = 'fas fa-bell';
                      }
                    } else {
                      $iconClass = preg_match('/\\b(fa[srldb]|fab|fa-solid|fa-regular|fa-light)\\b/i', $rawIcon) ? $rawIcon : 'fas ' . $rawIcon;
                    }
                  ?>
                  <i class="<?php echo htmlspecialchars($iconClass); ?> text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2">
                    <h5 class="font-plex font-semibold text-xs text-slate-800 leading-tight line-clamp-2"><?php echo htmlspecialchars($item['title'] ?? 'Notifikasi'); ?></h5>
                    <div class="flex items-center gap-2">
                      <span class="text-[9px] font-semibold text-slate-400 whitespace-nowrap"><?php echo htmlspecialchars($item['time_label'] ?? ''); ?></span>
                      <span class="w-2 h-2 rounded-full inline-block <?php echo (
                        ($item['tone'] ?? '') === 'danger' ? 'bg-red-500' : (
                        ($item['tone'] ?? '') === 'warning' ? 'bg-amber-400' : (
                        ($item['tone'] ?? '') === 'success' ? 'bg-emerald-400' : (
                        ($item['tone'] ?? '') === 'info' ? 'bg-blue-400' : 'bg-slate-300')))
                      ); ?>" aria-hidden="true"></span>
                    </div>
                  </div>
                  <p class="font-plex text-xs text-slate-500 leading-snug mt-0.5 line-clamp-2"><?php echo htmlspecialchars($item['message'] ?? ''); ?></p>
                </div>
              </a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>

      <button class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity"
        onclick="toggleDropdown(event)">
        <div id="header-user-avatar"
          class="w-9 h-9 rounded-full flex items-center justify-center font-plex font-semibold text-sm shrink-0 overflow-hidden"
          style="background: <?php echo $role_avatar_bg; ?>; color: <?php echo $role_avatar_color; ?>;">
          <?php if ($role_photo): ?>
            <img id="header-user-photo" src="<?php echo htmlspecialchars($role_photo); ?>" alt="Profile" class="w-full h-full object-cover">
          <?php else: ?>
            <span id="header-user-initial"><?php echo htmlspecialchars($role_avatar_initial); ?></span>
          <?php endif; ?>
        </div>
        <div class="flex flex-col items-start gap-0 text-left hidden sm:flex">
          <span id="header-user-name" class="font-plex font-semibold text-sm text-[#101828] leading-5 whitespace-nowrap"><?php echo htmlspecialchars($role_name); ?></span>
          <span id="header-user-role" class="font-plex font-medium text-xs leading-4 <?php echo $role_label_color; ?> whitespace-nowrap"><?php echo htmlspecialchars($role_label); ?></span>
        </div>
        <i id="user-chevron"
          class="fas fa-chevron-down text-[#9CA3AF] text-xs transition-transform duration-200 hidden sm:block"></i>
      </button>

      <div id="user-dropdown"
        class="hidden absolute right-6 top-[72px] bg-white rounded-xl shadow-xl border border-gray-100 py-2 min-w-[180px] z-50">
        <a href="index.php?page=profil"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-plex"><i
            class="fas fa-user text-slate-400 w-4"></i> Profil Saya</a>
        <a href="index.php?page=pengaturan"
          class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-plex"><i
            class="fas fa-cog text-slate-400 w-4"></i> Pengaturan</a>
        <div class="my-1 border-t border-gray-100"></div>
        <!-- Logout: gunakan onclick showLogoutModal() â€” BUKAN href langsung, untuk mencegah double-log via SPA -->
        <button onclick="showLogoutModal()"
          class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors font-plex text-left">
          <i class="fas fa-sign-out-alt text-red-400 w-4"></i> Keluar
        </button>
      </div>
    </div>
  </header>
  <!-- ======================== END HEADER ======================== -->

<style>
  @keyframes logoutModalIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0)    scale(1);    }
  }
  @keyframes logoutModalOut {
    from { opacity: 1; transform: translateY(0)    scale(1);    }
    to   { opacity: 0; transform: translateY(12px) scale(0.97); }
  }
</style>

<script>
  /* â”€â”€â”€ Logout Modal â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
  function showLogoutModal() {
    const modal = document.getElementById('logout-modal');
    const card  = document.getElementById('logout-modal-card');
    if (modal.parentNode !== document.body) {
      document.body.appendChild(modal);
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    card.style.animation = 'logoutModalIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards';
    // Reset tombol jika sebelumnya pernah menekan
    document.getElementById('btn-logout-confirm').disabled = false;
    document.getElementById('btn-logout-icon').className = 'fas fa-right-from-bracket text-sm';
    document.getElementById('btn-logout-text').textContent = 'Ya, Keluar';
  }

  function closeLogoutModal() {
    const modal = document.getElementById('logout-modal');
    const card  = document.getElementById('logout-modal-card');
    card.style.animation = 'logoutModalOut 0.25s ease forwards';
    setTimeout(() => {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }, 240);
  }

  function logoutConfirmed() {
    const btn  = document.getElementById('btn-logout-confirm');
    const icon = document.getElementById('btn-logout-icon');
    const text = document.getElementById('btn-logout-text');
    // Disable tombol langsung â€” mencegah double-klik dan double-log
    btn.disabled = true;
    btn.style.opacity = '0.75';
    icon.className = 'fas fa-spinner fa-spin text-sm';
    text.textContent = 'Keluar...';
    // Navigasi langsung (bypass SPA interceptor) â€” hanya SATU request ke logout.php
    window.location.href = '/BHP-Poli-Gigi/pages/auth/logout.php';
  }

  // Tutup modal saat klik backdrop
  document.getElementById('logout-modal').addEventListener('click', function(e) {
    if (e.target === this) closeLogoutModal();
  });

  // Tutup modal dengan Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      const modal = document.getElementById('logout-modal');
      if (!modal.classList.contains('hidden')) closeLogoutModal();
    }
  });
</script>
