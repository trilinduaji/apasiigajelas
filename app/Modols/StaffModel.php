<?php
/**
 * SIPEDO - Staff Model (MySQL/PDO)
 * Sesuai dengan tabel `staff_profiles` di query.sql
 * Kolom staff_profiles: id, user_id, kode, jabatan, joined_at, status
 */
class StaffModel {

    /**
     * Ambil semua staff dengan data user
     */
    public static function all(): array {
        return DB::fetchAll(
            'SELECT u.id, u.name, u.email, u.initials, u.color, u.photo,
                    sp.kode, sp.jabatan, sp.joined_at, sp.status
             FROM users u
             JOIN staff_profiles sp ON sp.user_id = u.id
             WHERE u.role = \'staff\'
             ORDER BY sp.joined_at DESC'
        );
    }

    /**
     * Ambil semua staff aktif
     */
    public static function active(): array {
        return DB::fetchAll(
            "SELECT u.id, u.name, u.email, u.initials, u.color, u.photo,
                    sp.kode, sp.jabatan, sp.joined_at, sp.status
             FROM users u
             JOIN staff_profiles sp ON sp.user_id = u.id
             WHERE u.role = 'staff' AND sp.status = 'active'
             ORDER BY sp.joined_at DESC"
        );
    }

    /**
     * Cari staff berdasarkan user ID
     */
    public static function findById(int $userId): ?array {
        return DB::fetchOne(
            'SELECT u.*, sp.kode, sp.jabatan, sp.joined_at, sp.status AS staff_status
             FROM users u
             JOIN staff_profiles sp ON sp.user_id = u.id
             WHERE u.id = ? AND u.role = \'staff\' LIMIT 1',
            [$userId]
        );
    }

    /**
     * Cari staff berdasarkan kode staff
     */
    public static function findByKode(string $kode): ?array {
        return DB::fetchOne(
            'SELECT u.*, sp.kode, sp.jabatan, sp.joined_at, sp.status AS staff_status
             FROM users u
             JOIN staff_profiles sp ON sp.user_id = u.id
             WHERE sp.kode = ? LIMIT 1',
            [$kode]
        );
    }

    /**
     * Buat user dengan role 'staff' + buat staff_profile
     */
    public static function create(string $name, string $email, string $jabatan = 'Staff Verifikasi'): int {
        // Cek apakah email sudah ada
        $existing = UserModel::findByEmail($email);
        if ($existing) return (int)$existing['id'];

        // Buat user dengan role staff
        $userId = UserModel::create($name, $email, 'staff123', 'staff');

        // Generate kode staff
        $count = (int) DB::fetchScalar('SELECT COUNT(*) FROM staff_profiles');
        $kode  = 'STF-' . str_pad((string)($count + 1), 2, '0', STR_PAD_LEFT);

        // Insert ke staff_profiles
        DB::run(
            "INSERT INTO staff_profiles (user_id, kode, jabatan, joined_at, status)
             VALUES (?, ?, ?, CURDATE(), 'active')",
            [$userId, $kode, $jabatan]
        );

        return $userId;
    }

    /**
     * Update jabatan staff
     */
    public static function updateJabatan(int $userId, string $jabatan): bool {
        DB::run('UPDATE staff_profiles SET jabatan = ? WHERE user_id = ?', [$jabatan, $userId]);
        return true;
    }

    /**
     * Set status staff (active/inactive)
     */
    public static function setStatus(int $userId, string $status): ?string {
        $user = UserModel::findById($userId);
        if (!$user) return null;
        
        DB::run('UPDATE staff_profiles SET status = ? WHERE user_id = ?', [$status, $userId]);
        return $user['name'];
    }

    /**
     * Hapus staff (hapus user dan staff_profile)
     */
    public static function delete(int $userId): ?string {
        $user = UserModel::findById($userId);
        if (!$user) return null;

        // Staff_profile akan terhapus CASCADE dari users
        DB::run('DELETE FROM users WHERE id = ?', [$userId]);
        return $user['name'];
    }

    /**
     * Hitung total staff aktif
     */
    public static function countActive(): int {
        return (int) DB::fetchScalar("SELECT COUNT(*) FROM staff_profiles WHERE status = 'active'");
    }
}
