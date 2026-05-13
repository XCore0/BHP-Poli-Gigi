// Toggle show/hide password
function togglePassword() {
  const input = document.getElementById('password');
  const isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  document.getElementById('eye-icon').style.opacity = isText ? '1' : '0.5';
}

// Function to show error message dynamically in the top container
function showLocalError(msg) {
  const container = document.getElementById('alert-container');
  let alertBox = document.getElementById('error-alert');
  
  if (!alertBox) {
    alertBox = document.createElement('div');
    alertBox.id = 'error-alert';
    alertBox.className = 'error-box flex items-start gap-3 p-4 mb-6';
    alertBox.innerHTML = `
      <svg class="flex-shrink-0 mt-0.5" width="18" height="18" viewBox="0 0 20 20" fill="none">
        <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="#EF4444" stroke-width="1.5"/>
        <path d="M10 6V10M10 14H10.01" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <p class="text-sm font-medium text-red-600" id="error-msg-text"></p>
      <button type="button" onclick="this.parentElement.remove()" class="ml-auto text-red-300 hover:text-red-500 transition-colors flex-shrink-0">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
      </button>
    `;
    container.appendChild(alertBox);
  }
  
  const msgText = document.getElementById('error-msg-text');
  if (msgText) msgText.textContent = msg;

  // Trigger shake animation
  alertBox.style.animation = 'none';
  alertBox.offsetHeight; /* trigger reflow */
  alertBox.style.animation = 'shakeIn 0.4s ease';
}

// Loading state on submit
const loginForm = document.getElementById('login-form');
if (loginForm) {
  loginForm.addEventListener('submit', function(e) {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const emailVal = email.value.trim();
    const passVal = password.value;
    
    // Reset styling
    email.classList.remove('error-field');
    password.classList.remove('error-field');

    // 1. Check empty
    if (!emailVal || !passVal) { 
      e.preventDefault(); 
      showLocalError('Email dan password tidak boleh kosong!');
      if(!emailVal) email.classList.add('error-field');
      if(!passVal) password.classList.add('error-field');
      return; 
    }

    // 2. Check email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailVal)) {
      e.preventDefault();
      showLocalError('Format email tidak valid! (contoh: nama@email.com)');
      email.classList.add('error-field');
      return;
    }

    const btn = document.getElementById('btn-submit');
    const text = document.getElementById('btn-text');
    const arrow = document.getElementById('btn-arrow');
    const spinner = document.getElementById('btn-spinner');

    btn.disabled = true;
    btn.style.opacity = '0.8';
    text.textContent = 'Memverifikasi...';
    arrow.classList.add('hidden');
    spinner.classList.remove('hidden');
  });
}
