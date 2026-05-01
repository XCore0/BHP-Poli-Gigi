<?php
/**
 * Halaman Login - Poli Gigi Klinik Pratama
 * Halaman akses utama â€” session dimulai di sini sebelum apapun
 */

// â”€â”€ Session harus dimulai PERTAMA kali di halaman login â”€â”€
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';
use App\Classes\Auth;

// Jika sudah login, redirect ke dashboard sesuai role
$auth = new Auth();
if ($auth->isLoggedIn()) {
    $role = $auth->getRole();
    switch ($role) {
        case 'admin':        header('Location: /BHP-Poli-Gigi/admin/index.php'); exit();
        case 'dokter':       header('Location: /BHP-Poli-Gigi/dokter/index.php'); exit();
        case 'kepala_klinik': header('Location: /BHP-Poli-Gigi/kepala_klinik/index.php'); exit();
    }
}

// Ambil pesan error dari session (dikirim oleh login_process.php)
$errorMsg = '';
if (!empty($_SESSION['login_error'])) {
    $errorMsg = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

// Pertahankan email yang diketik sebelumnya
$prevEmail = htmlspecialchars($_POST['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poli Gigi - Login</title>
  <meta name="description" content="Login ke sistem manajemen inventaris BHP Poli Gigi Klinik Pratama">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/BHP-Poli-Gigi/assets/css/login.css">
</head>

<body class="m-0 p-0 bg-white">
  <div class="min-h-screen flex flex-col lg:flex-row">

    <!-- Left Panel - Green Brand Side -->
    <div class="relative gradient-green flex flex-col justify-between overflow-hidden lg:w-1/2 min-h-[320px] lg:min-h-screen">

      <!-- SVG Background -->
      <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 893 1080" fill="none"
        xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
        <g clip-path="url(#clip-bg)">
          <path d="M822.5 366.432C991.223 366.432 1128 244.317 1128 93.6816C1128 -56.954 991.223 -179.068 822.5 -179.068C653.777 -179.068 517 -56.954 517 93.6816C517 244.317 653.777 366.432 822.5 366.432Z" fill="white" fill-opacity="0.06" />
          <path d="M446.5 887.136C628.202 887.136 775.5 731.718 775.5 540C775.5 348.282 628.202 192.864 446.5 192.864C264.798 192.864 117.5 348.282 117.5 540C117.5 731.718 264.798 887.136 446.5 887.136Z" stroke="white" stroke-opacity="0.07" stroke-width="0.978143" />
          <path d="M446.5 787.954C576.287 787.954 681.5 676.941 681.5 540C681.5 403.058 576.287 292.045 446.5 292.045C316.713 292.045 211.5 403.058 211.5 540C211.5 676.941 316.713 787.954 446.5 787.954Z" stroke="white" stroke-opacity="0.05" stroke-width="0.978143" />
          <path d="M446.5 688.772C524.372 688.772 587.5 622.165 587.5 540C587.5 457.835 524.372 391.227 446.5 391.227C368.628 391.227 305.5 457.835 305.5 540C305.5 622.165 368.628 688.772 446.5 688.772Z" stroke="white" stroke-opacity="0.04" stroke-width="0.978143" />
          <path d="M0 862.895C148.833 825.702 297.667 825.702 446.5 862.895C595.333 900.088 744.167 900.088 893 862.895V1210.03H0V862.895Z" fill="white" fill-opacity="0.035" />
        </g>
        <defs>
          <clipPath id="clip-bg"><rect width="893" height="1080" fill="white" /></clipPath>
        </defs>
      </svg>

      <!-- Content -->
      <div class="relative z-10 flex flex-col items-center justify-center flex-1 px-8 py-16 lg:py-20 text-center">

        <!-- Logo Icon -->
        <div class="logo-card flex items-center justify-center w-28 h-28 rounded-[28px] mb-10">
          <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.95" d="M18.4134 4.33252C15.3806 4.33252 11.9145 6.06554 9.96488 8.88171C8.01523 11.6979 7.58197 15.1639 8.23186 18.4133C8.88174 21.6628 9.74825 23.829 10.3981 26.4286C11.048 29.0281 11.4813 32.0609 12.1312 35.7436C12.781 39.4262 13.8642 44.4087 15.5972 46.1417C16.4637 47.0082 17.5469 47.6581 18.8466 47.6581C20.1464 47.6581 21.0129 46.7916 21.6628 45.4918C22.3127 44.1921 22.7459 42.2424 23.1792 40.0761C23.6124 37.9098 24.0457 35.5269 24.9122 34.2272C25.3455 33.5773 25.7787 33.144 25.9953 33.144C26.212 33.144 26.6452 33.5773 27.0785 34.2272C27.945 35.5269 28.3783 37.9098 28.8115 40.0761C29.2448 42.2424 29.678 44.1921 30.3279 45.4918C30.9778 46.7916 31.8443 47.6581 33.1441 47.6581C34.4438 47.6581 35.527 47.0082 36.3935 46.1417C38.1265 44.4087 39.2097 39.4262 39.8595 35.7436C40.5094 32.0609 40.9427 29.0281 41.5926 26.4286C42.2424 23.829 43.109 21.6628 43.7588 18.4133C44.4087 15.1639 43.9755 11.6979 42.0258 8.88171C40.0762 6.06554 36.6101 4.33252 33.5773 4.33252C31.6277 4.33252 29.8946 5.19903 28.5949 6.06554C27.7284 6.71543 26.8619 7.14868 25.9953 7.14868C25.1288 7.14868 24.2623 6.71543 23.3958 6.06554C22.096 5.19903 20.363 4.33252 18.4134 4.33252Z" fill="white" />
            <path opacity="0.45" d="M19.4965 9.74805C18.1968 10.3979 16.6804 11.9143 15.8139 13.864C15.164 15.1637 14.9473 16.6801 15.164 17.9799" stroke="white" stroke-width="2.59954" stroke-linecap="round" />
          </svg>
        </div>

        <!-- Brand Name -->
        <div class="mb-4">
          <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-tight tracking-tight">Poli Gigi</h1>
          <h2 class="text-5xl md:text-6xl font-extrabold leading-tight tracking-tight" style="color: #A4F4CF;">Klinik Pratama</h2>
        </div>

        <!-- Subtitle -->
        <p class="text-base font-medium max-w-md leading-relaxed mb-10" style="color: rgba(208, 250, 229, 0.8);">
          Platform manajemen inventaris bahan habis pakai klinik yang modern, efisien, dan terintegrasi.
        </p>

        <!-- Feature Cards -->
        <div class="flex flex-col gap-4 w-full max-w-sm">
          <div class="feature-card flex items-center gap-4 px-5 py-3.5 rounded-2xl">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
              <g clip-path="url(#clip-inv)">
                <path d="M9.16503 18.105C9.41835 18.2512 9.70571 18.3282 9.99822 18.3282C10.2907 18.3282 10.5781 18.2512 10.8314 18.105L16.6637 14.7723C16.9168 14.6261 17.127 14.4161 17.2732 14.1631C17.4194 13.9101 17.4966 13.6231 17.4969 13.3308V6.66537C17.4966 6.37315 17.4194 6.08615 17.2732 5.83315C17.127 5.58016 16.9168 5.37007 16.6637 5.22396L10.8314 1.89122C10.5781 1.74497 10.2907 1.66797 9.99822 1.66797C9.70571 1.66797 9.41835 1.74497 9.16503 1.89122L3.33274 5.22396C3.07967 5.37007 2.86947 5.58016 2.72324 5.83315C2.577 6.08615 2.49986 6.37315 2.49956 6.66537V13.3308C2.49986 13.6231 2.577 13.9101 2.72324 14.1631C2.86947 14.4161 3.07967 14.6261 3.33274 14.7723L9.16503 18.105Z" stroke="#A4F4CF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M9.99821 18.3299V9.99805" stroke="#A4F4CF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.74118 5.83252L9.99822 9.99844L17.2553 5.83252" stroke="#A4F4CF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.24889 3.55762L13.7475 7.84852" stroke="#A4F4CF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
              </g>
              <defs><clipPath id="clip-inv"><rect width="19.9964" height="19.9964" fill="white" /></clipPath></defs>
            </svg>
            <span class="text-base font-medium text-left" style="color: rgba(255, 255, 255, 0.85);">Pantau stok BHP secara real-time</span>
          </div>
          <div class="feature-card flex items-center gap-4 px-5 py-3.5 rounded-2xl">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
              <g clip-path="url(#clip-usage)">
                <path d="M18.3301 9.99835H16.2638C15.8996 9.99757 15.5453 10.1161 15.2549 10.3358C14.9645 10.5554 14.754 10.8642 14.6557 11.2148L12.6977 18.1802C12.6851 18.2235 12.6588 18.2615 12.6227 18.2885C12.5867 18.3156 12.5428 18.3302 12.4978 18.3302C12.4527 18.3302 12.4088 18.3156 12.3728 18.2885C12.3367 18.2615 12.3104 18.2235 12.2978 18.1802L7.69862 1.81648C7.686 1.77321 7.65969 1.7352 7.62363 1.70816C7.58758 1.68112 7.54373 1.6665 7.49866 1.6665C7.45359 1.6665 7.40974 1.68112 7.37368 1.70816C7.33762 1.7352 7.31131 1.77321 7.29869 1.81648L5.34071 8.7819C5.24278 9.13113 5.03358 9.43888 4.74487 9.65842C4.45616 9.87796 4.1037 9.99731 3.741 9.99835H1.66637" stroke="#A4F4CF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
              </g>
              <defs><clipPath id="clip-usage"><rect width="19.9964" height="19.9964" fill="white" /></clipPath></defs>
            </svg>
            <span class="text-base font-medium text-left" style="color: rgba(255, 255, 255, 0.85);">Rekam pemakaian dokter per pasien</span>
          </div>
          <div class="feature-card flex items-center gap-4 px-5 py-3.5 rounded-2xl">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
              <path d="M12.4978 1.6665H7.49867C7.03851 1.6665 6.66548 2.03953 6.66548 2.49969V4.16606C6.66548 4.62621 7.03851 4.99924 7.49867 4.99924H12.4978C12.9579 4.99924 13.331 4.62621 13.331 4.16606V2.49969C13.331 2.03953 12.9579 1.6665 12.4978 1.6665Z" stroke="#A4F4CF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
              <path d="M13.3309 3.33252H14.9973C15.4393 3.33252 15.8631 3.50808 16.1756 3.82059C16.4881 4.13309 16.6637 4.55694 16.6637 4.99889V16.6635C16.6637 17.1054 16.4881 17.5293 16.1756 17.8418C15.8631 18.1543 15.4393 18.3298 14.9973 18.3298H4.9991C4.55715 18.3298 4.13331 18.1543 3.8208 17.8418C3.5083 17.5293 3.33273 17.1054 3.33273 16.6635V4.99889C3.33273 4.55694 3.5083 4.13309 3.8208 3.82059C4.13331 3.50808 4.55715 3.33252 4.9991 3.33252H6.66547" stroke="#A4F4CF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="text-base font-medium text-left" style="color: rgba(255, 255, 255, 0.85);">Laporan otomatis & notifikasi stok</span>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="relative z-10 pb-6 text-center">
        <p class="text-sm font-medium" style="color: rgba(255, 255, 255, 0.45);">Â© 2025 Poli Gigi Inventory System</p>
      </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="flex-1 flex items-center justify-center px-4 py-12 lg:py-16 gradient-light relative">

      <!-- Decorative Blur -->
      <div class="blur-right"></div>

      <!-- Form Container -->
      <div class="w-full max-w-[540px] relative z-10">

        <!-- Card -->
        <div class="card-blur rounded-3xl overflow-hidden">

          <!-- Top Accent Bar -->
          <div class="accent-bar h-2 w-full"></div>

          <!-- Form Content -->
          <div class="px-10 pt-16 pb-10">

            <!-- Heading -->
            <div class="mb-10">
              <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1.5">Selamat Datang</h2>
              <p class="text-base text-gray-600 font-normal">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <?php if (!empty($errorMsg)): ?>
            <!-- Error Alert -->
            <div class="error-box flex items-start gap-3 p-4 mb-6" id="error-alert">
              <svg class="flex-shrink-0 mt-0.5" width="18" height="18" viewBox="0 0 20 20" fill="none">
                <path d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="#EF4444" stroke-width="1.5"/>
                <path d="M10 6V10M10 14H10.01" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              <p class="text-sm font-medium text-red-600"><?php echo htmlspecialchars($errorMsg); ?></p>
              <button onclick="document.getElementById('error-alert').remove()" class="ml-auto text-red-300 hover:text-red-500 transition-colors flex-shrink-0">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
              </button>
            </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form method="POST" action="/BHP-Poli-Gigi/process/login_process.php" class="space-y-6" id="login-form" novalidate>

              <!-- Email Field -->
              <div class="space-y-2">
                <label for="email" class="block text-base font-semibold text-gray-800">Email</label>
                <div class="relative">
                  <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                      <path d="M16.6637 3.33252H3.33275C2.41244 3.33252 1.66638 4.07858 1.66638 4.99889V14.9971C1.66638 15.9174 2.41244 16.6635 3.33275 16.6635H16.6637C17.584 16.6635 18.3301 15.9174 18.3301 14.9971V4.99889C18.3301 4.07858 17.584 3.33252 16.6637 3.33252Z" stroke="#99A1AF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M18.3301 5.83252L10.8564 10.5817C10.5992 10.7428 10.3018 10.8283 9.99823 10.8283C9.69468 10.8283 9.39727 10.7428 9.14005 10.5817L1.66638 5.83252" stroke="#99A1AF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                  <input type="email" id="email" name="email"
                    placeholder="nama@poligigi.com"
                    value="<?php echo $prevEmail; ?>"
                    class="input-field w-full h-[52px] pl-12 pr-4 rounded-2xl text-base text-gray-800 placeholder-gray-400 <?php echo !empty($errorMsg) ? 'error-field' : ''; ?>"
                    autocomplete="email" required>
                </div>
              </div>

              <!-- Password Field -->
              <div class="space-y-2">
                <label for="password" class="block text-base font-semibold text-gray-800">Password</label>
                <div class="relative">
                  <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                      <path d="M15.8305 9.16504H4.16588C3.24557 9.16504 2.49951 9.9111 2.49951 10.8314V16.6637C2.49951 17.584 3.24557 18.3301 4.16588 18.3301H15.8305C16.7508 18.3301 17.4968 17.584 17.4968 16.6637V10.8314C17.4968 9.9111 16.7508 9.16504 15.8305 9.16504Z" stroke="#99A1AF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M5.83228 9.16516V5.83243C5.83228 4.72755 6.27118 3.66794 7.05245 2.88667C7.83371 2.10541 8.89333 1.6665 9.9982 1.6665C11.1031 1.6665 12.1627 2.10541 12.9439 2.88667C13.7252 3.66794 14.1641 4.72755 14.1641 5.83243V9.16516" stroke="#99A1AF" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                  <input type="password" id="password" name="password"
                    placeholder="Masukkan password"
                    class="input-field w-full h-[52px] pl-12 pr-12 rounded-2xl text-base text-gray-800 placeholder-gray-400 <?php echo !empty($errorMsg) ? 'error-field' : ''; ?>"
                    autocomplete="current-password" required>
                  <!-- Toggle show/hide password -->
                  <button type="button" id="toggle-password"
                    class="absolute right-4 top-1/2 -translate-y-1/2 eye-btn text-gray-400"
                    onclick="togglePassword()" aria-label="Tampilkan/sembunyikan password">
                    <svg id="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                      <path d="M1.66638 9.99835C1.66638 9.99835 4.16601 4.16504 9.9982 4.16504C15.8304 4.16504 18.3301 9.99835 18.3301 9.99835C18.3301 9.99835 15.8304 15.8317 9.9982 15.8317C4.16601 15.8317 1.66638 9.99835 1.66638 9.99835Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M9.9982 12.4984C11.3789 12.4984 12.4982 11.3791 12.4982 9.99835C12.4982 8.61764 11.3789 7.49835 9.9982 7.49835C8.61749 7.49835 7.4982 8.61764 7.4982 9.99835C7.4982 11.3791 8.61749 12.4984 9.9982 12.4984Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Submit Button -->
              <button type="submit" id="btn-submit"
                class="gradient-btn-green btn-login w-full h-[52px] flex items-center justify-center gap-2 rounded-2xl text-white text-base font-bold">
                <span id="btn-text">Masuk ke Sistem</span>
                <svg id="btn-arrow" width="20" height="20" viewBox="0 0 20 20" fill="none">
                  <path d="M4.16589 9.99805H15.8305" stroke="white" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M9.99817 4.16602L15.8305 9.99831L9.99817 15.8306" stroke="white" stroke-width="1.66637" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <!-- Loading spinner (hidden by default) -->
                <svg id="btn-spinner" class="hidden animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" stroke-opacity="0.3"/>
                  <path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="3" stroke-linecap="round"/>
                </svg>
              </button>
            </form>

          </div>
        </div>

        <!-- Footer Tagline -->
        <p class="text-center text-sm font-medium text-gray-500 mt-6">Klinik Poli Gigi Â· Sistem Inventaris BHP v2.0</p>
      </div>
    </div>
  </div>

  <script src="/BHP-Poli-Gigi/assets/js/login.js"></script>
</body>
</html>
