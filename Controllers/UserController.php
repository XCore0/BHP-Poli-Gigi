<?php
namespace App\Controllers;

use App\Classes\Auth;
use App\Classes\UserManager;
use App\Classes\ActivityLog;

class UserController
{
    public function handleRequest(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        // Pastikan yang mengakses adalah admin yang sudah login
        $auth = new Auth();
        if (!$auth->isLoggedIn() || $auth->getRole() !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
            exit();
        }

        $action  = $_POST['action'] ?? '';
        $manager = new UserManager();

        switch ($action) {

            case 'add':
                $result = $manager->addUser([
                    'nama'     => $_POST['nama']     ?? '',
                    'email'    => $_POST['email']    ?? '',
                    'password' => $_POST['password'] ?? '',
                    'role'     => $_POST['role']     ?? '',
                    'no_telp'  => $_POST['no_telp']  ?? '',
                    'jenis_kelamin'     => $_POST['jenis_kelamin']     ?? '',
                    'tanggal_bergabung' => $_POST['tanggal_bergabung'] ?? '',
                ]);
                if ($result['success']) {
                    // Handle foto upload jika ada
                    if (!empty($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = __DIR__ . '/../assets/uploads/foto_profil';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        $newUserId = $result['id'] ?? 0;
                        if ($newUserId > 0) {
                            $manager->uploadFoto($newUserId, $_FILES['foto'], $uploadDir);
                        }
                    }
                    $currentUser = $auth->getCurrentUser();
                    (new ActivityLog())->logTambahPengguna(
                        (int) $currentUser['id'],
                        $currentUser['nama'],
                        $_POST['email'] ?? '',
                        $_POST['role']  ?? ''
                    );
                }
                echo json_encode($result);
                break;

            case 'toggle_status':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id <= 0) {
                    echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
                    break;
                }
                $result = $manager->toggleStatus($id);
                if ($result['success']) {
                    $currentUser = $auth->getCurrentUser();
                    // Ambil nama target dari DB
                    $allUsers = $manager->getAllUsers();
                    $targetNama = '';
                    foreach ($allUsers as $u) { if ((int)$u['id_user'] === $id) { $targetNama = $u['Nama_lengkap']; break; } }
                    (new ActivityLog())->logToggleStatus(
                        (int) $currentUser['id'],
                        $currentUser['nama'],
                        $targetNama,
                        $result['new_status']
                    );
                }
                echo json_encode($result);
                break;

            case 'delete':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id <= 0) {
                    echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
                    break;
                }
                $currentUser = $auth->getCurrentUser();
                if ($id === (int) $currentUser['id']) {
                    echo json_encode(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri.']);
                    break;
                }
                // Ambil nama target sebelum dihapus
                $allUsers = $manager->getAllUsers();
                $targetNama = '';
                foreach ($allUsers as $u) { if ((int)$u['id_user'] === $id) { $targetNama = $u['Nama_lengkap']; break; } }
                $result = $manager->deleteUser($id);
                if ($result['success'] && $targetNama) {
                    (new ActivityLog())->logHapusPengguna(
                        (int) $currentUser['id'],
                        $currentUser['nama'],
                        $targetNama
                    );
                }
                echo json_encode($result);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali.']);
        }
        exit();
    }
}
