<div class="section-head">
    <h3 class="section-title">Panel Verifikasi Donasi</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>ID</th><th>Donatur</th><th>Program</th><th>Jumlah</th><th>Metode</th><th>Bukti</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach ($_SESSION['donations'] as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['id']) ?></td>
                    <td><?= avatar($d['init'], $d['col']) ?><?= e($d['donor']) ?></td>
                    <td><?= e($d['program']) ?></td>
                    <td class="amount">Rp <?= e($d['amount']) ?></td>
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
                                <input type="hidden" name="id" value="<?= e($d['id']) ?>">
                                <button class="btn green" type="submit">Setujui</button>
                            </form>
                            <form action="index.php?route=donation/verify" method="post">
                                <input type="hidden" name="action" value="reject">
                                <input type="hidden" name="id" value="<?= e($d['id']) ?>">
                                <button class="btn red" type="submit">Tolak</button>
                            </form>
                        <?php else: ?>
                            <span class="muted"><?= e($d['processedBy']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
