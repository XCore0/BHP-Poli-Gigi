<?php // components/loader.php - Universal premium loader ?>
<!-- INITIAL LOADER -->
<div id="initial-loader" class="fixed inset-0 z-[9999] premium-loader flex flex-col items-center justify-center transition-all duration-700 ease-out">
  <!-- Blurred backdrop with ambient glow and noise -->
  <div class="absolute inset-0 bg-slate-50/70 backdrop-blur-xl"></div>
  <div class="absolute inset-0 bg-noise pointer-events-none"></div>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-emerald-400/10 rounded-full blur-[80px] pointer-events-none"></div>

  <div class="relative z-10 flex flex-col items-center">
    <!-- 3D Floating Tooth with Diamond Glassmorphism -->
    <div class="loader-tooth-wrapper w-32 h-32 flex items-center justify-center mb-10 relative">
      <div class="pulse-ring"></div>
      <div class="pulse-ring"></div>
      
      <!-- Elegant Glass Container (Tilted) -->
      <div class="absolute inset-0 rounded-[2rem] bg-white/40 border border-white/60 shadow-[0_8px_32px_rgba(0,107,71,0.08)] backdrop-blur-md overflow-hidden rotate-45 flex items-center justify-center transition-transform"></div>
      
      <!-- The Tooth with Scanner -->
      <div class="relative z-20 tooth-glow flex flex-col items-center justify-center h-full w-full">
         <i class="fa-solid fa-tooth text-[3.5rem] text-[#006B47] drop-shadow-sm"></i>
         <div class="scanner-line"></div>
      </div>
    </div>

    <!-- Progress Bar -->
    <div class="flex flex-col items-center text-center">
       <div class="w-48 h-[4px] mt-4 bg-slate-200/80 rounded-full overflow-hidden relative shadow-inner">
          <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-[#34D399] to-[#006B47] rounded-full w-0" style="animation: fill-progress 0.8s ease-out forwards;"></div>
       </div>
    </div>
  </div>
</div>
<!-- END INITIAL LOADER -->

<style>
  /* Premium Loader Custom Styling */
  .premium-loader { perspective: 1000px; }
  .loader-tooth-wrapper {
    position: relative;
    animation: float-tooth 4s ease-in-out infinite;
    transform-style: preserve-3d;
  }
  .scanner-line {
    position: absolute;
    top: 0; left: -10%; right: -10%; height: 2px;
    background: linear-gradient(90deg, transparent, #10B981, transparent);
    box-shadow: 0 0 10px #34D399, 0 4px 20px #10B981;
    animation: scan-tooth 2.5s ease-in-out infinite alternate;
    z-index: 10;
  }
  .tooth-glow { filter: drop-shadow(0 0 12px rgba(16, 185, 129, 0.4)); }
  @keyframes float-tooth {
    0%, 100% { transform: translateY(0) rotateX(5deg); }
    50% { transform: translateY(-12px) rotateX(-5deg); }
  }
  @keyframes scan-tooth {
    0% { top: 10%; opacity: 0; }
    15% { opacity: 1; }
    85% { opacity: 1; }
    100% { top: 90%; opacity: 0; }
  }
  .pulse-ring {
    position: absolute; inset: -20px;
    border: 1.5px solid rgba(16, 185, 129, 0.3);
    border-radius: 50%;
    animation: ripple-ring 2.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
  }
  .pulse-ring:nth-child(2) { animation-delay: 1.25s; }
  @keyframes ripple-ring {
    0% { transform: scale(0.6); opacity: 1; }
    100% { transform: scale(1.6); opacity: 0; }
  }
  .bg-noise {
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.03'/%3E%3C/svg%3E");
  }
  @keyframes fill-progress {
    0% { width: 0%; opacity: 0.8; }
    100% { width: 100%; opacity: 1; }
  }
</style>

<script>
  // Loader hide logic
  let loaderHidden = false;
  const hideLoader = () => {
    if (loaderHidden) return;
    loaderHidden = true;
    const loader = document.getElementById('initial-loader');
    if (loader) {
      loader.style.opacity = '0';
      setTimeout(() => { loader.style.display = 'none'; }, 300);
    }
  };
  window.addEventListener('load', hideLoader);
  setTimeout(hideLoader, 800);
</script>
