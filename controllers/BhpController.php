<?php
namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\BhpManager;
use App\Classes\ActivityLog;
use Throwable;

class BhpController
{
    public function handleRequest(): void
    {
        // Matikan output error agar tidak merusak JSON
        ini_set('display_errors', 0);
        error_reporting(E_ALL);

        // Buffer output untuk mencegah whitespace/warning merusak JSON
        ob_start();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Bersihkan buffer sebelum kirim JSON
        if (ob_get_length()) {
            ob_clean();
        }
        header('Content-Type: application/json; charset=utf-8');

        try {
            $auth = new Auth();
            if (!$auth->isLoggedIn() || $auth->getRole() !== 'admin') {
                echo json_encode(['success' => false, 'message' => 'Akses ditolak. Pastikan Anda sudah login sebagai admin.']);
                exit();
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
                exit();
            }

            $action = $_POST['action'] ?? '';
            $mgr    = new BhpManager();
            $log    = new ActivityLog();
            $user   = $auth->getCurrentUser();
            $uid    = (int)($user['id'] ?? 0);
            $uname  = $user['nama']  ?? 'Unknown';
            $urole  = $user['role']  ?? 'admin';

            switch ($action) {

                // ── SATUAN ────────────────────────────────────
                case 'add_satuan':
                    $result = $mgr->addSatuan($_POST['nama_satuan'] ?? '');
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'tambah_satuan', 'bhp',
                            'Menambahkan satuan: ' . ($_POST['nama_satuan'] ?? '') . '.');
                    }
                    echo json_encode($result);
                    break;

                case 'edit_satuan':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->editSatuan($id, $_POST['nama_satuan'] ?? '');
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'edit_satuan', 'bhp',
                            'Mengedit satuan ID ' . $id . ' menjadi: ' . ($_POST['nama_satuan'] ?? '') . '.');
                    }
                    echo json_encode($result);
                    break;

                case 'delete_satuan':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->deleteSatuan($id);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'hapus_satuan', 'bhp',
                            'Menghapus satuan ID ' . $id . '.');
                    }
                    echo json_encode($result);
                    break;

                // ── KATEGORI ──────────────────────────────────
                case 'add_kategori':
                    $result = $mgr->addKategori(
                        $_POST['nama_kategori'] ?? '',
                        $_POST['kode_kategori'] ?? ''
                    );
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'tambah_kategori', 'bhp',
                            'Menambahkan kategori: ' . ($_POST['nama_kategori'] ?? '') . '.');
                    }
                    echo json_encode($result);
                    break;

                case 'edit_kategori':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->editKategori(
                        $id,
                        $_POST['nama_kategori'] ?? '',
                        $_POST['kode_kategori'] ?? ''
                    );
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'edit_kategori', 'bhp',
                            'Mengedit kategori ID ' . $id . ' menjadi: ' . ($_POST['nama_kategori'] ?? '') . '.');
                    }
                    echo json_encode($result);
                    break;

                case 'delete_kategori':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->deleteKategori($id);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'hapus_kategori', 'bhp',
                            'Menghapus kategori ID ' . $id . '.');
                    }
                    echo json_encode($result);
                    break;

                // ── BHP ───────────────────────────────────────
                case 'add_bhp':
                    $result = $mgr->addBhp($_POST);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'tambah_bhp', 'bhp',
                            'Menambahkan BHP: ' . ($_POST['nama_bhp'] ?? '') . ' (kode: ' . ($result['kode'] ?? '') . ').');
                    }
                    echo json_encode($result);
                    break;

                case 'edit_bhp':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->editBhp($id, $_POST);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'edit_bhp', 'bhp',
                            'Mengedit BHP ID ' . $id . ': ' . ($_POST['nama_bhp'] ?? '') . '.');
                    }
                    echo json_encode($result);
                    break;

                case 'delete_bhp':
                    $id     = (int)($_POST['id'] ?? 0);
                    $result = $mgr->deleteBhp($id);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'hapus_bhp', 'bhp',
                            'Menghapus BHP ID ' . $id . '.');
                    }
                    echo json_encode($result);
                    break;

                default:
                    echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali: ' . htmlspecialchars($action)]);
            }

        } catch (Throwable $e) {
            if (ob_get_length()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }

        exit();
    }
}
