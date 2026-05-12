<?php
/**
 * Program Staff - Staff View
 * Menampilkan program yang dikelola (tidak termasuk yang dihapus)
 */
$programs = array_values(array_filter(
    ProgramModel::all(),
    fn($p) => ($p['status'] ?? '') !== 'deleted'
));
?>
<div class="section-head">
    <h3 class="section-title">Program yang Dikelola</h3>
    <a class="btn green" href="index.php?route=app&page=tambah-program">+ Tambah Program Baru</a>
</div>

<div class="program-grid">
    <?php if (empty($programs)): ?>
        <p class="muted">Belum ada program. Klik tombol di atas untuk menambah program baru.</p>
    <?php endif; ?>
    <?php foreach ($programs as $p): ?>
        <a class="program-card-v2 program-card-link" href="index.php?route=app&page=edit-program&id=<?= e($p['kode']) ?>">
            <div class="pc-banner" <?= empty($p['image']) ? 'style="background:' . e($p['gradient'] ?? 'linear-gradient(135deg,#0D1B3E,#2A4080)') . ';"' : '' ?>>
                <?php if (!empty($p['image'])): ?>
                    <img src="<?= e(pub($p['image'])) ?>" alt="<?= e($p['name']) ?>">
                <?php endif; ?>
                <div class="pc-banner-badge"><?= badge($p['status']) ?></div>
            </div>
            <div class="pc-body">
                <h4 class="pc-title"><?= e($p['name']) ?></h4>
                <p class="pc-desc"><?= e($p['description'] ?? '') ?></p>
                <div class="pc-meta">
                    <span><?= e($p['category']) ?></span>
                    <span>Tenggat: <?= e(formatTanggal($p['deadline'])) ?></span>
                </div>
                <div class="pc-stats">
                    <div>
                        <div class="pc-label">Terkumpul</div>
                        <div class="pc-value pc-emerald"><?= formatRupiah((float)$p['collected']) ?></div>
                    </div>
                    <div style="text-align:right;">
                        <div class="pc-label">Target</div>
                        <div class="pc-value"><?= formatRupiah((float)$p['target']) ?></div>
                    </div>
                </div>
                <div class="progress"><span style="width:<?= e($p['pct']) ?>%"></span></div>
                <div class="pc-pct"><?= e($p['pct']) ?>% tercapai</div>
                <span class="pc-cta">Klik untuk Edit / Detail</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
