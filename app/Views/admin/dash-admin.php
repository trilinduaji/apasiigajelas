<?php
/**
 * Dashboard Admin - Admin View
 * Menampilkan ringkasan sistem dari database
 */
$pendingCount = DonationModel::countByStatus('pending');
$activePrograms = ProgramModel::countActive();
$activeStaff = StaffModel::countActive();
$totalCollected = DonationModel::totalCollectedRp();
$totalDonors = DonationModel::uniqueDonors();
$recentDonations = DonationModel::recent(5);
$programs = array_values(array_filter(ProgramModel::all(), fn($p) => ($p['status'] ?? '') !== 'deleted'));
?>
<div class="stats">
    <div class="card"><div class="label">Total Dana Masuk</div><div class="value"><?= formatRupiah($totalCollected) ?></div></div>
    <div class="card"><div class="label">Total Donatur</div><div class="value"><?= e($totalDonors) ?></div></div>
    <div class="card"><div class="label">Program Aktif</div><div class="value"><?= $activePrograms ?></div></div>
    <div class="card"><div class="label">Staff Aktif</div><div class="value"><?= $activeStaff ?></div></div>
</div>

<div class="section-head">
    <h3 class="section-title">Rekap Donasi Terbaru</h3>
    <a class="btn light" href="index.php?route=app&page=rekap-donasi">Lihat Semua</a>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Donatur</th><th>Program</th><th>Jumlah</th><th>Status</th></tr></thead>
        <tbody>
            <?php if (empty($recentDonations)): ?>
                <tr><td colspan="5" class="muted">Belum ada data donasi.</td></tr>
            <?php endif; ?>
            <?php foreach ($recentDonations as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['kode']) ?></td>
                    <td><?= avatar($d['donor_init'] ?? '?', $d['donor_color'] ?? '#666') ?><?= e($d['donor_name'] ?? '-') ?></td>
                    <td><?= e($d['program_name'] ?? '-') ?></td>
                    <td class="amount"><?= formatRupiahFull((float)$d['amount']) ?></td>
                    <td><?= badge($d['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="section-head">
    <h3 class="section-title">Program Bantuan</h3>
    <a class="btn light" href="index.php?route=app&page=program-admin">Kelola Program</a>
</div>
<div class="program-grid">
    <?php if (empty($programs)): ?>
        <p class="muted">Belum ada program.</p>
    <?php endif; ?>
    <?php foreach (array_slice($programs, 0, 4) as $p): ?>
        <a class="program-card-v2 program-card-link" href="index.php?route=app&page=program-detail&id=<?= e($p['kode']) ?>">
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
                <span class="pc-cta">Klik untuk Detail</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
