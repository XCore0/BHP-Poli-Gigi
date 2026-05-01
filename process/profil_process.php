<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ProfilController;

$controller = new ProfilController();
$controller->handleRequest();
