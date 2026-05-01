<?php
// â”€â”€ Inisialisasi (autoload sudah di-load dari index.php) â”€â”€
use App\Classes\BhpManager;
use App\Classes\PemakaianManager;
use App\Classes\Auth;

$auth     = new Auth();
$user     = $auth->getCurrentUser();
$uid      = (int)($user['id'] ?? 0);
$namaDokter = $user['nama'] ?? 'Dokter';

$bhpMgr   = new BhpManager();
$pemMgr   = new PemakaianManager();
$bhpList  = $bhpMgr->getAllBhp();
$riwayat  = $pemMgr->getAllPemakaian(['limit' => 20]);
$today    = date('Y-m-d');
?>
<div class="w-full p-3 sm:p-6 lg:p-8">

  <!-- Toast -->
  <div id="toastCatat" class="fixed top-6 right-6 z-[200] hidden">
    <div id="toastCatatInner" class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs">
      <i id="toastCatatIcon" class="text-[15px]"></i>
      <span id="toastCatatMsg"></span>
    </div>
  </div>

  <div class="max-w-[1400px] mx-auto flex flex-col xl:flex-row gap-6 lg:gap-8 w-full font-plex">

    <!-- LEFT: Form -->
    <div class="flex-1 min-w-0 flex flex-col">
      <h2 class="text-2xl font-display font-semibold text-slate-800 tracking-tight mb-5">Catat Pemakaian</h2>

      <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 p-4 sm:p-8">
        <h3 class="text-lg font-bold text-slate-800 mb-1">Catat Pemakaian BHP</h3>
        <p class="text-[13px] text-slate-500 mb-8 font-medium">Catat penggunaan Bahan Habis Pakai (BHP) untuk setiap tindakan medis.</p>

        <form id="formCatat" onsubmit="submitPemakaian(event)">
          <!-- Row 1 -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Tanggal Pemakaian <span class="text-red-500">*</span></label>
              <input type="date" name="tanggal" id="inputTglCatat" required value="<?= $today ?>"
                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl h-12 px-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
            </div>
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Nama Dokter <span class="text-red-500">*</span></label>
              <div class="relative">
                <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" value="<?= htmlspecialchars($namaDokter) ?>" readonly
                  class="w-full border border-slate-200 bg-slate-100 rounded-xl h-12 pl-10 pr-4 text-[14px] text-slate-700 font-medium outline-none cursor-not-allowed">
              </div>
            </div>
          </div>

          <!-- Nama Pasien (WAJIB) -->
          <div class="mb-8">
            <label class="block text-[12px] font-bold text-slate-600 mb-2">
              Nama Pasien <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_pasien" required placeholder="Masukkan nama lengkap pasien..."
              class="w-full border border-slate-200 bg-slate-50/50 rounded-xl h-12 px-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors placeholder:font-normal placeholder:text-slate-400">
          </div>

          <!-- Tambah BHP -->
          <div class="border-t border-slate-100 pt-8 mb-8">
            <h4 class="text-[15px] font-bold text-slate-800 flex items-center gap-2.5 mb-6">
              <i class="fas fa-pen text-blue-500 text-[14px]"></i> Tambah BHP Digunakan
            </h4>

            <!-- Pilih BHP row -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 sm:p-5 mb-6">
              <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 min-w-0">
                  <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Pilih BHP</label>
                  <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[13px]"></i>
                    <select id="selectBhpCatat"
                      class="w-full border border-slate-200 bg-white rounded-xl h-11 pl-10 pr-10 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 appearance-none transition-colors">
                      <option value="" disabled selected>Pilih BHP...</option>
                      <?php foreach ($bhpList as $b): ?>
                      <option value="<?= $b['id_bhp'] ?>"
                        data-nama="<?= htmlspecialchars($b['Nama_bhp']) ?>"
                        data-satuan="<?= htmlspecialchars($b['Nama_satuan'] ?? '') ?>"
                        data-stok="<?= $b['Jumlah'] ?>">
                        <?= htmlspecialchars($b['Nama_bhp']) ?> â€” Stok: <?= $b['Jumlah'] ?> <?= htmlspecialchars($b['Nama_satuan'] ?? '') ?>
                      </option>
                      <?php endforeach; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                  </div>
                </div>
                <div class="w-full sm:w-[130px] flex-shrink-0">
                  <label class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wide">Jumlah</label>
                  <input type="number" id="inputJmlCatat" value="1" min="1"
                    class="w-full border border-slate-200 bg-white rounded-xl h-11 px-4 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors">
                </div>
                <div class="w-full sm:w-auto flex items-end">
                  <button type="button" onclick="tambahItemBhp()"
                    class="w-full sm:w-auto h-11 px-5 rounded-xl text-[13px] font-bold text-blue-600 bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-plus text-[11px]"></i> Tambah
                  </button>
                </div>
              </div>
            </div>

            <!-- Tabel item BHP yang ditambahkan -->
            <div class="border border-slate-100 rounded-2xl overflow-hidden mb-6">
              <div class="overflow-x-auto">
                <table class="w-full text-left" style="min-width:560px">
                  <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                      <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nama BHP</th>
                      <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center w-20">Stok</th>
                      <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center w-28">Jumlah Pakai</th>
                      <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center w-28">Kondisi</th>
                      <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center w-16">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyItemBhp" class="divide-y divide-slate-100 text-[13px]">
                    <tr id="emptyItemRow">
                      <td colspan="5" class="py-8 text-center text-slate-400 text-[13px] font-medium">
                        <i class="fas fa-box-open mb-2 text-xl block opacity-40"></i>
                        Belum ada BHP yang ditambahkan
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Catatan -->
            <div>
              <label class="block text-[12px] font-bold text-slate-600 mb-2">Catatan Tambahan</label>
              <textarea name="catatan" placeholder="Tambahkan keterangan jika diperlukan..."
                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl max-h-32 min-h-[5rem] px-4 py-3 text-[14px] text-slate-700 font-medium outline-none focus:border-brand-500 transition-colors placeholder:font-normal placeholder:text-slate-400"></textarea>
            </div>
          </div>

          <!-- Submit -->
          <div class="flex justify-end">
            <button type="submit" id="btnSimpanCatat"
              class="h-12 px-8 rounded-xl text-[14px] font-bold text-white transition-opacity hover:opacity-90 active:scale-[0.98] shadow-md flex items-center gap-2"
              style="background: linear-gradient(135deg, #00B47A 0%, #008D5B 100%);">
              <i class="fas fa-save text-[12px]"></i> Simpan Catatan Pemakaian
            </button>
          </div>
        </form>
      </div>

      <!-- Riwayat Pemakaian -->
      <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 p-4 sm:p-8 mt-6">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center">
              <i class="fas fa-history text-purple-500"></i>
            </div>
            <div>
              <h3 class="font-bold text-[15px] text-slate-800">Riwayat Pemakaian</h3>
              <p class="text-[12px] text-slate-400 font-medium mt-0.5">20 catatan terbaru</p>
            </div>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left" style="min-width:520px">
            <thead>
              <tr class="text-[10px] uppercase tracking-widest text-slate-400 border-b border-slate-100 font-bold">
                <th class="py-3 px-3">TANGGAL</th>
                <th class="py-3 px-3">DOKTER</th>
                <th class="py-3 px-3">PASIEN</th>
                <th class="py-3 px-3 text-center">ITEM</th>
                <th class="py-3 px-3"></th>
              </tr>
            </thead>
            <tbody id="tbodyRiwayat" class="text-[13px] font-plex divide-y divide-slate-50/50">
              <?php if (empty($riwayat)): ?>
              <tr><td colspan="6" class="py-10 text-center text-slate-400 text-[13px]">Belum ada catatan pemakaian</td></tr>
              <?php else: ?>
              <?php foreach ($riwayat as $r): ?>
              <tr class="hover:bg-slate-50/50 transition-colors riwayat-row" data-id="<?= $r['id_pemakaian'] ?>">
                <td class="py-4 px-3">
                  <div class="font-medium text-slate-600"><?= date('d M Y', strtotime($r['tanggal'])) ?></div>
                  <div class="text-[11px] text-slate-400"><?= date('H:i', strtotime($r['created_at'])) ?></div>
                </td>
                <td class="py-4 px-3 font-medium text-slate-700"><?= htmlspecialchars($r['nama_dokter'] ?? '-') ?></td>
                <td class="py-4 px-3 text-slate-500 text-[12px]"><?= htmlspecialchars($r['nama_pasien'] ?? '-') ?></td>
                <td class="py-4 px-3 text-center">
                  <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 font-bold text-[11px]"><?= $r['jumlah_item'] ?> item</span>
                </td>
                <td class="py-4 px-3 text-right">
                  <button onclick="deletePemakaian(<?= $r['id_pemakaian'] ?>, this)"
                    class="text-red-400 hover:text-red-600 transition-colors" title="Hapus">
                    <i class="far fa-trash-alt text-[13px]"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- RIGHT: Info Panel -->
    <div class="w-full xl:w-[300px] flex flex-col gap-6 shrink-0 xl:mt-12">
      <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-50 flex gap-3 items-center">
          <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
            <i class="fas fa-box-open text-[15px]"></i>
          </div>
          <div>
            <h4 class="font-bold text-[14px] text-slate-800">Informasi BHP</h4>
            <p class="text-[11px] text-slate-400 font-medium">Detail item terpilih</p>
          </div>
        </div>
        <div class="p-5 flex flex-col gap-4" id="panelInfoBhp">
          <p class="text-[12px] text-slate-400 text-center py-4">Pilih BHP untuk melihat info</p>
        </div>
      </div>

      <!-- Stats card -->
      <div class="rounded-[24px] p-6 shadow-xl relative overflow-hidden" style="background: linear-gradient(145deg, #1A2436 0%, #101622 100%);">
        <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none w-32 h-32 bg-white rounded-tl-full"></div>
        <div class="relative z-10 flex flex-col items-start px-2">
          <p class="text-[12px] font-bold text-slate-300 mb-0.5 opacity-90">Total BHP Tersedia</p>
          <div class="flex items-end gap-2 mt-2">
            <span class="text-[42px] font-display font-bold text-white leading-none tracking-tight"><?= count($bhpList) ?></span>
            <span class="text-[13px] font-medium text-slate-400 mb-1.5">Item BHP</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Item BHP array (state di client)
