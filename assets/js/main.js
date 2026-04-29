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
  const dropdown = document.getElementById("user-dropdown");
  const chevron = document.getElementById("user-chevron");
  if (dropdown) {
    dropdown.classList.toggle("hidden");
    if (chevron) { 
      chevron.style.transform = dropdown.classList.contains("hidden") ? "rotate(0deg)" : "rotate(180deg)"; 
    }
  }
}

document.addEventListener("click", function (e) {
  const dropdown = document.getElementById("user-dropdown");
  const btn = e.target.closest("button[onclick='toggleDropdown(event)']");
  if (!btn && dropdown && !dropdown.classList.contains("hidden")) {
    dropdown.classList.add("hidden");
    const chevron = document.getElementById("user-chevron");
    if (chevron) chevron.style.transform = "rotate(0deg)";
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
}
