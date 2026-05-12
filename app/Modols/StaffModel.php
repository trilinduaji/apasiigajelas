<?php
/**
 * SIPEDO - Staff Model (MySQL/PDO)
 */
class StaffModel {

    public static function all(): array {
        return DB::fetchAll(
            'SELECT u.id, u.name, u.email, u.status,
                    sp.kode, sp.job_role AS role, sp.joined_at AS since
             FROM users u
             JOIN staff_profiles sp ON sp.user_id = u.id
             ORDER BY sp.joined_at DESC'
        );
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne(
            'SELECT u.*, sp.kode, sp.job_role AS role, sp.joined_at AS since
             FROM users u
             JOIN staff_profiles sp ON sp.user_id = u.id
             WHERE u.id = ? LIMIT 1',
            [$id]
        );
    }

    /**
     * Buat user dengan role 'staff' + buat staff_profile.
     */
    public static function create(string $name, string $email): int {
        // Cek apakah email sudah ada
        $existing = UserModel::findByEmail($email);
        if ($existing) return (int)$existing['id'];

        $userId = UserModel::create($name, $email, 'staff123', 'staff');

        $count = (int) DB::fetchScalar('SELECT COUNT(*) FROM staff_profiles');
        $kode  = 'STF-' . str_pad((string)($count + 1), 2, '0', STR_PAD_LEFT);

        DB::run(
            "INSERT INTO staff_profiles (user_id, kode, job_role, joined_at)
             VALUES (?, ?, 'Staff Verifikasi', CURDATE())",
            [$userId, $kode]
        );
        return $userId;
    }

    public static function setStatus(int $userId, string $status): ?string {
        $user = UserModel::findById($userId);
        if (!$user) return null;
        UserModel::setStatus($userId, $status);
        return $user['name'];
    }

    public static function delete(int $userId): ?string {
        $user = UserModel::findById($userId);
        if (!$user) return null;
        // Hapus staff_profile dulu (CASCADE sudah handle, tapi eksplisit lebih aman)
        DB::run('DELETE FROM staff_profiles WHERE user_id = ?', [$userId]);
        DB::run('DELETE FROM users WHERE id = ?', [$userId]);
        return $user['name'];
    }
}
