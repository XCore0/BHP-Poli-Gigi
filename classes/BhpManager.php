<?php
namespace App\Classes;

use PDO;
use PDOException;
use Exception;
use App\Config\Database;

/**
 * Class BhpManager
 * Menangani CRUD untuk tabel bhp, kategori_bhp, satuan_bhp
 */
class BhpManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ══════════════════════════════════════════════
    //  SATUAN BHP
    // ══════════════════════════════════════════════

    /** Ambil semua satuan */
    public function getAllSatuan(): array
    {
        return $this->db->query('SELECT * FROM satuan_bhp ORDER BY Nama_satuan ASC')->fetchAll();
    }

    /** Hitung total satuan */
    public function countSatuan(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM satuan_bhp')->fetchColumn();
    }

    /** Tambah satuan */
    public function addSatuan(string $nama): array
    {
        $nama = trim($nama);
        if ($nama === '') return ['success' => false, 'message' => 'Nama satuan tidak boleh kosong.'];
        $chk = $this->db->prepare('SELECT id_satuan FROM satuan_bhp WHERE LOWER(Nama_satuan) = LOWER(?)');
        $chk->execute([$nama]);
        if ($chk->fetch()) return ['success' => false, 'message' => "Satuan \"$nama\" sudah ada."];
        $stmt = $this->db->prepare('INSERT INTO satuan_bhp (Nama_satuan) VALUES (?)');
        $stmt->execute([$nama]);
        return ['success' => true, 'message' => "Satuan \"$nama\" berhasil ditambahkan.", 'id' => (int)$this->db->lastInsertId()];
    }

    /** Edit satuan */
    public function editSatuan(int $id, string $nama): array
    {
        $nama = trim($nama);
        if ($nama === '') return ['success' => false, 'message' => 'Nama satuan tidak boleh kosong.'];
        $chk = $this->db->prepare('SELECT id_satuan FROM satuan_bhp WHERE LOWER(Nama_satuan) = LOWER(?) AND id_satuan != ?');
        $chk->execute([$nama, $id]);
        if ($chk->fetch()) return ['success' => false, 'message' => "Satuan \"$nama\" sudah ada."];
        $stmt = $this->db->prepare('UPDATE satuan_bhp SET Nama_satuan = ? WHERE id_satuan = ?');
        $stmt->execute([$nama, $id]);
        return ['success' => true, 'message' => 'Satuan berhasil diperbarui.'];
    }

    /** Hapus satuan */
    public function deleteSatuan(int $id): array
    {
        $chk = $this->db->prepare('SELECT COUNT(*) FROM bhp WHERE id_satuan = ?');
        $chk->execute([$id]);
        if ((int)$chk->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Satuan ini masih digunakan oleh data BHP dan tidak dapat dihapus.'];
        }
        $stmt = $this->db->prepare('DELETE FROM satuan_bhp WHERE id_satuan = ?');
        $stmt->execute([$id]);
        return ['success' => true, 'message' => 'Satuan berhasil dihapus.'];
    }

    // ══════════════════════════════════════════════
    //  KATEGORI BHP
    // ══════════════════════════════════════════════

    /** Ambil semua kategori */
    public function getAllKategori(): array
    {
        return $this->db->query('SELECT * FROM kategori_bhp ORDER BY Nama_kategori ASC')->fetchAll();
    }

    /** Hitung total kategori */
    public function countKategori(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM kategori_bhp')->fetchColumn();
    }

    /** Tambah kategori (dengan kode opsional) */
    public function addKategori(string $nama, string $kode = ''): array
    {
        $nama = trim($nama);
        $kode = trim($kode);
        if ($nama === '') return ['success' => false, 'message' => 'Nama kategori tidak boleh kosong.'];
        $chk = $this->db->prepare('SELECT id_kategori FROM kategori_bhp WHERE LOWER(Nama_kategori) = LOWER(?)');
        $chk->execute([$nama]);
        if ($chk->fetch()) return ['success' => false, 'message' => "Kategori \"$nama\" sudah ada."];
        $stmt = $this->db->prepare('INSERT INTO kategori_bhp (Kode_kategori, Nama_kategori) VALUES (?, ?)');
        $stmt->execute([$kode ?: null, $nama]);
        return ['success' => true, 'message' => "Kategori \"$nama\" berhasil ditambahkan.", 'id' => (int)$this->db->lastInsertId()];
    }

    /** Edit kategori (dengan kode opsional) */
    public function editKategori(int $id, string $nama, string $kode = ''): array
    {
        $nama = trim($nama);
        $kode = trim($kode);
        if ($nama === '') return ['success' => false, 'message' => 'Nama kategori tidak boleh kosong.'];
        $chk = $this->db->prepare('SELECT id_kategori FROM kategori_bhp WHERE LOWER(Nama_kategori) = LOWER(?) AND id_kategori != ?');
        $chk->execute([$nama, $id]);
        if ($chk->fetch()) return ['success' => false, 'message' => "Kategori \"$nama\" sudah ada."];
        $stmt = $this->db->prepare('UPDATE kategori_bhp SET Kode_kategori = ?, Nama_kategori = ? WHERE id_kategori = ?');
        $stmt->execute([$kode ?: null, $nama, $id]);
        return ['success' => true, 'message' => 'Kategori berhasil diperbarui.'];
    }

    /** Hapus kategori */
    public function deleteKategori(int $id): array
    {
        $chk = $this->db->prepare('SELECT COUNT(*) FROM bhp WHERE id_kategori = ?');
        $chk->execute([$id]);
        if ((int)$chk->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Kategori ini masih digunakan oleh data BHP dan tidak dapat dihapus.'];
        }
        $stmt = $this->db->prepare('DELETE FROM kategori_bhp WHERE id_kategori = ?');
        $stmt->execute([$id]);
        return ['success' => true, 'message' => 'Kategori berhasil dihapus.'];
    }

    // ══════════════════════════════════════════════
    //  DATA BHP
    // ══════════════════════════════════════════════

    /** Ambil semua BHP dengan join kategori & satuan */
    public function getAllBhp(array $filter = []): array
    {
        $where  = [];
        $params = [];
        if (!empty($filter['keyword'])) {
            $where[]  = '(b.Nama_bhp LIKE ? OR b.Kode_bhp LIKE ?)';
            $kw       = '%' . $filter['keyword'] . '%';
            $params[] = $kw;
            $params[] = $kw;
        }
        if (!empty($filter['id_kategori'])) {
            $where[]  = 'b.id_kategori = ?';
            $params[] = (int)$filter['id_kategori'];
        }
        $sql = 'SELECT b.*, k.Nama_kategori, k.Kode_kategori, s.Nama_satuan
                FROM bhp b
                LEFT JOIN kategori_bhp k ON b.id_kategori = k.id_kategori
                LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY b.id_bhp DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Generate kode BHP otomatis */
    public function generateKodeBhp(): string
    {
        do {
            $kode = 'BHP' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $chk  = $this->db->prepare('SELECT id_bhp FROM bhp WHERE Kode_bhp = ?');
            $chk->execute([$kode]);
        } while ($chk->fetch());
        return $kode;
    }

    /** Tambah BHP */
    public function addBhp(array $data): array
    {
        $nama      = trim($data['nama_bhp']    ?? '');
        $kode      = trim($data['kode_bhp']    ?? '') ?: $this->generateKodeBhp();
        $jumlah    = max(0, (int)($data['jumlah']    ?? 0));
        $pemakaian = max(0, (int)($data['Pemakaian'] ?? 0));
        $id_kat    = (int)($data['id_kategori'] ?? 0) ?: null;
        $id_sat    = (int)($data['id_satuan']   ?? 0) ?: null;

        if ($nama === '') return ['success' => false, 'message' => 'Nama BHP tidak boleh kosong.'];

        if ($kode !== '') {
            $chk = $this->db->prepare('SELECT id_bhp FROM bhp WHERE Kode_bhp = ?');
            $chk->execute([$kode]);
            if ($chk->fetch()) return ['success' => false, 'message' => "Kode BHP \"$kode\" sudah digunakan."];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO bhp (Kode_bhp, Nama_bhp, Jumlah, Pemakaian, id_kategori, id_satuan)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$kode ?: null, $nama, $jumlah, $pemakaian, $id_kat, $id_sat]);
        return ['success' => true, 'message' => "BHP \"$nama\" berhasil ditambahkan.", 'kode' => $kode];
    }

    /** Edit BHP */
    public function editBhp(int $id, array $data): array
    {
        $nama      = trim($data['nama_bhp']    ?? '');
        $kode      = trim($data['kode_bhp']    ?? '');
        $jumlah    = max(0, (int)($data['jumlah']    ?? 0));
        $pemakaian = max(0, (int)($data['Pemakaian'] ?? 0));
        $id_kat    = (int)($data['id_kategori'] ?? 0) ?: null;
        $id_sat    = (int)($data['id_satuan']   ?? 0) ?: null;

        if ($nama === '') return ['success' => false, 'message' => 'Nama BHP tidak boleh kosong.'];

        if ($kode !== '') {
            $chk = $this->db->prepare('SELECT id_bhp FROM bhp WHERE Kode_bhp = ? AND id_bhp != ?');
            $chk->execute([$kode, $id]);
            if ($chk->fetch()) return ['success' => false, 'message' => "Kode BHP \"$kode\" sudah digunakan."];
        }

        $stmt = $this->db->prepare(
            'UPDATE bhp SET Kode_bhp=?, Nama_bhp=?, Jumlah=?, Pemakaian=?, id_kategori=?, id_satuan=? WHERE id_bhp=?'
        );
        $stmt->execute([$kode ?: null, $nama, $jumlah, $pemakaian, $id_kat, $id_sat, $id]);
        return ['success' => true, 'message' => "BHP \"$nama\" berhasil diperbarui."];
    }

    /** Hapus BHP */
    public function deleteBhp(int $id): array
    {
        $stmt = $this->db->prepare('DELETE FROM bhp WHERE id_bhp = ?');
        $stmt->execute([$id]);
        return ['success' => true, 'message' => 'Data BHP berhasil dihapus.'];
    }
}
