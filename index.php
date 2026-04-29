<?php
/**
 * Entry Point - Poli Gigi Klinik Pratama
 * Saat project dibuka (http://localhost/BHP-Poli-Gigi/),
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
            header('Location: /BHP-Poli-Gigi/admin/index.php');
            exit();
        case 'dokter':
            header('Location: /BHP-Poli-Gigi/dokter/index.php');
            exit();
        case 'kepala_klinik':
            header('Location: /BHP-Poli-Gigi/kepala_klinik/index.php');
            exit();
    }
}

// Belum login → ke halaman Login
header('Location: /BHP-Poli-Gigi/Login.php');
exit();
