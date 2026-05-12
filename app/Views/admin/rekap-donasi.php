<?php
/**
 * Rekap Seluruh Donasi - Admin View
 * Menampilkan semua donasi dari database
 */
$allDonations = DonationModel::all();
?>
<div class="section-head">
    <h3 class="section-title">Rekap Seluruh Donasi</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Donatur</th><th>Program</th><th>Jumlah</th><th>Metode</th><th>Tanggal</th><th>Diproses Oleh</th><th>Status</th></tr></thead>
        <tbody>
            <?php if (empty($allDonations)): ?>
                <tr><td colspan="8" class="muted">Belum ada data donasi.</td></tr>
            <?php endif; ?>
            <?php foreach ($allDonations as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['kode']) ?></td>
                    <td><?= avatar($d['donor_init'] ?? '?', $d['donor_color'] ?? '#666') ?><?= e($d['donor_name'] ?? '-') ?></td>
                    <td><?= e($d['program_name'] ?? '-') ?></td>
                    <td class="amount"><?= formatRupiahFull((float)$d['amount']) ?></td>
                    <td><?= e($d['method']) ?></td>
                    <td class="muted"><?= e(formatTanggal($d['donated_at'])) ?></td>
                    <td><?= e($d['processed_name'] ?? '-') ?></td>
                    <td><?= badge($d['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
