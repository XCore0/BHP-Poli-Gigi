<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;

$controller = new UserController();
$controller->handleRequest();
