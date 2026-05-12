<?php
/**
 * Riwayat Verifikasi - Staff View
 * Menampilkan donasi yang sudah diproses (verified/rejected)
 */
$allDonations = DonationModel::all();
$doneDonations = array_filter($allDonations, fn($d) => $d['status'] !== 'pending');
?>
<div class="section-head">
    <h3 class="section-title">Riwayat Verifikasi</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Donatur</th><th>Jumlah</th><th>Program</th><th>Status</th><th>Diproses Oleh</th></tr></thead>
        <tbody>
            <?php if (empty($doneDonations)): ?>
                <tr><td colspan="6" class="muted">Belum ada riwayat verifikasi.</td></tr>
            <?php endif; ?>
            <?php foreach ($doneDonations as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['kode']) ?></td>
                    <td><?= avatar($d['donor_init'] ?? '?', $d['donor_color'] ?? '#666') ?><?= e($d['donor_name'] ?? '-') ?></td>
                    <td class="amount"><?= formatRupiahFull((float)$d['amount']) ?></td>
                    <td><?= e($d['program_name'] ?? '-') ?></td>
                    <td><?= badge($d['status']) ?></td>
                    <td><?= e($d['processed_name'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
