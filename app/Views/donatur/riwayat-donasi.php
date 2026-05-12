<?php
/**
 * Riwayat Donasi - Donatur View
 * Menampilkan semua donasi yang dilakukan oleh donatur yang sedang login
 */
$user = current_user();
$myDonations = DonationModel::byUserId((int)$user['id']);
?>
<div class="section-head">
    <h3 class="section-title">Riwayat Donasi Saya</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Program</th><th>Jumlah</th><th>Metode</th><th>Tanggal</th><th>Status</th></tr></thead>
        <tbody>
            <?php if (empty($myDonations)): ?>
                <tr><td colspan="6" class="muted">Belum ada riwayat donasi.</td></tr>
            <?php endif; ?>
            <?php foreach ($myDonations as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['kode']) ?></td>
                    <td><?= e($d['program_name'] ?? '-') ?></td>
                    <td class="amount"><?= formatRupiahFull((float)$d['amount']) ?></td>
                    <td><?= e($d['method']) ?></td>
                    <td class="muted"><?= e(formatTanggal($d['donated_at'])) ?></td>
                    <td><?= badge($d['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
