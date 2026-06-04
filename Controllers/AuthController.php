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
            header('Location: /Pages/auth/login.php');
            exit();
        }

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->auth->login($email, $password);

        if (!$result['success']) {
            $_SESSION['login_error'] = $result['message'];
            header('Location: /Pages/auth/login.php');
            exit();
        }

        // Redirect berdasarkan role
        $role = $result['role'];
        switch ($role) {
            case 'admin':
                header('Location: /Pages/admin/index.php');
                break;
            case 'dokter':
                header('Location: /Pages/dokter/index.php');
                break;
            case 'kepala_klinik':
                header('Location: /Pages/kepala_klinik/index.php');
                break;
            default:
                $_SESSION['login_error'] = 'Role pengguna tidak dikenali.';
                header('Location: /Pages/auth/login.php');
        }
        exit();
    }
}
