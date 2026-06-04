<?php
/**
 * Export Endpoint — BHP Poli Gigi
 * URL: /api/export.php
 *
 * Query params:
 *   type  = pdf | excel
 *   page  = bhp | stok | laporan | pengguna | kategori | satuan
 *   + filter params (tgl_mulai, tgl_akhir, keyword, dll.)
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);
ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Config\Database;
use App\Classes\Auth;

// ── Session & Auth ───────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Sesi tidak valid.']);
    exit();
}

$db      = Database::getInstance()->getConnection();
$type    = strtolower(trim($_GET['type'] ?? 'pdf'));
$page    = strtolower(trim($_GET['page'] ?? 'bhp'));

// ── Filter params ────────────────────────────────────────────
$tglMulai = $_GET['tgl_mulai'] ?? date('Y-m-01');
$tglAkhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$keyword  = trim($_GET['keyword'] ?? '');
$idKat    = (int)($_GET['id_kategori'] ?? 0);

// ═══════════════════════════════════════════════════════════════
// FUNGSI PENGAMBIL DATA
// ═══════════════════════════════════════════════════════════════

function fetchBhpData(PDO $db, string $keyword = '', int $idKat = 0): array
{
    $where  = [];
    $params = [];
    if ($keyword !== '') {
        $where[]  = '(b.Nama_bhp LIKE ? OR b.Kode_bhp LIKE ?)';
        $kw       = '%' . $keyword . '%';
        $params[] = $kw;
        $params[] = $kw;
    }
    if ($idKat > 0) {
        $where[]  = 'b.id_kategori = ?';
        $params[] = $idKat;
    }
    $sql = 'SELECT b.Kode_bhp, b.Nama_bhp, k.Nama_kategori, s.Nama_satuan,
                   b.Jumlah, b.Pemakaian,
                   CASE WHEN b.Jumlah <= 0 THEN "Habis"
                        WHEN b.Jumlah <= 10 THEN "Menipis"
                        ELSE "Aman" END AS Status
            FROM bhp b
            LEFT JOIN kategori_bhp k ON b.id_kategori = k.id_kategori
            LEFT JOIN satuan_bhp   s ON b.id_satuan   = s.id_satuan';
    if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY b.id_bhp DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchStokData(PDO $db, string $keyword = '', string $tglMulai = '', string $tglAkhir = ''): array
{
    $where  = ['sm.tanggal_terima BETWEEN ? AND ?'];
    $params = [$tglMulai, $tglAkhir];
    if ($keyword !== '') {
        $kw       = '%' . $keyword . '%';
        $where[]  = '(b.Nama_bhp LIKE ? OR sm.catatan LIKE ?)';
        $params[] = $kw;
        $params[] = $kw;
    }
    $sql = 'SELECT sm.tanggal_terima, b.Kode_bhp, b.Nama_bhp, sm.jumlah,
                   s.Nama_satuan, sm.tgl_kadaluarsa,
                   sm.catatan, u.Nama_lengkap AS nama_user, sm.created_at
            FROM stok_masuk sm
            LEFT JOIN bhp        b ON sm.id_bhp   = b.id_bhp
            LEFT JOIN satuan_bhp s ON b.id_satuan  = s.id_satuan
            LEFT JOIN user       u ON sm.id_user   = u.id_user
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY sm.tanggal_terima DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchLogData(PDO $db, string $keyword = '', string $kategori = '', string $role = ''): array
{
    $where  = [];
    $params = [];
    if ($keyword !== '') {
        $kw       = '%' . $keyword . '%';
        $where[]  = '(nama_user LIKE ? OR detail LIKE ? OR aksi LIKE ?)';
        $params[] = $kw;
        $params[] = $kw;
        $params[] = $kw;
    }
    if ($kategori !== '') {
        $where[]  = 'kategori = ?';
        $params[] = $kategori;
    }
    if ($role !== '') {
        $where[]  = 'role_user = ?';
        $params[] = $role;
    }
    $sql = 'SELECT waktu, nama_user, role_user, aksi, kategori, detail, ip_address
            FROM log_aktivitas';
    if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY waktu DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchLaporanData(PDO $db, string $keyword = '', string $tglMulai = '', string $tglAkhir = ''): array
{
    $where  = ['p.tanggal BETWEEN ? AND ?'];
    $params = [$tglMulai, $tglAkhir];
    if ($keyword !== '') {
        $kw       = '%' . $keyword . '%';
        $where[]  = '(p.nama_pasien LIKE ? OR b.Nama_bhp LIKE ?)';
        $params[] = $kw;
        $params[] = $kw;
    }
    $sql = 'SELECT p.tanggal, p.nama_pasien, p.unit_tindakan,
                   b.Nama_bhp, s.Nama_satuan, d.jumlah, d.kondisi,
                   u.Nama_lengkap AS nama_dokter
            FROM pemakaian_bhp p
            JOIN pemakaian_bhp_detail d ON d.id_pemakaian = p.id_pemakaian
            JOIN bhp                  b ON d.id_bhp        = b.id_bhp
            LEFT JOIN satuan_bhp      s ON b.id_satuan     = s.id_satuan
            LEFT JOIN user            u ON p.id_user       = u.id_user
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY p.tanggal DESC, p.id_pemakaian DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchPenggunaData(PDO $db): array
{
    $stmt = $db->query('SELECT Nama_lengkap, Email, No_telp, Role,
                               Jenis_kelamin, Tanggal_bergabung
                        FROM user ORDER BY Nama_lengkap ASC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ═══════════════════════════════════════════════════════════════
// KONFIGURASI PER PAGE
// ═══════════════════════════════════════════════════════════════

$configs = [
    'bhp' => [
        'title'   => 'Laporan Data Bahan Habis Pakai',
        'headers' => ['No', 'Kode BHP', 'Nama BHP', 'Kategori', 'Satuan', 'Stok (Unit)', 'Total Pemakaian', 'Status'],
        'keys'    => ['#', 'Kode_bhp', 'Nama_bhp', 'Nama_kategori', 'Nama_satuan', 'Jumlah', 'Pemakaian', 'Status'],
        'data_fn' => fn() => fetchBhpData($db, $keyword, $idKat),
        'filename'=> 'data-bhp',
    ],
    'stok' => [
        'title'   => 'Laporan Stok Masuk BHP',
        'subtitle'=> "Periode: $tglMulai s/d $tglAkhir",
        'headers' => ['No', 'Tgl Terima', 'Kode BHP', 'Nama BHP', 'Jumlah', 'Satuan', 'Tgl Kadaluarsa', 'Catatan', 'Dicatat Oleh'],
        'keys'    => ['#', 'tanggal_terima', 'Kode_bhp', 'Nama_bhp', 'jumlah', 'Nama_satuan', 'tgl_kadaluarsa', 'catatan', 'nama_user'],
        'data_fn' => fn() => fetchStokData($db, $keyword, $tglMulai, $tglAkhir),
        'filename'=> 'stok-masuk',
    ],
    'laporan' => [
        'title'   => 'Laporan Pemakaian BHP',
        'subtitle'=> "Periode: $tglMulai s/d $tglAkhir",
        'headers' => ['No', 'Tanggal', 'Nama BHP', 'Jumlah', 'Satuan', 'Kondisi', 'Pasien', 'Unit Tindakan', 'Dokter'],
        'keys'    => ['#', 'tanggal', 'Nama_bhp', 'jumlah', 'Nama_satuan', 'kondisi', 'nama_pasien', 'unit_tindakan', 'nama_dokter'],
        'data_fn' => fn() => fetchLaporanData($db, $keyword, $tglMulai, $tglAkhir),
        'filename'=> 'laporan-pemakaian',
    ],
    'pengguna' => [
        'title'   => 'Daftar Pengguna Sistem',
        'headers' => ['No', 'Nama Lengkap', 'Email', 'No. Telp', 'Role', 'Jenis Kelamin', 'Tanggal Bergabung'],
        'keys'    => ['#', 'Nama_lengkap', 'Email', 'No_telp', 'Role', 'Jenis_kelamin', 'Tanggal_bergabung'],
        'data_fn' => fn() => fetchPenggunaData($db),
        'filename'=> 'data-pengguna',
    ],
    'log' => [
        'title'   => 'Log Aktivitas Sistem',
        'subtitle'=> 'Rekam jejak seluruh aktivitas pengguna',
        'headers' => ['No', 'Waktu', 'Nama User', 'Role', 'Aksi', 'Kategori', 'Detail', 'IP Address'],
        'keys'    => ['#', 'waktu', 'nama_user', 'role_user', 'aksi', 'kategori', 'detail', 'ip_address'],
        'data_fn' => fn() => fetchLogData(
            $db,
            $_GET['log_keyword'] ?? '',
            $_GET['log_kategori'] ?? '',
            $_GET['log_role'] ?? ''
        ),
        'filename'=> 'log-aktivitas',
    ],
];

if (!isset($configs[$page])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Page tidak dikenali: ' . $page]);
    exit();
}

$cfg  = $configs[$page];
$data = ($cfg['data_fn'])();
$now  = date('d-m-Y H:i');

// ═══════════════════════════════════════════════════════════════
// EXPORT EXCEL
// ═══════════════════════════════════════════════════════════════

if ($type === 'excel') {
    ob_end_clean();

    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle(mb_substr($cfg['title'], 0, 31));

    // Warna tema
    $green     = '006B47';
    $greenLight= 'E6F4EF';
    $grayHead  = 'F1F5F9';

    // ── Judul ──────────────────────────────────────────────
    $colCount = count($cfg['headers']);
    $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

    $sheet->mergeCells("A1:{$lastCol}1");
    $sheet->setCellValue('A1', $cfg['title']);
    $sheet->getStyle('A1')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF' . $green]],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $greenLight]],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(28);

    $subtitleRow = 2;
    if (!empty($cfg['subtitle'])) {
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $cfg['subtitle']);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $subtitleRow = 3;
    }

    // Baris tanggal cetak
    $sheet->mergeCells("A{$subtitleRow}:{$lastCol}{$subtitleRow}");
    $sheet->setCellValue("A{$subtitleRow}", "Dicetak: $now");
    $sheet->getStyle("A{$subtitleRow}")->applyFromArray([
        'font'      => ['size' => 9, 'color' => ['argb' => 'FF94A3B8']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
    ]);

    $headerRow = $subtitleRow + 2;

    // ── Header kolom ───────────────────────────────────────
    foreach ($cfg['headers'] as $ci => $hdr) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1);
        $sheet->setCellValue("{$colLetter}{$headerRow}", $hdr);
    }
    $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")->applyFromArray([
        'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . $green]],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
    ]);
    $sheet->getRowDimension($headerRow)->setRowHeight(22);

    // ── Data rows ──────────────────────────────────────────
    foreach ($data as $ri => $row) {
        $dataRow = $headerRow + 1 + $ri;
        $isEven  = ($ri % 2 === 0);

        foreach ($cfg['keys'] as $ci => $key) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1);
            $val = $key === '#' ? ($ri + 1) : ($row[$key] ?? '-');

            // Format tanggal
            if (in_array($key, ['tanggal', 'tanggal_terima', 'tgl_kadaluarsa', 'Tanggal_bergabung'])
                && $val && $val !== '-') {
                try { $val = date('d M Y', strtotime($val)); } catch (\Throwable $e) {}
            }
            // Format waktu datetime (log)
            if ($key === 'waktu' && $val && $val !== '-') {
                try { $val = date('d/m/Y H:i', strtotime($val)); } catch (\Throwable $e) {}
            }
            // Format role
            if ($key === 'Role' || $key === 'role_user') {
                $roleMap = ['admin' => 'Administrator', 'dokter' => 'Dokter Gigi', 'kepala_klinik' => 'Kepala Klinik'];
                $val = $roleMap[$val] ?? ucfirst($val ?? '-');
            }
            // Format aksi
            if ($key === 'aksi' && $val && $val !== '-') {
                $val = ucwords(str_replace('_', ' ', $val));
            }
            // Format kategori
            if ($key === 'kategori' && $val && $val !== '-') {
                $val = ucfirst($val);
            }

            $sheet->setCellValue("{$colLetter}{$dataRow}", $val ?? '-');
        }

        $rowStyle = [
            'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $isEven ? 'FFFFFFFF' : 'FFF8FAFC']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ];
        $sheet->getStyle("A{$dataRow}:{$lastCol}{$dataRow}")->applyFromArray($rowStyle);
        $sheet->getRowDimension($dataRow)->setRowHeight(18);
    }

    // ── Auto-width ─────────────────────────────────────────
    for ($ci = 1; $ci <= $colCount; $ci++) {
        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci);
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ── Footer summary ─────────────────────────────────────
    $totalRow = $headerRow + count($data) + 2;
    $sheet->mergeCells("A{$totalRow}:{$lastCol}{$totalRow}");
    $sheet->setCellValue("A{$totalRow}", 'Total Data: ' . count($data) . ' baris');
    $sheet->getStyle("A{$totalRow}")->applyFromArray([
        'font'      => ['bold' => true, 'color' => ['argb' => 'FF' . $green]],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
    ]);

    // ── Download ───────────────────────────────────────────
    $filename = $cfg['filename'] . '_' . date('Ymd_His') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// ═══════════════════════════════════════════════════════════════
// EXPORT PDF (dompdf)
// ═══════════════════════════════════════════════════════════════

if ($type === 'pdf') {
    ob_end_clean();

    // ── Build HTML table rows ──────────────────────────────
    $rows = '';
    foreach ($data as $ri => $row) {
        $cells = '';
        foreach ($cfg['keys'] as $key) {
            $val = $key === '#' ? ($ri + 1) : ($row[$key] ?? '-');

            if (in_array($key, ['tanggal', 'tanggal_terima', 'tgl_kadaluarsa', 'Tanggal_bergabung'])
                && $val && $val !== '-') {
                try { $val = date('d M Y', strtotime((string)$val)); } catch (\Throwable $e) {}
            }
            // Format waktu datetime (log)
            if ($key === 'waktu' && $val && $val !== '-') {
                try { $val = date('d/m/Y H:i', strtotime((string)$val)); } catch (\Throwable $e) {}
            }
            if ($key === 'Role' || $key === 'role_user') {
                $roleMap = ['admin' => 'Administrator', 'dokter' => 'Dokter Gigi', 'kepala_klinik' => 'Kepala Klinik'];
                $val = $roleMap[$val] ?? ucfirst((string)($val ?? '-'));
            }
            if ($key === 'aksi' && $val && $val !== '-') {
                $val = ucwords(str_replace('_', ' ', (string)$val));
            }
            if ($key === 'kategori' && $val && $val !== '-') {
                $val = ucfirst((string)$val);
            }
            if ($key === 'kondisi') {
                $kondisiColor = $val === 'habis' ? '#EF4444' : '#F59E0B';
                $val = $val === 'habis' ? 'Habis' : 'Sisa';
                $cells .= '<td style="color:' . $kondisiColor . ';font-weight:600;text-align:center">' . htmlspecialchars((string)$val) . '</td>';
                continue;
            }
            if ($key === 'Status') {
                $color = ['Habis' => '#EF4444', 'Menipis' => '#F59E0B', 'Aman' => '#10B981'][$val] ?? '#64748B';
                $cells .= '<td style="color:' . $color . ';font-weight:600">' . htmlspecialchars((string)($val ?? '-')) . '</td>';
                continue;
            }
            if ($key === 'jumlah' || $key === '#') {
                $cells .= '<td style="text-align:center">' . htmlspecialchars((string)($val ?? '-')) . '</td>';
                continue;
            }
            $cells .= '<td>' . htmlspecialchars((string)($val ?? '-')) . '</td>';
        }
        $bg = $ri % 2 === 0 ? '#ffffff' : '#f8fafc';
        $rows .= "<tr style=\"background:$bg\">$cells</tr>\n";
    }

    // ── Build header cells ─────────────────────────────────
    $headerCells = implode('', array_map(fn($h) => "<th>$h</th>", $cfg['headers']));

    // ── HTML template ──────────────────────────────────────
    $subtitle = !empty($cfg['subtitle']) ? "<p class='subtitle'>{$cfg['subtitle']}</p>" : '';
    $totalData = count($data);

    // Khusus laporan pemakaian — gunakan template formal
    if ($page === 'laporan') {
        $periodeStr = date('d M Y', strtotime($tglMulai)) . ' s/d ' . date('d M Y', strtotime($tglAkhir));
        $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size:9px; color:#1e293b; }

  /* KOP SURAT */
  .kop { display:table; width:100%; border-bottom:2.5px solid #006B47; padding-bottom:10px; margin-bottom:12px; }
  .kop-logo { display:table-cell; width:64px; vertical-align:middle; }
  .kop-logo .logo-box {
    width:54px; height:54px; border-radius:8px;
    background:linear-gradient(135deg,#006B47,#1DB879);
    display:flex; align-items:center; justify-content:center;
    font-size:22px; font-weight:bold; color:white; text-align:center;
    line-height:54px;
  }
  .kop-text { display:table-cell; vertical-align:middle; padding-left:10px; }
  .kop-text h1 { font-size:14px; font-weight:bold; color:#006B47; }
  .kop-text p  { font-size:8px; color:#64748b; margin-top:2px; }

  /* JUDUL LAPORAN */
  .doc-title { text-align:center; margin:10px 0 6px; }
  .doc-title h2 { font-size:12px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; }
  .doc-title .underline { display:block; width:200px; height:2px; background:#006B47; margin:4px auto 0; }
  .doc-meta { text-align:center; font-size:8px; color:#64748b; margin-bottom:12px; }

  /* SUMMARY */
  .summary-row { display:table; width:100%; margin-bottom:10px; }
  .summary-cell { display:table-cell; width:33.3%; padding:6px 8px; font-size:8px; border:1px solid #e2e8f0; background:#f8fafc; }
  .summary-cell strong { display:block; font-size:11px; color:#006B47; }

  /* TABEL */
  table { width:100%; border-collapse:collapse; margin-top:4px; }
  thead th {
    background:#006B47; color:white;
    padding:5px 6px; text-align:left;
    font-size:8px; font-weight:bold;
    border:1px solid #005538;
  }
  tbody td {
    padding:4px 6px; border:1px solid #e2e8f0;
    font-size:8px; vertical-align:top;
  }
  tbody tr:hover td { background:#f0fdf4; }

  /* FOOTER */
  .ttd-section { display:table; width:100%; margin-top:28px; }
  .ttd-box { display:table-cell; width:33.3%; text-align:center; font-size:8px; vertical-align:top; padding:0 8px; }
  .ttd-box .label { font-weight:bold; margin-bottom:52px; }
  .ttd-box .line { border-top:1px solid #334155; padding-top:4px; }

  .doc-footer { margin-top:16px; font-size:7.5px; color:#94a3b8; border-top:1px solid #e2e8f0; padding-top:6px; text-align:center; }
</style>
</head>
<body>

  <!-- KOP SURAT -->
  <div class="kop">
    <div class="kop-logo">
      <div class="logo-box">&#x2665;</div>
    </div>
    <div class="kop-text">
      <h1>KLINIK POLI GIGI</h1>
      <p>Sistem Informasi Manajemen Bahan Habis Pakai</p>
      <p>Jl. Contoh No. 1 &nbsp;|&nbsp; Telp. (021) 000-0000</p>
    </div>
  </div>

  <!-- JUDUL -->
  <div class="doc-title">
    <h2>Laporan Pemakaian Bahan Habis Pakai (BHP)</h2>
    <span class="underline"></span>
  </div>
  <p class="doc-meta">Periode: <strong>{$periodeStr}</strong> &nbsp;&nbsp; Dicetak: <strong>{$now}</strong></p>

  <!-- SUMMARY -->
  <div class="summary-row">
    <div class="summary-cell">Total Catatan<strong>{$totalData} record</strong></div>
    <div class="summary-cell">Filter Keyword<strong><?= $keyword ?: '(semua)' ?></strong></div>
    <div class="summary-cell">Dicetak Oleh<strong><?= htmlspecialchars($auth->getCurrentUser()['nama'] ?? 'Sistem') ?></strong></div>
  </div>

  <!-- TABEL DATA -->
  <table>
    <thead><tr>{$headerCells}</tr></thead>
    <tbody>{$rows}</tbody>
  </table>

  <!-- TANDA TANGAN -->
  <div class="ttd-section">
    <div class="ttd-box">
      <div class="label">Mengetahui,<br>Kepala Klinik</div>
      <div class="line">( _________________________ )</div>
    </div>
    <div class="ttd-box">
      <div class="label">&nbsp;</div>
    </div>
    <div class="ttd-box">
      <div class="label">Dicetak Oleh,<br>Petugas / Dokter</div>
      <div class="line">( _________________________ )</div>
    </div>
  </div>

  <div class="doc-footer">
    Dokumen ini dicetak secara otomatis oleh Sistem BHP Klinik Poli Gigi &nbsp;&mdash;&nbsp; {$now}
  </div>

</body>
</html>
HTML;
    } else {
        $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9px; color: #1e293b; }

  .header-block {
    background: linear-gradient(135deg, #006B47 0%, #1DB879 100%);
    color: white; padding: 14px 18px; margin-bottom: 16px;
    border-radius: 4px;
  }
  .header-block h1 { font-size: 15px; font-weight: bold; margin-bottom: 2px; }
  .header-block .subtitle { font-size: 9px; opacity: 0.85; margin-top: 3px; }
  .header-block .meta { font-size: 8px; opacity: 0.7; margin-top: 6px; }

  table { width: 100%; border-collapse: collapse; margin-top: 8px; }
  thead th {
    background: #006B47; color: white;
    padding: 6px 7px; text-align: left;
    font-size: 8px; font-weight: bold;
    border: 1px solid #005538;
  }
  tbody td {
    padding: 5px 7px; border: 1px solid #e2e8f0;
    font-size: 8px; vertical-align: top;
  }
  .footer {
    margin-top: 16px; font-size: 8px; color: #94a3b8;
    border-top: 1px solid #e2e8f0; padding-top: 8px;
    display: flex; justify-content: space-between;
  }
  .total-badge {
    display: inline-block; background: #E6F4EF; color: #006B47;
    border-radius: 4px; padding: 3px 8px; font-size: 8px;
    font-weight: bold; margin-bottom: 8px;
  }
</style>
</head>
<body>
  <div class="header-block">
    <h1>{$cfg['title']}</h1>
    {$subtitle}
    <div class="meta">Dicetak pada: {$now} &nbsp;|&nbsp; Klinik Poli Gigi</div>
  </div>

  <span class="total-badge">Total Data: {$totalData} baris</span>

  <table>
    <thead><tr>{$headerCells}</tr></thead>
    <tbody>{$rows}</tbody>
  </table>

  <div class="footer">
    <span>BHP Poli Gigi — Sistem Informasi Manajemen Klinik</span>
    <span>Halaman: <span class="pagenum"></span></span>
  </div>
</body>
</html>
HTML;
    }

    // ── Render PDF ─────────────────────────────────────────
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $options->set('isRemoteEnabled', false);
    $options->set('isHtml5ParserEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $filename = $cfg['filename'] . '_' . date('Ymd_His') . '.pdf';
    $dompdf->stream($filename, ['Attachment' => true]);
    exit();
}

// ── Tipe tidak dikenali ────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['success' => false, 'message' => "Tipe export tidak dikenali: $type. Gunakan 'pdf' atau 'excel'."]);
exit();
