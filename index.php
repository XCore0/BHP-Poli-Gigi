<?php
/**
 * Entry Point - Poli Gigi Klinik Pratama
 * Saat project dibuka (http://localhost/),
 * langsung diarahkan ke halaman Login.
 */

// Mulai session di entry point utama
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';
use App\Classes\Auth;

$auth = new Auth();

// Jika sudah login, langsung masuk ke dashboard sesuai role
if ($auth->isLoggedIn()) {
    switch ($auth->getRole()) {
        case 'admin':
            header('Location: /Pages/admin/index.php');
            exit();
        case 'dokter':
            header('Location: /Pages/dokter/index.php');
            exit();
        case 'kepala_klinik':
            header('Location: /Pages/kepala_klinik/index.php');
            exit();
    }
}

// Belum login → ke halaman Login
header('Location: /Pages/auth/login.php');
exit();
