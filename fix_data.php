<?php
require_once __DIR__ . '/vendor/autoload.php';
$db = App\Config\Database::getInstance()->getConnection();
$db->exec("UPDATE bhp SET isi_per_stok = 5 WHERE Nama_bhp = 'Amoc'");
$db->exec("UPDATE bhp SET Pemakaian = Jumlah * isi_per_stok WHERE Nama_bhp = 'Amoc'");
$db->exec("UPDATE bhp SET Pemakaian = 10, Jumlah = 2, isi_per_stok = 5 WHERE Nama_bhp = 'masker'");
echo "Data updated.";
