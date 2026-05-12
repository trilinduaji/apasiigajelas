<?php
$topDonors = [
    ['rank' => 1, 'init' => 'DK', 'col' => '#2563eb', 'name' => 'Dewi Kusuma',     'prog' => 'Beasiswa Anak Yatim, Klinik', 'amount' => '18.500.000'],
    ['rank' => 2, 'init' => 'HP', 'col' => '#7c3aed', 'name' => 'Hendro Pratama',  'prog' => 'Bantuan Pangan, Masjid',      'amount' => '12.750.000'],
    ['rank' => 3, 'init' => 'SR', 'col' => '#d97706', 'name' => 'Siti Rahayu',     'prog' => 'Bantuan Pangan Dhuafa',       'amount' => '9.200.000'],
    ['rank' => 4, 'init' => 'BH', 'col' => '#059669', 'name' => 'Budi Hartono',    'prog' => 'Beasiswa Anak Yatim',         'amount' => '7.000.000'],
    ['rank' => 5, 'init' => 'RW', 'col' => '#dc2626', 'name' => 'Ratna Wulandari', 'prog' => 'Air Bersih, Klinik',          'amount' => '5.500.000'],
    ['rank' => 6, 'init' => 'MR', 'col' => '#2563eb', 'name' => 'M. Rizky Fauzan', 'prog' => 'Renovasi Masjid Al-Ikhlas',   'amount' => '3.250.000'],
    ['rank' => 7, 'init' => 'AS', 'col' => '#7c3aed', 'name' => 'Ahmad Syarif',    'prog' => 'Beasiswa Anak Yatim',         'amount' => '2.000.000'],
];
$medals = [1 => 'gold', 2 => 'silver', 3 => 'bronze'];
?>
<div class="section-head">
    <h3 class="section-title">Progress Dana Program</h3>
</div>
<div class="panel" style="padding:20px 24px;">
    <?php foreach ($_SESSION['programs'] as $p): ?>
        <div style="margin-bottom:18px;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px;">
                <strong><?= e($p['name']) ?></strong>
                <span class="amount"><?= e($p['pct']) ?>%</span>
            </div>
            <div class="progress"><span style="width:<?= e($p['pct']) ?>%"></span></div>
            <div style="display:flex;justify-content:space-between;margin-top:4px;font-size:.8rem;color:#6b7280;">
                <span>Rp <?= e($p['collected']) ?> Jt</span>
                <span>Target: Rp <?= e($p['target']) ?> Jt</span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="section-head" style="margin-top:24px;">
    <h3 class="section-title">Top Donatur</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Peringkat</th><th>Donatur</th><th>Program</th><th>Total Donasi</th></tr></thead>
        <tbody>
            <?php foreach ($topDonors as $d): ?>
                <tr>
                    <td class="id">
                        <?php if (isset($medals[$d['rank']])): ?>
                            <?= $d['rank'] === 1 ? '🥇' : ($d['rank'] === 2 ? '🥈' : '🥉') ?>
                        <?php else: ?>
                            #<?= e($d['rank']) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= avatar($d['init'], $d['col']) ?><?= e($d['name']) ?></td>
                    <td><?= e($d['prog']) ?></td>
                    <td class="amount">Rp <?= e($d['amount']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
