<?php
/**
 * SIPEDO - Program Model (MySQL/PDO)
 * Sesuai dengan tabel `programs` di query.sql
 * Kolom: id, kode, name, description, category, target, collected, pct, deadline, status, image, gradient, created_by, created_at, updated_at
 */
class ProgramModel {
    private static array $palette = [
        'linear-gradient(135deg,#0D1B3E,#2A4080)',
        'linear-gradient(135deg,#065F46,#0F9D58)',
        'linear-gradient(135deg,#7C3AED,#A78BFA)',
        'linear-gradient(135deg,#B45309,#F59E0B)',
        'linear-gradient(135deg,#0E7490,#22D3EE)',
    ];

    /**
     * Ambil semua program
     */
    public static function all(): array {
        return DB::fetchAll('SELECT * FROM programs ORDER BY created_at DESC');
    }

    /**
     * Ambil semua program aktif
     */
    public static function active(): array {
        return DB::fetchAll("SELECT * FROM programs WHERE status = 'active' ORDER BY created_at DESC");
    }

    /**
     * Cari program berdasarkan ID
     */
    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM programs WHERE id = ? LIMIT 1', [$id]);
    }

    /**
     * Cari program berdasarkan kode
     */
    public static function findByKode(string $kode): ?array {
        return DB::fetchOne('SELECT * FROM programs WHERE kode = ? LIMIT 1', [$kode]);
    }

    /**
     * Buat program baru
     * Target dan collected dalam Rupiah penuh
     */
    public static function create(array $data, ?int $createdBy = null): int {
        $count    = (int) DB::fetchScalar('SELECT COUNT(*) FROM programs');
        $kode     = 'PR-' . str_pad((string)($count + 1), 2, '0', STR_PAD_LEFT);
        $gradient = self::$palette[$count % count(self::$palette)];

        DB::run(
            'INSERT INTO programs (kode, name, description, category, target, collected, pct, deadline, status, image, gradient, created_by)
             VALUES (?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?)',
            [
                $kode,
                $data['name'],
                $data['description'] ?? '',
                $data['category'],
                $data['target'],          // dalam Rupiah penuh
                $data['deadline'],
                $data['status'] ?? 'active',
                $data['image'] ?? '',
                $gradient,
                $createdBy,
            ]
        );
        return (int) DB::lastInsertId();
    }

    /**
     * Update program
     */
    public static function update(int $id, array $data): bool {
        $newTarget = (float) $data['target'];
        $collected = (float) DB::fetchScalar('SELECT collected FROM programs WHERE id = ?', [$id]);
        $pct       = $newTarget > 0 ? round(($collected / $newTarget) * 100, 2) : 0;

        $sql = 'UPDATE programs SET name=?, category=?, deadline=?, description=?, status=?, target=?, pct=?';
        $params = [
            $data['name'],
            $data['category'],
            $data['deadline'],
            $data['description'] ?? '',
            $data['status'] ?? 'active',
            $newTarget,
            $pct
        ];

        if (!empty($data['image'])) {
            $sql .= ', image=?';
            $params[] = $data['image'];
        }
        $sql .= ' WHERE id=?';
        $params[] = $id;

        DB::run($sql, $params);
        return true;
    }

    /**
     * Set status program
     */
    public static function setStatus(int $id, string $status): bool {
        DB::run('UPDATE programs SET status=? WHERE id=?', [$status, $id]);
        return true;
    }

    /**
     * Tambahkan nominal donasi ke program (update collected + pct)
     * amount dalam Rupiah penuh
     */
    public static function addCollected(int $id, float $amountRp): void {
        DB::run(
            'UPDATE programs
             SET collected = collected + ?,
                 pct = ROUND((collected + ?) / NULLIF(target, 0) * 100, 2)
             WHERE id = ?',
            [$amountRp, $amountRp, $id]
        );
    }

    /**
     * Hitung total program aktif
     */
    public static function countActive(): int {
        return (int) DB::fetchScalar("SELECT COUNT(*) FROM programs WHERE status = 'active'");
    }

    /**
     * Hitung total target dari semua program aktif
     */
    public static function totalTarget(): float {
        return (float) DB::fetchScalar("SELECT COALESCE(SUM(target), 0) FROM programs WHERE status IN ('active', 'closed')");
    }

    /**
     * Hitung total terkumpul dari semua program
     */
    public static function totalCollected(): float {
        return (float) DB::fetchScalar("SELECT COALESCE(SUM(collected), 0) FROM programs WHERE status IN ('active', 'closed')");
    }
}
