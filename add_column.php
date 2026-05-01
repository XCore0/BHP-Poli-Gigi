<?php
require_once __DIR__ . '/vendor/autoload.php';
$db = App\Config\Database::getInstance()->getConnection();
try {
    $db->exec("ALTER TABLE bhp ADD COLUMN isi_per_stok INT NOT NULL DEFAULT 1 AFTER Jumlah");
    echo "Success: Added isi_per_stok column.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
