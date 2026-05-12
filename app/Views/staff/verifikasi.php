<?php
/**
 * Panel Verifikasi Donasi - Staff View
 * Menampilkan daftar donasi pending untuk diverifikasi
 */
$pendingDonations = DonationModel::pending();
?>
<div class="section-head">
    <h3 class="section-title">Panel Verifikasi Donasi</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Donatur</th><th>Program</th><th>Jumlah</th><th>Metode</th><th>Bukti</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php if (empty($pendingDonations)): ?>
                <tr><td colspan="8" class="muted">Tidak ada donasi yang perlu diverifikasi.</td></tr>
            <?php endif; ?>
            <?php foreach ($pendingDonations as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['kode']) ?></td>
                    <td><?= avatar($d['donor_init'] ?? '?', $d['donor_color'] ?? '#666') ?><?= e($d['donor_name'] ?? '-') ?></td>
                    <td><?= e($d['program_name'] ?? '-') ?></td>
                    <td class="amount"><?= formatRupiahFull((float)$d['amount']) ?></td>
                    <td><?= e($d['method']) ?></td>
                    <td>
                        <?php if (!empty($d['proof'])): ?>
                            <a class="btn light" href="<?= e(pub($d['proof'])) ?>" target="_blank">Lihat Bukti</a>
                        <?php else: ?>
                            <span class="muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?= badge($d['status']) ?></td>
                    <td class="actions">
                        <form action="index.php?route=donation/verify" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="verify">
                            <input type="hidden" name="donation_id" value="<?= e($d['kode']) ?>">
                            <button class="btn green" type="submit">Setujui</button>
                        </form>
                        <form action="index.php?route=donation/verify" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="donation_id" value="<?= e($d['kode']) ?>">
                            <button class="btn red" type="submit">Tolak</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
