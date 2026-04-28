<?php
/**
 * Entry Point - Poli Gigi Klinik Pratama
 * Saat project dibuka (http://localhost/be-poli/),
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
            header('Location: /be-poli/admin/index.php');
            exit();
        case 'dokter':
            header('Location: /be-poli/dokter/index.php');
            exit();
        case 'kepala_klinik':
            header('Location: /be-poli/kepala_klinik/index.php');
            exit();
    }
}

// Belum login → ke halaman Login
header('Location: /be-poli/Login.php');
exit();