let itemsBhp = [];

function showToastCatat(msg, ok=true) {
  const t=document.getElementById('toastCatat');
  const i=document.getElementById('toastCatatInner');
  const ic=document.getElementById('toastCatatIcon');
  document.getElementById('toastCatatMsg').textContent=msg;
  if(ok){i.className='flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-emerald-500 text-white';ic.className='fas fa-check-circle text-[15px]';}
  else{i.className='flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-plex text-[13px] font-bold max-w-xs bg-red-500 text-white';ic.className='fas fa-exclamation-circle text-[15px]';}
  t.classList.remove('hidden');
  setTimeout(()=>t.classList.add('hidden'),3500);
}

function escapeH(s){const d=document.createElement('div');d.appendChild(document.createTextNode(s));return d.innerHTML;}

// Update panel info BHP saat pilih
document.getElementById('selectBhpCatat').addEventListener('change', function(){
  const opt=this.options[this.selectedIndex];
  const nama=opt.dataset.nama||'';
  const satuan=opt.dataset.satuan||'';
  const stok=opt.dataset.stok||0;
  const panel=document.getElementById('panelInfoBhp');
  panel.innerHTML=`
    <div class="flex items-center justify-between"><span class="text-[12px] text-slate-500">Nama BHP</span><span class="text-[13px] font-bold text-slate-800">${escapeH(nama)}</span></div>
    <div class="flex items-center justify-between"><span class="text-[12px] text-slate-500">Satuan</span><span class="text-[12px] font-semibold text-slate-600">${escapeH(satuan)}</span></div>
    <div class="flex items-center justify-between"><span class="text-[12px] text-slate-500">Stok Saat Ini</span><span class="text-[13px] font-bold text-slate-800">${stok} <span class="text-[11px] font-medium text-slate-400">${escapeH(satuan)}</span></span></div>
    <div class="flex items-center justify-between"><span class="text-[12px] text-slate-500">Status</span>
      ${parseInt(stok)>0
        ?'<span class="px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-600 text-[11px] font-bold">Tersedia</span>'
        :'<span class="px-2.5 py-1 rounded-md bg-red-50 border border-red-100 text-red-600 text-[11px] font-bold">Habis</span>'}
    </div>`;
});

