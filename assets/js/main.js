function toggleMobileMenu() {
  const sidebar = document.getElementById("sidebarMenu");
  const overlay = document.getElementById("sidebarOverlay");
  if(sidebar && overlay) {
    if(sidebar.classList.contains("-translate-x-full")) {
      sidebar.classList.remove("-translate-x-full");
      overlay.classList.remove("opacity-0", "pointer-events-none");
      overlay.classList.add("opacity-100");
    } else {
      sidebar.classList.add("-translate-x-full");
      overlay.classList.remove("opacity-100");
      overlay.classList.add("opacity-0", "pointer-events-none");
    }
  }
}

function closeMobileMenu() {
  const sidebar = document.getElementById("sidebarMenu");
  const overlay = document.getElementById("sidebarOverlay");
  if (sidebar && !sidebar.classList.contains("-translate-x-full")) {
    sidebar.classList.add("-translate-x-full");
    if(overlay) { 
      overlay.classList.remove("opacity-100"); 
      overlay.classList.add("opacity-0", "pointer-events-none"); 
    }
  }
}

function toggleSubmenu(id) {
  const submenu = document.getElementById(id + "-submenu");
  const chevron = document.getElementById(id + "-chevron");
  if (submenu && chevron) { 
    submenu.classList.toggle("open"); 
    chevron.classList.toggle("open"); 
  }
}

function toggleDropdown(e) {
  if (e) e.stopPropagation();
  closeNotificationDropdown();
  const dropdown = document.getElementById("user-dropdown");
  const chevron = document.getElementById("user-chevron");
  if (dropdown) {
    dropdown.classList.toggle("hidden");
    if (chevron) { 
      chevron.style.transform = dropdown.classList.contains("hidden") ? "rotate(0deg)" : "rotate(180deg)"; 
    }
  }
}

function closeDropdowns() {
  const userDropdown = document.getElementById("user-dropdown");
  const userChevron = document.getElementById("user-chevron");
  const notificationDropdown = document.getElementById("notification-dropdown");

  if (userDropdown && !userDropdown.classList.contains("hidden")) {
    userDropdown.classList.add("hidden");
    if (userChevron) userChevron.style.transform = "rotate(0deg)";
  }

  if (notificationDropdown && !notificationDropdown.classList.contains("hidden")) {
    notificationDropdown.classList.add("hidden");
  }
}

function toggleNotificationDropdown(e) {
  if (e) e.stopPropagation();
  const dropdown = document.getElementById("notification-dropdown");
  if (!dropdown) return;

  const isHidden = dropdown.classList.contains("hidden");
  closeDropdowns();
  if (isHidden) {
    dropdown.classList.remove("hidden");
  }
}

function closeNotificationDropdown() {
  const dropdown = document.getElementById("notification-dropdown");
  if (dropdown && !dropdown.classList.contains("hidden")) {
    dropdown.classList.add("hidden");
  }
}

document.addEventListener("click", function (e) {
  const dropdown = document.getElementById("user-dropdown");
  const notifDropdown = document.getElementById("notification-dropdown");
  const btn = e.target.closest("button[onclick='toggleDropdown(event)']");
  const notifBtn = e.target.closest("button[onclick='toggleNotificationDropdown(event)']");

  if (!btn && !notifBtn) {
    if (dropdown && !dropdown.classList.contains("hidden")) {
      dropdown.classList.add("hidden");
      const chevron = document.getElementById("user-chevron");
      if (chevron) chevron.style.transform = "rotate(0deg)";
    }
    if (notifDropdown && !notifDropdown.classList.contains("hidden")) {
      notifDropdown.classList.add("hidden");
    }
  }
});

