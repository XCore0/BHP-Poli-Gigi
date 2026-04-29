<?php
// Shared header - uses $role_name, $role_label, $role_avatar_initial, $role_avatar_bg, $role_avatar_color, $role_label_color
$role_name = $role_name ?? 'User';
$role_label = $role_label ?? 'User';
$role_avatar_initial = $role_avatar_initial ?? 'U';
$role_avatar_bg = $role_avatar_bg ?? 'linear-gradient(135deg, #a8edea 0%, #5b9bd5 100%)';
$role_avatar_color = $role_avatar_color ?? '#1e4a7a';
$role_label_color = $role_label_color ?? 'text-brand-600';
?>

<!-- ======================== LOGOUT MODAL ======================== -->
<div id="logout-modal" class="fixed inset-0 z-[999] hidden items-center justify-center px-4"
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
      <div class="relative flex items-center justify-center cursor-pointer hover:opacity-80 transition-opacity">
        <i class="far fa-bell text-[#9CA3AF] text-xl"></i>
        <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 w-2.5 h-2.5 rounded-full bg-red-500"></span>
      </div>

      <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>

      <button class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity"
        onclick="toggleDropdown(event)">
        <div
          class="w-9 h-9 rounded-full flex items-center justify-center font-plex font-semibold text-sm shrink-0"
          style="background: <?php echo $role_avatar_bg; ?>; color: <?php echo $role_avatar_color; ?>;">
          <?php echo $role_avatar_initial; ?>
        </div>
        <div class="flex flex-col items-start gap-0 text-left hidden sm:flex">
          <span class="font-plex font-semibold text-sm text-[#101828] leading-5 whitespace-nowrap"><?php echo htmlspecialchars($role_name); ?></span>
          <span class="font-plex font-medium text-xs leading-4 <?php echo $role_label_color; ?> whitespace-nowrap"><?php echo htmlspecialchars($role_label); ?></span>
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
        <!-- Logout: gunakan onclick showLogoutModal() — BUKAN href langsung, untuk mencegah double-log via SPA -->
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
  /* ─── Logout Modal ─────────────────────────────────────── */
  function showLogoutModal() {
    const modal = document.getElementById('logout-modal');
    const card  = document.getElementById('logout-modal-card');
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
    // Disable tombol langsung — mencegah double-klik dan double-log
    btn.disabled = true;
    btn.style.opacity = '0.75';
    icon.className = 'fas fa-spinner fa-spin text-sm';
    text.textContent = 'Keluar...';
    // Navigasi langsung (bypass SPA interceptor) — hanya SATU request ke logout.php
    window.location.href = '/BHP-Poli-Gigi/logout.php';
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
