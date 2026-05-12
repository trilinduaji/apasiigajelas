<?php
/**
 * Progress & Top Donatur - Staff View
 * Menampilkan progress dana program dan top donatur dari database
 */
$programs = ProgramModel::active();
$topDonors = DonationModel::topDonors(10);
$medals = [0 => 'gold', 1 => 'silver', 2 => 'bronze'];
?>
<div class="section-head">
    <h3 class="section-title">Progress Dana Program</h3>
</div>
<div class="panel" style="padding:20px 24px;">
    <?php if (empty($programs)): ?>
        <p class="muted">Belum ada program aktif.</p>
    <?php endif; ?>
    <?php foreach ($programs as $p): ?>
        <div style="margin-bottom:18px;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px;">
                <strong><?= e($p['name']) ?></strong>
                <span class="amount"><?= e($p['pct']) ?>%</span>
            </div>
            <div class="progress"><span style="width:<?= e($p['pct']) ?>%"></span></div>
            <div style="display:flex;justify-content:space-between;margin-top:4px;font-size:.8rem;color:#6b7280;">
                <span><?= formatRupiah((float)$p['collected']) ?></span>
                <span>Target: <?= formatRupiah((float)$p['target']) ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="section-head" style="margin-top:24px;">
    <h3 class="section-title">Top Donatur</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Peringkat</th><th>Donatur</th><th>Jumlah Donasi</th><th>Total Donasi</th></tr></thead>
        <tbody>
            <?php if (empty($topDonors)): ?>
                <tr><td colspan="4" class="muted">Belum ada data donatur.</td></tr>
            <?php endif; ?>
            <?php foreach ($topDonors as $i => $d): ?>
                <tr>
                    <td class="id">
                        <?php if ($i === 0): ?>
                            <span style="font-size:1.2rem;">1</span>
                        <?php elseif ($i === 1): ?>
                            <span style="font-size:1.2rem;">2</span>
                        <?php elseif ($i === 2): ?>
                            <span style="font-size:1.2rem;">3</span>
                        <?php else: ?>
                            #<?= e($i + 1) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= avatar($d['initials'] ?? '?', $d['color'] ?? '#666') ?><?= e($d['nama']) ?></td>
                    <td><?= e($d['count']) ?> donasi</td>
                    <td class="amount"><?= formatRupiahFull((float)$d['total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