// ═══════════════════════════════════════════════════════════════════
// Notifications Read State Tracker (localStorage)
// ═══════════════════════════════════════════════════════════════════
(function initNotifications() {

  function getReadNotifs() {
    return JSON.parse(localStorage.getItem('read_notifications') || '[]');
  }

  function saveReadNotif(id) {
    const read = getReadNotifs();
    if (!read.includes(id)) {
      read.push(id);
      localStorage.setItem('read_notifications', JSON.stringify(read));
    }
  }

  function updateBadge() {
    const badge = document.getElementById('notification-count-badge');
    if (!badge) return;

    const read = getReadNotifs();
    const items = document.querySelectorAll('a[data-notif-id]');
    let unreadCount = 0;

    items.forEach(item => {
      const id = item.getAttribute('data-notif-id');
      if (id && read.includes(id)) {
        item.classList.add('opacity-60');
        // Optional: change dot color or hide it
        const dot = item.querySelector('.rounded-full');
        if (dot) dot.classList.add('bg-slate-300');
      } else {
        unreadCount++;
      }
    });

    const badgeText = document.querySelector('#notification-dropdown span.bg-red-100');

    if (unreadCount > 0) {
      badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
      badge.classList.remove('hidden');
      if (badgeText) badgeText.textContent = unreadCount > 99 ? '99+' : unreadCount;
    } else {
      badge.classList.add('hidden');
      if (badgeText) badgeText.textContent = '0';
    }
  }

  // Initial update
  updateBadge();
  window.updateNotifications = updateBadge;

  // Click listener for notifications
  document.addEventListener('click', function(e) {
    const link = e.target.closest('a[data-notif-id]');
    if (link) {
      const id = link.getAttribute('data-notif-id');
      if (id) {
        saveReadNotif(id);
        updateBadge(); // Update immediately before navigation
      }
    }
  });
})();

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeDropdowns();
  }
});

document.addEventListener('click', async (e) => {
  const link = e.target.closest('a');
  if (!link || !link.href || !link.href.includes(window.location.origin) || link.target === '_blank' || link.hasAttribute('download')) return;
  
  // Skip link yang ditandai data-no-spa atau mengandung logout
  if (link.hasAttribute('data-no-spa') || link.href.includes('logout')) {
    window.location.href = link.href;
    return;
  }
  
  if (typeof closeMobileMenu === 'function') closeMobileMenu();
  e.preventDefault();
  const url = link.href;
  history.pushState(null, '', url);
  await fetchAndRenderPage(url);
});

window.addEventListener('popstate', () => { 
  fetchAndRenderPage(location.href); 
});

async function fetchAndRenderPage(url) {
  try {
    const resp = await fetch(url);
    const text = await resp.text();
    const parser = new DOMParser();
    const newDoc = parser.parseFromString(text, 'text/html');
    if (document.startViewTransition) { 
      document.startViewTransition(() => updatePageContent(newDoc)); 
    } else { 
      updatePageContent(newDoc); 
    }
  } catch(err) { 
    window.location = url; 
  }
}

function updatePageContent(newDoc) {
  document.title = newDoc.title;
  const currentH = document.querySelector('header'); 
  const newH = newDoc.querySelector('header');
  if (currentH && newH) currentH.innerHTML = newH.innerHTML;
  
  const currentS = document.querySelector('aside'); 
  const newS = newDoc.querySelector('aside');
  if (currentS && newS) currentS.innerHTML = newS.innerHTML;
  
  const currentM = document.querySelector('main'); 
  const newM = newDoc.querySelector('main');
  if (currentM && newM) {
    currentM.innerHTML = newM.innerHTML;
    Array.from(currentM.querySelectorAll("script")).forEach(oldScript => {
      const newScript = document.createElement("script");
      Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
      newScript.appendChild(document.createTextNode(oldScript.innerHTML));
      oldScript.parentNode.replaceChild(newScript, oldScript);
    });
  }
  if (typeof window.updateNotifications === 'function') window.updateNotifications();
}

