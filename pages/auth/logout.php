<?php
/**
 * Logout Handler
 * Menghancurkan session dan redirect ke halaman login
 */
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\Auth;

$auth = new Auth();
$auth->logout();

$reason = $_GET['reason'] ?? '';
$redirectUrl = '/Pages/auth/login.php';
if ($reason) {
    $redirectUrl .= '?reason=' . urlencode($reason);
}

header("Location: $redirectUrl");
exit();
