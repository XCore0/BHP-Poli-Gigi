<?php
// Laporan pemakaian â€” shared dengan dokter
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\Auth;
use App\Config\Database;

$auth = new Auth();
$auth->requireRole(['admin'], '/BHP-Poli-Gigi/Login.php');

include __DIR__ . '/../../components/shared/laporan.php';
