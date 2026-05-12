<?php
/**
 * SIPEDO - User Model (MySQL/PDO)
 * Sesuai dengan tabel `users` di query.sql
 */
class UserModel {

    /**
     * Cari user berdasarkan email
     */
    public static function findByEmail(string $email): ?array {
        return DB::fetchOne('SELECT * FROM users WHERE email = ? LIMIT 1', [$email]);
    }

    /**
     * Cari user berdasarkan ID
     */
    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM users WHERE id = ? LIMIT 1', [$id]);
    }

    /**
     * Ambil semua user
     */
    public static function all(): array {
        return DB::fetchAll('SELECT * FROM users ORDER BY created_at DESC');
    }

    /**
     * Ambil semua user berdasarkan role
     */
    public static function byRole(string $role): array {
        return DB::fetchAll('SELECT * FROM users WHERE role = ? ORDER BY created_at DESC', [$role]);
    }

    /**
     * Autentikasi user - tanpa pengecekan status (tidak ada di skema baru)
     */
    public static function authenticate(string $email, string $password, string $role): ?array {
        $user = self::findByEmail($email);
        if (!$user) return null;
        if ($user['role'] !== $role) return null;
        if (!password_verify($password, $user['password'])) return null;
        return $user;
    }

    /**
     * Verifikasi password user
     */
    public static function verifyPassword(int $userId, string $password): bool {
        $hash = DB::fetchScalar('SELECT password FROM users WHERE id = ?', [$userId]);
        return $hash && password_verify($password, (string)$hash);
    }

    /**
     * Buat user baru
     * Kolom: id, name, email, password, role, initials, color, photo, created_at, updated_at
     */
    public static function create(string $name, string $email, string $password, string $role = 'donatur'): int {
        $parts    = preg_split('/\s+/', trim($name));
        $initials = strtoupper(substr($parts[0] ?? 'U', 0, 1) . substr($parts[1] ?? '', 0, 1));
        $color    = match($role) { 'admin' => '#2563eb', 'staff' => '#d97706', default => '#059669' };

        DB::run(
            "INSERT INTO users (name, email, password, role, initials, color, photo)
             VALUES (?, ?, ?, ?, ?, ?, '')",
            [$name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $initials, $color]
        );
        return (int) DB::lastInsertId();
    }

    /**
     * Update profil user (nama dan foto)
     */
    public static function updateProfile(int $userId, string $name, string $photoRel = ''): void {
        $parts    = preg_split('/\s+/', trim($name));
        $initials = strtoupper(substr($parts[0] ?? 'U', 0, 1) . substr($parts[1] ?? '', 0, 1));

        if ($photoRel !== '') {
            DB::run('UPDATE users SET name=?, initials=?, photo=? WHERE id=?', [$name, $initials, $photoRel, $userId]);
        } else {
            DB::run('UPDATE users SET name=?, initials=? WHERE id=?', [$name, $initials, $userId]);
        }

        if (isset($_SESSION['currentUser']) && (int)($_SESSION['currentUser']['id'] ?? 0) === $userId) {
            $_SESSION['currentUser']['name']     = $name;
            $_SESSION['currentUser']['initials'] = $initials;
            if ($photoRel !== '') $_SESSION['currentUser']['photo'] = $photoRel;
        }
    }

    /**
     * Ganti password user
     */
    public static function changePassword(int $userId, string $newPassword): void {
        DB::run('UPDATE users SET password=? WHERE id=?', [password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
    }

    /**
     * Update warna avatar user
     */
    public static function updateColor(int $userId, string $color): void {
        DB::run('UPDATE users SET color=? WHERE id=?', [$color, $userId]);
    }

    /**
     * Hapus user berdasarkan ID
     */
    public static function delete(int $userId): bool {
        DB::run('DELETE FROM users WHERE id = ?', [$userId]);
        return true;
    }

    /**
     * Hitung total user berdasarkan role
     */
    public static function countByRole(string $role): int {
        return (int) DB::fetchScalar('SELECT COUNT(*) FROM users WHERE role = ?', [$role]);
    }
}
