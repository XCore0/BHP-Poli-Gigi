<?php
// Laporan stok masuk — shared dengan kepala klinik
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Classes\Auth;

$auth = new Auth();
$auth->requireRole(['admin'], '/BHP-Poli-Gigi/pages/auth/login.php');

include __DIR__ . '/../../components/shared/laporan_stok.php';
