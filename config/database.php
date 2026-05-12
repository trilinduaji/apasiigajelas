<?php
/**
 * SIPEDO - Database Connection (PDO)
 * Singleton pattern agar koneksi hanya dibuat sekali
 */
class DB {
    private static ?PDO $pdo = null;

    // ── Konfigurasi ──────────────────────────────────────
    private static string $host     = '127.0.0.1';
    private static string $port     = '3306';
    private static string $dbname   = 'sipedo_db';
    private static string $username = 'root';
    private static string $password = '';          // ganti sesuai env Anda
    private static string $charset  = 'utf8mb4';

    /**
     * Ambil instance PDO (lazy init).
     */
    public static function pdo(): PDO {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                self::$host,
                self::$port,
                self::$dbname,
                self::$charset
            );

            self::$pdo = new PDO($dsn, self::$username, self::$password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }

    /**
     * Shortcut: execute query + bind params → return PDOStatement
     */
    public static function run(string $sql, array $params = []): \PDOStatement {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Ambil semua baris
     */
    public static function fetchAll(string $sql, array $params = []): array {
        return self::run($sql, $params)->fetchAll();
    }

    /**
     * Ambil satu baris
     */
    public static function fetchOne(string $sql, array $params = []): ?array {
        $row = self::run($sql, $params)->fetch();
        return $row ?: null;
    }

    /**
     * Ambil nilai scalar (kolom pertama baris pertama)
     */
    public static function fetchScalar(string $sql, array $params = []): mixed {
        return self::run($sql, $params)->fetchColumn();
    }

    /**
     * Ambil ID terakhir yang di-insert
     */
    public static function lastInsertId(): string {
        return self::pdo()->lastInsertId();
    }
}
