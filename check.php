<?php
require_once __DIR__ . '/vendor/autoload.php';
$db = App\Config\Database::getInstance()->getConnection();
print_r($db->query('DESCRIBE bhp')->fetchAll(PDO::FETCH_ASSOC));
