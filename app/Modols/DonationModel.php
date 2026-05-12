<?php
/**
 * SIPEDO - Donation Model (MySQL/PDO)
 */
class DonationModel {

    public static function all(): array {
        return DB::fetchAll(
            'SELECT d.*, u.name AS processed_name
             FROM donations d
             LEFT JOIN users u ON u.id = d.processed_by
             ORDER BY d.donated_at DESC'
        );
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM donations WHERE id = ? LIMIT 1', [$id]);
    }

    public static function findByKode(string $kode): ?array {
        return DB::fetchOne('SELECT * FROM donations WHERE kode = ? LIMIT 1', [$kode]);
    }

    public static function byDonorId(int $donorId): array {
        return DB::fetchAll(
            'SELECT * FROM donations WHERE donor_id = ? ORDER BY donated_at DESC',
            [$donorId]
        );
    }

    public static function pending(): array {
        return DB::fetchAll("SELECT * FROM donations WHERE status = 'pending' ORDER BY donated_at DESC");
    }

    public static function verified(): array {
        return DB::fetchAll("SELECT * FROM donations WHERE status = 'verified' ORDER BY donated_at DESC");
    }

    public static function create(array $data): int {
        $count = (int) DB::fetchScalar('SELECT COUNT(*) FROM donations');
        $kode  = 'DN-' . (2024 + $count + 1);

        DB::run(
            'INSERT INTO donations
                (kode, donor_id, donor_name, donor_init, donor_color,
                 program_id, program_name, amount, method, proof, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'pending\')',
            [
                $kode,
                $data['donor_id']   ?? null,
                $data['donor'],
                $data['initials'],
                $data['color'],
                $data['programId']  ?? null,
                $data['programName'],
                $data['amount'],
                $data['method'],
                $data['proof']      ?? '',
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

    public static function totalCollectedRp(): int {
        return (int) DB::fetchScalar("SELECT COALESCE(SUM(amount),0) FROM donations WHERE status = 'verified'");
    }

    public static function uniqueDonors(): int {
        return (int) DB::fetchScalar("SELECT COUNT(DISTINCT donor_name) FROM donations");
    }

    public static function topDonors(int $limit = 5): array {
        return DB::fetchAll(
            "SELECT donor_name AS nama, donor_init AS initials, donor_color AS color,
                    SUM(amount) AS total, COUNT(*) AS count
             FROM donations
             WHERE status = 'verified'
             GROUP BY donor_name, donor_init, donor_color
             ORDER BY total DESC
             LIMIT ?",
            [$limit]
        );
    }
}