// ═══════════════════════════════════════════════════════════════════
// Global Delete Confirm Modal
// Usage: showDeleteConfirm('Hapus BHP?', 'Detail pesan', () => yourDeleteFn())
// ═══════════════════════════════════════════════════════════════════
(function initDeleteModal() {
  const MODAL_ID = '__global_delete_modal__';
  let _resolveCallback = null;

  function ensureModal() {
    if (document.getElementById(MODAL_ID)) return;
    const div = document.createElement('div');
    div.innerHTML = `
      <div id="${MODAL_ID}" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4"
        style="background:rgba(15,23,42,0.52);backdrop-filter:blur(4px);"
        onclick="if(event.target.id==='${MODAL_ID}')window.__closeDeleteModal()">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden"
          style="animation:_delModalIn .25s cubic-bezier(.34,1.56,.64,1) both">
          <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
            </div>
            <h3 id="${MODAL_ID}_title" class="font-display font-bold text-slate-800 text-lg mb-1">Hapus?</h3>
            <p id="${MODAL_ID}_msg" class="font-plex text-slate-500 text-sm">Tindakan ini tidak dapat dibatalkan.</p>
          </div>
          <div class="flex gap-3 px-6 pb-6">
            <button onclick="window.__closeDeleteModal()"
              class="flex-1 h-11 border-2 border-slate-200 rounded-xl text-sm font-plex font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
              Batal
            </button>
            <button id="${MODAL_ID}_btn" onclick="window.__confirmDelete()"
              class="flex-1 h-11 rounded-xl text-sm font-plex font-bold text-white"
              style="background:linear-gradient(135deg,#dc2626 0%,#ef4444 100%);box-shadow:0 4px 14px rgba(220,38,38,0.35);">
              <i class="fas fa-trash-alt mr-1 text-sm"></i> Ya, Hapus
            </button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(div.firstElementChild);

    // Add keyframe if not already added
    if (!document.getElementById('_delModalStyle')) {
      const style = document.createElement('style');
      style.id = '_delModalStyle';
      style.textContent = '@keyframes _delModalIn{from{opacity:0;transform:scale(.9) translateY(16px)}to{opacity:1;transform:scale(1) translateY(0)}}';
      document.head.appendChild(style);
    }
  }

  window.__closeDeleteModal = function() {
    const m = document.getElementById(MODAL_ID);
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
    _resolveCallback = null;
  };

  window.__confirmDelete = async function() {
    const btn = document.getElementById(MODAL_ID + '_btn');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menghapus...'; }
    if (typeof _resolveCallback === 'function') {
      try { await _resolveCallback(); } catch(e) { console.error(e); }
    }
    window.__closeDeleteModal();
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash-alt mr-1 text-sm"></i> Ya, Hapus'; }
  };

  /**
   * showDeleteConfirm(title, message, onConfirm)
   * Shows a custom animated delete confirm modal.
   * onConfirm: async function to call when user confirms
   */
  window.showDeleteConfirm = function(title, message, onConfirm) {
    ensureModal();
    document.getElementById(MODAL_ID + '_title').textContent = title || 'Konfirmasi Hapus';
    document.getElementById(MODAL_ID + '_msg').textContent   = message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
    _resolveCallback = onConfirm;
    const m = document.getElementById(MODAL_ID);
    m.classList.remove('hidden'); m.classList.add('flex');
  };

  // Escape key closes modal
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && document.getElementById(MODAL_ID) && !document.getElementById(MODAL_ID).classList.contains('hidden')) {
      window.__closeDeleteModal();
    }
  });
})();

// ═══════════════════════════════════════════════════════════════════
// Idle Timeout Tracker
// Otomatis logout jika tidak ada aktivitas selama 30 menit
// ═══════════════════════════════════════════════════════════════════
(function initIdleTimeout() {
  const IDLE_TIMEOUT = 30 * 60 * 1000; // 30 menit dalam milidetik
  let idleTimer;

  function resetTimer() {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(logout, IDLE_TIMEOUT);
  }

  function logout() {
    // Redirect ke logout script
    window.location.href = '/BHP-Poli-Gigi/pages/auth/logout.php?reason=timeout';
  }

  // Event listener untuk berbagai jenis aktivitas user
  const activityEvents = [
    'mousedown', 'mousemove', 'keypress', 
    'scroll', 'touchstart', 'click'
  ];

  activityEvents.forEach(event => {
    document.addEventListener(event, resetTimer, true);
  });

  // Reset juga saat terjadi navigasi SPA
  const originalFetchAndRenderPage = window.fetchAndRenderPage;
  if (typeof originalFetchAndRenderPage === 'function') {
    window.fetchAndRenderPage = async function(url) {
      resetTimer();
      return await originalFetchAndRenderPage(url);
    };
  }

  // Inisialisasi timer pertama kali
  resetTimer();
})();
