<?php
namespace App\Classes;

use PDO;
use PDOException;
use Exception;
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
    public function getAllUsers(): array
    {
        $stmt = $this->db->query(
            'SELECT id_user, Nama_lengkap, Email, Role, Status_akun FROM user ORDER BY id_user ASC'
        );
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
     * Toggle status akun (aktif ↔ nonaktif)
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
    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM user')->fetchColumn();
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
}
