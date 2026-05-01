<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PemakaianController;

$controller = new PemakaianController();
$controller->handleRequest();
