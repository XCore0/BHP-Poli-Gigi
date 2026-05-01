<?php

namespace App\Classes;

use PDO;
use PDOException;
use App\Config\Database;

/**
 * Class UserManager
 * Menangani operasi CRUD pengguna sistem (OOP)
 * Password di-hash menggunakan password_hash() dengan algoritma BCRYPT
 */
class UserManager
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ambil semua pengguna dari database
     *
     * @return array
     */
    public function getAllUsers(array $filter = []): array
    {
        $where  = [];
        $params = [];
        if (!empty($filter['keyword'])) {
            $where[]  = '(Nama_lengkap LIKE ? OR Email LIKE ?)';
            $kw       = '%' . $filter['keyword'] . '%';
            $params[] = $kw;
            $params[] = $kw;
        }
        if (!empty($filter['role'])) {
            $where[]  = 'Role = ?';
            $params[] = $filter['role'];
        }

        $sql = 'SELECT id_user, Nama_lengkap, Email, Role, Status_akun FROM user';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY id_user DESC';
        
        if (isset($filter['limit'])) {
            $sql .= ' LIMIT ' . (int)$filter['limit'];
            if (isset($filter['offset'])) {
                $sql .= ' OFFSET ' . (int)$filter['offset'];
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Tambah pengguna baru
     * Password di-hash dengan PASSWORD_BCRYPT sebelum disimpan
     *
     * @param array $data ['nama', 'email', 'password', 'role']
     * @return array ['success' => bool, 'message' => string]
     */
    public function addUser(array $data): array
    {
        $nama     = trim($data['nama'] ?? '');
        $email    = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $role     = $data['role'] ?? '';

        // Validasi input
        if (empty($nama) || empty($email) || empty($password) || empty($role)) {
            return ['success' => false, 'message' => 'Semua field wajib diisi.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Format email tidak valid.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password minimal 6 karakter.'];
        }

        $allowedRoles = ['admin', 'dokter', 'kepala_klinik'];
        if (!in_array($role, $allowedRoles)) {
            return ['success' => false, 'message' => 'Role tidak valid.'];
        }

        // Cek duplikasi email
        $cek = $this->db->prepare('SELECT id_user FROM user WHERE Email = ? LIMIT 1');
        $cek->execute([$email]);
        if ($cek->fetch()) {
            return ['success' => false, 'message' => 'Email sudah terdaftar.'];
        }

        // Hash password menggunakan password_hash() dengan BCRYPT
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare(
            'INSERT INTO user (Nama_lengkap, Email, Password, Role, Status_akun)
             VALUES (?, ?, ?, ?, "aktif")'
        );
        $stmt->execute([$nama, $email, $hashedPassword, $role]);

        return ['success' => true, 'message' => 'Pengguna berhasil ditambahkan.'];
    }

    /**
     * Hapus pengguna berdasarkan ID
     *
     * @param int $id
     * @return array
     */
    public function deleteUser(int $id): array
    {
        $stmt = $this->db->prepare('DELETE FROM user WHERE id_user = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Pengguna berhasil dihapus.'];
        }
        return ['success' => false, 'message' => 'Pengguna tidak ditemukan.'];
    }

    /**
     * Toggle status akun (aktif â†” nonaktif)
     *
     * @param int $id
     * @return array
     */
    public function toggleStatus(int $id): array
    {
        // Ambil status saat ini
        $stmt = $this->db->prepare('SELECT Status_akun FROM user WHERE id_user = ? LIMIT 1');
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'Pengguna tidak ditemukan.'];
        }

        $newStatus = ($user['Status_akun'] === 'aktif') ? 'nonaktif' : 'aktif';
        $update = $this->db->prepare('UPDATE user SET Status_akun = ? WHERE id_user = ?');
        $update->execute([$newStatus, $id]);

        return [
            'success'    => true,
            'message'    => "Status berhasil diubah ke $newStatus.",
            'new_status' => $newStatus,
        ];
    }

    /**
     * Hitung jumlah pengguna berdasarkan role
     *
     * @param string $role
     * @return int
     */
    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM user WHERE Role = ?');
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Hitung total pengguna
     *
     * @return int
     */
    public function countAll(array $filter = []): int
    {
        $where  = [];
        $params = [];
        if (!empty($filter['keyword'])) {
            $where[]  = '(Nama_lengkap LIKE ? OR Email LIKE ?)';
            $kw       = '%' . $filter['keyword'] . '%';
            $params[] = $kw;
            $params[] = $kw;
        }
        if (!empty($filter['role'])) {
            $where[]  = 'Role = ?';
            $params[] = $filter['role'];
        }

        $sql = 'SELECT COUNT(*) FROM user';
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Ubah password pengguna (dengan hash baru)
     *
     * @param int $id
     * @param string $newPassword Password plaintext baru
     * @return array
     */
    public function changePassword(int $id, string $newPassword): array
    {
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'Password minimal 6 karakter.'];
        }

        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('UPDATE user SET Password = ? WHERE id_user = ?');
        $stmt->execute([$hashed, $id]);

        return ['success' => true, 'message' => 'Password berhasil diubah.'];
    }

    /**
     * Ambil data profil lengkap satu user
     */
    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id_user, Nama_lengkap, Email, Role, Status_akun,
                    No_telp, Jenis_kelamin, Tanggal_bergabung, Foto
             FROM user WHERE id_user = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Update profil user (nama, email, no_telp, jenis_kelamin)
     */
    public function updateProfil(int $id, array $data): array
    {
        $nama   = trim($data['nama']          ?? '');
        $email  = trim($data['email']         ?? '');
        $telp   = trim($data['no_telp']       ?? '');
        $gender = trim($data['jenis_kelamin'] ?? '');

        if ($nama === '')  return ['success' => false, 'message' => 'Nama tidak boleh kosong.'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['success' => false, 'message' => 'Format email tidak valid.'];

        // Cek email duplikat (kecuali milik user sendiri)
        $chk = $this->db->prepare('SELECT id_user FROM user WHERE Email = ? AND id_user != ?');
        $chk->execute([$email, $id]);
        if ($chk->fetch()) return ['success' => false, 'message' => 'Email sudah digunakan oleh akun lain.'];

        $allowedGender = ['Laki-laki', 'Perempuan', ''];
        if (!in_array($gender, $allowedGender)) $gender = '';

        $stmt = $this->db->prepare(
            'UPDATE user SET Nama_lengkap=?, Email=?, No_telp=?, Jenis_kelamin=? WHERE id_user=?'
        );
        $stmt->execute([$nama, $email, $telp ?: null, $gender ?: null, $id]);

        return ['success' => true, 'message' => 'Profil berhasil diperbarui.'];
    }

    /**
     * Upload / ganti foto profil
     * @param int   $id       ID user
     * @param array $file     $_FILES['foto'] element
     * @param string $uploadDir Absolute path direktori upload
     */
    public function uploadFoto(int $id, array $file, string $uploadDir): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Gagal mengunggah file.'];
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo       = finfo_open(FILEINFO_MIME_TYPE);
        $mime        = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMime)) {
            return ['success' => false, 'message' => 'Format foto tidak valid. Gunakan JPG, PNG, atau WebP.'];
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            return ['success' => false, 'message' => 'Ukuran foto maksimal 2MB.'];
        }

        $ext      = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'][$mime];
        $filename = 'user_' . $id . '_' . time() . '.' . $ext;
        $destPath = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            return ['success' => false, 'message' => 'Gagal menyimpan file ke server.'];
        }

        // Hapus foto lama
        $old = $this->db->prepare('SELECT Foto FROM user WHERE id_user = ?');
        $old->execute([$id]);
        $oldFoto = $old->fetchColumn();
        if ($oldFoto && file_exists(rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . basename($oldFoto))) {
            @unlink(rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . basename($oldFoto));
        }

        $webPath = '/BHP-Poli-Gigi/assets/uploads/foto_profil/' . $filename;
        $upd = $this->db->prepare('UPDATE user SET Foto = ? WHERE id_user = ?');
        $upd->execute([$webPath, $id]);

        return ['success' => true, 'message' => 'Foto profil berhasil diperbarui.', 'foto_url' => $webPath];
    }

    /**
     * Ganti password dengan verifikasi password lama
     */
    public function gantiPassword(int $id, string $passLama, string $passBaru, string $konfirmasi): array
    {
        if ($passBaru !== $konfirmasi) return ['success' => false, 'message' => 'Konfirmasi kata sandi tidak cocok.'];
        if (strlen($passBaru) < 8)    return ['success' => false, 'message' => 'Kata sandi baru minimal 8 karakter.'];

        $stmt = $this->db->prepare('SELECT Password FROM user WHERE id_user = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) return ['success' => false, 'message' => 'User tidak ditemukan.'];

        if (!password_verify($passLama, $row['Password'])) {
            return ['success' => false, 'message' => 'Kata sandi saat ini tidak sesuai.'];
        }

        $hashed = password_hash($passBaru, PASSWORD_BCRYPT);
        $upd = $this->db->prepare('UPDATE user SET Password=? WHERE id_user=?');
        $upd->execute([$hashed, $id]);

        return ['success' => true, 'message' => 'Kata sandi berhasil diperbarui.'];
    }

    /**
     * Ambil preferensi notifikasi user
     */
    public function getPreferensi(int $id): array
    {
        $stmt = $this->db->prepare('SELECT * FROM user_preferensi WHERE id_user = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            // Return default
            return ['notif_stok_kurang' => 1, 'notif_laporan_harian' => 0];
        }
        return $row;
    }

    /**
     * Simpan preferensi notifikasi (upsert)
     */
    public function savePreferensi(int $id, array $data): array
    {
        $stok   = isset($data['notif_stok_kurang'])    ? 1 : 0;
        $harian = isset($data['notif_laporan_harian'])  ? 1 : 0;

        $stmt = $this->db->prepare(
            'INSERT INTO user_preferensi (id_user, notif_stok_kurang, notif_laporan_harian)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE
               notif_stok_kurang    = VALUES(notif_stok_kurang),
               notif_laporan_harian = VALUES(notif_laporan_harian)'
        );
        $stmt->execute([$id, $stok, $harian]);

        return ['success' => true, 'message' => 'Preferensi berhasil disimpan.'];
    }
}

