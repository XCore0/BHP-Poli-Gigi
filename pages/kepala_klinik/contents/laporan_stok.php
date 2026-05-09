<?php
// Laporan stok masuk — shared dengan admin
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Classes\Auth;

$auth = new Auth();
$auth->requireRole(['kepala_klinik'], '/BHP-Poli-Gigi/pages/auth/login.php');

include __DIR__ . '/../../components/shared/laporan_stok.php';
