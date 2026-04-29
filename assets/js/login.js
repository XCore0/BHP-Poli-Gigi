// Toggle show/hide password
function togglePassword() {
  const input = document.getElementById('password');
  const isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  document.getElementById('eye-icon').style.opacity = isText ? '1' : '0.5';
}

// Loading state on submit
const loginForm = document.getElementById('login-form');
if (loginForm) {
  loginForm.addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    if (!email || !password) { 
      e.preventDefault(); 
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
