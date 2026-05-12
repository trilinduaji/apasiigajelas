<?php
/**
 * SIPEDO - Donation Model (MySQL/PDO)
 * Disesuaikan dengan schema: kolom user_id (FK users), program_id (FK programs),
 * tidak ada donor_name/donor_init/donor_color/program_name langsung di tabel.
 */
class DonationModel {

    public static function all(): array {
        return DB::fetchAll(
            'SELECT d.*,
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name,
                    pu.name AS processed_name
             FROM donations d
             JOIN users u  ON u.id = d.user_id
             JOIN programs p ON p.id = d.program_id
             LEFT JOIN users pu ON pu.id = d.processed_by
             ORDER BY d.donated_at DESC'
        );
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne(
            'SELECT d.*,
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name
             FROM donations d
             JOIN users u  ON u.id = d.user_id
             JOIN programs p ON p.id = d.program_id
             WHERE d.id = ? LIMIT 1',
            [$id]
        );
    }

    public static function findByKode(string $kode): ?array {
        return DB::fetchOne(
            'SELECT d.*,
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name
             FROM donations d
             JOIN users u  ON u.id = d.user_id
             JOIN programs p ON p.id = d.program_id
             WHERE d.kode = ? LIMIT 1',
            [$kode]
        );
    }

    public static function byUserId(int $userId): array {
        return DB::fetchAll(
            'SELECT d.*,
                    p.name AS program_name
             FROM donations d
             JOIN programs p ON p.id = d.program_id
             WHERE d.user_id = ?
             ORDER BY d.donated_at DESC',
            [$userId]
        );
    }

    public static function pending(): array {
        return DB::fetchAll(
            "SELECT d.*,
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name
             FROM donations d
             JOIN users u  ON u.id = d.user_id
             JOIN programs p ON p.id = d.program_id
             WHERE d.status = 'pending'
             ORDER BY d.donated_at DESC"
        );
    }

    public static function verified(): array {
        return DB::fetchAll(
            "SELECT d.*,
                    u.name AS donor_name, u.initials AS donor_init, u.color AS donor_color,
                    p.name AS program_name
             FROM donations d
             JOIN users u  ON u.id = d.user_id
             JOIN programs p ON p.id = d.program_id
             WHERE d.status = 'verified'
             ORDER BY d.donated_at DESC"
        );
    }

    public static function create(array $data): int {
        $count = (int) DB::fetchScalar('SELECT COUNT(*) FROM donations');
        $kode  = 'DN-' . (2024 + $count + 1);

        DB::run(
            "INSERT INTO donations (kode, user_id, program_id, amount, method, proof, status)
             VALUES (?, ?, ?, ?, ?, ?, 'pending')",
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

    public static function updateStatus(int $id, string $status, int $processedBy): bool {
        DB::run(
            'UPDATE donations SET status=?, processed_by=?, processed_at=NOW() WHERE id=?',
            [$status, $processedBy, $id]
        );
        return true;
    }

    public static function totalCollectedRp(): float {
        return (float) DB::fetchScalar(
            "SELECT COALESCE(SUM(d.amount), 0)
             FROM donations d
             WHERE d.status = 'verified'"
        );
    }

    public static function uniqueDonors(): int {
        return (int) DB::fetchScalar(
            "SELECT COUNT(DISTINCT user_id) FROM donations"
        );
    }

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
}
