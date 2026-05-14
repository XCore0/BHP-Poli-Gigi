<?php
namespace App\Classes;

use PDO;
use PDOException;
use Exception;
use App\Config\Database;

/**
 * Class StokMasukManager
 * Menangani CRUD untuk tabel stok_masuk
 * dan sinkronisasi jumlah stok pada tabel bhp
 */
class StokMasukManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    // ─── Auto-create tabel jika belum ada ────────────────────
    private function ensureTable(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS `stok_masuk` (
              `id_stok_masuk`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `id_bhp`         INT UNSIGNED NOT NULL,
              `jumlah`         INT          NOT NULL DEFAULT 1,
              `isi_per_stok`   INT          NOT NULL DEFAULT 1,
              `tanggal_terima` DATE         NOT NULL,
              `supplier`       VARCHAR(100) DEFAULT NULL,
              `tgl_kadaluarsa` DATE         DEFAULT NULL,
              `catatan`        TEXT         DEFAULT NULL,
              `id_user`        INT UNSIGNED DEFAULT NULL,
              `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id_stok_masuk`),
              KEY `fk_stok_bhp`  (`id_bhp`),
              KEY `fk_stok_user` (`id_user`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        // Periksa & Tambah kolom isi_per_stok di tabel stok_masuk
        $this->addColIfNotExist('stok_masuk', 'isi_per_stok', 'INT NOT NULL DEFAULT 1 AFTER `jumlah`');
        // Periksa & Tambah kolom isi_per_stok di tabel bhp
        $this->addColIfNotExist('bhp', 'isi_per_stok', 'INT NOT NULL DEFAULT 1 AFTER `Pemakaian`');
    }

    private function addColIfNotExist(string $table, string $column, string $definition): void
    {
        try {
            // Gunakan @ untuk meredam warning jika tabel belum ada sama sekali
            $check = @$this->db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
            if ($check instanceof \PDOStatement && $check->rowCount() === 0) {
                $this->db->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            }
        } catch (\Throwable $e) {
            // Diamkan agar tidak mengganggu output JSON
        }
    }

    // ══════════════════════════════════════════════
    //  READ
    // ══════════════════════════════════════════════

    /**
     * Ambil semua riwayat stok masuk (join bhp & user)
     *
     * @param array $filter ['keyword', 'id_bhp', 'limit', 'offset']
     */
    public function getAllStokMasuk(array $filter = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filter['keyword'])) {
            $kw       = '%' . $filter['keyword'] . '%';
            $where[]  = '(b.Nama_bhp LIKE ? OR sm.supplier LIKE ?)';
            $params[] = $kw;
            $params[] = $kw;
        }
        if (!empty($filter['id_bhp'])) {
            $where[]  = 'sm.id_bhp = ?';
            $params[] = (int)$filter['id_bhp'];
        }

        $sql = "
            SELECT sm.*,
                   b.Nama_bhp, b.Kode_bhp,
                   s.Nama_satuan,
                   u.Nama_lengkap AS nama_user
            FROM   stok_masuk sm
            LEFT JOIN bhp        b ON sm.id_bhp  = b.id_bhp
            LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan
            LEFT JOIN user       u ON sm.id_user  = u.id_user
        ";
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY sm.created_at DESC';

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

    /** Hitung total record stok masuk */
    public function countStokMasuk(array $filter = []): int
    {
        $where  = [];
        $params = [];
        if (!empty($filter['keyword'])) {
            $kw       = '%' . $filter['keyword'] . '%';
            $where[]  = '(b.Nama_bhp LIKE ? OR sm.supplier LIKE ?)';
            $params[] = $kw;
            $params[] = $kw;
        }
        $sql = "SELECT COUNT(*) FROM stok_masuk sm LEFT JOIN bhp b ON sm.id_bhp = b.id_bhp";
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // ══════════════════════════════════════════════
    //  CREATE
    // ══════════════════════════════════════════════

    /**
     * Tambah record stok masuk + update jumlah di tabel bhp
     *
     * @param array $data   POST data
     * @param int   $userId ID user yang menginput
     */
    public function addStokMasuk(array $data, int $userId): array
    {
        $id_bhp         = (int)($data['id_bhp']         ?? 0);
        $jumlah         = (int)($data['jumlah']         ?? 0);
        $tanggal_terima = trim($data['tanggal_terima']  ?? '');
        $supplier       = trim($data['supplier']        ?? '');
        $tgl_kadaluarsa = trim($data['tgl_kadaluarsa']  ?? '');
        $catatan        = trim($data['catatan']         ?? '');
        $isiPerStokBaru = max(1, (int)($data['isi_per_stok'] ?? 1));

        // Validasi
        if ($id_bhp <= 0)         return ['success' => false, 'message' => 'Pilih barang BHP terlebih dahulu.'];
        if ($jumlah <= 0)         return ['success' => false, 'message' => 'Jumlah harus lebih dari 0.'];
        if ($tanggal_terima === '') return ['success' => false, 'message' => 'Tanggal terima tidak boleh kosong.'];
        if ($tgl_kadaluarsa === '') return ['success' => false, 'message' => 'Tanggal kedaluarsa tidak boleh kosong.'];

        // Cek apakah BHP ada dan ambil isi_per_stok
        $chk = $this->db->prepare('SELECT id_bhp, isi_per_stok FROM bhp WHERE id_bhp = ?');
        $chk->execute([$id_bhp]);
        $bhp = $chk->fetch();
        if (!$bhp) {
            return ['success' => false, 'message' => 'Data BHP tidak ditemukan.'];
        }

        // Gunakan isi_per_stok dari form (yang terbaru)
        $isiPerStok = $isiPerStokBaru;

        try {
            $this->db->beginTransaction();

            // Update isi_per_stok di tabel bhp jika berubah
            if ($isiPerStok !== (int)$bhp['isi_per_stok']) {
                $updIsi = $this->db->prepare('UPDATE bhp SET isi_per_stok = ? WHERE id_bhp = ?');
                $updIsi->execute([$isiPerStok, $id_bhp]);
            }

            // Insert stok_masuk
            $stmt = $this->db->prepare("
                INSERT INTO stok_masuk
                    (id_bhp, jumlah, isi_per_stok, tanggal_terima, tgl_kadaluarsa, catatan, id_user)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $id_bhp,
                $jumlah,
                $isiPerStok,
                $tanggal_terima,
                $tgl_kadaluarsa ?: null,
                $catatan   ?: null,
                $userId > 0 ? $userId : null,
            ]);
            $newId = (int)$this->db->lastInsertId();

            // Update jumlah stok BHP dan total pemakaian
            $tambahPemakaian = $jumlah * $isiPerStok;
            $upd = $this->db->prepare('UPDATE bhp SET Jumlah = Jumlah + ?, Pemakaian = Pemakaian + ? WHERE id_bhp = ?');
            $upd->execute([$jumlah, $tambahPemakaian, $id_bhp]);

            // Ambil data lengkap untuk dikembalikan ke front-end
            $getData = $this->db->prepare("
                SELECT sm.*, b.Nama_bhp, b.Kode_bhp, s.Nama_satuan, u.Nama_lengkap as nama_user
                FROM stok_masuk sm
                JOIN bhp b ON sm.id_bhp = b.id_bhp
                LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan
                LEFT JOIN user u ON sm.id_user = u.id_user
                WHERE sm.id_stok_masuk = ?
            ");
            $getData->execute([$newId]);
            $fullData = $getData->fetch(PDO::FETCH_ASSOC);

            $this->db->commit();

            return [
                'success' => true, 
                'message' => 'Stok masuk berhasil dicatat.', 
                'id'      => $newId,
                'data'    => $fullData
            ];

        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return ['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════════
    //  UPDATE
    // ══════════════════════════════════════════════

    /**
     * Edit record stok masuk + sesuaikan jumlah stok BHP
     * Mendukung pergantian BHP: balikkan stok lama, terapkan ke BHP baru.
     */
    public function editStokMasuk(int $id, array $data): array
    {
        $idBhpBaru      = (int)($data['id_bhp']        ?? 0);
        $jumlahBaru     = (int)($data['jumlah']        ?? 0);
        $tanggal_terima = trim($data['tanggal_terima'] ?? '');
        $tgl_kadaluarsa = trim($data['tgl_kadaluarsa'] ?? '');
        $catatan        = trim($data['catatan']        ?? '');

        if ($idBhpBaru <= 0)        return ['success' => false, 'message' => 'Pilih barang BHP terlebih dahulu.'];
        if ($jumlahBaru <= 0)       return ['success' => false, 'message' => 'Jumlah harus lebih dari 0.'];
        if ($tanggal_terima === '')  return ['success' => false, 'message' => 'Tanggal terima tidak boleh kosong.'];
        if ($tgl_kadaluarsa === '')  return ['success' => false, 'message' => 'Tanggal kedaluarsa tidak boleh kosong.'];

        // Ambil data lama stok_masuk
        $sel = $this->db->prepare('SELECT sm.*, b.isi_per_stok as bhp_isi FROM stok_masuk sm JOIN bhp b ON sm.id_bhp = b.id_bhp WHERE sm.id_stok_masuk = ?');
        $sel->execute([$id]);
        $lama = $sel->fetch();
        if (!$lama) return ['success' => false, 'message' => 'Data stok masuk tidak ditemukan.'];

        $idBhpLama  = (int)$lama['id_bhp'];
        $jumlahLama = (int)$lama['jumlah'];
        $isiLama    = (int)($lama['isi_per_stok'] ?? 1);

        // Cek BHP baru
        $cekBhp = $this->db->prepare('SELECT id_bhp, isi_per_stok FROM bhp WHERE id_bhp = ?');
        $cekBhp->execute([$idBhpBaru]);
        $bhpBaru = $cekBhp->fetch();
        if (!$bhpBaru) return ['success' => false, 'message' => 'Data BHP baru tidak ditemukan.'];

        $isiBaru = (int)($bhpBaru['isi_per_stok'] ?? 1);
        $bhpGanti = ($idBhpBaru !== $idBhpLama);

        try {
            $this->db->beginTransaction();

            if ($bhpGanti) {
                // ── BHP DIGANTI: balikkan stok BHP lama ──
                $deltaLamaUnit   = $jumlahLama * $isiLama;
                $updLama = $this->db->prepare("
                    UPDATE bhp
                    SET Jumlah    = GREATEST(0, Jumlah    - ?),
                        Pemakaian = GREATEST(0, Pemakaian - ?)
                    WHERE id_bhp = ?
                ");
                $updLama->execute([$jumlahLama, $deltaLamaUnit, $idBhpLama]);

                // Tambahkan stok ke BHP baru
                $deltaBaruUnit = $jumlahBaru * $isiBaru;
                $updBaru = $this->db->prepare("
                    UPDATE bhp
                    SET Jumlah    = Jumlah    + ?,
                        Pemakaian = Pemakaian + ?
                    WHERE id_bhp = ?
                ");
                $updBaru->execute([$jumlahBaru, $deltaBaruUnit, $idBhpBaru]);

                // Update stok_masuk: ganti id_bhp, jumlah, isi_per_stok
                $upd = $this->db->prepare("
                    UPDATE stok_masuk
                    SET id_bhp = ?, jumlah = ?, isi_per_stok = ?,
                        tanggal_terima = ?, tgl_kadaluarsa = ?, catatan = ?
                    WHERE id_stok_masuk = ?
                ");
                $upd->execute([
                    $idBhpBaru, $jumlahBaru, $isiBaru,
                    $tanggal_terima, $tgl_kadaluarsa ?: null,
                    $catatan ?: null, $id,
                ]);
            } else {
                // ── BHP SAMA: sesuaikan selisih ──
                $selisihJumlah = $jumlahBaru - $jumlahLama;
                $selisihUnit   = ($jumlahBaru * $isiBaru) - ($jumlahLama * $isiLama);
                $updBhp = $this->db->prepare("
                    UPDATE bhp
                    SET Jumlah    = GREATEST(0, Jumlah    + ?),
                        Pemakaian = GREATEST(0, Pemakaian + ?)
                    WHERE id_bhp = ?
                ");
                $updBhp->execute([$selisihJumlah, $selisihUnit, $idBhpLama]);

                $upd = $this->db->prepare("
                    UPDATE stok_masuk
                    SET jumlah = ?, tanggal_terima = ?, tgl_kadaluarsa = ?, catatan = ?
                    WHERE id_stok_masuk = ?
                ");
                $upd->execute([
                    $jumlahBaru,
                    $tanggal_terima, $tgl_kadaluarsa ?: null,
                    $catatan ?: null, $id,
                ]);
            }

            // Ambil data lengkap untuk dikembalikan ke front-end
            $getData = $this->db->prepare("
                SELECT sm.*, b.Nama_bhp, b.Kode_bhp, s.Nama_satuan, u.Nama_lengkap as nama_user
                FROM stok_masuk sm
                JOIN bhp b ON sm.id_bhp = b.id_bhp
                LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan
                LEFT JOIN user u ON sm.id_user = u.id_user
                WHERE sm.id_stok_masuk = ?
            ");
            $getData->execute([$id]);
            $fullData = $getData->fetch(PDO::FETCH_ASSOC);

            $this->db->commit();
            return ['success' => true, 'message' => 'Data stok masuk berhasil diperbarui.', 'data' => $fullData];

        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return ['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════════
    //  DELETE
    // ══════════════════════════════════════════════

    /**
     * Hapus record stok masuk + kembalikan jumlah ke tabel bhp
     */
    public function deleteStokMasuk(int $id): array
    {
        // Ambil data sebelum hapus beserta isi_per_stok
        $sel = $this->db->prepare('SELECT sm.id_bhp, sm.jumlah, b.isi_per_stok FROM stok_masuk sm JOIN bhp b ON sm.id_bhp = b.id_bhp WHERE sm.id_stok_masuk = ?');
        $sel->execute([$id]);
        $row = $sel->fetch();
        if (!$row) return ['success' => false, 'message' => 'Data stok masuk tidak ditemukan.'];

        $isiPerStok = (int)($row['isi_per_stok'] ?? 1);
        $kurangPemakaian = $row['jumlah'] * $isiPerStok;

        try {
            $this->db->beginTransaction();

            // Kurangi kembali stok bhp: Pemakaian -= jumlah*isi_per_stok, Jumlah = FLOOR(Pemakaian/isi_per_stok)
            $upd = $this->db->prepare(
                'UPDATE bhp
                 SET Pemakaian = GREATEST(0, Pemakaian - ?),
                     Jumlah    = FLOOR(GREATEST(0, Pemakaian - ?) / NULLIF(isi_per_stok, 0))
                 WHERE id_bhp = ?'
            );
            $upd->execute([$kurangPemakaian, $kurangPemakaian, $row['id_bhp']]);

            // Hapus record
            $del = $this->db->prepare('DELETE FROM stok_masuk WHERE id_stok_masuk = ?');
            $del->execute([$id]);

            $this->db->commit();
            return ['success' => true, 'message' => 'Data stok masuk berhasil dihapus.'];

        } catch (\Throwable $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()];
        }
    }
}
