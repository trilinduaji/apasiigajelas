<?php
/**
 * Program Admin - Admin View
 * Menampilkan semua program dari database dengan filter
 */
$programs = ProgramModel::all();

$countActive  = count(array_filter($programs, fn($p) => ($p['status'] ?? '') === 'active'));
$countClosed  = count(array_filter($programs, fn($p) => ($p['status'] ?? '') === 'closed'));
$countDeleted = count(array_filter($programs, fn($p) => ($p['status'] ?? '') === 'deleted'));

$q          = trim($_GET['q'] ?? '');
$statusFilt = $_GET['status'] ?? '';
$catFilt    = $_GET['cat'] ?? '';

// Ambil kategori unik
$categories = array_values(array_unique(array_filter(array_map(fn($p) => $p['category'] ?? '', $programs))));
sort($categories);

// Filter program
$visible = array_filter($programs, function ($p) use ($q, $statusFilt, $catFilt) {
    if (($p['status'] ?? '') === 'deleted' && $statusFilt !== 'deleted') return false;
    if ($q !== '' && stripos($p['name'] ?? '', $q) === false) return false;
    if ($statusFilt !== '' && ($p['status'] ?? '') !== $statusFilt) return false;
    if ($catFilt !== '' && ($p['category'] ?? '') !== $catFilt) return false;
    return true;
});
?>
<div class="stats">
    <div class="card"><div class="label">Program Aktif</div><div class="value"><?= $countActive ?></div></div>
    <div class="card"><div class="label">Program Selesai</div><div class="value"><?= $countClosed ?></div></div>
    <div class="card"><div class="label">Program Dihapus</div><div class="value"><?= $countDeleted ?></div></div>
</div>

<div class="section-head">
    <h3 class="section-title">Daftar Program</h3>
    <form action="index.php?route=app" method="get" class="filter-bar">
        <input type="hidden" name="page" value="program-admin">
        <input type="text" name="q" placeholder="Cari nama program..." value="<?= e($q) ?>">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="active"  <?= $statusFilt === 'active'  ? 'selected' : '' ?>>Aktif</option>
            <option value="closed"  <?= $statusFilt === 'closed'  ? 'selected' : '' ?>>Selesai</option>
            <option value="deleted" <?= $statusFilt === 'deleted' ? 'selected' : '' ?>>Dihapus</option>
        </select>
        <select name="cat">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= e($c) ?>" <?= $catFilt === $c ? 'selected' : '' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn" type="submit">Filter</button>
        <?php if ($q !== '' || $statusFilt !== '' || $catFilt !== ''): ?>
            <a class="btn light" href="index.php?route=app&page=program-admin">Reset</a>
        <?php endif; ?>
    </form>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Target</th><th>Terkumpul</th><th>Progress</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php if (empty($visible)): ?>
                <tr><td colspan="8" style="text-align:center;color:#6b7280;padding:24px;">Tidak ada program yang cocok dengan filter.</td></tr>
            <?php endif; ?>
            <?php foreach ($visible as $p): ?>
                <tr>
                    <td class="id"><a href="index.php?route=app&page=program-detail&id=<?= e($p['kode']) ?>" style="color:inherit;">#<?= e($p['kode']) ?></a></td>
                    <td><a href="index.php?route=app&page=program-detail&id=<?= e($p['kode']) ?>" style="color:#1A9C6B;font-weight:600;"><?= e($p['name']) ?></a></td>
                    <td><?= e($p['category']) ?></td>
                    <td><?= formatRupiah((float)$p['target']) ?></td>
                    <td class="amount"><?= formatRupiah((float)$p['collected']) ?></td>
                    <td><?= progress_bar($p['pct']) ?></td>
                    <td><?= badge($p['status']) ?></td>
                    <td class="actions">
                        <?php if ($p['status'] === 'active'): ?>
                            <form action="index.php?route=program/close" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= e($p['kode']) ?>">
                                <button class="btn amber" type="submit">Tutup</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($p['status'] !== 'deleted'): ?>
                            <form action="index.php?route=program/delete" method="post" style="display:inline;" onsubmit="return confirm('Hapus program <?= e($p['name']) ?>?');">
                                <input type="hidden" name="id" value="<?= e($p['kode']) ?>">
                                <button class="btn red" type="submit">Hapus</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
