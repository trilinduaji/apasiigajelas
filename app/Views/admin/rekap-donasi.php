<div class="section-head">
    <h3 class="section-title">Rekap Seluruh Donasi</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>ID</th><th>Donatur</th><th>Program</th><th>Jumlah</th><th>Metode</th><th>Tanggal</th><th>Diproses Oleh</th><th>Status</th></tr></thead>
        <tbody>
            <?php foreach ($_SESSION['donations'] as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['id']) ?></td>
                    <td><?= avatar($d['init'], $d['col']) ?><?= e($d['donor']) ?></td>
                    <td><?= e($d['program']) ?></td>
                    <td class="amount">Rp <?= e($d['amount']) ?></td>
                    <td><?= e($d['method']) ?></td>
                    <td class="muted"><?= e($d['date']) ?></td>
                    <td><?= e($d['processedBy']) ?></td>
                    <td><?= badge($d['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
