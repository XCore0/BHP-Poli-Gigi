<?php

namespace App\Config;

use PDO;
use PDOException;

/**
 * Class Database
 * Singleton PDO connection ke db_poli_gigi
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private string $host     = 'localhost';
    private string $dbname   = 'db_poli_gigi';
    private string $username = 'root';
    private string $password = '';
    private string $charset  = 'utf8mb4';

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            error_log('[Database] ' . $e->getMessage());
            die(json_encode([
                'status'  => 'error',
                'message' => 'Koneksi database gagal. Silakan coba lagi nanti.'
            ]));
        }
    }

    /** Mencegah clone instance */
    private function __clone() {}

    /** Mendapatkan instance tunggal (Singleton) */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Mendapatkan objek PDO */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
