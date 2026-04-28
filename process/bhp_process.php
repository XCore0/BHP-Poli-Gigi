<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\BhpController;

$controller = new BhpController();
$controller->handleRequest();
