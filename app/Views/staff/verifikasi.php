<div class="section-head">
    <h3 class="section-title">Panel Verifikasi Donasi</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Donatur</th><th>Program</th><th>Jumlah</th><th>Metode</th><th>Bukti</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach ($donations as $d): ?>
                <tr>
                    <td class="id"><?= e($d['kode']) ?></td>
                    <td><?= avatar($d['donor_init'], $d['donor_color']) ?><?= e($d['donor_name']) ?></td>
                    <td><?= e($d['program_name']) ?></td>
                    <td class="amount">Rp <?= number_format((float)$d['amount'], 0, ',', '.') ?></td>
                    <td><?= e($d['method']) ?></td>
                    <td>
                        <?php if (!empty($d['proof'])): ?>
                            <a class="btn light" href="<?= e(pub($d['proof'])) ?>" target="_blank">Lihat Bukti</a>
                        <?php else: ?>
                            <span class="muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?= badge($d['status']) ?></td>
                    <td class="actions">
                        <?php if ($d['status'] === 'pending'): ?>
                            <form action="index.php?route=donation/verify" method="post">
                                <input type="hidden" name="action" value="verify">
                                <input type="hidden" name="donation_id" value="<?= e($d['kode']) ?>">
                                <button class="btn green" type="submit">Setujui</button>
                            </form>
                            <form action="index.php?route=donation/verify" method="post">
                                <input type="hidden" name="action" value="reject">
                                <input type="hidden" name="donation_id" value="<?= e($d['kode']) ?>">
                                <button class="btn red" type="submit">Tolak</button>
                            </form>
                        <?php else: ?>
                            <span class="muted"><?= e($d['processed_name'] ?? '—') ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
