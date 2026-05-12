<?php $done = array_filter($donations, fn($d) => $d['status'] !== 'pending'); ?>
<div class="section-head">
    <h3 class="section-title">Riwayat Verifikasi</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Donatur</th><th>Jumlah</th><th>Program</th><th>Status</th><th>Diproses Oleh</th></tr></thead>
        <tbody>
            <?php foreach ($done as $d): ?>
                <tr>
                    <td class="id"><?= e($d['kode']) ?></td>
                    <td><?= avatar($d['donor_init'], $d['donor_color']) ?><?= e($d['donor_name']) ?></td>
                    <td class="amount">Rp <?= number_format((float)$d['amount'], 0, ',', '.') ?></td>
                    <td><?= e($d['program_name']) ?></td>
                    <td><?= badge($d['status']) ?></td>
                    <td><?= e($d['processed_name'] ?? '—') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
