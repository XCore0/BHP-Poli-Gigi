<?php
namespace App\Classes;

use PDO;
use PDOException;
use Exception;
use App\Config\Database;

/**
 * Class Auth
 * Menangani autentikasi pengguna: login, logout, proteksi halaman
 * Menggunakan password_hash() dan password_verify() untuk keamanan
 */
class Auth
{
    private PDO $db;
    private string $sessionKey = 'poli_user';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Proses login: verifikasi email & password.
     * Menggunakan password_verify() untuk membandingkan hash.
     *
     * @param string $email
     * @param string $password Password plaintext dari form
     * @return array ['success' => bool, 'message' => string]
     */
    public function login(string $email, string $password): array
    {
        $email = trim($email);

        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email dan password tidak boleh kosong.'];
        }

        // Cari user berdasarkan email
        $stmt = $this->db->prepare(
            'SELECT id_user, Nama_lengkap, Email, Password, Role, Status_akun
             FROM user WHERE Email = ? LIMIT 1'
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'Email atau password salah.'];
        }

        if ($user['Status_akun'] !== 'aktif') {
            return ['success' => false, 'message' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.'];
        }

        // Verifikasi password menggunakan password_verify() (bcrypt)
        if (!password_verify($password, $user['Password'])) {
            return ['success' => false, 'message' => 'Email atau password salah.'];
        }

        session_regenerate_id(true);

        // Simpan data user ke session (tanpa password)
        $_SESSION[$this->sessionKey] = [
            'id'     => $user['id_user'],
            'nama'   => $user['Nama_lengkap'],
            'email'  => $user['Email'],
            'role'   => $user['Role'],
            'status' => $user['Status_akun'],
        ];

        // Catat log login
        (new ActivityLog())->logLogin(
            (int) $user['id_user'],
            $user['Nama_lengkap'],
            $user['Role']
        );

        return ['success' => true, 'message' => 'Login berhasil.', 'role' => $user['Role']];
    }

    /**
     * Logout: hapus session pengguna
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Catat log logout sebelum session dihapus
        $user = $this->getCurrentUser();
        if ($user) {
            (new ActivityLog())->logLogout(
                (int) $user['id'],
                $user['nama'],
                $user['role']
            );
        }
        unset($_SESSION[$this->sessionKey]);
        session_destroy();
    }

    /**
     * Cek apakah pengguna sudah login
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION[$this->sessionKey]);
    }

    /**
     * Dapatkan data user yang sedang login
     */
    public function getCurrentUser(): ?array
    {
        return $_SESSION[$this->sessionKey] ?? null;
    }

    /**
     * Paksa login â€” redirect ke Login.php jika belum masuk
     *
     * @param string $loginUrl Path ke halaman login
     */
    public function requireLogin(string $loginUrl = '/BHP-Poli-Gigi/Login.php'): void
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header("Location: $loginUrl");
            exit();
        }
    }

    /**
     * Paksa role tertentu â€” redirect jika role tidak sesuai
     *
     * @param string|array $roles Role yang diizinkan
     * @param string $redirectUrl URL redirect jika ditolak
     */
    public function requireRole($roles, string $redirectUrl = '/BHP-Poli-Gigi/Login.php'): void
    {
        $this->requireLogin($redirectUrl);
        $user = $this->getCurrentUser();
        $allowedRoles = (array) $roles;

        if (!in_array($user['role'], $allowedRoles)) {
            header("Location: $redirectUrl");
            exit();
        }
    }

    /**
     * Ambil role user aktif
     */
    public function getRole(): ?string
    {
        $user = $this->getCurrentUser();
        return $user['role'] ?? null;
    }

    /**
     * Ambil nama user aktif
     */
    public function getNama(): ?string
    {
        $user = $this->getCurrentUser();
        return $user['nama'] ?? null;
    }
}
