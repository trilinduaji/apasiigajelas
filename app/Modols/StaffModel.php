<?php
/**
 * SIPEDO - Staff Model (MySQL/PDO)
 * Disesuaikan dengan schema: staff_profiles.jabatan (bukan job_role),
 * staff_profiles.status untuk aktif/nonaktif (bukan users.status).
 */
class StaffModel {

    public static function all(): array {
        return DB::fetchAll(
            'SELECT u.id, u.name, u.email, u.initials, u.color, u.photo,
                    sp.kode, sp.jabatan, sp.joined_at, sp.status
             FROM users u
             JOIN staff_profiles sp ON sp.user_id = u.id
             ORDER BY sp.joined_at DESC'
        );
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne(
            'SELECT u.*, sp.kode, sp.jabatan, sp.joined_at, sp.status AS staff_status
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
        $existing = UserModel::findByEmail($email);
        if ($existing) return (int)$existing['id'];

        $userId = UserModel::create($name, $email, 'staff123', 'staff');

        $count = (int) DB::fetchScalar('SELECT COUNT(*) FROM staff_profiles');
        $kode  = 'STF-' . str_pad((string)($count + 1), 2, '0', STR_PAD_LEFT);

        DB::run(
            "INSERT INTO staff_profiles (user_id, kode, jabatan, joined_at, status)
             VALUES (?, ?, 'Staff Verifikasi', CURDATE(), 'active')",
            [$userId, $kode]
        );
        return $userId;
    }

    /**
     * Ubah status aktif/nonaktif staff via staff_profiles.status
     */
    public static function setStatus(int $userId, string $status): ?string {
        $user = UserModel::findById($userId);
        if (!$user) return null;
        DB::run('UPDATE staff_profiles SET status=? WHERE user_id=?', [$status, $userId]);
        return $user['name'];
    }

    public static function delete(int $userId): ?string {
        $user = UserModel::findById($userId);
        if (!$user) return null;
        // staff_profiles akan terhapus via ON DELETE CASCADE
        DB::run('DELETE FROM users WHERE id = ?', [$userId]);
        return $user['name'];
    }
}
