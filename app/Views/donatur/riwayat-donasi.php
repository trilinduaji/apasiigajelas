<?php
$user = current_user();
$myDonations = array_filter($_SESSION['donations'], fn($d) => $d['donor'] === $user['name']);
?>
<div class="section-head">
    <h3 class="section-title">Riwayat Donasi Saya</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>ID</th><th>Program</th><th>Jumlah</th><th>Metode</th><th>Tanggal</th><th>Status</th></tr></thead>
        <tbody>
            <?php if (!$myDonations): ?>
                <tr><td colspan="6" class="muted">Belum ada riwayat donasi.</td></tr>
            <?php endif; ?>
            <?php foreach ($myDonations as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['id']) ?></td>
                    <td><?= e($d['program']) ?></td>
                    <td class="amount">Rp <?= e($d['amount']) ?></td>
                    <td><?= e($d['method']) ?></td>
                    <td class="muted"><?= e($d['date']) ?></td>
                    <td><?= badge($d['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
