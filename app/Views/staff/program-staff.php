<?php
$programs = array_values(array_filter(
    $_SESSION['programs'] ?? [],
    fn($p) => ($p['status'] ?? '') !== 'deleted'
));
?>
<div class="section-head">
    <h3 class="section-title">Program yang Dikelola</h3>
    <a class="btn green" href="index.php?route=app&page=tambah-program">+ Tambah Program Baru</a>
</div>

<div class="program-grid">
    <?php foreach ($programs as $p): ?>
        <a class="program-card-v2 program-card-link" href="index.php?route=app&page=program-detail&id=<?= e($p['id']) ?>">
            <div class="pc-banner" <?= empty($p['image']) ? 'style="background:' . e($p['gradient'] ?? 'linear-gradient(135deg,#0D1B3E,#2A4080)') . ';"' : '' ?>>
                <?php if (!empty($p['image'])): ?>
                    <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>">
                <?php endif; ?>
                <div class="pc-banner-badge"><?= badge($p['status']) ?></div>
            </div>
            <div class="pc-body">
                <h4 class="pc-title"><?= e($p['name']) ?></h4>
                <p class="pc-desc"><?= e($p['desc'] ?? '') ?></p>
                <div class="pc-meta">
                    <span><?= e($p['cat']) ?></span>
                    <span>Tenggat: <?= e($p['deadline']) ?></span>
                </div>
                <div class="pc-stats">
                    <div>
                        <div class="pc-label">Terkumpul</div>
                        <div class="pc-value pc-emerald">Rp <?= e($p['collected']) ?> Jt</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="pc-label">Target</div>
                        <div class="pc-value">Rp <?= e($p['target']) ?> Jt</div>
                    </div>
                </div>
                <div class="progress"><span style="width:<?= e($p['pct']) ?>%"></span></div>
                <div class="pc-pct"><?= e($p['pct']) ?>% tercapai</div>
                <span class="pc-cta">Klik untuk Edit / Detail →</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
