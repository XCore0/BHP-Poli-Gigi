<?php
/**
 * REST API — BHP Poli Gigi
 * URL: /BHP-Poli-Gigi/api/index.php
 *
 * Cara pakai di Postman:
 *   1. POST ?resource=login  { email, password }  → dapat session cookie
 *   2. GET/POST ?resource=<nama>  (cookie otomatis dikirim)
 *
 * Semua response: { "success": bool, "message": string, "data": mixed }
 */

// ── Error handling ────────────────────────────────────────────
ini_set('display_errors', 1); // Diubah ke 1 agar error terlihat di JSON debug
error_reporting(E_ALL);

// ── Buffer output agar tidak ada whitespace sebelum JSON ──────
ob_start();

// ── Autoload ──────────────────────────────────────────────────
require_once __DIR__ . '/../vendor/autoload.php';

// ── Header JSON ───────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// ── Session ───────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Bersihkan buffer sebelum output ──────────────────────────
if (ob_get_length()) ob_clean();

// ── Ambil Method ──────────────────────────────────────────────
$method = strtoupper($_SERVER['REQUEST_METHOD']);

// ── Decode JSON body jika dikirim sebagai application/json ────
$body = [];
$contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
if (str_contains($contentType, 'application/json')) {
    $raw  = file_get_contents('php://input');
    $body = (array)(json_decode($raw, true) ?? []);
}

// ── Ambil resource dari: URL param > POST field > JSON body ───
$resource = trim(
    $_GET['resource']  ??
    $_POST['resource'] ??
    $body['resource']  ??
    ''
);

// ── Fungsi helper response ────────────────────────────────────
function apiOk(array $data = [], string $message = 'Berhasil'): void
{
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data]);
    exit();
}

