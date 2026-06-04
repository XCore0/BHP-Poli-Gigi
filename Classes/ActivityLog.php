<?php
namespace App\Classes;

use PDO;
use PDOException;
use Exception;
use App\Config\Database;

/**
 * Class ActivityLog
 * Mencatat semua aktivitas pengguna ke tabel log_aktivitas
 * Digunakan oleh Auth (login/logout) dan proses lainnya
 */
class ActivityLog
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    /**
     * Buat tabel log_aktivitas otomatis jika belum ada
     */
    private function ensureTable(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `log_aktivitas` (
                `id_log`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_user`     INT UNSIGNED NULL,
                `nama_user`   VARCHAR(100) NOT NULL,
                `role_user`   ENUM('admin','dokter','kepala_klinik') NOT NULL,
                `aksi`        VARCHAR(100) NOT NULL,
                `kategori`    ENUM('auth','pengguna','bhp','stok','laporan','sistem') NOT NULL DEFAULT 'sistem',
                `detail`      TEXT NULL,
                `ip_address`  VARCHAR(45) NULL,
                `waktu`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_log`),
                KEY `idx_user`    (`id_user`),
                KEY `idx_waktu`   (`waktu`),
                KEY `idx_kategori`(`kategori`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    /**
     * Catat aktivitas ke database
     */
    public function catat(
        ?int   $idUser,
        string $namaUser,
        string $roleUser,
        string $aksi,
        string $kategori = 'sistem',
        string $detail   = ''
    ): bool {
        try {
            $ip = $this->getIp();
            $stmt = $this->db->prepare(
                'INSERT INTO log_aktivitas (id_user, nama_user, role_user, aksi, kategori, detail, ip_address)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$idUser, $namaUser, $roleUser, $aksi, $kategori, $detail, $ip]);
            return true;
        } catch (PDOException $e) {
            error_log('[ActivityLog] Gagal catat log: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ambil log aktivitas dengan filter & pagination
     */
    public function getLogs(array $filter = [], int $limit = 20, int $offset = 0): array
    {
        try {
            $where  = [];
            $params = [];

            if (!empty($filter['kategori'])) { $where[] = 'kategori = ?'; $params[] = $filter['kategori']; }
            if (!empty($filter['role']))     { $where[] = 'role_user = ?'; $params[] = $filter['role']; }
            if (!empty($filter['aksi']))     { $where[] = 'aksi = ?'; $params[] = $filter['aksi']; }
            if (!empty($filter['tanggal_dari'])) { $where[] = 'DATE(waktu) >= ?'; $params[] = $filter['tanggal_dari']; }
            if (!empty($filter['tanggal_sampai'])) { $where[] = 'DATE(waktu) <= ?'; $params[] = $filter['tanggal_sampai']; }
            if (!empty($filter['keyword'])) {
                $where[] = '(nama_user LIKE ? OR detail LIKE ? OR aksi LIKE ?)';
                $kw = '%' . $filter['keyword'] . '%';
                $params[] = $kw; $params[] = $kw; $params[] = $kw;
            }

            $sql = 'SELECT * FROM log_aktivitas';
            if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
            $sql .= ' ORDER BY waktu DESC LIMIT ? OFFSET ?';
            $params[] = $limit;
            $params[] = $offset;

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('[ActivityLog] getLogs error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Hitung total log (untuk pagination)
     */
    public function countLogs(array $filter = []): int
    {
        try {
            $where  = [];
            $params = [];

            if (!empty($filter['kategori'])) { $where[] = 'kategori = ?'; $params[] = $filter['kategori']; }
            if (!empty($filter['role']))     { $where[] = 'role_user = ?'; $params[] = $filter['role']; }
            if (!empty($filter['keyword'])) {
                $where[] = '(nama_user LIKE ? OR detail LIKE ? OR aksi LIKE ?)';
                $kw = '%' . $filter['keyword'] . '%';
                $params = array_merge($params, [$kw, $kw, $kw]);
            }

            $sql = 'SELECT COUNT(*) FROM log_aktivitas';
            if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('[ActivityLog] countLogs error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Hitung aktivitas hari ini
     */
    public function countToday(): int
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM log_aktivitas WHERE DATE(waktu) = CURDATE()");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Hitung per kategori
     */
    public function countByKategori(string $kategori): int
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM log_aktivitas WHERE kategori = ?");
            $stmt->execute([$kategori]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }


    /**
     * Ambil IP address pengguna
     */
    private function getIp(): string
    {
        $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                return trim(explode(',', $_SERVER[$key])[0]);
            }
        }
        return '0.0.0.0';
    }

    // ── Helper shortcut methods ────────────────────────────

    /** Log login berhasil */
    public function logLogin(int $id, string $nama, string $role): void
    {
        $this->catat($id, $nama, $role, 'login', 'auth', "$nama berhasil masuk ke sistem.");
    }

    /** Log logout */
    public function logLogout(int $id, string $nama, string $role): void
    {
        $this->catat($id, $nama, $role, 'logout', 'auth', "$nama keluar dari sistem.");
    }

    /** Log tambah pengguna */
    public function logTambahPengguna(int $idAdmin, string $namaAdmin, string $emailBaru, string $roleBaru): void
    {
        $this->catat($idAdmin, $namaAdmin, 'admin', 'tambah_pengguna', 'pengguna',
            "Menambahkan pengguna baru: $emailBaru (role: $roleBaru).");
    }

    /** Log hapus pengguna */
    public function logHapusPengguna(int $idAdmin, string $namaAdmin, string $targetNama): void
    {
        $this->catat($idAdmin, $namaAdmin, 'admin', 'hapus_pengguna', 'pengguna',
            "Menghapus pengguna: $targetNama.");
    }

    /** Log toggle status pengguna */
    public function logToggleStatus(int $idAdmin, string $namaAdmin, string $targetNama, string $status): void
    {
        $this->catat($idAdmin, $namaAdmin, 'admin', 'ubah_status_pengguna', 'pengguna',
            "Mengubah status $targetNama menjadi $status.");
    }

    /** Log catat pemakaian BHP */
    public function logPemakaianBhp(int $idUser, string $namaUser, string $role, string $namaBhp, int $qty, string $pasien): void
    {
        $this->catat($idUser, $namaUser, $role, 'catat_pemakaian', 'bhp',
            "Catat pemakaian: $namaBhp ×$qty — Pasien: $pasien.");
    }

    /** Log stok masuk */
    public function logStokMasuk(int $idUser, string $namaUser, string $namaBhp, int $qty): void
    {
        $this->catat($idUser, $namaUser, 'admin', 'stok_masuk', 'stok',
            "Stok masuk: $namaBhp +$qty unit.");
    }
}
