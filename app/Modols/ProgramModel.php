<?php
/**
 * SIPEDO - Program Model (MySQL/PDO)
 */
class ProgramModel {
    private static array $palette = [
        'linear-gradient(135deg,#0D1B3E,#2A4080)',
        'linear-gradient(135deg,#065F46,#0F9D58)',
        'linear-gradient(135deg,#7C3AED,#A78BFA)',
        'linear-gradient(135deg,#B45309,#F59E0B)',
        'linear-gradient(135deg,#0E7490,#22D3EE)',
    ];

    public static function all(): array {
        return DB::fetchAll('SELECT * FROM programs ORDER BY created_at DESC');
    }

    public static function active(): array {
        return DB::fetchAll("SELECT * FROM programs WHERE status = 'active' ORDER BY created_at DESC");
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM programs WHERE id = ? LIMIT 1', [$id]);
    }

    public static function findByKode(string $kode): ?array {
        return DB::fetchOne('SELECT * FROM programs WHERE kode = ? LIMIT 1', [$kode]);
    }

    public static function create(array $data, ?int $createdBy = null): int {
        $count    = (int) DB::fetchScalar('SELECT COUNT(*) FROM programs');
        $kode     = 'PR-' . str_pad((string)($count + 1), 2, '0', STR_PAD_LEFT);
        $gradient = self::$palette[$count % count(self::$palette)];

        DB::run(
            'INSERT INTO programs (kode, name, category, target_juta, collected_juta, pct, deadline, status, image, description, gradient, created_by)
             VALUES (?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?, ?)',
            [
                $kode,
                $data['name'],
                $data['category'],
                $data['target'] / 1_000_000,
                $data['deadline'],
                $data['status'],
                $data['image'] ?? '',
                $data['description'],
                $gradient,
                $createdBy,
            ]
        );
        return (int) DB::lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $newTargetJuta = $data['target'] / 1_000_000;
        $collected     = (float) DB::fetchScalar('SELECT collected_juta FROM programs WHERE id = ?', [$id]);
        $pct           = $newTargetJuta > 0 ? round(($collected / $newTargetJuta) * 100, 1) : 0;

        $sql = 'UPDATE programs SET name=?, category=?, deadline=?, description=?, status=?, target_juta=?, pct=?';
        $params = [$data['name'], $data['category'], $data['deadline'], $data['description'], $data['status'], $newTargetJuta, $pct];

        if (!empty($data['image'])) {
            $sql .= ', image=?';
            $params[] = $data['image'];
        }
        $sql .= ' WHERE id=?';
        $params[] = $id;

        DB::run($sql, $params);
        return true;
    }

    public static function setStatus(int $id, string $status): bool {
        DB::run('UPDATE programs SET status=? WHERE id=?', [$status, $id]);
        return true;
    }

    /** Tambahkan nominal donasi ke program (update collected + pct) */
    public static function addCollected(int $id, float $amountRp): void {
        DB::run(
            'UPDATE programs
             SET collected_juta = collected_juta + ?,
                 pct = ROUND((collected_juta + ?) / NULLIF(target_juta, 0) * 100, 1)
             WHERE id = ?',
            [$amountRp / 1_000_000, $amountRp / 1_000_000, $id]
        );
    }
}