function renderTableBhp(){
  const tbody=document.getElementById('tbodyItemBhp');
  if(itemsBhp.length===0){
    tbody.innerHTML='<tr id="emptyItemRow"><td colspan="5" class="py-8 text-center text-slate-400 text-[13px] font-medium"><i class="fas fa-box-open mb-2 text-xl block opacity-40"></i>Belum ada BHP yang ditambahkan</td></tr>';
    return;
  }
  tbody.innerHTML=itemsBhp.map((item,idx)=>`
    <tr class="hover:bg-slate-50/50 transition-colors">
      <td class="px-4 py-3 font-semibold text-slate-700 text-[12px]">${escapeH(item.nama)}</td>
      <td class="px-4 py-3 text-center text-slate-500 text-[12px]">${item.stok} ${escapeH(item.satuan)}</td>
      <td class="px-4 py-3 text-center">
        <div class="flex items-center justify-center gap-2">
          <button type="button" onclick="ubahJml(${idx},-1)" class="w-6 h-6 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition"><i class="fas fa-minus text-[9px]"></i></button>
          <span class="w-8 text-center font-bold text-slate-700 text-[13px]">${item.jumlah}</span>
          <button type="button" onclick="ubahJml(${idx},1)" class="w-6 h-6 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition"><i class="fas fa-plus text-[9px]"></i></button>
        </div>
      </td>
      <td class="px-4 py-3 text-center">
        <select onchange="itemsBhp[${idx}].kondisi=this.value" class="border border-slate-200 bg-slate-50 rounded-lg h-7 pl-2 pr-6 text-[11px] text-slate-600 font-medium outline-none focus:border-brand-500 appearance-none">
          <option value="habis" ${item.kondisi==='habis'?'selected':''}>Habis</option>
          <option value="sisa" ${item.kondisi==='sisa'?'selected':''}>Sisa</option>
        </select>
      </td>
      <td class="px-4 py-3 text-center">
        <button type="button" onclick="hapusItem(${idx})" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center justify-center mx-auto"><i class="far fa-trash-alt text-[12px]"></i></button>
      </td>
    </tr>`).join('');
}

