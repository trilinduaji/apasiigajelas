<?php
/**
 * SIPEDO - User Model (MySQL/PDO)
 * Disesuaikan dengan schema: users tidak punya kolom 'status'
 */
class UserModel {

    public static function findByEmail(string $email): ?array {
        return DB::fetchOne('SELECT * FROM users WHERE email = ? LIMIT 1', [$email]);
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM users WHERE id = ? LIMIT 1', [$id]);
    }

    public static function all(): array {
        return DB::fetchAll('SELECT * FROM users ORDER BY created_at DESC');
    }

    public static function authenticate(string $email, string $password, string $role): ?array {
        $user = self::findByEmail($email);
        if (!$user) return null;
        if ($user['role'] !== $role) return null;
        if (!password_verify($password, $user['password'])) return null;
        return $user;
    }

    public static function verifyPassword(int $userId, string $password): bool {
        $hash = DB::fetchScalar('SELECT password FROM users WHERE id = ?', [$userId]);
        return $hash && password_verify($password, (string)$hash);
    }

    public static function create(string $name, string $email, string $password, string $role): int {
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

    public static function changePassword(int $userId, string $newPassword): void {
        DB::run('UPDATE users SET password=? WHERE id=?', [password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
    }
}
