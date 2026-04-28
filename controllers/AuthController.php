<?php
namespace App\Controllers;

use App\Classes\Auth;

class AuthController
{
    private Auth $auth;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->auth = new Auth();
    }

    public function handleLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /be-poli/Login.php');
            exit();
        }

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->auth->login($email, $password);

        if (!$result['success']) {
            $_SESSION['login_error'] = $result['message'];
            header('Location: /be-poli/Login.php');
            exit();
        }

        // Redirect berdasarkan role
        $role = $result['role'];
        switch ($role) {
            case 'admin':
                header('Location: /be-poli/admin/index.php');
                break;
            case 'dokter':
                header('Location: /be-poli/dokter/index.php');
                break;
            case 'kepala_klinik':
                header('Location: /be-poli/kepala_klinik/index.php');
                break;
            default:
                $_SESSION['login_error'] = 'Role pengguna tidak dikenali.';
                header('Location: /be-poli/Login.php');
        }
        exit();
    }
}