function apiError(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

// ── Auth ──────────────────────────────────────────────────────
use App\Classes\Auth;
$auth = new Auth();

// Endpoint yang tidak butuh login
$publicEndpoints = ['login'];

if (!in_array($resource, $publicEndpoints) && !$auth->isLoggedIn()) {
    apiError('Sesi tidak valid. Silakan login terlebih dahulu.', 401);
}

// ── Ambil data POST/body secara unified ──────────────────────
// Supaya controller bisa baca dari $_POST atau $body JSON
function getParam(string $key, $default = '')
{
    global $body;
    return $_POST[$key] ?? $_GET[$key] ?? $body[$key] ?? $default;
}

// ─────────────────────────────────────────────────────────────
// ROUTER
// ─────────────────────────────────────────────────────────────
try {
    switch ($resource) {

        // ══════════════════════════════════════════════════════
        // LOGIN
        // ══════════════════════════════════════════════════════
        case 'login':
            if ($method !== 'POST') {
                apiError('Gunakan method POST untuk login.', 405);
            }
            $email = trim(getParam('email'));
            $pass  = getParam('password');

            if (!$email || !$pass) {
                apiError('Email dan password wajib diisi.');
            }

            $result = $auth->login($email, $pass);
            echo json_encode($result);
            break;

        // ══════════════════════════════════════════════════════
        // GET: Daftar BHP
        // ══════════════════════════════════════════════════════
        case 'get_bhp':
            $auth->requireRole(['admin', 'dokter'], null, true);
            $mgr    = new \App\Classes\BhpManager();
            $filter = [
                'keyword'     => getParam('keyword'),
                'id_kategori' => getParam('id_kategori'),
                'limit'       => (int)(getParam('limit', 100)),
                'offset'      => (int)(getParam('offset', 0)),
            ];
            apiOk([
                'items' => $mgr->getAllBhp($filter),
                'total' => $mgr->countAllBhp($filter),
            ]);
            break;

        // ══════════════════════════════════════════════════════
        // GET: Statistik Dashboard
        // ══════════════════════════════════════════════════════
        case 'stats':
            $auth->requireRole(['admin', 'dokter', 'kepala_klinik'], null, true);
            $mgr  = new \App\Classes\BhpManager();
            $pmgr = new \App\Classes\PemakaianManager();
            $smgr = new \App\Classes\StokMasukManager();
            apiOk([
                'total_bhp'       => $mgr->countAllBhp(),
                'total_kategori'  => $mgr->countKategori(),
                'total_satuan'    => $mgr->countSatuan(),
                'total_pemakaian' => $pmgr->countPemakaian(),
                'total_stok'      => $smgr->countStokMasuk(),
            ]);
            break;

        // ══════════════════════════════════════════════════════
        // BHP CRUD — menggunakan BhpController yang sudah ada
        // POST ?resource=bhp&action=add_bhp|edit_bhp|delete_bhp
        //                         |add_kategori|edit_kategori|...
        // ══════════════════════════════════════════════════════
        case 'bhp':
            $auth->requireRole(['admin', 'dokter'], null, true);
            if ($method !== 'POST') apiError('Gunakan POST.', 405);

            $mgr    = new \App\Classes\BhpManager();
            $log    = new \App\Classes\ActivityLog();
            $user   = $auth->getCurrentUser();
            $uid    = (int)($user['id']   ?? 0);
            $uname  = $user['nama']  ?? '';
            $urole  = $user['role']  ?? '';
            $action = getParam('action');

            switch ($action) {
                case 'add_bhp':
                    $res = $mgr->addBhp($_POST, $uid);
                    if ($res['success']) $log->catat($uid,$uname,$urole,'tambah_bhp','bhp','BHP: '.(getParam('nama_bhp')));
                    echo json_encode($res);
                    break;
                case 'edit_bhp':
                    $res = $mgr->editBhp((int)getParam('id'), $_POST);
                    if ($res['success']) $log->catat($uid,$uname,$urole,'edit_bhp','bhp','Edit BHP ID: '.getParam('id'));
                    echo json_encode($res);
                    break;
                case 'delete_bhp':
                    $res = $mgr->deleteBhp((int)getParam('id'));
                    if ($res['success']) $log->catat($uid,$uname,$urole,'hapus_bhp','bhp','Hapus BHP ID: '.getParam('id'));
                    echo json_encode($res);
                    break;
                case 'add_kategori':
                    $res = $mgr->addKategori(getParam('nama_kategori'), getParam('kode_kategori'));
                    echo json_encode($res);
                    break;
                case 'edit_kategori':
                    $res = $mgr->editKategori((int)getParam('id'), getParam('nama_kategori'), getParam('kode_kategori'));
                    echo json_encode($res);
                    break;
                case 'delete_kategori':
                    $res = $mgr->deleteKategori((int)getParam('id'));
                    echo json_encode($res);
                    break;
                case 'add_satuan':
                    $res = $mgr->addSatuan(getParam('nama_satuan'));
                    echo json_encode($res);
                    break;
                case 'edit_satuan':
                    $res = $mgr->editSatuan((int)getParam('id'), getParam('nama_satuan'));
                    echo json_encode($res);
                    break;
                case 'delete_satuan':
                    $res = $mgr->deleteSatuan((int)getParam('id'));
                    echo json_encode($res);
                    break;
                case 'get_kategori':
                    echo json_encode(['success'=>true,'data'=>$mgr->getAllKategori()]);
                    break;
                case 'get_satuan':
                    echo json_encode(['success'=>true,'data'=>$mgr->getAllSatuan()]);
                    break;
                default:
                    apiError("Action tidak dikenali: $action");
            }
            break;

        // ══════════════════════════════════════════════════════
        // STOK MASUK CRUD
        // POST ?resource=stok&action=add_stok_masuk|delete_stok_masuk
        // GET  ?resource=stok&action=get_all_stok_masuk
        // ══════════════════════════════════════════════════════
        case 'stok':
            $auth->requireRole(['admin', 'dokter'], null, true);
            $mgr   = new \App\Classes\StokMasukManager();
            $log   = new \App\Classes\ActivityLog();
            $user  = $auth->getCurrentUser();
            $uid   = (int)($user['id']  ?? 0);
            $uname = $user['nama'] ?? '';
            $urole = $user['role'] ?? '';
            $action = getParam('action');

            switch ($action) {
                case 'add_stok_masuk':
                    if ($method !== 'POST') apiError('Gunakan POST.', 405);
                    $res = $mgr->addStokMasuk($_POST, $uid);
                    if ($res['success']) $log->catat($uid,$uname,$urole,'tambah_stok_masuk','stok','Stok masuk BHP ID: '.getParam('id_bhp'));
                    echo json_encode($res);
                    break;
                case 'delete_stok_masuk':
                    if ($method !== 'POST') apiError('Gunakan POST.', 405);
                    $id  = (int)getParam('id');
                    $res = $mgr->deleteStokMasuk($id);
                    if ($res['success']) $log->catat($uid,$uname,$urole,'hapus_stok_masuk','stok',"Hapus stok masuk ID: $id");
                    echo json_encode($res);
                    break;
                case 'get_all_stok_masuk':
                    $filter = [
                        'keyword' => getParam('keyword'),
                        'limit'   => (int)(getParam('limit', 20)),
                        'offset'  => (int)(getParam('offset', 0)),
                    ];
                    echo json_encode([
                        'success' => true,
                        'data'    => $mgr->getAllStokMasuk($filter),
                        'total'   => $mgr->countStokMasuk($filter),
                    ]);
                    break;
                default:
                    apiError("Action tidak dikenali: $action");
            }
            break;

        // ══════════════════════════════════════════════════════
        // PEMAKAIAN BHP CRUD
        // POST ?resource=pemakaian&action=add_pemakaian|delete_pemakaian
        // GET  ?resource=pemakaian&action=get_all_pemakaian
        // ══════════════════════════════════════════════════════
        case 'pemakaian':
            $auth->requireRole(['admin', 'dokter'], null, true);
            $mgr   = new \App\Classes\PemakaianManager();
            $log   = new \App\Classes\ActivityLog();
            $user  = $auth->getCurrentUser();
            $uid   = (int)($user['id']  ?? 0);
            $uname = $user['nama'] ?? '';
            $urole = $user['role'] ?? '';
            $action = getParam('action');

            switch ($action) {
                case 'add_pemakaian':
                    if ($method !== 'POST') apiError('Gunakan POST.', 405);
                    $header = [
                        'tanggal'       => getParam('tanggal'),
                        'unit_tindakan' => getParam('unit_tindakan'),
                        'lokasi'        => getParam('lokasi'),
                        'nama_pasien'   => getParam('nama_pasien'),
                        'catatan'       => getParam('catatan'),
                    ];
                    $itemsRaw = getParam('items', '[]');
                    $items    = is_array($itemsRaw) ? $itemsRaw : (json_decode($itemsRaw, true) ?? []);
                    $res = $mgr->addPemakaian($header, $items, $uid);
                    if ($res['success']) $log->catat($uid,$uname,$urole,'catat_pemakaian','stok','Pemakaian tgl: '.getParam('tanggal'));
                    echo json_encode($res);
                    break;
                case 'delete_pemakaian':
                    if ($method !== 'POST') apiError('Gunakan POST.', 405);
                    $id  = (int)getParam('id');
                    $res = $mgr->deletePemakaian($id);
                    if ($res['success']) $log->catat($uid,$uname,$urole,'hapus_pemakaian','stok',"Hapus pemakaian ID: $id");
                    echo json_encode($res);
                    break;
                case 'get_all_pemakaian':
                    $filter = [
                        'keyword'    => getParam('keyword'),
                        'tgl_mulai'  => getParam('tgl_mulai'),
                        'tgl_akhir'  => getParam('tgl_akhir'),
                        'limit'      => (int)(getParam('limit', 20)),
                        'offset'     => (int)(getParam('offset', 0)),
                    ];
                    echo json_encode([
                        'success' => true,
                        'data'    => $mgr->getAllPemakaian($filter),
                        'total'   => $mgr->countPemakaian($filter),
                    ]);
                    break;
                case 'get_pemakaian_detail':
                    $id = (int)getParam('id');
                    echo json_encode(['success'=>true,'data'=>$mgr->getPemakaianDetail($id)]);
                    break;
                default:
                    apiError("Action tidak dikenali: $action");
            }
            break;

        // ══════════════════════════════════════════════════════
        // USER CRUD — hanya admin
        // ══════════════════════════════════════════════════════
        case 'user':
            $auth->requireRole(['admin'], null, true);
            $mgr    = new \App\Classes\UserManager();
            $user   = $auth->getCurrentUser();
            $action = getParam('action');

            switch ($action) {
                case 'add':
                    if ($method !== 'POST') apiError('Gunakan POST.', 405);
                    $data = [
                        'nama'              => getParam('nama'),
                        'email'             => getParam('email'),
                        'password'          => getParam('password'),
                        'role'              => getParam('role'),
                        'no_telp'           => getParam('no_telp'),
                        'jenis_kelamin'     => getParam('jenis_kelamin'),
                        'tanggal_bergabung' => getParam('tanggal_bergabung'),
                    ];
                    $res = $mgr->addUser($data);
                    echo json_encode($res);
                    break;
                case 'toggle_status':
                    if ($method !== 'POST') apiError('Gunakan POST.', 405);
                    $res = $mgr->toggleStatus((int)getParam('id'));
                    echo json_encode($res);
                    break;
                case 'delete':
                    if ($method !== 'POST') apiError('Gunakan POST.', 405);
                    $id = (int)getParam('id');
                    if ($id === (int)($user['id'] ?? 0)) {
                        apiError('Tidak dapat menghapus akun sendiri.');
                    }
                    $res = $mgr->deleteUser($id);
                    echo json_encode($res);
                    break;
                case 'get_all':
                    $filter = [
                        'keyword' => getParam('keyword'),
                        'role'    => getParam('role'),
                        'limit'   => (int)(getParam('limit', 20)),
                        'offset'  => (int)(getParam('offset', 0)),
                    ];
                    echo json_encode([
                        'success' => true,
                        'data'    => $mgr->getAllUsers($filter),
                        'total'   => $mgr->countAll($filter),
                    ]);
                    break;
                default:
                    apiError("Action tidak dikenali: $action");
            }
            break;

        // ══════════════════════════════════════════════════════
        // PROFIL
        // ══════════════════════════════════════════════════════
        case 'profil':
            $ctrl = new \App\Controllers\ProfilController();
            $ctrl->handleRequest();
            break;

        // ══════════════════════════════════════════════════════
        // Resource tidak dikenali → 404
        // ══════════════════════════════════════════════════════
        default:
            apiError(
                $resource === ''
                    ? 'Parameter "resource" tidak ditemukan. Tambahkan ?resource=login ke URL.'
                    : "Resource tidak dikenali: $resource",
                404
            );
    }

} catch (\Throwable $e) {
    if (ob_get_length()) ob_clean();
    http_response_code(500);
    error_log('[API Error] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server.',
        'debug'   => (ini_get('display_errors') ? $e->getMessage() : null),
    ]);
}

exit();
