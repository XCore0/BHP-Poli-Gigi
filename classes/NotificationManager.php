<?php

namespace App\Classes;

use App\Config\Database;
use PDO;

/**
 * Class NotificationManager
 * Menyusun notifikasi role-based untuk header global.
 */
class NotificationManager
{
    private PDO $db;
    private BhpManager $bhpMgr;
    private PemakaianManager $pemakaianMgr;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->bhpMgr = new BhpManager();
        $this->pemakaianMgr = new PemakaianManager();
    }

    /**
     * Dapatkan notifikasi untuk header sesuai role user.
     */
    public function getHeaderNotifications(array $user, int $limit = 6): array
    {
        $role = strtolower($user['role'] ?? '');

        $items = [];
        $heading = 'Notifikasi';
        $emptyMessage = 'Belum ada notifikasi baru.';
        $ctaLabel = 'Lihat halaman';
        $ctaUrl = 'index.php';

        if ($role === 'admin') {
            $heading = 'Aktivitas Admin';
            $emptyMessage = 'Semua aktivitas admin sedang aman.';
            $ctaLabel = 'Buka Log Aktivitas';
            $ctaUrl = 'index.php?page=log';
            $items = array_merge(
                $this->buildLowStockItems('index.php?page=data_bhp'),
                $this->buildExpiringStockItems('index.php?page=stock'),
                $this->buildRecentAdminLogs('index.php?page=log', 5)
            );
        } elseif ($role === 'dokter') {
            $heading = 'Info Dokter';
            $emptyMessage = 'Tidak ada notifikasi khusus untuk dokter saat ini.';
            $ctaLabel = 'Buka Dashboard';
            $ctaUrl = 'index.php?page=dashboard';
            $items = array_merge(
                $this->buildLowStockItems('index.php?page=data_bhp'),
                $this->buildExpiringStockItems('index.php?page=laporan_stok'),
                $this->buildRecentPemakaianItems((int)($user['id'] ?? 0), 'index.php?page=catat', 4)
            );
        } elseif ($role === 'kepala_klinik') {
            $heading = 'Monitoring Klinik';
            $emptyMessage = 'Semua indikator klinik sedang dalam kondisi aman.';
            $ctaLabel = 'Buka Dashboard';
            $ctaUrl = 'index.php?page=dashboard';
            $items = $this->buildRecentPemakaianItems(0, 'index.php?page=laporan', 4, true);
        }

        $items = array_values(array_filter($items, static function ($item) {
            return is_array($item) && !empty($item['title']);
        }));

        usort($items, static function (array $a, array $b): int {
            $priorityA = (int)($a['priority'] ?? 0);
            $priorityB = (int)($b['priority'] ?? 0);
            if ($priorityA !== $priorityB) {
                return $priorityB <=> $priorityA;
            }
            return (int)($b['timestamp'] ?? 0) <=> (int)($a['timestamp'] ?? 0);
        });

        $total = count($items);

        foreach ($items as &$item) {
            $item['time_label'] = $this->formatTimeLabel((int)($item['timestamp'] ?? time()));
        }
        unset($item);

        return [
            'count' => $total,
            'items' => $items,
            'heading' => $heading,
            'empty_message' => $emptyMessage,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
        ];
    }

    private function buildLowStockItems(string $url): array
    {
        $items = [];
        $rows = $this->bhpMgr->getAllBhp();

        foreach ($rows as $index => $row) {
            $status = BhpManager::getStatusStok((int)($row['Jumlah'] ?? 0), (int)($row['Pemakaian'] ?? 0));
            if ((int)($status['level'] ?? 0) === 0) {
                continue;
            }

            $name = trim((string)($row['Nama_bhp'] ?? 'BHP'));
            $wadah = max(0, (int)($row['Jumlah'] ?? 0));
            $unit = max(0, (int)($row['Pemakaian'] ?? 0));

            $items[] = $this->makeItem(
                ($status['level'] ?? 0) === 2 ? 'Stok habis' : 'Stok menipis',
                sprintf('%s tersisa %d wadah dan %d unit pemakaian.', $name, $wadah, $unit),
                ($status['level'] ?? 0) === 2 ? 'fa-circle-exclamation' : 'fa-triangle-exclamation',
                ($status['level'] ?? 0) === 2 ? 'danger' : 'warning',
                $url,
                time() - $index,
                ($status['level'] ?? 0) === 2 ? 100 : 90,
                'stok'
            );
        }

        return $items;
    }

    private function buildExpiringStockItems(string $url): array
    {
        $items = [];
        $stmt = $this->db->prepare(
            "SELECT sm.*, b.Nama_bhp, s.Nama_satuan, u.Nama_lengkap AS nama_user
             FROM stok_masuk sm
             LEFT JOIN bhp b ON sm.id_bhp = b.id_bhp
             LEFT JOIN satuan_bhp s ON b.id_satuan = s.id_satuan
             LEFT JOIN user u ON sm.id_user = u.id_user
             WHERE sm.tgl_kadaluarsa IS NOT NULL
               AND sm.tgl_kadaluarsa <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
             ORDER BY sm.tgl_kadaluarsa ASC, sm.created_at DESC
             LIMIT 10"
        );
        $stmt->execute();

        foreach ($stmt->fetchAll() as $row) {
            $tgl = (string)($row['tgl_kadaluarsa'] ?? '');
            if ($tgl === '') {
                continue;
            }

            $expTs = strtotime($tgl . ' 23:59:59') ?: time();
            $daysLeft = (int)floor(($expTs - time()) / 86400);
            $name = trim((string)($row['Nama_bhp'] ?? 'BHP'));
            $unit = trim((string)($row['Nama_satuan'] ?? ''));
            $who = trim((string)($row['nama_user'] ?? 'Sistem'));

            if ($daysLeft < 0) {
                $title = 'BHP sudah kadaluarsa';
                $tone = 'danger';
                $icon = 'fa-solid fa-calendar-xmark';
                $priority = 99;
                $message = sprintf('%s sudah lewat tanggal kedaluarsa pada %s.', $name, date('d M Y', $expTs));
            } elseif ($daysLeft <= 7) {
                $title = 'BHP segera kadaluarsa';
                $tone = 'warning';
                $icon = 'fa-solid fa-calendar-day';
                $priority = 94;
                $message = sprintf('%s akan kadaluarsa dalam %d hari lagi.', $name, max(0, $daysLeft));
            } else {
                $title = 'BHP mendekati kadaluarsa';
                $tone = 'info';
                $icon = 'fa-solid fa-clock';
                $priority = 84;
                $message = sprintf('%s kadaluarsa pada %s.', $name, date('d M Y', $expTs));
            }

            if ($unit !== '') {
                $message .= ' Satuan: ' . $unit . '.';
            }
            if ($who !== '') {
                $message .= ' Dicatat oleh ' . $who . '.';
            }

            $items[] = $this->makeItem(
                $title,
                $this->truncate($message, 110),
                $icon,
                $tone,
                $url,
                $expTs,
                $priority,
                'stok'
            );
        }

        return $items;
    }

    private function buildRecentAdminLogs(string $url, int $limit = 5): array
    {
        $items = [];
        $stmt = $this->db->prepare(
            "SELECT * FROM log_aktivitas
             WHERE kategori IN ('pengguna', 'bhp', 'stok')
             ORDER BY waktu DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($stmt->fetchAll() as $row) {
            $kategori = (string)($row['kategori'] ?? 'sistem');
            $aksi = trim((string)($row['aksi'] ?? 'aktivitas baru'));
            $detail = trim((string)($row['detail'] ?? ''));
            $targetUrl = $kategori === 'pengguna'
                ? 'index.php?page=pengguna'
                : ($kategori === 'stok' ? 'index.php?page=stock' : 'index.php?page=data_bhp');

            $items[] = $this->makeItem(
                ucwords(str_replace('_', ' ', $aksi)),
                $this->truncate($detail !== '' ? $detail : 'Ada aktivitas baru pada sistem.', 110),
                $this->iconForKategori($kategori),
                $this->toneForKategori($kategori),
                $targetUrl,
                strtotime((string)($row['waktu'] ?? 'now')) ?: time(),
                $kategori === 'pengguna' ? 72 : 70,
                $kategori
            );
        }

        return $items;
    }

    private function buildRecentPemakaianItems(int $userId, string $url, int $limit = 4, bool $allUsers = false): array
    {
        $items = [];
        $rows = $allUsers
            ? $this->pemakaianMgr->getAllPemakaian(['limit' => $limit])
            : $this->pemakaianMgr->getAllPemakaian(['id_user' => $userId, 'limit' => $limit]);

        foreach ($rows as $row) {
            $jumlahItem = (int)($row['jumlah_item'] ?? 0);
            $pasien = trim((string)($row['nama_pasien'] ?? 'pasien'));
            $dokter = trim((string)($row['nama_dokter'] ?? 'Dokter'));
            $tindakan = trim((string)($row['unit_tindakan'] ?? 'tindakan'));

            $title = $allUsers ? 'Pemakaian terbaru klinik' : 'Pemakaian Anda dicatat';
            $message = $allUsers
                ? sprintf('%s menangani %s dengan %d item BHP.', $dokter, $pasien, $jumlahItem)
                : sprintf('%s mencatat %d item untuk %s.', $tindakan, $jumlahItem, $pasien);

            $items[] = $this->makeItem(
                $title,
                $this->truncate($message, 110),
                'fa-clipboard-check',
                'success',
                $url,
                strtotime((string)($row['created_at'] ?? $row['tanggal'] ?? 'now')) ?: time(),
                $allUsers ? 60 : 58,
                'pemakaian'
            );
        }

        return $items;
    }

    private function buildRecentStokMasukItems(string $url, int $limit = 3): array
    {
        $items = [];
        $rows = (new StokMasukManager())->getAllStokMasuk(['limit' => $limit]);

        foreach ($rows as $row) {
            $name = trim((string)($row['Nama_bhp'] ?? 'BHP'));
            $supplier = trim((string)($row['supplier'] ?? ''));
            $user = trim((string)($row['nama_user'] ?? 'Sistem'));
            $jumlah = (int)($row['jumlah'] ?? 0);
            $isiPerStok = max(1, (int)($row['isi_per_stok'] ?? 1));

            $message = sprintf('%s masuk %d wadah (%d unit pemakaian).', $name, $jumlah, $jumlah * $isiPerStok);
            if ($supplier !== '') {
                $message .= ' Supplier: ' . $supplier . '.';
            }
            if ($user !== '') {
                $message .= ' Input oleh ' . $user . '.';
            }

            $items[] = $this->makeItem(
                'Stok baru diterima',
                $this->truncate($message, 110),
                'fa-boxes-stacked',
                'info',
                $url,
                strtotime((string)($row['created_at'] ?? 'now')) ?: time(),
                52,
                'stok'
            );
        }

        return $items;
    }

    private function makeItem(
        string $title,
        string $message,
        string $icon,
        string $tone,
        string $url,
        int $timestamp,
        int $priority,
        string $kind
    ): array {
        return [
            'id' => md5($title . $message),
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'tone' => $tone,
            'url' => $url,
            'timestamp' => $timestamp,
            'priority' => $priority,
            'kind' => $kind,
        ];
    }

    private function iconForKategori(string $kategori): string
    {
        return match ($kategori) {
            'pengguna' => 'fa-users',
            'bhp'      => 'fa-boxes-stacked',
            'stok'     => 'fa-truck-ramp-box',
            default    => 'fa-bell',
        };
    }

    private function toneForKategori(string $kategori): string
    {
        return match ($kategori) {
            'pengguna' => 'info',
            'bhp'      => 'success',
            'stok'     => 'warning',
            default    => 'neutral',
        };
    }

    private function truncate(string $text, int $limit): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        if (function_exists('mb_strlen') && mb_strlen($text) > $limit) {
            return rtrim(mb_substr($text, 0, $limit - 1)) . '…';
        }

        if (strlen($text) > $limit) {
            return rtrim(substr($text, 0, $limit - 1)) . '…';
        }

        return $text;
    }

    private function formatTimeLabel(int $timestamp): string
    {
        $diff = time() - $timestamp;
        if ($diff < 60) {
            return 'Baru saja';
        }
        if ($diff < 3600) {
            return floor($diff / 60) . ' menit lalu';
        }
        if ($diff < 86400) {
            return floor($diff / 3600) . ' jam lalu';
        }

        return date('d M Y', $timestamp);
    }
}