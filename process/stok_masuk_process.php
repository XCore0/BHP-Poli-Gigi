<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\StokMasukController;

$controller = new StokMasukController();
$controller->handleRequest();
