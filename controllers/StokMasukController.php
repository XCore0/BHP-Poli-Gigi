<?php
namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\StokMasukManager;
use App\Classes\BhpManager;
use App\Classes\ActivityLog;
use Throwable;

/**
 * Class StokMasukController
 * Menangani AJAX request untuk CRUD Stok Masuk
 */
class StokMasukController
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

            // Izinkan dokter & admin
            if (!$auth->isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Akses ditolak. Silakan login terlebih dahulu.']);
                exit();
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
                exit();
            }

            $action  = $_POST['action'] ?? '';
            $mgr     = new StokMasukManager();
            $bhpMgr  = new BhpManager();
            $log     = new ActivityLog();
            $user    = $auth->getCurrentUser();
            $uid     = (int)($user['id']   ?? 0);
            $uname   = $user['nama']  ?? 'Unknown';
            $urole   = $user['role']  ?? 'dokter';

            switch ($action) {

                // ── TAMBAH STOK MASUK ──────────────────────────
                case 'add_stok_masuk':
                    $result = $mgr->addStokMasuk($_POST, $uid);
                    if ($result['success']) {
                        // Ambil nama BHP untuk log
                        $bhpList = $bhpMgr->getAllBhp(['id_bhp_exact' => (int)($_POST['id_bhp'] ?? 0)]);
                        $namaBhp = $_POST['id_bhp'] ?? '?';
                        $log->catat($uid, $uname, $urole, 'tambah_stok_masuk', 'stok',
                            "Input stok masuk BHP ID {$namaBhp} sejumlah " . ($_POST['jumlah'] ?? 0) . " unit.");
                    }
                    echo json_encode($result);
                    break;

                // ── HAPUS STOK MASUK ───────────────────────────
                case 'delete_stok_masuk':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->deleteStokMasuk($id);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'hapus_stok_masuk', 'stok',
                            "Menghapus stok masuk ID {$id}.");
                    }
                    echo json_encode($result);
                    break;

                // ── AMBIL SEMUA STOK MASUK (untuk refresh tabel) ──
                case 'get_all_stok_masuk':
                    $list  = $mgr->getAllStokMasuk($_POST);
                    $count = $mgr->countStokMasuk($_POST);
                    echo json_encode(['success' => true, 'data' => $list, 'total' => $count]);
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
