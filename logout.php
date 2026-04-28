<?php
/**
 * Logout Handler
 * Menghancurkan session dan redirect ke halaman login
 */
require_once __DIR__ . '/vendor/autoload.php';
use App\Classes\Auth;

$auth = new Auth();
$auth->logout();

header('Location: /be-poli/Login.php');
exit();
