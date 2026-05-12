<?php
/**
 * Dashboard Staff - Staff View
 * Menampilkan ringkasan verifikasi dan program dari database
 */
$user = current_user();
$pendingDonations = DonationModel::pending();
$verifiedToday = DonationModel::countByStatus('verified');
$rejectedToday = DonationModel::countByStatus('rejected');
$totalProcessed = $verifiedToday + $rejectedToday;
$programs = array_values(array_filter(ProgramModel::all(), fn($p) => ($p['status'] ?? '') !== 'deleted'));
?>
<div class="stats">
    <div class="card"><div class="label">Donasi Pending</div><div class="value"><?= count($pendingDonations) ?></div></div>
    <div class="card"><div class="label">Terverifikasi</div><div class="value"><?= $verifiedToday ?></div></div>
    <div class="card"><div class="label">Ditolak</div><div class="value"><?= $rejectedToday ?></div></div>
    <div class="card"><div class="label">Total Diproses</div><div class="value"><?= $totalProcessed ?></div></div>
</div>

<div class="section-head">
    <h3 class="section-title">Program yang Dikelola</h3>
    <a class="btn light" href="index.php?route=app&page=program-staff">Lihat Semua</a>
</div>
<div class="program-grid">
    <?php if (empty($programs)): ?>
        <p class="muted">Belum ada program. <a href="index.php?route=app&page=tambah-program">Tambah program baru</a>.</p>
    <?php endif; ?>
    <?php foreach (array_slice($programs, 0, 3) as $p): ?>
        <a class="program-card-v2 program-card-link" href="index.php?route=app&page=edit-program&id=<?= e($p['kode']) ?>">
            <div class="pc-banner" <?= empty($p['image']) ? 'style="background:' . e($p['gradient'] ?? 'linear-gradient(135deg,#0D1B3E,#2A4080)') . ';"' : '' ?>>
                <?php if (!empty($p['image'])): ?>
                    <img src="<?= e(pub($p['image'])) ?>" alt="<?= e($p['name']) ?>">
                <?php endif; ?>
                <div class="pc-banner-badge"><?= badge($p['status']) ?></div>
            </div>
            <div class="pc-body">
                <h4 class="pc-title"><?= e($p['name']) ?></h4>
                <p class="pc-desc"><?= e($p['description'] ?? '') ?></p>
                <div class="pc-stats">
                    <div>
                        <div class="pc-label">Terkumpul</div>
                        <div class="pc-value pc-emerald"><?= formatRupiah((float)$p['collected']) ?></div>
                    </div>
                    <div style="text-align:right;">
                        <div class="pc-label">Target</div>
                        <div class="pc-value"><?= formatRupiah((float)$p['target']) ?></div>
                    </div>
                </div>
                <div class="progress"><span style="width:<?= e($p['pct']) ?>%"></span></div>
                <div class="pc-pct"><?= e($p['pct']) ?>% tercapai</div>
                <span class="pc-cta">Klik untuk Edit / Detail</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<div class="section-head" style="margin-top:24px;">
    <h3 class="section-title">Donasi Pending</h3>
    <a class="btn light" href="index.php?route=app&page=verifikasi">Panel Verifikasi</a>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>Kode</th><th>Donatur</th><th>Program</th><th>Jumlah</th><th>Bukti</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php if (empty($pendingDonations)): ?>
                <tr><td colspan="6" class="muted">Tidak ada donasi pending.</td></tr>
            <?php endif; ?>
            <?php foreach (array_slice($pendingDonations, 0, 5) as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['kode']) ?></td>
                    <td><?= avatar($d['donor_init'] ?? '?', $d['donor_color'] ?? '#666') ?><?= e($d['donor_name'] ?? '-') ?></td>
                    <td><?= e($d['program_name'] ?? '-') ?></td>
                    <td class="amount"><?= formatRupiahFull((float)$d['amount']) ?></td>
                    <td>
                        <?php if (!empty($d['proof'])): ?>
                            <a class="btn light" href="<?= e(pub($d['proof'])) ?>" target="_blank">Lihat Bukti</a>
                        <?php else: ?>
                            <span class="muted">-</span>
                        <?php endif; ?>
                    </td>
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
