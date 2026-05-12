<?php
/**
 * SIPEDO - Activity Log Model (MySQL/PDO)
 * Sesuai dengan tabel `activity_logs` di query.sql
 * Kolom: id, user_id, actor_name, role, description, ref, created_at
 */
class ActivityLogModel {

    /**
     * Ambil semua log aktivitas
     */
    public static function all(): array {
        return DB::fetchAll(
            'SELECT al.*, u.name AS user_name
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC'
        );
    }

    /**
     * Ambil log aktivitas terbaru
     */
    public static function recent(int $limit = 10): array {
        return DB::fetchAll(
            'SELECT al.*, u.name AS user_name
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC
             LIMIT ?',
            [$limit]
        );
    }

    /**
     * Ambil log berdasarkan user_id
     */
    public static function byUserId(int $userId): array {
        return DB::fetchAll(
            'SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC',
            [$userId]
        );
    }

    /**
     * Tambah log aktivitas baru
     */
    public static function create(?int $userId, string $actorName, string $role, string $description, string $ref = ''): int {
        DB::run(
            'INSERT INTO activity_logs (user_id, actor_name, role, description, ref)
             VALUES (?, ?, ?, ?, ?)',
            [$userId, $actorName, $role, $description, $ref]
        );
        return (int) DB::lastInsertId();
    }

    /**
     * Cari log berdasarkan referensi
     */
    public static function findByRef(string $ref): array {
        return DB::fetchAll(
            'SELECT * FROM activity_logs WHERE ref = ? ORDER BY created_at DESC',
            [$ref]
        );
    }

    /**
     * Hapus log lama (lebih dari X hari)
     */
    public static function deleteOlderThan(int $days): int {
        $stmt = DB::run(
            'DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)',
            [$days]
        );
        return $stmt->rowCount();
    }

    /**
     * Hitung total log
     */
    public static function count(): int {
        return (int) DB::fetchScalar('SELECT COUNT(*) FROM activity_logs');
    }
}
