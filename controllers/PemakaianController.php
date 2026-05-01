<?php
namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\PemakaianManager;
use App\Classes\ActivityLog;
use Throwable;

/**
 * Class PemakaianController
 * Menangani AJAX request untuk CRUD Catatan Pemakaian BHP
 */
class PemakaianController
{
    public function handleRequest(): void
    {
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
        ob_start();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $auth = new Auth();

            if (!$auth->isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Akses ditolak. Silakan login terlebih dahulu.']);
                exit();
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
                exit();
            }

            $action = $_POST['action'] ?? '';
            $mgr    = new PemakaianManager();
            $log    = new ActivityLog();
            $user   = $auth->getCurrentUser();
            $uid    = (int)($user['id']   ?? 0);
            $uname  = $user['nama']  ?? 'Unknown';
            $urole  = $user['role']  ?? 'dokter';

            switch ($action) {

                // ── TAMBAH PEMAKAIAN ───────────────────────────
                case 'add_pemakaian':
                    // Header data
                    $header = [
                        'tanggal'       => $_POST['tanggal']       ?? '',
                        'unit_tindakan' => $_POST['unit_tindakan'] ?? '',
                        'lokasi'        => $_POST['lokasi']        ?? '',
                        'nama_pasien'   => $_POST['nama_pasien']   ?? '',
                        'catatan'       => $_POST['catatan']       ?? '',
                    ];
                    // Items — dikirim sebagai JSON string dari JS
                    $itemsJson = $_POST['items'] ?? '[]';
                    $items     = json_decode($itemsJson, true);
                    if (!is_array($items)) $items = [];

                    $result = $mgr->addPemakaian($header, $items, $uid);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'catat_pemakaian', 'stok',
                            "Mencatat pemakaian BHP tgl " . ($header['tanggal']) . " - " . count($items) . " item.");
                    }
                    echo json_encode($result);
                    break;

                // ── HAPUS PEMAKAIAN ────────────────────────────
                case 'delete_pemakaian':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->deletePemakaian($id);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'hapus_pemakaian', 'stok',
                            "Menghapus catatan pemakaian ID {$id}.");
                    }
                    echo json_encode($result);
                    break;

                // ── AMBIL SEMUA PEMAKAIAN ──────────────────────
                case 'get_all_pemakaian':
                    $filter = $_POST;
                    $list   = $mgr->getAllPemakaian($filter);
                    $count  = $mgr->countPemakaian($filter);
                    echo json_encode(['success' => true, 'data' => $list, 'total' => $count]);
                    break;

                // ── AMBIL DETAIL PEMAKAIAN ─────────────────────
                case 'get_pemakaian_detail':
                    $id   = (int)($_POST['id'] ?? 0);
                    $data = $mgr->getPemakaianDetail($id);
                    echo json_encode(['success' => true, 'data' => $data]);
                    break;

                default:
                    echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali: ' . htmlspecialchars($action)]);
            }

        } catch (Throwable $e) {
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }

        exit();
    }
}
