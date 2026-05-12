<?php
/**
 * SIPEDO - Donation Model (MySQL/PDO)
 * Sesuai dengan tabel `donations` di query.sql
 * Kolom: id, kode, user_id, program_id, amount, method, proof, status, processed_by, processed_at, note, donated_at, created_at
 */
class DonationModel {

    /**
     * Ambil semua donasi dengan join ke users dan programs
     */
    public static function all(): array {
        return DB::fetchAll(
            'SELECT d.*, 
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name, p.kode AS program_kode,
                    proc.name AS processed_name
             FROM donations d
             LEFT JOIN users u ON u.id = d.user_id
             LEFT JOIN programs p ON p.id = d.program_id
             LEFT JOIN users proc ON proc.id = d.processed_by
             ORDER BY d.donated_at DESC'
        );
    }

    /**
     * Cari donasi berdasarkan ID
     */
    public static function findById(int $id): ?array {
        return DB::fetchOne(
            'SELECT d.*, 
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name, p.kode AS program_kode
             FROM donations d
             LEFT JOIN users u ON u.id = d.user_id
             LEFT JOIN programs p ON p.id = d.program_id
             WHERE d.id = ? LIMIT 1',
            [$id]
        );
    }

    /**
     * Cari donasi berdasarkan kode
     */
    public static function findByKode(string $kode): ?array {
        return DB::fetchOne(
            'SELECT d.*, 
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name, p.kode AS program_kode
             FROM donations d
             LEFT JOIN users u ON u.id = d.user_id
             LEFT JOIN programs p ON p.id = d.program_id
             WHERE d.kode = ? LIMIT 1',
            [$kode]
        );
    }

    /**
     * Ambil donasi berdasarkan user_id (donatur)
     */
    public static function byUserId(int $userId): array {
        return DB::fetchAll(
            'SELECT d.*, 
                    p.name AS program_name, p.kode AS program_kode
             FROM donations d
             LEFT JOIN programs p ON p.id = d.program_id
             WHERE d.user_id = ? ORDER BY d.donated_at DESC',
            [$userId]
        );
    }

    /**
     * Ambil donasi pending
     */
    public static function pending(): array {
        return DB::fetchAll(
            "SELECT d.*, 
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name, p.kode AS program_kode
             FROM donations d
             LEFT JOIN users u ON u.id = d.user_id
             LEFT JOIN programs p ON p.id = d.program_id
             WHERE d.status = 'pending' ORDER BY d.donated_at DESC"
        );
    }

    /**
     * Ambil donasi verified
     */
    public static function verified(): array {
        return DB::fetchAll(
            "SELECT d.*, 
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name, p.kode AS program_kode
             FROM donations d
             LEFT JOIN users u ON u.id = d.user_id
             LEFT JOIN programs p ON p.id = d.program_id
             WHERE d.status = 'verified' ORDER BY d.donated_at DESC"
        );
    }

    /**
     * Buat donasi baru
     */
    public static function create(array $data): int {
        // Generate kode: DN-XXXX (berdasarkan tahun + increment)
        $year  = date('Y');
        $count = (int) DB::fetchScalar('SELECT COUNT(*) FROM donations');
        $kode  = 'DN-' . ($year - 2000) . str_pad((string)($count + 1), 2, '0', STR_PAD_LEFT);

        DB::run(
            'INSERT INTO donations (kode, user_id, program_id, amount, method, proof, status, note, donated_at)
             VALUES (?, ?, ?, ?, ?, ?, \'pending\', \'\', NOW())',
            [
                $kode,
                $data['user_id'],
                $data['program_id'],
                $data['amount'],
                $data['method'],
                $data['proof'] ?? '',
            ]
        );
        return (int) DB::lastInsertId();
    }

    /**
     * Update status donasi
     */
    public static function updateStatus(int $id, string $status, int $processedBy, string $note = ''): bool {
        DB::run(
            'UPDATE donations SET status=?, processed_by=?, processed_at=NOW(), note=? WHERE id=?',
            [$status, $processedBy, $note, $id]
        );
        return true;
    }

    /**
     * Total donasi terkumpul (verified) dalam Rupiah
     */
    public static function totalCollectedRp(): float {
        return (float) DB::fetchScalar("SELECT COALESCE(SUM(amount), 0) FROM donations WHERE status = 'verified'");
    }

    /**
     * Hitung unique donatur
     */
    public static function uniqueDonors(): int {
        return (int) DB::fetchScalar("SELECT COUNT(DISTINCT user_id) FROM donations WHERE user_id IS NOT NULL");
    }

    /**
     * Top donatur berdasarkan total donasi verified
     */
    public static function topDonors(int $limit = 5): array {
        return DB::fetchAll(
            "SELECT u.name AS nama, u.initials, u.color,
                    SUM(d.amount) AS total, COUNT(*) AS count
             FROM donations d
             JOIN users u ON u.id = d.user_id
             WHERE d.status = 'verified'
             GROUP BY d.user_id, u.name, u.initials, u.color
             ORDER BY total DESC
             LIMIT ?",
            [$limit]
        );
    }

    /**
     * Hitung total donasi berdasarkan status
     */
    public static function countByStatus(string $status): int {
        return (int) DB::fetchScalar('SELECT COUNT(*) FROM donations WHERE status = ?', [$status]);
    }

    /**
     * Ambil donasi terbaru
     */
    public static function recent(int $limit = 5): array {
        return DB::fetchAll(
            "SELECT d.*, 
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name
             FROM donations d
             LEFT JOIN users u ON u.id = d.user_id
             LEFT JOIN programs p ON p.id = d.program_id
             ORDER BY d.donated_at DESC
             LIMIT ?",
            [$limit]
        );
    }
}
