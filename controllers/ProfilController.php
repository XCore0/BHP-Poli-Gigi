<?php
namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\UserManager;
use App\Classes\ActivityLog;
use Throwable;

/**
 * Class ProfilController
 * Menangani AJAX untuk update profil, ganti password, dan preferensi
 */
class ProfilController
{
    public function handleRequest(): void
    {
        ini_set('display_errors', 0);
        ob_start();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $auth = new Auth();
            if (!$auth->isLoggedIn()) {
                echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
                exit();
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
                exit();
            }

            $user  = $auth->getCurrentUser();
            $uid   = (int)($user['id']  ?? 0);
            $uname = $user['nama'] ?? 'Unknown';
            $urole = $user['role'] ?? 'dokter';

            $mgr    = new UserManager();
            $log    = new ActivityLog();
            $action = $_POST['action'] ?? '';

            switch ($action) {

                case 'update_profil':
                    $result = $mgr->updateProfil($uid, $_POST);
                    if ($result['success']) {
                        // Refresh session nama & email
                        $_SESSION['poli_user']['nama']  = trim($_POST['nama']  ?? $uname);
                        $_SESSION['poli_user']['email'] = trim($_POST['email'] ?? $user['email']);
                        $log->catat($uid, $uname, $urole, 'update_profil', 'pengguna', 'Memperbarui data profil.');
                    }
                    echo json_encode($result);
                    break;

                case 'upload_foto':
                    if (empty($_FILES['foto'])) {
                        echo json_encode(['success' => false, 'message' => 'File foto tidak ditemukan.']);
                        break;
                    }
                    $uploadDir = __DIR__ . '/../assets/uploads/foto_profil';
                    $result    = $mgr->uploadFoto($uid, $_FILES['foto'], $uploadDir);
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'upload_foto', 'pengguna', 'Mengganti foto profil.');
                    }
                    echo json_encode($result);
                    break;

                case 'ganti_password':
                    $result = $mgr->gantiPassword(
                        $uid,
                        $_POST['password_lama']     ?? '',
                        $_POST['password_baru']     ?? '',
                        $_POST['konfirmasi_password'] ?? ''
                    );
                    if ($result['success']) {
                        $log->catat($uid, $uname, $urole, 'ganti_password', 'pengguna', 'Mengganti kata sandi.');
                    }
                    echo json_encode($result);
                    break;

                case 'save_preferensi':
                    $result = $mgr->savePreferensi($uid, $_POST);
                    echo json_encode($result);
                    break;

                default:
                    echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali.']);
            }

        } catch (Throwable $e) {
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }

        exit();
    }
}
