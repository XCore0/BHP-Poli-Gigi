<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;

$controller = new AuthController();
$controller->handleLogin();
