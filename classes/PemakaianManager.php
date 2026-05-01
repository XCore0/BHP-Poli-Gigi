<?php
namespace App\Classes;

use PDO;
use App\Config\Database;

/**
 * Class PemakaianManager
 * Menangani CRUD untuk tabel pemakaian_bhp & pemakaian_bhp_detail
 * Serta sinkronisasi field Jumlah & Pemakaian pada tabel bhp
 */
class PemakaianManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTables();
    }

    // ─── Auto-create tabel jika belum ada ────────────────────
    private function ensureTables(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `pemakaian_bhp` (
              `id_pemakaian`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `tanggal`       DATE         NOT NULL,
              `id_user`       INT UNSIGNED DEFAULT NULL,
              `unit_tindakan` VARCHAR(100) DEFAULT NULL,
              `lokasi`        VARCHAR(100) DEFAULT NULL,
              `nama_pasien`   VARCHAR(100) DEFAULT NULL,
              `catatan`       TEXT         DEFAULT NULL,
              `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id_pemakaian`),
              KEY `fk_pemakaian_user` (`id_user`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `pemakaian_bhp_detail` (
              `id_detail`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `id_pemakaian` INT UNSIGNED NOT NULL,
              `id_bhp`       INT UNSIGNED NOT NULL,
              `jumlah`       INT          NOT NULL DEFAULT 1,
              `kondisi`      ENUM('habis','sisa') NOT NULL DEFAULT 'habis',
              PRIMARY KEY (`id_detail`),
              KEY `fk_detail_pemakaian` (`id_pemakaian`),
              KEY `fk_detail_bhp`       (`id_bhp`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    // ══════════════════════════════════════════════
    //  READ — HEADER PEMAKAIAN
    // ══════════════════════════════════════════════

    /**
     * Ambil semua sesi pemakaian (join user)
     *
     * @param array $filter ['keyword', 'id_user', 'limit', 'offset']
     */
    public function getAllPemakaian(array $filter = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filter['id_user'])) {
            $where[]  = 'p.id_user = ?';
            $params[] = (int)$filter['id_user'];
        }
        if (!empty($filter['keyword'])) {
            $kw       = '%' . $filter['keyword'] . '%';
            $where[]  = '(p.nama_pasien LIKE ? OR p.unit_tindakan LIKE ? OR p.lokasi LIKE ?)';
            $params[] = $kw;
            $params[] = $kw;
            $params[] = $kw;
        }

        $sql = "
            SELECT p.*,
                   u.Nama_lengkap AS nama_dokter,
                   (SELECT COUNT(*) FROM pemakaian_bhp_detail d WHERE d.id_pemakaian = p.id_pemakaian) AS jumlah_item
            FROM   pemakaian_bhp p
            LEFT JOIN user u ON p.id_user = u.id_user
        ";
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY p.created_at DESC';

        if (!empty($filter['limit'])) {
            $sql     .= ' LIMIT ?';
            $params[] = (int)$filter['limit'];
            if (!empty($filter['offset'])) {
                $sql     .= ' OFFSET ?';
                $params[] = (int)$filter['offset'];
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Hitung total sesi pemakaian */
    public function countPemakaian(array $filter = []): int
    {
        $where  = [];
        $params = [];
        if (!empty($filter['id_user'])) {
            $where[]  = 'id_user = ?';
            $params[] = (int)$filter['id_user'];
        }
        $sql = 'SELECT COUNT(*) FROM pemakaian_bhp';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Ambil detail item BHP dari satu sesi pemakaian
     */
    public function getPemakaianDetail(int $idPemakaian): array
    {
        $stmt = $this->db->prepare("
            SELECT d.*, b.Nama_bhp, b.Kode_bhp, s.Nama_satuan
            FROM   pemakaian_bhp_detail d
            LEFT JOIN bhp        b ON d.id_bhp    = b.id_bhp
            LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan
            WHERE  d.id_pemakaian = ?
        ");
        $stmt->execute([$idPemakaian]);
        return $stmt->fetchAll();
    }

    // ══════════════════════════════════════════════
    //  CREATE
    // ══════════════════════════════════════════════

    /**
     * Simpan sesi pemakaian + detail item BHP
     * Mengurangi bhp.Jumlah dan menambah bhp.Pemakaian
     *
     * @param array $header  data header (tanggal, unit, lokasi, pasien, catatan)
     * @param array $items   array of ['id_bhp', 'jumlah', 'kondisi']
     * @param int   $userId  ID dokter yang mencatat
     */
    public function addPemakaian(array $header, array $items, int $userId): array
    {
        $tanggal       = trim($header['tanggal']       ?? '');
        $unit_tindakan = trim($header['unit_tindakan'] ?? '');
        $lokasi        = trim($header['lokasi']        ?? '');
        $nama_pasien   = trim($header['nama_pasien']   ?? '');
        $catatan       = trim($header['catatan']       ?? '');

        if ($tanggal === '') {
            return ['success' => false, 'message' => 'Tanggal pemakaian tidak boleh kosong.'];
        }
        if (empty($items)) {
            return ['success' => false, 'message' => 'Tambahkan minimal 1 BHP yang digunakan.'];
        }

        try {
            $this->db->beginTransaction();

            // Insert header
            $stmtH = $this->db->prepare("
                INSERT INTO pemakaian_bhp
                    (tanggal, id_user, unit_tindakan, lokasi, nama_pasien, catatan)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmtH->execute([
                $tanggal,
                $userId > 0 ? $userId : null,
                $unit_tindakan ?: null,
                $lokasi        ?: null,
                $nama_pasien   ?: null,
                $catatan       ?: null,
            ]);
            $idPemakaian = (int)$this->db->lastInsertId();

            // Insert detail + update stok bhp
            $stmtD = $this->db->prepare("
                INSERT INTO pemakaian_bhp_detail (id_pemakaian, id_bhp, jumlah, kondisi)
                VALUES (?, ?, ?, ?)
            ");
            $stmtUpd = $this->db->prepare("
                UPDATE bhp
                SET Pemakaian = GREATEST(0, Pemakaian - ?),
                    Jumlah    = CEIL(GREATEST(0, Pemakaian - ?) / NULLIF(isi_per_stok, 0))
                WHERE id_bhp = ?
            ");

            foreach ($items as $item) {
                $id_bhp  = (int)($item['id_bhp']  ?? 0);
                $jumlah  = max(1, (int)($item['jumlah']  ?? 1));
                $kondisi = in_array($item['kondisi'] ?? '', ['habis', 'sisa']) ? $item['kondisi'] : 'habis';

                if ($id_bhp <= 0) continue;

                $stmtD->execute([$idPemakaian, $id_bhp, $jumlah, $kondisi]);
                $stmtUpd->execute([$jumlah, $jumlah, $id_bhp]);
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Catatan pemakaian berhasil disimpan.', 'id' => $idPemakaian];

        } catch (\Throwable $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════════
    //  DELETE
    // ══════════════════════════════════════════════

    /**
     * Hapus sesi pemakaian + kembalikan stok bhp
     * (detail dihapus otomatis via CASCADE)
     */
    public function deletePemakaian(int $id): array
    {
        // Ambil semua detail sebelum hapus, untuk kembalikan stok
        $details = $this->getPemakaianDetail($id);

        // Cek apakah header ada
        $chk = $this->db->prepare('SELECT id_pemakaian FROM pemakaian_bhp WHERE id_pemakaian = ?');
        $chk->execute([$id]);
        if (!$chk->fetch()) {
            return ['success' => false, 'message' => 'Data pemakaian tidak ditemukan.'];
        }

        try {
            $this->db->beginTransaction();

            // Kembalikan stok bhp
            if (!empty($details)) {
                $stmtUpd = $this->db->prepare("
                    UPDATE bhp
                    SET Pemakaian = Pemakaian + ?,
                        Jumlah    = CEIL((Pemakaian + ?) / NULLIF(isi_per_stok, 0))
                    WHERE id_bhp = ?
                ");
                foreach ($details as $d) {
                    $stmtUpd->execute([$d['jumlah'], $d['jumlah'], $d['id_bhp']]);
                }
            }

            // Hapus header (detail CASCADE)
            $del = $this->db->prepare('DELETE FROM pemakaian_bhp WHERE id_pemakaian = ?');
            $del->execute([$id]);

            $this->db->commit();
            return ['success' => true, 'message' => 'Catatan pemakaian berhasil dihapus.'];

        } catch (\Throwable $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()];
        }
    }
}
