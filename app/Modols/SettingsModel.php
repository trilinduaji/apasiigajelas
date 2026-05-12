<?php
/**
 * SIPEDO - Settings Model (MySQL/PDO)
 * Sesuai dengan tabel `settings` di query.sql
 * Kolom: key, value, description, updated_at
 */
class SettingsModel {

    /**
     * Ambil semua settings
     */
    public static function all(): array {
        return DB::fetchAll('SELECT * FROM settings ORDER BY `key`');
    }

    /**
     * Ambil nilai setting berdasarkan key
     */
    public static function get(string $key, ?string $default = null): ?string {
        $value = DB::fetchScalar('SELECT value FROM settings WHERE `key` = ? LIMIT 1', [$key]);
        return $value !== false ? $value : $default;
    }

    /**
     * Set nilai setting
     */
    public static function set(string $key, string $value, string $description = ''): bool {
        $existing = DB::fetchOne('SELECT * FROM settings WHERE `key` = ? LIMIT 1', [$key]);
        
        if ($existing) {
            DB::run('UPDATE settings SET value = ? WHERE `key` = ?', [$value, $key]);
        } else {
            DB::run(
                'INSERT INTO settings (`key`, value, description) VALUES (?, ?, ?)',
                [$key, $value, $description]
            );
        }
        return true;
    }

    /**
     * Hapus setting
     */
    public static function delete(string $key): bool {
        DB::run('DELETE FROM settings WHERE `key` = ?', [$key]);
        return true;
    }

    /**
     * Ambil setting sebagai array asosiatif
     */
    public static function toArray(): array {
        $settings = self::all();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        return $result;
    }

    /**
     * Ambil nama aplikasi
     */
    public static function appName(): string {
        return self::get('app_name', 'SIPEDO');
    }

    /**
     * Ambil subtitle aplikasi
     */
    public static function appSubtitle(): string {
        return self::get('app_subtitle', 'Sistem Pengelolaan Donasi');
    }

    /**
     * Ambil email kontak
     */
    public static function contactEmail(): string {
        return self::get('contact_email', 'admin@sipedo.org');
    }

    /**
     * Ambil batas waktu verifikasi (jam)
     */
    public static function verificationDeadline(): int {
        return (int) self::get('verification_deadline', '24');
    }

    /**
     * Ambil max upload size (MB)
     */
    public static function maxUploadMb(): int {
        return (int) self::get('max_upload_mb', '2');
    }
}