function tambahItemBhp(){
  const sel=document.getElementById('selectBhpCatat');
  const jml=parseInt(document.getElementById('inputJmlCatat').value)||1;
  const id=sel.value;
  if(!id){showToastCatat('Pilih BHP terlebih dahulu.',false);return;}
  const opt=sel.options[sel.selectedIndex];
  // Cek duplikat
  if(itemsBhp.find(x=>x.id_bhp===id)){showToastCatat('BHP ini sudah ditambahkan.',false);return;}
  itemsBhp.push({id_bhp:id,nama:opt.dataset.nama||opt.text,satuan:opt.dataset.satuan||'',stok:opt.dataset.stok||0,jumlah:jml,kondisi:'habis'});
  renderTableBhp();
  sel.value='';
  document.getElementById('inputJmlCatat').value=1;
}

function ubahJml(idx,delta){
  itemsBhp[idx].jumlah=Math.max(1,itemsBhp[idx].jumlah+delta);
  renderTableBhp();
}

function hapusItem(idx){
  itemsBhp.splice(idx,1);
  renderTableBhp();
}

function submitPemakaian(e){
  e.preventDefault();
  if(itemsBhp.length===0){showToastCatat('Tambahkan minimal 1 BHP.',false);return;}
  const form=document.getElementById('formCatat');
  const fd=new FormData(form);
  fd.append('action','add_pemakaian');
  fd.append('items',JSON.stringify(itemsBhp.map(x=>({id_bhp:x.id_bhp,jumlah:x.jumlah,kondisi:x.kondisi}))));
  const btn=document.getElementById('btnSimpanCatat');
  btn.disabled=true;
  btn.innerHTML='<i class="fas fa-spinner fa-spin text-[12px]"></i> Menyimpan...';
  fetch('/BHP-Poli-Gigi/process/pemakaian_process.php',{method:'POST',body:fd,credentials:'same-origin'})
  .then(r=>r.json()).then(res=>{
    if(res.success){
      showToastCatat(res.message,true);
      itemsBhp=[];renderTableBhp();
      form.reset();
      document.getElementById('inputTglCatat').value='<?= $today ?>';
      setTimeout(()=>window.location.reload(),1200);
    }else{showToastCatat(res.message||'Gagal menyimpan.',false);}
  }).catch(()=>showToastCatat('Koneksi gagal.',false))
  .finally(()=>{btn.disabled=false;btn.innerHTML='<i class="fas fa-save text-[12px]"></i> Simpan Catatan Pemakaian';});
}

function deletePemakaian(id,btn){
  if(!confirm('Hapus catatan pemakaian ini?\nStok BHP akan dikembalikan.'))return;
  const orig=btn.innerHTML;
  btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin text-[11px]"></i>';
  const fd=new FormData();fd.append('action','delete_pemakaian');fd.append('id',id);
  fetch('/BHP-Poli-Gigi/process/pemakaian_process.php',{method:'POST',body:fd,credentials:'same-origin'})
  .then(r=>r.json()).then(res=>{
    if(res.success){
      const row=document.querySelector(`.riwayat-row[data-id="${id}"]`);
      if(row){row.style.opacity='0';row.style.transition='opacity .3s';setTimeout(()=>row.remove(),300);}
      showToastCatat(res.message,true);
    }else{btn.disabled=false;btn.innerHTML=orig;showToastCatat(res.message||'Gagal.',false);}
  }).catch(()=>{btn.disabled=false;btn.innerHTML=orig;showToastCatat('Koneksi gagal.',false);});
}
</script>
